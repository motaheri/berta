<?php

$app->group(['prefix' => 'v1', 'namespace' => 'App\Dev'], function () use ($app) {
    $app->get('test', 'TestController@get');
    $app->get('render-entry', 'TestController@renderEntry');
});
