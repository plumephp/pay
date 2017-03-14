<?php

if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))){
    return false;
}

require_once __DIR__.'/../vendor/autoload.php';

use Plume\Application;

$app = new Application();
$app['plume.root.path'] = __DIR__.'/../';// 指定根路径
$app['plume.module.default']='payservice';// 指定默认模块
// $app['plume.cache.expire']=200;// 设置缓存过期时间
// $app['plume.cache.request']=true;// 开启请求缓存
// $app['plume.cache.db']=true;// 开启数据库缓存
// $app['plume.cache.config']=array(// 设置缓存详细路由和过期时间
// 	'/example/index/index' => 0,
// 	'/example/index/edit' => 2,
// 	'/example/index/save' => 0,
// );
 $app['plume.log.debug']=true;// 开启debug日志
// $app['plume.log.time']=true;// 开启性能日志
// $app['plume.templates.300']= 'templates/index.html';// 定制默认模板页面
// $app['plume.templates.404']= 'templates/404.html';
// $app['plume.templates.500']= 'templates/500.html';
$app->run();
