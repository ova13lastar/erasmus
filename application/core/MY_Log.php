<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Log extends CI_Log
{

    protected $_levels = array('ERROR' => 1, 'DEBUG' => 2, 'INFO' => 3, 'ALL' => 4, 'APP  ' => '5');

    /**
     * Constructeur
     */
    public function __construct()
    {
        parent::__construct();
    }

}
