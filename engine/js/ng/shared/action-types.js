(function(window, document) {
  'use strict';

  window.ActionTypes = {
    GET_STATE: 'GET_STATE',
    SET_STATE: 'SET_STATE',

    CREATE_SITE: 'CREATE_SITE',
    SITE_CREATED: 'SITE_CREATED',
    UPDATE_SITE: 'UPDATE_SITE',
    SITE_UPDATED: 'SITE_UPDATED',
    DELETE_SITE: 'DELETE_SITE',
    SITE_DELETED: 'SITE_DELETED',
    ORDER_SITES: 'ORDER_SITES',
    SITES_ORDERED: 'SITES_ORDERED',

    UPDATE_SITE_SETTINGS: 'UPDATE_SITE_SETTINGS',
    SITE_SETTINGS_UPDATED: 'SITE_SETTINGS_UPDATED',
    SITE_SETTINGS_CREATED: 'SITE_SETTINGS_CREATED',
    DELETE_SITE_SETTINGS: 'DELETE_SITE_SETTINGS',
    SITE_SETTINGS_DELETED: 'SITE_SETTINGS_DELETED',
    RENAME_SITE_SETTINGS_SITENAME: 'RENAME_SITE_SETTINGS_SITENAME',

    UPDATE_SITE_TEMPLATE_SETTINGS: 'UPDATE_SITE_TEMPLATE_SETTINGS',
    SITE_TEMPLATE_SETTINGS_UPDATED: 'SITE_TEMPLATE_SETTINGS_UPDATED',
    SITE_TEMPLATE_SETTINGS_CREATED: 'SITE_TEMPLATE_SETTINGS_CREATED',
    DELETE_SITE_TEMPLATE_SETTINGS: 'DELETE_SITE_TEMPLATE_SETTINGS',
    SITE_TEMPLATE_SETTINGS_DELETED: 'SITE_TEMPLATE_SETTINGS_DELETED',
    RENAME_SITE_TEMPLATE_SETTINGS_SITENAME: 'RENAME_SITE_TEMPLATE_SETTINGS_SITENAME',

    INIT_CREATE_SITE_SECTION: 'INIT_CREATE_SITE_SECTION',
    CREATE_SITE_SECTION: 'CREATE_SITE_SECTION',
    INIT_UPDATE_SITE_SECTION: 'INIT_UPDATE_SITE_SECTION',
    UPDATE_SITE_SECTION: 'UPDATE_SITE_SECTION',
    RESET_SITE_SECTION: 'RESET_SITE_SECTION',
    INIT_DELETE_SITE_SECTION: 'INIT_DELETE_SITE_SECTION',
    DELETE_SITE_SECTION: 'DELETE_SITE_SECTION',
    INIT_ORDER_SITE_SECTIONS: 'INIT_ORDER_SITE_SECTIONS',
    ORDER_SITE_SECTIONS: 'ORDER_SITE_SECTIONS',
    RENAME_SITE_SECTIONS_SITENAME: 'RENAME_SITE_SECTIONS_SITENAME',
    DELETE_SITE_SECTIONS: 'DELETE_SITE_SECTIONS',

    INIT_DELETE_SITE_SECTION_BACKGROUND: 'INIT_DELETE_SITE_SECTION_BACKGROUND',
    DELETE_SITE_SECTION_BACKGROUND: 'DELETE_SITE_SECTION_BACKGROUND',
    INIT_ORDER_SITE_SECTION_BACKGROUNDS: 'INIT_ORDER_SITE_SECTION_BACKGROUNDS',
    ORDER_SITE_SECTION_BACKGROUNDS: 'ORDER_SITE_SECTION_BACKGROUNDS',

    ADD_SECTION_TAGS: 'ADD_SECTION_TAGS',
    INIT_UPDATE_SECTION_TAGS: 'INIT_UPDATE_SECTION_TAGS',
    RENAME_SECTION_TAGS: 'RENAME_SECTION_TAGS',
    RENAME_SECTION_TAGS_SITENAME: 'RENAME_SECTION_TAGS_SITENAME',
    DELETE_SECTION_TAGS: 'DELETE_SECTION_TAGS',
    SITE_SECTIONS_TAGS_DELETED: 'SITE_SECTIONS_TAGS_DELETED',
    ADD_SITE_SECTIONS_TAGS: 'ADD_SITE_SECTIONS_TAGS',
    DELETE_SITE_SECTIONS_TAGS: 'DELETE_SITE_SECTIONS_TAGS'
  };
})(window, document);
