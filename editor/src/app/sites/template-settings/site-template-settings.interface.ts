export interface SitesTemplateSettingsStateModel {
  [siteName: string]: SiteTemplateSettingsModel;
}

export interface SiteTemplateSettingsModel {
  [settingGroup: string]: any;
}