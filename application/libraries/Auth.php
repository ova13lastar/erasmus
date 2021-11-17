<?php
session_cache_limiter('nocache, must-revalidate');
header('x-ua-compatible: ie=edge');
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth
{

    /**
     * Objet CodeIgniter
     * @var object
     */
    protected $CI;

    /**
     * Constructeur qui récupère l'instance de CI et qui demarre ou reprend la session en cours
     */
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE)
        {
            session_start();
        }
        $this->CI = & get_instance();
        $this->CI->load->model('user_model', 'user');
    }

    /**
     * On verification si l'utilisateur fait partie de la CPAM
     */
    public function check_auth()
    {
        // Erreur si navigateur trop vieux
        $this->check_http_user_agent();
        //  Si PHPSESSID n'existe pas => on appelle le serveur SRVSEC
        if (isset($_SESSION['PHPSESSID']) === FALSE)
        {
            echo 'Authentification SRVSEC en cours...';
            $this->call_srvsec();
            exit;
        }
        else
        {
            //  Si identif_user n'existe pas => le jeton doit etre recupere et les donnes placees dans $_SESSION
            if (isset($_SESSION['identif_user']) === FALSE)
            {
                // On recupere le jeton
                $jeton = $this->get_jeton();
                //debug($jeton);
                // On place les infos du jeton dans $_SESSION
                $this->set_jeton_in_session($jeton);
                // Si utilisateur n'est pas de la CPAM => on bloque l'accès
                if (in_array($_SESSION['site'], $this->CI->config->item('app_caisse_sites')) === FALSE)
                {
                    $msg = "ECHEC AUTHENTIFICATION ACCESSMASTER pour ".$_SESSION['identif_user']." : votre site d'appartenance n'est pas correctement référencé (".$_SESSION['site'].")";
                    echo $msg;
                    log_message('ERROR', $msg);
                    session_destroy();
                    exit;
                }
                // Sinon on redirige vers la page d'accueil
                else
                {
                    $base_url = $this->CI->config->item('base_url');
                    header("location:$base_url");
                    exit;
                }
            }
        }
        //  Si utilisateur n'a pas d'habilitation ACCESS MASTER => => on bloque l'accès
        if ( (empty($_SESSION['role_user']) === TRUE) && (uri_string() !== 'acces_sans_habilitation') )  {
            $msg = "ECHEC AUTHENTIFICATION ACCESSMASTER pour ".$_SESSION['identif_user']." : vous n'avez pas les droits nécessaires pour accéder à l'application ".$this->CI->config->item('access_master_systeme');
            log_message('ERROR', $msg);
            $_SESSION['role_user_name'] = $this->CI->config->item('user_role_empty_name');
            $url = $this->CI->config->item('base_url').'acces_sans_habilitation/';
            header("location:$url");
            exit;
        }
    }

    /**
     * Retourne le nom du role pour un role_id donnee
     */
    public function get_user_role_name($role_id)
    {
        $arr_roles = $this->CI->config->item('user_roles');
        $role_user_name = $this->CI->config->item('user_role_empty_name');
        foreach ($arr_roles as $role)
        {
            if ($role_id === $role['role_user'])
            {
                $role_user_name = $role['role_user_name'];
                break;
            }
        }
        return $role_user_name;
    }

    /**
     * Retourne le role access master pour un role_id donnee
     *
     * @param string $role_id
     * @return string
     */
    public function get_role_user_access_master($role_id)
    {
        $arr_roles = $this->CI->config->item('user_roles');
        $role_user_access_master = $arr_roles[0];
        foreach ($arr_roles as $role)
        {
            if ($role_id === $role['role_user'])
            {
                $role_user_access_master = $role['role_user_access_master'];
                break;
            }
        }
        return $role_user_access_master;
    }

    /**
     * Renvoi vrai si role >= agent
     * @return boolean
     */
    public function is_agent()
    {
        return ($_SESSION['role_user'] >= 1) ? TRUE : FALSE;
    }

    /**
     * Renvoi vrai si role >= superviseur
     * @return boolean
     */
    public function is_superviseur()
    {
        return ($_SESSION['role_user'] >= 5) ? TRUE : FALSE;
    }

    /**
     * Renvoi vrai si role >= responsable
     * @return boolean
     */
    public function is_responsable()
    {
        return ($_SESSION['role_user'] >= 6) ? TRUE : FALSE;
    }

    /**
     * Renvoi vrai si role = administrateur
     * @return boolean
     */
    public function is_admin()
    {
        return ($_SESSION['role_user'] == 9) ? TRUE : FALSE;
    }

    /**
     * Actions effectuées uniquement lors de la connexion pour un agent habilité
     */
    public function user_connexion_actions()
    {
        // Mise a jour des infos de connexion
        log_message('APP  ', $_SESSION['identif_user'].' : Connexion a l\'application');
        $where = array('id_user' => $_SESSION['identif_user']);
        $data = array(
                'role' => $_SESSION['role_user'],
                'dt_last_connect' => date('Y-m-d H:i:s')
        );
        $this->CI->user->update($where, $data);
    }

    /**
     * Erreur si navigateur trop vieux
     */
    public function check_http_user_agent() {
        if (isset($_SERVER["HTTP_USER_AGENT"]))
        {
            if (preg_match('/MSIE *([0-9]*)/', $_SERVER["HTTP_USER_AGENT"], $reg))
            {
                if (intval($reg[1]) < 7)
                {
                    $msg = "Navigateur non conforme, trop ancien (".$reg[0].").";
                    log_message('ERROR',$msg);
                    die($msg);
                }
            }
        }
    }

    /**
     * Appel au serveur SRVSEC
     */
    public function call_srvsec()
    {
        //$Systeme = "APP_LOCALE";
        $Systeme = $this->CI->config->item('access_master_systeme');
        $Attrib = "ABCDEFGHIJLM0S1Q1";
        $Alea = strtoupper(sprintf("%08x%08x", rand(), rand()));
        if (isset($_SERVER['HTTPS']))
        {
            $URL_SRVSEC = "https://127.0.0.1:1924/";
            $URL_RETOUR_SRVSEC = "https://";
        }
        else
        {
            $URL_SRVSEC = "http://127.0.0.1:1923/";
            $URL_RETOUR_SRVSEC = "http://";
        }
        $WEB_SERVER = $_SERVER['HTTP_HOST'];
        $URL_RETOUR_SRVSEC .= $WEB_SERVER . $_SERVER['PHP_SELF'] . "?version=";
        $_SESSION["PHPSESSID"] = session_id();
        $_SESSION["QUERY_STRING"] = $_SERVER['QUERY_STRING'];
        session_write_close();
        ?>
        <SCRIPT LANGUAGE="Javascript">
            var etatComposant = null;
            alea = 100000 * (Math.random());
            var urlJS = "<?php print $URL_SRVSEC ?>testcomposant?" + alea;
            function urlTestComposant()
            {
                document.write("<SCRIPT LANGUAGE='JavaScript' SRC='" + urlJS + "'><\/SCRIPT>");
            }
            urlTestComposant();
        </SCRIPT>
        <FORM NAME="formSrvSec" ACTION="<?php print $URL_SRVSEC ?>secjava" METHOD="GET">
            <INPUT type="hidden" name="SERVEUR" value="<?php print $URL_RETOUR_SRVSEC ?>">
            <INPUT type="hidden" name="SYSTEM" value="<?php print $Systeme ?>">
            <INPUT type="hidden" name="ATTRIBUTS" value="<?php print $Attrib ?>">
            <INPUT type="hidden" name="ALEA" value="<?php print $Alea ?>">
        </FORM>
        <SCRIPT LANGUAGE="Javascript">
            if (etatComposant != null)
            {
                val = document.formSrvSec.SERVEUR.value + etatComposant
                document.formSrvSec.SERVEUR.value = val;
                document.formSrvSec.submit();
            } else
            {
                url = document.formSrvSec.elements["SERVEUR"].value + "null&Jeton=SRVSEC_INDISPONIBLE";
                document.location.href = url;
            }
        </SCRIPT>
        <?php
    }

    /**
     * Renvoi le jeton issu du SRVSEC
     * @return array
     */
    public function get_jeton()
    {
        if (isset($_POST['Jeton']))
            $jeton = $_POST['Jeton'];
        if (isset($_GET['Jeton']))
            $jeton = $_GET['Jeton'];
        if (array_key_exists("Jeton", $_GET) && $_GET['Jeton'] == "SRVSEC_INDISPONIBLE")
        {
            $msg = "ECHEC AUTHENTIFICATION ACCESSMASTER : le composant de sécurité (SRVSEC) sur le poste de travail est indisponible";
            echo $msg;
            log_message('ERROR', $msg);
            exit;
        }
        return $jeton;
    }

    /**
     * Place les infos du jeton dans $_SESSION
     * @param string $jeton [issu de la fonction get_jeton()]
     */
    public function set_jeton_in_session($jeton)
    {
        $tab_jeton = explode("_", $jeton);
        //$tab_jeton[4] contient le numéro d'agent
        if (is_numeric(substr($tab_jeton[4], 2)))
        {
            $_SESSION['identif_user'] = substr($tab_jeton[3], 2) . "-" . substr($tab_jeton[4], 2);
            $_SESSION['nom_user'] = substr($tab_jeton[3], 2);
            $long_nom = 1;
            if (is_numeric(substr($tab_jeton[7], 2)))
            {
                $_SESSION['prenom_user'] = substr($tab_jeton[6], 2);
                $_SESSION['site'] = substr($tab_jeton[7], 7);
            }
            elseif (is_numeric(substr($tab_jeton[8], 2)))
            {
                $long_var = strlen(substr($tab_jeton[6], 2));
                $tmp = substr(substr($tab_jeton[3], 2), -$long_var);
                if ($tmp == substr($tab_jeton[6], 2))
                {
                    $_SESSION['prenom_user'] = substr($tab_jeton[7], 2);
                }
                else
                {
                    $_SESSION['prenom_user'] = substr($tab_jeton[6], 2) . "-" . substr($tab_jeton[7], 2);
                }
                $_SESSION['site'] = substr($tab_jeton[8], 7);
            }
            elseif (is_numeric(substr($tab_jeton[9], 2)))
            {
                $_SESSION['prenom_user'] = substr($tab_jeton[6], 2) . "-" . substr($tab_jeton[7], 2) . "-" . substr($tab_jeton[8], 2);
                $_SESSION['site'] = substr($tab_jeton[9], 7);
            }
            elseif (is_numeric(substr($tab_jeton[10], 2)))
            {
                $_SESSION['prenom_user'] = substr($tab_jeton[9], 2);
                $_SESSION['site'] = substr($tab_jeton[10], 7);
            }
        }
        elseif (is_numeric(substr($tab_jeton[5], 2)))
        {
            $_SESSION['identif_user'] = substr($tab_jeton[3], 2) . "" . substr($tab_jeton[4], 2) . "-" . substr($tab_jeton[5], 2);
            //echo "Numéro d'agent : ".substr($tab_jeton[5],2)."<br>";
            $_SESSION['nom_user'] = substr($tab_jeton[3], 2) . " " . substr($tab_jeton[4], 2);
            $long_nom = 2;
            if (is_numeric(substr($tab_jeton[9], 2)))
            {
                $_SESSION['prenom_user'] = substr($tab_jeton[8], 2);
                $_SESSION['site'] = substr($tab_jeton[9], 7);
            }
            elseif (is_numeric(substr($tab_jeton[10], 2)))
            {
                $_SESSION['prenom_user'] = substr($tab_jeton[8], 2) . "-" . substr($tab_jeton[9], 2);
                $_SESSION['site'] = substr($tab_jeton[10], 7);
            }
            elseif (is_numeric(substr($tab_jeton[11], 2)))
            {
                $_SESSION['prenom_user'] = substr($tab_jeton[8], 2) . "-" . substr($tab_jeton[9], 2) . "-" . substr($tab_jeton[10], 2);
                $_SESSION['site'] = substr($tab_jeton[11], 7);
            }
        }
        elseif (is_numeric(substr($tab_jeton[6], 2)))
        {
            $_SESSION['identif_user'] = substr($tab_jeton[3], 2) . "" . substr($tab_jeton[4], 2) . "" . substr($tab_jeton[5], 2) . "-" . substr($tab_jeton[6], 2);
            //echo "Numéro d'agent : ".substr($tab_jeton[6],2)."<br>";
            $_SESSION['nom_user'] = substr($tab_jeton[3], 2) . " " . substr($tab_jeton[4], 2) . " " . substr($tab_jeton[5], 2);
            $long_nom = 3;
            if (is_numeric(substr($tab_jeton[11], 2)))
            {
                $_SESSION['prenom_user'] = substr($tab_jeton[10], 2);
                $_SESSION['site'] = substr($tab_jeton[11], 7);
            }
            elseif (is_numeric(substr($tab_jeton[12], 2)))
            {
                $_SESSION['prenom_user'] = substr($tab_jeton[10], 2) . "-" . substr($tab_jeton[11], 2);
                $_SESSION['site'] = substr($tab_jeton[12], 7);
            }
            elseif (is_numeric(substr($tab_jeton[13], 2)))
            {
                $_SESSION['prenom_user'] = substr($tab_jeton[10], 2) . "-" . substr($tab_jeton[11], 2) . "-" . substr($tab_jeton[12], 2);
                $_SESSION['site'] = substr($tab_jeton[13], 7);
            }
        }
        elseif (is_numeric(substr($tab_jeton[7], 2)))
        {
            $_SESSION['identif_user'] = substr($tab_jeton[3], 2) . "" . substr($tab_jeton[4], 2) . "" . substr($tab_jeton[5], 2) . "" . substr($tab_jeton[6], 2) . "-" . substr($tab_jeton[7], 2);
            //echo "Numéro d'agent : ".substr($tab_jeton[7],2)."<br>";
            $_SESSION['nom_user'] = substr($tab_jeton[3], 2) . " " . substr($tab_jeton[4], 2) . " " . substr($tab_jeton[5], 2) . " " . substr($tab_jeton[6], 2);
            $long_nom = 4;
            if (is_numeric(substr($tab_jeton[13], 2)))
            {
                $_SESSION['prenom_user'] = substr($tab_jeton[12], 2);
                $_SESSION['site'] = substr($tab_jeton[13], 7);
            }
            elseif (is_numeric(substr($tab_jeton[14], 2)))
            {
                $_SESSION['prenom_user'] = substr($tab_jeton[12], 2) . "-" . substr($tab_jeton[13], 2);
                $_SESSION['site'] = substr($tab_jeton[14], 7);
            }
            elseif (is_numeric(substr($tab_jeton[15], 2)))
            {
                $_SESSION['prenom_user'] = substr($tab_jeton[12], 2) . "-" . substr($tab_jeton[13], 2) . "-" . substr($tab_jeton[14], 2);
                $_SESSION['site'] = substr($tab_jeton[14], 7);
            }
        }
        // Initialisation du Service Access Master a blanc
        $_SESSION['role_user'] = "";
        $_SESSION['role_user_access_master'] = "";

        // Recuperation de tous les roles de l'application (cf. config_custom.php)
        $arr_roles = $this->CI->config->item('user_roles');
        // Boucle de recherche
        foreach ($arr_roles as $role)
        {
            $role_suffix = substr($role['role_user_access_master'], strpos($role['role_user_access_master'], "_")+1);
            // On verifie dans le jeton que la chaine $role_suffix existe
            if (strpos($jeton, $role_suffix) != false)
            {
                $_SESSION['role_user'] = $role['role_user'];
                $_SESSION['role_user_access_master'] = $this->get_role_user_access_master($_SESSION['role_user']);
                $_SESSION['role_user_name'] = $this->get_user_role_name($_SESSION['role_user']);
                break;
            }
        }
        //debug($_SESSION);

        /* ============= BOUCHON !!!!! ============= */        
        if (in_array($_SESSION['identif_user'], $this->CI->config->item('users_granted'))) {
            $_SESSION['role_user'] = '9';
            $_SESSION['role_user_access_master'] = $this->get_role_user_access_master($_SESSION['role_user']);
            $_SESSION['role_user_name'] = $this->get_user_role_name($_SESSION['role_user']);
        }

        //-----------
        $this->user_connexion_actions();
        //-----------
    }
}
