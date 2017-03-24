<?php
//define a localidade 
setlocale(LC_TIME, "pt_BR.utf8"); 

//define o fuso horário 
date_default_timezone_set('America/Sao_Paulo');

// debug mode will print erros, if false erros will be saved in log folder
define('DEBUG_MODE', true);

// database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB', 'test');

/* controllers config. */
// default controller
define('DEFAULT_CONTROLLER', 'home');
// hide default controller from url (true, false) EX: /index, /about
define('HIDE_DEFAULT_CONTROLLER', false);
/* ./end controller config */

// Pusher keys
define('APP_ID', '');
define('APP_KEY', '');
define('APP_SECRET', '');

// fill with the folder name followed by a / or just leave empty.
$webFolder =  'Simple-MVC-Framework/';
define('BASE_URL', Helper::getServerUrl().'/'.$webFolder);