<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Userstatus_model extends CI_Model
{

    /**
     * Constructeur
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Recuperation des informations dans ERASME
     * @param int $caisse
     * @param string $username
     * @return array
     */
    public function get_userstatus($caisse, $usernameornum)
    {
        // Requete qui recherche les beneficiaires + infos pour le matricule poste
        $query_text = "SELECT '036A' as theme,
                                username, 
                                account_status, 
                                to_char(lock_date, 'DD/MM/YYYY') as lock_date, 
                                to_char(last_login, 'DD/MM/YYYY') as last_login, 
                                to_char(password_change_date, 'DD/MM/YYYY') as password_change_date
                        FROM dba_users
                        WHERE username like '%'||?||'%'";
        $dataresult = array();
        $db_erasme = $this->load->database('erasme'.$caisse, TRUE);
        $query = $db_erasme->query($query_text, $usernameornum);
        if ($query->num_rows() > 0)
        {
            $dataresult = $query->result_array();
            $db_erasme->close();
        }
        return $dataresult;
    }

}
