<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends MY_Controller
{

    /**
     * Constructeur
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Index
     */
    public function index()
    {
        $this->data['title_sep'] = '';
        $this->data['title'] = '';
        $this->data['content'] = ($this->auth->is_agent()) ? 'index_view' : 'errors/not_granted';
        $this->load->view('_layout_app', $this->data);
    }

}
