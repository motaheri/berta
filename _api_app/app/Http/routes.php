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

    $app->group(['prefix' => 'v1', 'namespace' => 'App\Sites'], function() use ($app) {
        $app->post('sites', ['as' => 'sites', 'uses' => 'SitesController@create']);
        $app->patch('sites', 'SitesController@update');
        $app->put('sites', 'SitesController@order');
        $app->delete('sites', 'SitesController@delete');
    });

    $app->patch('site-settings', ['as' => 'site_settings', 'uses' => 'Sites\Settings\SiteSettingsController@update']);

    $app->patch('site-template-settings', ['as' => 'site_template_settings', 'uses' => 'Sites\SiteTemplateSettings\SiteTemplateSettingsController@update']);

    $app->group(['prefix' => 'v1', 'namespace' => 'App\Sites\Sections'], function() use ($app) {
        $app->post('sections', ['as' => 'sections', 'uses' => 'SiteSectionsController@create']);
        $app->patch('sections', 'SiteSectionsController@update');
        $app->patch('sections-reset', ['as' => 'sections_reset', 'uses' => 'SiteSectionsController@reset']);
        $app->put('sections', 'SiteSectionsController@order');
        $app->delete('sections', 'SiteSectionsController@delete');

        $app->put('section-backgrounds', ['as' => 'section_backgrounds', 'uses' => 'SiteSectionsController@galleryOrder']);
        $app->delete('section-backgrounds', 'SiteSectionsController@galleryDelete');
    });
});
