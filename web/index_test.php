<?php

if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))){
    return false;
}

require_once __DIR__.'/../vendor/autoload.php';

use Plume\Application;

$app = new Application('test');
$app['plume.root.path'] = __DIR__.'/../';
// $app['plume.module.default']='example';
// $app['plume.cache.request']=true;
// $app['plume.cache.db']=true;
$app->run();