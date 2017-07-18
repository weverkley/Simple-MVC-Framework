<?php
ob_start();

/* site path */
define ('ROOT', realpath(dirname(__FILE__)));
define('DS', DIRECTORY_SEPARATOR);

function sanitize_output($buffer) {
    $search = array(
        '/\>[^\S ]+/s',  // strip whitespaces after tags, except space
        '/[^\S ]+\</s',  // strip whitespaces before tags, except space
        '/(\s)+/s'       // shorten multiple whitespace sequences
    );
    $replace = array(
        '>',
        '<',
        '\\1'
    );
    $buffer = preg_replace($search, $replace, $buffer);
    return $buffer;
}

ob_start('sanitize_output');


/* require library/bootstrap.php */
require_once ROOT.DS.'library'.DS.'bootstrap.php';
ob_end_flush();