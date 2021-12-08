<?php
set_time_limit(100000);
ini_set('display_errors', 1);

use framework\Application;

include __DIR__.'/../vendor/autoload.php';
$router1 = new \framework\Components\Routers\Router();
$app = Application::run(['router'=>$router1]);
