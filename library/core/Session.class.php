<?php
class Session {

    public function __construct() {
    	// set custom save location for session.
    	ini_set('session.save_path', ROOT.DS.'tmp'.DS.'session');

    	//it sets session.gc_probability to zero and it runs a cron 
    	//job to clean up old session data in the default directory.
    	ini_set('session.gc_probability', 1);
	
		// Start the session
		if(!headers_sent() && !session_id()){
           session_start();
        }
    }

    public static function set($index, $value) {
        $_SESSION[$index] = $value; 
    }

    public static function get($index) {
        return (isset($_SESSION[$index])) ? $_SESSION[$index] : false; 
    }

    public static function exist($index) {
        return (bool)(isset($_SESSION[$index])) ? $_SESSION[$index] : false; 
    }

    public static function delete($index) {
        if(isset($_SESSION[$index])){
            unset($_SESSION[$index]);
            return false; 
        }
    }

    public static function destroy() {
        if(isset($_SESSION)){
        	foreach ($_SESSION as $key => $value) {
        		session_unset($key);
        	}
            session_destroy();
        }   
    }

    public static function dump() {
        if(isset($_SESSION)) {
            print_r($_SESSION);     
            return ;
        }
        throw new Exception("Session is not initialized");
    }

    public static function writeClose() {
        session_write_close();
    }
}