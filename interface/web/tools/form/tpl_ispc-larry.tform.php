<?php

/*
	Form Definition

	Tabellendefinition

	Datentypen:
	- INTEGER (Wandelt Ausdr�cke in Int um)
	- DOUBLE
	- CURRENCY (Formatiert Zahlen nach W�hrungsnotation)
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
	- CHECKBOXARRAY
	- FILE

	VALUE:
	- Wert oder Array

	Hinweis:
	Das ID-Feld ist nicht bei den Table Values einzuf�gen.


*/

$form['title'] 		= 'tpl_ispc-larry_head_txt';
$form['description'] 	= 'tpl_ispc-larry_desc_txt';
$form['name'] 		= 'tpl_ispc-larry';
$form['action']		= 'tpl_ispc-larry.php';
$form['db_table']	= 'sys_user'; // needs to be 'sys_user_theme'
$form['db_table_idx']	= 'userid'; //??
$form["db_history"]	= "no";
$form['tab_default']	= 'main';
$form['list_default']	= 'index.php';
$form['auth']		= 'no'; //?

//* 0 = id of the user, > 0 id must match with id of current user
$form['auth_preset']['userid']  = 0; 
//* 0 = default groupid of the user, > 0 id must match with groupid of current user
$form['auth_preset']['groupid'] = 0; 

//** Permissions are: r = read, i = insert, u = update, d = delete
$form['auth_preset']['perm_user']  = 'riud';
$form['auth_preset']['perm_group'] = 'riud';
$form['auth_preset']['perm_other'] = '';

//* Pick out modules
//* TODO: limit to activated modules of the user
$modules_list = array();
$handle = @opendir(ISPC_WEB_PATH); 
while ($file = @readdir ($handle)) { 
    if ($file != '.' && $file != '..') {
        if(@is_dir(ISPC_WEB_PATH."/$file")) {
            if(is_file(ISPC_WEB_PATH."/$file/lib/module.conf.php") and $file != 'login' && $file != 'designer' && $file != 'mailuser') {
				$modules_list[$file] = $file;
			}
        }
	}
}

//* Languages
$language_list = array();
$handle = @opendir(ISPC_ROOT_PATH.'/lib/lang'); 
while ($file = @readdir ($handle)) { 
    if ($file != '.' && $file != '..') {
        if(@is_file(ISPC_ROOT_PATH.'/lib/lang/'.$file) and substr($file,-4,4) == '.lng') {
			$tmp = substr($file, 0, 2);
			$language_list[$tmp] = $tmp;
        }
	}
} 

//* Load themes
$themes_list = array();
$handle = @opendir(ISPC_THEMES_PATH); 
while ($file = @readdir ($handle)) { 
    if (substr($file, 0, 1) != '.') {
        if(@is_dir(ISPC_THEMES_PATH."/$file")) {
			$themes_list[$file] = $file;
        }
	}
}

$form['tabs']['main'] = array (
	'title' 	=> 'Settings',
	'width' 	=> 80,
	'template' 	=> 'templates/interface_settings.htm',
	'fields' 	=> array (
	##################################
	# Beginn Datenbankfelder
	##################################
                'startmodule' => array (
			'datatype'	=> 'VARCHAR',
			'formtype'	=> 'SELECT',
			'regex'		=> '',
			'errmsg'	=> '',
			'default'	=> '',
			'value'		=> $modules_list,
			'separator'	=> '',
			'width'		=> '30',
			'maxlength'	=> '255',
			'rows'		=> '',
			'cols'		=> ''
		),
		'language' => array (
			'datatype'	=> 'VARCHAR',
			'formtype'	=> 'SELECT',
                        'validators'	=> array ( 0 => array (	'type'	=> 'NOTEMPTY',
                                                                'errmsg'=> 'language_is_empty'),
                                                   1 => array (	'type'	=> 'REGEX',
                                                                'regex' => '/^[a-z]{2}$/i',
                                                                'errmsg'=> 'language_regex_mismatch'),
                                                ),
			'regex'		=> '',
			'errmsg'	=> '',
			'default'	=> '',
			'value'		=> $language_list,
			'separator'	=> '',
			'width'		=> '30',
			'maxlength'	=> '2',
			'rows'		=> '',
			'cols'		=> ''
		),
                'app_theme' => array (
                            'datatype'	=> 'VARCHAR',
                            'formtype'	=> 'SELECT',
                            'regex'	=> '',
                            'errmsg'	=> '',
                            'default'	=> 'default',
                            'value'	=> $themes_list,
                            'separator'	=> '',
                            'width'	=> '30',
                            'maxlength'	=> '255',
                            'rows'	=> '',
                            'cols'	=> ''
                    )
	##################################
	# ENDE Datenbankfelder
	##################################
	)
);


?>
