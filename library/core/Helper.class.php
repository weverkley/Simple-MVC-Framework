<?php
class Helper{  
    
    private static $instance = array();

    /**
    * Check if the class is already an instantiated
    * 
    */
    public static function getInstance($class){
        if(isset(self::$instance[$class]))
            return self::$instance[$class];
        else
        { 
            self::$instance[$class] = new $class();
            return self::$instance[$class];
        }   
    }

    /**
    * Check if environment is development and display errors
    * 
    */
    public static function setReporting() {
        if (DEBUG_MODE == true) {
            error_reporting(E_ALL);
            ini_set('display_errors','On');
        } else {
            error_reporting(E_ALL);
            ini_set('display_errors','Off');
            ini_set("log_errors", 1);
            ini_set('log_errors', 'On');
            ini_set('error_log', ROOT.DS.'tmp'.DS.'log'.DS.'php-error.log');
        }
    }

    /**
    * Gets server url path
    */
    public static function getServerUrl(){
        $port = $_SERVER['SERVER_PORT'];
        $http = "http";
        
        if($port == "80"){
          $port = "";  
        }
        
        if(!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on"){
           $http = "https";
        }
        if(empty($port)){
           return $http."://".$_SERVER['SERVER_NAME'];
        }else{
           return $http."://".$_SERVER['SERVER_NAME'].":".$port; 
        }        
    }
    
    /**
    * Check register globals and remove them
    */
    public static function unregisterGlobals() {
        if (ini_get('register_globals')) {
            $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
            foreach ($array as $value) {
                if(isset($GLOBALS[$value]))
                {
                    foreach ($GLOBALS[$value] as $key => $var) {
                        unset($GLOBALS[$key]);
                    }
                }
            }
        }
    }  

    /**
    * Check for Magic Quotes and remove them
    */
    public static function removeMagicQuotes() {
        function stripSlashesDeep($value) {
            return is_array($value)? array_map("stripSlashesDeep", $value) : stripslashes($value);
        }

        if (get_magic_quotes_gpc() ) {
            if(isset($_GET)){
                $_GET    = stripSlashesDeep($_GET);
            }
            
            if(isset($_POST)){
                $_POST   = stripSlashesDeep($_POST);
            }
            
            if(isset($_COOKIE)){
                $_COOKIE = stripSlashesDeep($_COOKIE);
            }

            if(isset($_SESSION)){
                $_SESSION = stripSlashesDeep($_SESSION);
            }
            
        }
    }
}