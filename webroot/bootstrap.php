<?php

//echo 'hello';

require_once "../lib/Util/AutoLoader.php";

$autoLoader = Util_AutoLoader::getInstance();
$autoLoader->registerNamespace('Test');
$autoLoader->registerNamespace('Controller');
$autoLoader->registerNamespace('Helper');
$autoLoader->registerNamespace('Kernel');
$autoLoader->registerNamespace('Model');
$autoLoader->registerNamespace('Template');

Kernel_Registry_Configs::initConfigRegistry();

$debugger = Helper_Debugger::getInstance();
$administrator = Controller_Administrator::InitModels($model = array());

$dispatcher = Controller_Dispatcher::getInstance();
$dispatcher->run();