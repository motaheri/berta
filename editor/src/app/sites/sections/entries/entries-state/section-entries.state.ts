import { concat } from 'rxjs';
import { take, switchMap, tap } from 'rxjs/operators';
import { State, Action, StateContext, NgxsOnInit, Actions, ofActionSuccessful } from '@ngxs/store';

import { assignByPath } from 'src/app/shared/helpers';
import { AppStateService } from '../../../../app-state/app-state.service';
import { SectionEntriesStateModel } from './section-entries-state.model';
import {
  DeleteSiteSectionsEntriesAction,
  RenameSectionEntriesSitenameAction,
  DeleteSectionEntriesAction,
  RenameSectionEntriesAction,
  AddSiteEntriesAction,
  AddSectionEntriesAction,
  ResetSectionEntriesAction,
  InitSectionEntriesAction,
  UpdateSectionEntryFromSyncAction,
  OrderSectionEntriesFromSyncAction} from './section-entries.actions';
import { UserLoginAction } from '../../../../user/user.actions';
import { UpdateSiteSectionAction } from '../../sections-state/site-sections.actions';
import { UpdateSectionTagsAction } from '../../tags/section-tags.actions';


@State<SectionEntriesStateModel>({
  name: 'sectionEntries',
  defaults: {}
})
export class SectionEntriesState implements NgxsOnInit {

  constructor(
    private actions$: Actions,
    private appStateService: AppStateService) {
  }

  ngxsOnInit({ dispatch }: StateContext<SectionEntriesStateModel>) {
    concat(
      this.appStateService.getInitialState('', 'sectionEntries').pipe(take(1)),
      this.actions$.pipe(ofActionSuccessful(UserLoginAction), switchMap(() => {
        return this.appStateService.getInitialState('', 'sectionEntries').pipe(take(1));
      }))
    )
    .subscribe((sectionEntries) => {
      dispatch(new InitSectionEntriesAction(sectionEntries));
    });
  }

  @Action(AddSectionEntriesAction)
  addSectionEntries({ patchState, getState }: StateContext<SectionEntriesStateModel>, action: AddSectionEntriesAction) {
    const state = getState();
    patchState({[action.siteName]: [...state[action.siteName], ...action.entries]});
  }

  @Action(AddSiteEntriesAction)
  addSiteEntries({ patchState, getState }: StateContext<SectionEntriesStateModel>, action: AddSiteEntriesAction) {
    const currentState   = getState();
    const newEntries = {};
    newEntries[action.site.name] = action.entries;
    patchState({...currentState, ...newEntries});
  }

  @Action(RenameSectionEntriesAction)
  renameSectionEntries({ patchState, getState }: StateContext<SectionEntriesStateModel>, action: RenameSectionEntriesAction) {
    const state = getState();

    patchState({
      [action.section.site_name]: state[action.section.site_name].map(entry => {

        if (entry.sectionName === action.section.name) {
          return {...entry, sectionName: action.newSectionName};
        }
        return entry;
      })
    });
  }

  @Action(RenameSectionEntriesSitenameAction)
  renameSectionEntriesSitename({ setState, getState }: StateContext<SectionEntriesStateModel>, action: RenameSectionEntriesSitenameAction) {
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

  @Action(DeleteSectionEntriesAction)
  deleteSectionEntries({ patchState, getState }: StateContext<SectionEntriesStateModel>, action: DeleteSectionEntriesAction) {
    const state = getState();

    patchState({
      [action.section.site_name]: state[action.section.site_name].filter(entry => {
        return entry.sectionName !== action.section.name;
      })
    });
  }

  @Action(DeleteSiteSectionsEntriesAction)
  deleteSiteSectionsEntries({ getState, setState }: StateContext<SectionEntriesStateModel>, action: DeleteSiteSectionsEntriesAction) {
    const newState = {...getState()};
    delete newState[action.siteName];
    setState(newState);
  }

  @Action(ResetSectionEntriesAction)
  resetSectionEntries({ setState }: StateContext<SectionEntriesStateModel>) {
    setState({});
  }

  @Action(InitSectionEntriesAction)
  initSectionEntries({ setState }: StateContext<SectionEntriesStateModel>, action: InitSectionEntriesAction) {
    setState(action.payload);
  }

  @Action(UpdateSectionEntryFromSyncAction)
  updateSectionEntryFromSync({ getState, patchState, dispatch }: StateContext<SectionEntriesStateModel>,
                             action: UpdateSectionEntryFromSyncAction) {
    return this.appStateService.sync('sectionEntries', {
      path: action.path,
      value: action.payload
    }).pipe(
      tap(response => {
        if (response.error_message) {
          /* This should probably be handled in sync */
          console.error(response.error_message);
        } else {
          const currentState = getState();
          const [currentSite, , currentSection, entryId] = action.path.split('/');
          const siteName = currentSite === '0' ? '' : currentSite;
          let path = action.path.split('/').slice(4).join('/');
          let payload = action.payload;

          if (path === 'tags/tag') {
            path = 'tags';
            payload = response.entry.tags;
          }

          patchState({
            [siteName]: currentState[siteName].map(entry => {
              if (entry.id !== entryId || entry.sectionName !== currentSection) {
                return entry;
              }

              return assignByPath(entry, path, payload);
            })
          });

          if (response.section) {
            dispatch(new UpdateSiteSectionAction(
              siteName,
              response.section_order,
              {
                '@attributes': {
                  has_direct_content: response.has_direct_content
                }
              })
            );
          }

          if (response.tags) {
            dispatch(new UpdateSectionTagsAction(siteName, currentSection, response.tags));
          }
        }
      })
    );
  }

  @Action(OrderSectionEntriesFromSyncAction)
  OrderSectionEntriesFromSyncAction({ getState, patchState }: StateContext<SectionEntriesStateModel>,
                                    action: OrderSectionEntriesFromSyncAction) {
    return this.appStateService.sync('sectionEntries', {
      site: action.site,
      section: action.section,
      entryId: action.entryId,
      value: action.value
    },
    'PUT').pipe(
      tap(response => {
        if (response.error_message) {
          /* This should probably be handled in sync */
          console.error(response.error_message);
        } else {
          const currentState = getState();

          patchState({
            [action.site]: currentState[action.site].map(entry => {
              if (entry.sectionName !== action.section) {
                return entry;
              }

              return {...entry, order: response.order.indexOf(entry.id)};
            })
          });
        }
      })
    );
  }
}
