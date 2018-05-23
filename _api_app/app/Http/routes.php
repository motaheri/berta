<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// $app->get('/', function () use ($app) {
//     return 'Nothing here. Go away!';
// });

// @@@:TODO: Require login for API endpoints
$app->group(['prefix' => 'v1', 'namespace' => 'App'], function() use ($app) {
    $app->get('state/{site}', 'Http\Controllers\StateController@get');

    // @TODO remove this route later, user for testing only
    $app->get('render-entry', function() {


        $entries = new App\Sites\Sections\Entries\SectionEntriesDataService('', 'section-one');
        $entry = current($entries->get()['entry']);

        $sections = new App\Sites\Sections\SiteSectionsDataService('');
        $section = current($sections->get());

        $site_settings = new App\Sites\Settings\SiteSettingsDataService('');

        $site_template_settings = new App\Sites\TemplateSettings\SiteTemplateSettingsDataService('', 'messy-0.4.2');

        $entry_render_service = new App\Sites\Sections\Entries\SectionEntryRenderService([
            'entry' => $entry,
            'section' => $section,
            'site_settings' => $site_settings->get(),
            'site_template_settings' => $site_template_settings->get(),
        ]);

        return $entry_render_service->render();
    });

    $app->group(['prefix' => 'v1', 'namespace' => 'App\Sites'], function() use ($app) {
        $app->post('sites', ['as' => 'sites', 'uses' => 'SitesController@create']);
        $app->patch('sites', 'SitesController@update');
        $app->put('sites', 'SitesController@order');
        $app->delete('sites', 'SitesController@delete');

        $app->patch('sites/settings', ['as' => 'site_settings', 'uses' => 'Settings\SiteSettingsController@update']);

        $app->patch('sites/template-settings', ['as' => 'site_template_settings', 'uses' => 'TemplateSettings\SiteTemplateSettingsController@update']);

        $app->group(['prefix' => 'v1/sites', 'namespace' => 'App\Sites\Sections'], function() use ($app) {
            $app->post('sections', ['as' => 'site_sections', 'uses' => 'SiteSectionsController@create']);
            $app->patch('sections', 'SiteSectionsController@update');
            $app->patch('sections/reset', ['as' => 'site_sections_reset', 'uses' => 'SiteSectionsController@reset']);
            $app->put('sections', 'SiteSectionsController@order');
            $app->delete('sections', 'SiteSectionsController@delete');

            $app->put('sections/backgrounds', ['as' => 'site_section_backgrounds', 'uses' => 'SiteSectionsController@galleryOrder']);
            $app->delete('sections/backgrounds', 'SiteSectionsController@galleryDelete');
        });
    });
});
