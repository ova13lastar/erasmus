<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

    public $data = array();
    public $dataresult = array();

    /**
     * Constructeur
     */
    public function __construct()
    {
        parent::__construct();
        defined('DS') or define('DS', DIRECTORY_SEPARATOR);
        $this->data['app_name'] = $this->config->item('app_name');
        $this->data['app_slug'] = $this->config->item('app_slug');
        $this->data['app_caisse'] = $this->config->item('app_caisse');
        $this->data['app_logo'] = 'assets/images/cpam_'.$this->config->item('app_caisse').'.jpg';
        $this->data['app_name'] .= (ENVIRONMENT === 'production') ? '' : ' (' . ENVIRONMENT . ')';
        $this->data['files_path'] = $this->config->item('files_path');
        $this->data['access_master_systeme'] = $this->config->item('access_master_systeme');
        $this->data['arr_roles'] = $this->get_access_master_services();
        $this->data['title_sep'] = ' :: ';
        $this->data['errors'] = array();
        if ($this->config->item('maintenance_mode') === TRUE) 
        {
            echo "<pre>";
                echo "L'application ".$this->data['app_name']." est actuellement en maintenance !<br /><br />";
                echo "Merci de tenter de vous reconnecter ultérieurement...";
            echo "</pre>";
            exit;
        }
        else 
        {
            $this->auth->check_auth();
        }
    }

    /**
     * Redirection si page interdite d'acces
     */
    public function redirect_if_not_ajax()
    {
        if ($this->input->is_ajax_request() === FALSE)
        {
            log_message('APP  ', $this->log_prefix() . 'Acces direct interdit !');
            redirect('acces_interdit');
        }
    }
    
    /**
     * Renvoi page acces interdit
     */
    public function acces_interdit()
    {
        $this->data['title'] = 'Accès interdit';
        $this->data['content'] = 'errors/not_permitted';
        $this->load->view('_layout_app', $this->data);
    }
    
    /**
     * Renvoi page sans habilitation
     */
    public function acces_sans_habilitation()
    {
        $this->data['title'] = 'Accès sans habilitation';
        $this->data['content'] = 'errors/not_granted';
        $this->data['access_master_systeme'] = $this->config->item('access_master_systeme');
        $this->data['arr_roles'] = $this->get_access_master_services();
        $this->load->view('_layout_app', $this->data);
    }
    
    /**
     * Renvoi le profixe utilise dans les .log
     */
    public function log_prefix($id = '')
    {
        $uri = ($id === '') ? uri_string() : uri_string().'/'.$id;
        return '['.$uri.'] '.$_SESSION['identif_user'].' : ';
    }
    
    /**
     * Détruit la session en cours et redirige vers la page d'accueil
     */
    public function destroy()
    {
        session_destroy();
        $this->load->helper('cookie');
        delete_cookie('PHPSESSID');
        redirect('index');
    }
    
    /**
     * Renvoi la totalite de la session en cours
     */
    public function debug_session()
    {
        debug($_SESSION);
    }


    /**
     * Renvoi le contenu de $response au format JSON avec un exit (pour gérer les erreurs)
     * @param  array $response
     * @return JSON
     */
    public function output_json_exit($response)
    {
        // TODO a voir si retour  HTTP 200 est logique
        $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
                ->_display();
        exit;
    }

    /**
     * Renvoi un tableau de tous les id + noms de roles
     * @return array
     */
    public function get_roles_all()
    {
        $return = array();
        foreach ($this->config->item('user_roles') as $arr_roles)
        {
            $return[$arr_roles['role_user']] = $arr_roles['role_user'] . ' - ' . $arr_roles['role_user_name'];
        }
        return $return;
    }
    
    /**
     * Renvoi un tableau de tous les services access master disponibles
     * @return array
     */
    public function get_access_master_services()
    {
        $return = array();
        foreach ($this->config->item('user_roles') as $arr_roles)
        {
            $return[$arr_roles['role_user']] = $arr_roles['role_user_access_master'];
        }
        return $return;
    }

    /**
     * Conversion des points en undescores
     * @param string $str
     * @return string
     */
    public function convert_point_to_underscore($str)
    {
        return str_replace('.', '_', $str);
    }

    /**
     * Conversion des undescores en points
     * @param string $str
     * @return string
     */
    public function convert_underscore_to_point($str)
    {
        return str_replace('_', '.', $str);
    }

}
