<?php

/*
Copyright (c) 2007, Till Brehm, projektfarm Gmbh
All rights reserved.

Redistribution and use in source and binary forms, with or without modification,
are permitted provided that the following conditions are met:

    * Redistributions of source code must retain the above copyright notice,
      this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright notice,
      this list of conditions and the following disclaimer in the documentation
      and/or other materials provided with the distribution.
    * Neither the name of ISPConfig nor the names of its contributors
      may be used to endorse or promote products derived from this software without
      specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT,
INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY
OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE,
EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

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

$form["title"] 		= "tpl_ispc-larry_admin_head_txt";
$form["description"] 	= "tpl_ispc-larry_admin_desc_txt";
$form["name"] 		= "tpl_ispc-larry_admin";
$form["action"]		= "tpl_ispc-larry_admin.php";
$form["db_table"]	= "server_php";
$form["db_table_idx"]	= "server_php_id";
$form["db_history"]	= "yes";
$form["tab_default"]	= "php_name";
$form["list_default"]	= "server_php_list.php";
$form["auth"]		= 'yes';

$form["auth_preset"]["userid"]  = 0; // 0 = id of the user, > 0 id must match with id of current user
$form["auth_preset"]["groupid"] = 0; // 0 = default groupid of the user, > 0 id must match with groupid of current user
$form["auth_preset"]["perm_user"] = 'riud'; //r = read, i = insert, u = update, d = delete
$form["auth_preset"]["perm_group"] = 'riud'; //r = read, i = insert, u = update, d = delete
$form["auth_preset"]["perm_other"] = ''; //r = read, i = insert, u = update, d = delete

$form["tabs"]['php_name'] = array (
	'title' 	=> "Name",
	'width' 	=> 80,
	'template' 	=> "templates/server_php_name_edit.htm",
	'fields' 	=> array (
	##################################
	# Beginn Datenbankfelder
	##################################
		'server_id' => array (
			'datatype'	=> 'INTEGER',
			'formtype'	=> 'SELECT',
			'default'	=> '',
			'datasource'	=> array ( 	'type'	=> 'SQL',
										'querystring' => 'SELECT server_id,server_name FROM server WHERE {AUTHSQL} ORDER BY server_name',
										'keyfield'=> 'server_id',
										'valuefield'=> 'server_name'
									 ),
			'value'		=> ''
		),
		'client_id' => array (
			'datatype'	=> 'INTEGER',
			'formtype'	=> 'SELECT',
			'default'	=> '',
			'datasource'	=> array ( 	'type'	=> 'SQL',
										'querystring' => "SELECT client_id,CONCAT(contact_name,' :: ',username) as name FROM client WHERE {AUTHSQL} ORDER BY contact_name",
										'keyfield'=> 'client_id',
										'valuefield'=> 'name'
									 ),
			'value'		=> array(0 => ' ')
		),
		'name' => array (
			'datatype'	=> 'VARCHAR',
			'formtype'	=> 'TEXT',
			'validators' => array(0 => array('type' => 'NOTEMPTY',
											 'errmsg' => 'server_php_name_error_empty'),
			),
			'default'	=> '',
			'value'		=> '',
			'separator'	=> '',
			'width'		=> '40',
			'maxlength'	=> '255'
		),
	##################################
	# ENDE Datenbankfelder
	##################################
	)
);

$form["tabs"]['php_fastcgi'] = array(
	'title' => "FastCGI Settings",
	'width' => 80,
	'template' => "templates/server_php_fastcgi_edit.htm",
	'fields' => array(
		##################################
		# Begin Datatable fields
		##################################
		'php_fastcgi_binary' => array(
			'datatype' => 'VARCHAR',
			'formtype' => 'TEXT',
			'default' => '',
			'value' => '',
			'width' => '40',
			'maxlength' => '255'
		),
		'php_fastcgi_ini_dir' => array(
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

$form["tabs"]['php_fpm'] = array(
	'title' => "PHP-FPM Settings",
	'width' => 80,
	'template' => "templates/server_php_fpm_edit.htm",
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
?>