<?php defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('active_link'))
{
    function active_link($controller)
    {
        $CI =& get_instance();
        
        if ($CI->router->fetch_directory() === NULL) 
        {
            $class = str_replace('/', '', $CI->router->fetch_directory());
        }
        else
        {
            $class = $CI->router->fetch_class();
        }
        return ($class == $controller) ? 'active' : '';
    }
}
