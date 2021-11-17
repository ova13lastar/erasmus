<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Config specifique à la caisse
 */

date_default_timezone_set('Europe/Paris');

// Numero de la CPAM
$config['app_caisse'] = 624;
// SIRET des sites (utile pour authentification SRVSEC)
$config['app_caisse_sites'] = array(
	'51510213500016',
	'18003502400256'
);
// Memoire max du serveur
$config['memory_limit'] = '1000M';
// Page d'index
$config['index_page'] = '';

/**
 * Config specifique à l'application
 */
// Slug de l'application
$config['app_slug'] = 'erasmus';
// Nom de l'application
$config['app_name'] = 'ERASMUS';
// Numero de version
$config['app_num_version'] = '1.0.0';
// Date de la version
$config['app_dt_version'] = '17/11/2021';
// Liste des agents habilités
$config['users_granted'] = array('DANIEL-03598', 'WUILLAUME-01965', 'DELEERSNYDER-01845', 'STRUGALA-02180', 'PORQUET-02765', 'SAKWINSKI-01961');
// Activation du mode maintenance (TRUE = maintenance activée)
$config['maintenance_mode'] = FALSE;
// Protocole de l'application (http ou https)
$config['app_protocol'] = 'http';
// Determination de l'url de base
$config['base_url'] = $config['app_protocol'].'://'.$_SERVER['SERVER_NAME'].'/'.$config['app_slug'].'/';
// Determination du libellé complet de la version
$config['app_version'] = 'v'.$config['app_num_version'].' du '.$config['app_dt_version'];
// Repertoire des fichiers (lecture / écriture)
$config['files_path'] = '_files';
// Duree maximum d'excution du script (0 = illimité) : utile pour import scor
$config['max_execution_time'] = 0;
// Nb de jours a partir duquel les lignes de la table de supervision sont purgées
$config['purge_older_than'] = 180;
// SYSTEME Access Master
$config['access_master_systeme'] = 'SY_'.strtoupper($config['app_slug']);
// Roles de l'application
$config['user_roles'] = array(
	array('role_user' => '9', 'role_user_access_master' => 'ERASMUS_ADMINISTRATEUR', 'role_user_name' => 'Administrateur')	
);
// Nom a affecter au role sans habilitation
$config['user_role_empty_name'] = 'Sans habilitation';
// Config session
$config['sess_driver'] = 'files';
$config['sess_cookie_name'] = 'PHPSESSID';
