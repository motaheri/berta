export interface SettingsGroupModel {
  slug: string;
  settings: SettingModel[];
}

export interface SettingModel {
  slug: string;
  value: string|number|boolean;
}

export interface SettingGroupConfigModel {
  _?: {
    title?: string;
    invisible?: boolean;
    [k: string]: any;
  };
  [settingSlug: string]: SettingConfigModel;
}

export interface SettingConfigModel {
  default?: null | string | number | boolean;
  description?: string;
  title?: string;
  format?:
    | 'text'
    | 'longtext'
    | 'select'
    | 'fontselect'
    | 'toggle'
    | 'icon'
    | 'image'
    | 'color'
    | boolean;
  values?: Array<{
    title: string;
    value: string | number;
  }>;
  html_entities?: boolean;
  css_units?: boolean;
  min_width?: number | string;
  min_height?: number | string;
  max_width?: number | string;
  max_height?: number | string;
  allow_blank?: boolean;
  link?: boolean;
  validator?: 'GoogleAnalytics' | string;
  [k: string]: any;
}

/* responses */
export interface SettingGroupResponse {
  [setting: string]: string | number | null | boolean;
}

export interface SettingConfigGroupResponse {
  _?: {
    title?: string;
    invisible?: boolean;
    [k: string]: any;
  };
  [settingSlug: string]: SettingConfigResponse;
}

export interface SettingConfigResponse {
  default?: null | string | number | boolean;
  description?: string;
  title?: string;
  format?:
    | 'text'
    | 'longtext'
    | 'select'
    | 'fontselect'
    | 'toggle'
    | 'icon'
    | 'image'
    | 'color'
    | boolean;
  values?:
    | (string | number)[]
    | {
      [k: string]: string | number;
    };
  html_entities?: boolean;
  css_units?: boolean;
  min_width?: number | string;
  min_height?: number | string;
  max_width?: number | string;
  max_height?: number | string;
  allow_blank?: boolean;
  link?: boolean;
  validator?: 'GoogleAnalytics' | string;
  [k: string]: any;
}
