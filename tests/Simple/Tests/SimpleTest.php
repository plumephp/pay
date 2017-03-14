<?php

namespace Simple\Tests;

require_once __DIR__.'/../../../vendor/autoload.php';

use Plume\Application;

function run(){
	$app = new Application();
}
$a= array(
	'openid'=>'1234354',
	'amount'=>10000,
	'desc'=>'ok'
);
$aa = array(
	'source' => 'nh-hongbao',
	'content' => $a
);
$ja = json_encode($aa);
$ba = base64_encode($ja);
print_r($ba);
echo PHP_EOL;
run();


