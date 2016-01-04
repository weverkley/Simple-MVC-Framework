<?php
function autoload ($class){
    $valid = file_exists($file = ROOT.DS.'library'.DS.'core'.DS.$class.'.class.php');    
    if(!$valid)
        $valid = file_exists($file = ROOT.DS.'library'.DS.'mvc'.DS.$class.'.class.php');
    if(!$valid)
        $valid = file_exists($file = ROOT.DS.'application'.DS.'controller'.DS.$class.'.php');
    if(!$valid)
        $valid = file_exists($file = ROOT.DS.'application'.DS.'model'.DS.$class.'.php');
    if($valid)
       require_once $file;
}

// autoload classes
spl_autoload_register('autoload');

// loads config file
require_once ROOT.DS.'config'.DS.'config.php';

// start session
Helper::getInstance('Session');

// set error reporting to true or false at config to display errors or not
Helper::setReporting();

// remove the magic quotes
Helper::removeMagicQuotes();

// unregister globals
Helper::unregisterGlobals();

// start the url routing
$router = Helper::getInstance('Router');
$router->dispatch();

/*
var_dump($router);

foreach (get_required_files() as $file){
    echo $file.'<br>';
}
*/

// close session
Session::writeClose();