<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Userstatus extends MY_Controller
{

    /**
     * Constructeur
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('userstatus_model', 'userstatus');
    }

    /**
     * Index
     */
    public function index()
    {   
        $this->data['title'] = 'Statut d\'un utilisateur ERASME';
        $this->data['title624'] = 'ERASME 624';
        $this->data['title225'] = 'ERASME 225';
        $this->data['content'] = ($this->auth->is_superviseur()) ? 'userstatus_view' : 'errors/not_granted';
        $this->data['error'] = '';
        $this->load->library('form_validation');
        $this->form_validation->set_rules('usernameornum', 'Nom ou Numero', 'trim|required', array('required' => 'Le champ "%s" est obligatoire !'));
        if ($this->form_validation->run() == FALSE)
        {
            $this->data['user_accounts_624'] = array();
            $this->data['user_accounts_225'] = array();
            $this->load->view('_layout_app', $this->data);
            
        }
        else
        {
            $this->data['user_accounts_624'] = $this->userstatus->get_userstatus(624, $this->input->post('usernameornum'));
            $this->data['user_accounts_225'] = $this->userstatus->get_userstatus(225, $this->input->post('usernameornum'));
            $this->load->view('_layout_app', $this->data);
        }
    }

}
