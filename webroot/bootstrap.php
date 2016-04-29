<?php

echo 'hello';

require_once "../lib/Util/autoLoader.php";

$autoLoader = AutoLoader::getInstance();
$autoLoader->registerNamespace('Test');
$autoLoader->registerNamespace('Controller');
$autoLoader->registerNamespace('Helper');
$autoLoader->registerNamespace('Kernel');

$debugger = Helper_Debugger::getInstance();

$dispatcher = Controller_Dispatcher::getInstance();
$dispatcher->dispatch();