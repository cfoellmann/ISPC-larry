<?php

/*
	Form Definition

	Tabellendefinition

	Datentypen:
	- INTEGER (Wandelt Ausdrücke in Int um)
	- DOUBLE
	- CURRENCY (Formatiert Zahlen nach Währungsnotation)
	- VARCHAR (kein weiterer Format Check)
	- TEXT (kein weiterer Format Check)
	- DATE (Datumsformat, Timestamp Umwandlung)

	Formtype:
	- TEXT (normales Textfeld)
	- TEXTAREA (normales Textfeld)
	- PASSWORD (Feldinhalt wird nicht angezeigt)
	- SELECT (Gibt Werte als option Feld aus)
	- RADIO
	- CHECKBOX
	- FILE

	VALUE:
	- Wert oder Array

	Hinweis:
	Das ID-Feld ist nicht bei den Table Values einzufügen.


*/

$form["title"] 		= "tpl_ispc-larry_head_txt";
$form["description"] 	= "tpl_ispc-larry_desc_txt";
$form["name"] 		= "tpl_ispc-larry";
$form["action"]		= "tpl_ispc-larry.php";
$form["db_table"]	= "tpl_ispc_larry";
$form["db_table_idx"]	= "id";
$form["db_history"]	= "yes";
$form["tab_default"]	= "basic";
$form["list_default"]	= "system_config_edit.php";
$form["auth"]		= 'yes';

$form["auth_preset"]["userid"]  = 0; // 0 = id of the user, > 0 id must match with id of current user
$form["auth_preset"]["groupid"] = 0; // 0 = default groupid of the user, > 0 id must match with groupid of current user
$form["auth_preset"]["perm_user"] = 'riud'; //r = read, i = insert, u = update, d = delete
$form["auth_preset"]["perm_group"] = 'riud'; //r = read, i = insert, u = update, d = delete
$form["auth_preset"]["perm_other"] = ''; //r = read, i = insert, u = update, d = delete

$form["tabs"]['basic'] = array (
	'title' 	=> "Basic Settings",
	'width' 	=> 80,
	'template' 	=> "templates/tpl_ispc-larry_basic.htm",
	'fields' 	=> array (
	##################################
	# Beginn Datenbankfelder
	##################################
                'username' => array (
			'datatype'	=> 'VARCHAR',
			'formtype'	=> 'TEXT',
			'validators'    => '',
			'default'	=> 'global',
			'value'		=> 'global',
			'separator'	=> '',
			'width'		=> '40',
			'maxlength'	=> '64'
		),
		'logo_url' => array (
			'datatype'	=> 'VARCHAR',
			'formtype'	=> 'TEXT',
			'validators'    => '',
			'default'	=> '',
			'value'		=> '',
			'separator'	=> '',
			'width'		=> '40',
			'maxlength'	=> '255'
		),
                'sidebar_state' => array (
			'datatype'	=> 'INTEGER',
			'formtype'	=> 'TEXT',
			'validators'    => '',
			'default'	=> '',
			'value'		=> '',
			'separator'	=> '',
			'width'		=> '40'
		),
	##################################
	# ENDE Datenbankfelder
	##################################
	)
);
/*
$form["tabs"]['links'] = array(
	'title' => "Links",
	'width' => 80,
	'template' => "templates/tpl_ispc-larry_links.htm",
	'fields' => array(
		##################################
		# Begin Datatable fields
		##################################
		'php_fpm_init_script' => array(
			'datatype' => 'VARCHAR',
			'formtype' => 'TEXT',
			'default' => '',
			'value' => '',
			'width' => '40',
			'maxlength' => '255'
		),
		'php_fpm_ini_dir' => array(
			'datatype' => 'VARCHAR',
			'formtype' => 'TEXT',
			'default' => '',
			'value' => '',
			'width' => '40',
			'maxlength' => '255'
		),
		'php_fpm_pool_dir' => array(
			'datatype' => 'VARCHAR',
			'formtype' => 'TEXT',
			'default' => '',
			'value' => '',
			'width' => '40',
			'maxlength' => '255'
		),
	##################################
	# ENDE Datatable fields
	##################################
	)
);
*/
?>