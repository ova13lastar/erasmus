<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{

    /**
     * Nom de la table
     * @var string
     */
    private $table = 'user';
    
    /**
     * Colonnes pour affichage dans le Datatable
     * @var array 
     */
    private $column_order = array('id_user', 'name', 'dt_add', 'role', 'dt_last_connect');
    
    /**
     * Colonnes pour recherche dans le Datatable
     * @var array 
     */
    private $column_search = array('id_user', 'name', 'dt_add', 'role', 'dt_last_connect');
    
    /**
     * Colonnes pour tri par defaut
     * @var array 
     */
    private $order = array('id_user' => 'asc');

    /**
     * Constructeur
     */
    public function __construct()
    {
        parent::__construct();
        $this->db = $this->load->database('default', true);
    }

    /**
     * Fonction privee principale
     */
    private function _get_datatables_query()
    {
        $this->db->from($this->table);
        $i = 0;
        foreach ($this->column_search as $item) // loop column
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {
                if ($i === 0) // first loop
                {
                    // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }
        else if (isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    /**
     * Renvoi les donnees pour le Datatable
     * @return object
     */
    public function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Renvoi le nombre de lignes filtrees
     * @return int
     */
    public function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    /**
     * Renvoi le nombre de ligne total
     * @return int
     */
    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    /**
     * Renvoi les donnees pour un id donne
     * @param string $id
     * @return object
     */
    public function get_by_id($id)
    {
        $this->db->from($this->table);
        $this->db->where('id_user', $id);
        $query = $this->db->get();
        return $query->row();
    }

    /**
     * Renvoi les utilisateurs affectes a un site donne au format dropdown
     * @param int $id_site
     * @return object
     */
    public function get_dropdown_by_site($id_site)
    {
        $return = array();
        $this->db->from($this->table);
        $this->db->join("site_user", 'site_user.id_user = ' . $this->table . '.id_user');
        $this->db->where('site_user.id_site', $id_site);
        $this->db->order_by($this->table . '.name');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    /**
     * Insertion
     * @param array $data
     * @return int
     */
    public function save($data)
    {
        $data = $this->security->xss_clean($data);
        $return = ($this->db->insert($this->table, $data) === TRUE) ? $this->db->affected_rows() : 0;
        return $return;
    }

    /**
     * Modification
     * @param array $where
     * @param array $data
     * @return boolean
     */
    public function update($where, $data)
    {
        $data = $this->security->xss_clean($data);
        $return = $this->db->update($this->table, $data, $where);
        return $return;
    }

    /**
     * Suppression
     * @param string $id
     * @return boolean
     */
    public function delete_by_id($id)
    {
        $this->db->where('id_user', $id);
        return $this->db->delete($this->table);
    }
    
    /**
     * Verification que l'utilisateur n'est affecté dans aucun site
     * @return boolean
     */
    public function validate_is_empty($id)
    {
        $this->db->from('site_user');
        $this->db->where('id_user', $id);
        $query = $this->db->get();
        $return = ($query->num_rows() == 0) ? TRUE : FALSE;
        return $return;
    }

    /**
     * Validation du formulaire
     */
    public function validate()
    {
        $data = array();
        $html = '';
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        // id_user
        $id_user = $this->input->post('id_user');
        $num_agent = substr($this->input->post('id_user'), strpos($this->input->post('id_user'), '-') + 1);
        if ($id_user == '')
        {
            $data['inputerror'][] = 'id_user';
            $data['error_string'][] = 'L\'indentifiant de l\'utilisateur est obligatoire';
            $data['status'] = FALSE;
        }
        elseif (strpos($id_user, ' ') > 0)
        {
            $data['inputerror'][] = 'id_user';
            $data['error_string'][] = 'L\'indentifiant de l\'utilisateur ne doit pas contenir d\'espace';
            $data['status'] = FALSE;
        }
        elseif (strpos($id_user, '-') === 0)
        {
            $data['inputerror'][] = 'id_user';
            $data['error_string'][] = 'L\'indentifiant de l\'utilisateur doit contenir un tiret (exemple : DUPONT-04862)';
            $data['status'] = FALSE;
        }
        elseif (strlen($num_agent) != 5)
        {
            $data['inputerror'][] = 'id_user';
            $data['error_string'][] = 'Le numéro d\'agent doit être sur 5 positions (exemple : DUPONT-04862)';
            $data['status'] = FALSE;
        }
        elseif (is_numeric($num_agent) === FALSE)
        {
            $data['inputerror'][] = 'id_user';
            $data['error_string'][] = 'Le numéro d\'agent doit être être un entier sur 5 positions (exemple : DUPONT-04862)';
            $data['status'] = FALSE;
        }
        // name
        if ($this->input->post('name') === '')
        {
            $data['inputerror'][] = 'name';
            $data['error_string'][] = 'Le nom de l\'utililisateur est obligatoire';
            $data['status'] = FALSE;
        }
        // role
        if ($this->input->post('role') === '')
        {
            $data['inputerror'][] = 'role';
            $data['error_string'][] = 'Le choix du rôle est obligatoire';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE)
        {
            echo json_encode(array('data' => $data, 'html' => $html));
            exit();
        }
    }

}
