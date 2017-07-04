<?php
//Set custom memory limit
ini_set('memory_limit', '512M');
ini_set('error_reporting', E_ALL);

$GLOBALS['ROOT_DIR'] = dirname(__FILE__) . '/../../..';

require_once $GLOBALS['ROOT_DIR'] . '/vendor/autoload.php';

//Define include path for Magento and BliskaPaczka Module
// ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . $GLOBALS['ROOT_DIR'] . '/vendor/sendit/magento');
// ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . $GLOBALS['MODULES_DIR'] . '/vendor/sendit/magento/app');
// ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . $GLOBALS['APP_DIR']);

//Include Magento libraries
// require_once 'Mage.php';
// //Start the Magento application
// Mage::app('default');
//Avoid issues "Headers already send"
// session_start();
