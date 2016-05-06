<?php

echo 'hello';

require_once "../lib/Util/autoLoader.php";

$autoLoader = AutoLoader::getInstance();
$autoLoader->registerNamespace('Test');
$autoLoader->registerNamespace('Controller');
$autoLoader->registerNamespace('Helper');
$autoLoader->registerNamespace('Kernel');
$autoLoader->registerNamespace('Model');

$debugger = Helper_Debugger::getInstance();
$administrator = Controller_Administrator::InitModels($model = array());

$dispatcher = Controller_Dispatcher::getInstance();
$dispatcher->run();