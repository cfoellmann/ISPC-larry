<?php

/*
	Form Definition

	Tabledefinition

	Datatypes:
	- INTEGER (Forces the input to Int)
	- DOUBLE
	- CURRENCY (Formats the values to currency notation)
	- VARCHAR (no format check, maxlength: 255)
	- TEXT (no format check)
	- DATE (Dateformat, automatic conversion to timestamps)

	Formtype:
	- TEXT (Textfield)
	- TEXTAREA (Textarea)
	- PASSWORD (Password textfield, input is not shown when edited)
	- SELECT (Select option field)
	- RADIO
	- CHECKBOX
	- CHECKBOXARRAY
	- FILE

	VALUE:
	- Wert oder Array

	Hint:
	The ID field of the database table is not part of the datafield definition.
	The ID field must be always auto incement (int or bigint).


*/

$form["title"] 		= "App Settings";
$form["description"] 	= "";
$form["name"] 		= "tpl_ispc-larry_app";
$form["action"]		= "tpl_ispc-larry_app_edit.php";
$form["db_table"]	= "tpl_ispc_larry_apps";
$form["db_table_idx"]	= "app_id";
$form["db_history"]	= "yes";
$form["tab_default"]	= "app";
$form["list_default"]	= "dns_a_list.php";
$form["auth"]		= 'yes'; // yes / no

$form["auth_preset"]["userid"]  = 0; // 0 = id of the user, > 0 id must match with id of current user
$form["auth_preset"]["groupid"] = 0; // 0 = default groupid of the user, > 0 id must match with groupid of current user
$form["auth_preset"]["perm_user"] = 'riud'; //r = read, i = insert, u = update, d = delete
$form["auth_preset"]["perm_group"] = 'riud'; //r = read, i = insert, u = update, d = delete
$form["auth_preset"]["perm_other"] = ''; //r = read, i = insert, u = update, d = delete

$form["tabs"]['app'] = array (
	'title' 	=> "App Settings",
	'width' 	=> 100,
	'template' 	=> "templates/tpl_ispc-larry_app_edit.htm",
	'fields' 	=> array (
	##################################
	# Begin Datatable fields
	##################################
		'server_id' => array (
			'datatype'	=> 'INTEGER',
			'formtype'	=> 'SELECT',
			'default'	=> '',
			'value'		=> '',
			'width'		=> '30',
			'maxlength'	=> '255'
		),
		'zone' => array (
			'datatype'	=> 'INTEGER',
			'formtype'	=> 'TEXT',
			'default'	=> @intval($_REQUEST["zone"]),
			'value'		=> '',
			'width'		=> '30',
			'maxlength'	=> '255'
		),
		'name' => array (
			'datatype'	=> 'VARCHAR',
			'formtype'	=> 'TEXT',
			'validators'	=> array ( 	0 => array (	'type'	=> 'NOTEMPTY',
														'errmsg'=> 'name_error_empty'),
										1 => array (	'type'	=> 'REGEX',
														'regex' => '/^[\w\.\-]{1,255}$/',
														'errmsg'=> 'name_error_regex'),
									),
			'default'	=> '',
			'value'		=> '',
			'width'		=> '30',
			'maxlength'	=> '255'
		),
		'type' => array (
			'datatype'	=> 'VARCHAR',
			'formtype'	=> 'TEXT',
			'default'	=> 'ALIAS',
			'value'		=> '',
			'width'		=> '5',
			'maxlength'	=> '5'
		),
		'data' => array (
			'datatype'	=> 'VARCHAR',
			'formtype'	=> 'TEXT',
			'validators'	=> array ( 	0 => array (	'type'	=> 'NOTEMPTY',
														'errmsg'=> 'data_error_empty'),
										1 => array (	'type'	=> 'REGEX',
														'regex' => '/^[\w\.\-]{1,64}$/',
														'errmsg'=> 'data_error_regex'),
									),
			'default'	=> '',
			'value'		=> '',
			'width'		=> '30',
			'maxlength'	=> '255'
		),
		/*
		'aux' => array (
			'datatype'	=> 'INTEGER',
			'formtype'	=> 'TEXT',
			'default'	=> '0',
			'value'		=> '',
			'width'		=> '10',
			'maxlength'	=> '10'
		),
		*/
		'ttl' => array (
			'datatype'	=> 'INTEGER',
			'formtype'	=> 'TEXT',
			'default'	=> '86400',
			'value'		=> '',
			'width'		=> '10',
			'maxlength'	=> '10'
		),
		'active' => array (
			'datatype'	=> 'VARCHAR',
			'formtype'	=> 'CHECKBOX',
			'default'	=> 'Y',
			'value'		=> array(0 => 'N',1 => 'Y')
		),
		'stamp' => array (
			'datatype'	=> 'VARCHAR',
			'formtype'	=> 'TEXT',
			'default'	=> '',
			'value'		=> '',
			'width'		=> '30',
			'maxlength'	=> '255'
		),
		'serial' => array (
			'datatype'	=> 'INTEGER',
			'formtype'	=> 'TEXT',
			'default'	=> '',
			'value'		=> '',
			'width'		=> '10',
			'maxlength'	=> '10'
		),
	##################################
	# ENDE Datatable fields
	##################################
	)
);



?>