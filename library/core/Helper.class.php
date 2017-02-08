<?php
class Helper{  
    
    private static $instance = array();

    /**
    * Check if the class is already an instantiated
    * 
    * @param object $class
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
    * @param constant DEBUG_MODE from config.php
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
    
    public static function isLocalhost(){
        // if this is localhost
        return $_SERVER['SERVER_ADDR'] == '127.0.0.1' || $_SERVER['SERVER_ADDR'] == '::1';
    }    
    
    /**
    * Get client IP address
    * 
    */
    public static function getClientIP() {
        $ip = "127.0.0.1";
        
        if (getenv("HTTP_CLIENT_IP")){
            $ip = getenv("HTTP_CLIENT_IP");
        }
        else if(getenv("HTTP_X_FORWARDED_FOR")){
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        }
        else if(getenv("REMOTE_ADDR")){
            $ip = getenv("REMOTE_ADDR");
        }
        else{
            $ip = "UNKNOWN";
        }
        
        if($ip=="::1"){
            $ip = "127.0.0.1";
        }
        
        return $ip;
    }

    /**
    * Check if the action is AJAX request
    * 
    */
    public static function isAjax(){
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'));
    }
    
    /**
    * startsWith
    * 
    * @param mixed $haystack the source content
    * @param mixed $needle the string to search
    */                                              
    public static function startsWith($haystack, $needle) {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }
    
    /**
    * endsWith
    * 
    * @param mixed $haystack the source content
    * @param mixed $needle the string to search
    */
    public static function endsWith($haystack, $needle){
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        $start  = $length * -1; //negative
        return (substr($haystack, $start) === $needle);
    }
    
    /**
     * trim start character
     */
    public static function trimStart($prefix, $string) {
        
        if (substr($string, 0, strlen($prefix)) == $prefix) {
            $string = substr($string, strlen($prefix), strlen($string));
        }
        
        return $string; 
    }
    
    /**
     * trim end character
     */
    public static function trimEnd($suffix, $string) {        
        if (substr($string, (strlen($suffix) * -1)) == $suffix) {
            $string = substr($string, 0, strlen($string) - strlen($suffix));
        }
        
        return $string; 
    }

    /**
     * resolves a virtual path into an absolute path
     */
    public static function renderMenu(){
        $router = Helper::getInstance('Router');
        /*echo Router::$c;*/
        $router->getAction();
    }

    /**
     * convert mysql timestamp using pattern EX: 'd/m/Y H:i'
     */
    public static function convertDate($date, $pattern) {
        return date($pattern, strtotime($date));
    }
}