import { concat } from 'rxjs';
import { take, switchMap } from 'rxjs/operators';
import { State, Action, StateContext, Selector, NgxsOnInit, Store, Actions, ofActionSuccessful } from '@ngxs/store';
import { AppStateService } from '../../app-state/app-state.service';
import {
  SitesTemplateSettingsStateModel,
  SitesTemplateSettingsResponse,
  TemplateSettingsTemplateResponse
} from './site-template-settings.interface';
import { SettingsGroupModel } from '../../shared/interfaces';
import { SiteSettingsState } from '../settings/site-settings.state';
import { AppStateModel } from '../../app-state/app-state.interface';
import { AppState } from '../../app-state/app.state';
import {
  UpdateSiteTemplateSettingsAction,
  DeleteSiteTemplateSettingsAction,
  RenameSiteTemplateSettingsSitenameAction,
  CreateSiteTemplateSettingsAction,
  ResetSiteTemplateSettingsAction,
  InitSiteTemplateSettingsAction
} from './site-template-settings.actions';
import { UserLoginAction } from '../../user/user-actions';


@State<SitesTemplateSettingsStateModel>({
  name: 'siteTemplateSettings',
  defaults: {}
})
export class SiteTemplateSettingsState implements NgxsOnInit {

  @Selector([AppState, SiteSettingsState.getCurrentSiteTemplate])
  static getCurrentSiteTemplateSettings(
    state: SiteTemplateSettingsState,
    appState: AppStateModel,
    currentTemplateSlug: string) {

    if (!(state && appState && currentTemplateSlug && state[appState.site])) {
      return;
    }
    return state[appState.site][currentTemplateSlug];
  }

  @Selector([SiteTemplateSettingsState.getCurrentSiteTemplateSettings])
  static getIsResponsive(_, currentSiteTemplateSettings) {
    return currentSiteTemplateSettings.pageLayout.responsive === 'yes';
  }

  constructor(
    private store: Store,
    private actions$: Actions,
    private appStateService: AppStateService) {
  }


  ngxsOnInit({ dispatch }: StateContext<SitesTemplateSettingsStateModel>) {
    concat(
      this.appStateService.getInitialState('', 'site_template_settings').pipe(take(1)),
      this.actions$.pipe(ofActionSuccessful(UserLoginAction), switchMap(() => {
        return this.appStateService.getInitialState('', 'site_template_settings').pipe(take(1));
      }))
    )
    .subscribe({
      next: (response: SitesTemplateSettingsResponse) => {
        dispatch(new InitSiteTemplateSettingsAction(response));
      },
      error: (error) => console.error(error)
    });
  }

  @Action(CreateSiteTemplateSettingsAction)
  createSiteTemplateSettings({ patchState }: StateContext<SitesTemplateSettingsStateModel>,
                             action: CreateSiteTemplateSettingsAction) {
    const newTemplateSettings = {[action.site.name]: {}};

    for (const templateSlug in action.templateSettings) {
      newTemplateSettings[action.site.name][templateSlug] = this.initializeSettingsForTemplate(
        action.templateSettings[templateSlug]
      );
    }
    patchState(newTemplateSettings);
  }

  @Action(UpdateSiteTemplateSettingsAction)
  updateSiteTemplateSettings({ patchState, getState }: StateContext<SitesTemplateSettingsStateModel>,
    action: UpdateSiteTemplateSettingsAction) {

    const currentSite = this.store.selectSnapshot(AppState.getSite);
    const currentSiteTemplate = this.store.selectSnapshot(SiteSettingsState.getCurrentSiteTemplate);
    const settingKey = Object.keys(action.payload)[0];
    const data = {
      path: currentSite + '/site_template_settings/' + currentSiteTemplate + '/' + action.settingGroup + '/' + settingKey,
      value: action.payload[settingKey]
    };
    /** @todo: Loading should be triggered here */

    this.appStateService.sync('siteTemplateSettings', data)
      .subscribe(response => {
        /** @todo: additional action should be triggered here!!! */

        if (response.error_message) {
          // @TODO handle error message
          console.error(response.error_message);
        } else {
          const currentState = getState();

          patchState({
            [currentSite]: {
              ...currentState[currentSite],
              [currentSiteTemplate]: currentState[currentSite][currentSiteTemplate].map(settingGroup => {
                if (settingGroup.slug !== action.settingGroup) {
                  return settingGroup;
                }

                return {
                  ...settingGroup,
                  settings: settingGroup.settings.map(setting => {
                    if (setting.slug !== settingKey) {
                      return setting;
                    }
                    return { ...setting, value: action.payload[settingKey] };
                  })
                };
              })
            }
          });
        }
    });
  }

  @Action(RenameSiteTemplateSettingsSitenameAction)
  renameSiteTemplateSettingsSitename(
    { setState, getState }: StateContext<SitesTemplateSettingsStateModel>,
    action: RenameSiteTemplateSettingsSitenameAction) {

    const state = getState();
    const newState = {};

    /* Using the loop to retain the element order in the map */
    for (const siteName in state) {
      if (siteName === action.site.name) {
        newState[action.siteName] = state[siteName];
      } else {
        newState[siteName] = state[siteName];
      }
    }

    setState(newState);
  }

  @Action(DeleteSiteTemplateSettingsAction)
  deleteSiteTemplateSettings(
    { setState, getState }: StateContext<SitesTemplateSettingsStateModel>,
    action: DeleteSiteTemplateSettingsAction) {

    const newState = {...getState()};
    delete newState[action.siteName];
    setState(newState);
  }

  @Action(ResetSiteTemplateSettingsAction)
  resetSiteTemplateSettings({ setState }: StateContext<SitesTemplateSettingsStateModel>) {
    setState({});
  }

  @Action(InitSiteTemplateSettingsAction)
  initSiteTemplateSettings({ setState }: StateContext<SitesTemplateSettingsStateModel>, action: InitSiteTemplateSettingsAction) {
    /** Initializing state: */
    const newState: SitesTemplateSettingsStateModel = {};

    for (const siteSlug in action.payload) {
      newState[siteSlug] = {};

      for (const templateSlug in action.payload[siteSlug]) {
        newState[siteSlug][templateSlug] = this.initializeSettingsForTemplate(action.payload[siteSlug][templateSlug]);
      }
    }

    setState(newState);
  }

  initializeSettingsForTemplate(settings: TemplateSettingsTemplateResponse): SettingsGroupModel[] {
    return Object.keys(settings).map(settingGroupSlug => {
      return {
        slug: settingGroupSlug,
        settings: Object.keys(settings[settingGroupSlug]).map(settingSlug => {
          return {
            slug: settingSlug,
            value: settings[settingGroupSlug][settingSlug]
          };
        })
      };
    });
  }
}
