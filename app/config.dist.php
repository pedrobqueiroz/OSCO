<?php 

$w_config = [
   	//information de connexion à la bdd
	'db_host' => '',						//hôte (ip, domaine) de la bdd
    'db_user' => '',							//nom d'utilisateur pour la bdd
    'db_pass' => '',								//mot de passe de la bdd
    'db_name' => '',								//nom de la bdd
    'db_table_prefix' => '',						//préfixe ajouté aux noms de table

	//authentification, autorisation
	'security_user_table' => 'users',				//nom de la table contenant les infos des utilisateurs
	'security_id_property' => '',					//nom de la colonne pour la clef primaire
	'security_username_property' => '',		//nom de la colonne pour le "pseudo"
	'security_email_property' => '',			//nom de la colonne pour l'"email"
	'security_password_property' => '',		//nom de la colonne pour le "mot de passe"
	'security_role_property' => '',				//nom de la colonne pour le "role"

	'security_login_route_name' => 'login',			//nom de la route affichant le formulaire de connexion

	// configuration globale
	'site_name'	=> '', 								// contiendra le nom du site
];

require('routes.php');

