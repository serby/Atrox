<?php
set_include_path(".:" . realpath(dirname(__FILE__) . "/..") . "/:" . PEAR_INSTALL_DIR);
require_once("Atrox/Core/Application/Application.php");
Atrox_Core_ServiceLocator::load(Atrox_Core_ServiceLocator::getInstance());

$application = Atrox_Core_ServiceLocator::getApplication();
//$application->setupCustomExceptionHandler();
$application->setName("Atrox Unit Test Application");