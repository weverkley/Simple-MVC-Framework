<?php
// debug mode will print erros, if false erros will be saved in log folder
define('DEBUG_MODE', true);

// database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB', 'test');

// default controller.
define('DEFAULT_CONTROLLER', 'home');

// fill with the folder name followed by a / or just leave empty.
$webFolder =  'Simple-MVC-Framework/';
define('BASE_URL', Helper::getServerUrl().'/'.$webFolder);