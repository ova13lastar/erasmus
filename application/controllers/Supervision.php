<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Supervision extends MY_Controller
{

    /**
     * Constructeur
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('supervision_model', 'supervision');
    }

    /**
     * Index
     */
    public function index()
    {   
        $this->data['title'] = 'Liste des comptes bloqués sur ERASME';
        $this->data['title624'] = 'ERASME 624';
        $this->data['title225'] = 'ERASME 225';
        $this->data['content'] = ($this->auth->is_superviseur()) ? 'supervision_view' : 'errors/not_granted';
        $this->data['locked_users_624'] = $this->supervision->get_locked_users(624);
        $this->data['locked_users_225'] = $this->supervision->get_locked_users(225);
        $this->data['error'] = '';
        $this->load->view('_layout_app', $this->data);
    }
      
}
