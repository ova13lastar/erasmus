<?php defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('debug'))
{
    function debug($var)
    {
        echo "<pre>";
    	var_dump($var);
    	echo "</pre>";
    	die();
    }
}
