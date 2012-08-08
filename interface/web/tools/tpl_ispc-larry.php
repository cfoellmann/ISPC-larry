<?php

/******************************************
* Begin Form configuration
******************************************/

$tform_def_file = "form/tpl_ispc-larry.tform.php";

/******************************************
* End Form configuration
******************************************/

require_once('../../lib/config.inc.php');
require_once('../../lib/app.inc.php');

//* Check permissions for module
$app->auth->check_module_permissions('tools');

// Loading classes
$app->uses('tpl,tform,tform_actions');
$app->load('tform_actions');

class page_action extends tform_actions {
	
	function onLoad() {
                global $app, $conf, $tform_def_file;

                // Loading template classes and initialize template
                if(!is_object($app->tpl)) $app->uses('tpl');
                if(!is_object($app->tform)) $app->uses('tform');

                $app->tpl->newTemplate("tabbed_form.tpl.htm");

                // Load table definition from file
                $app->tform->loadFormDef($tform_def_file);
				
				// Importing ID
                $this->id = $_SESSION['s']['user']['userid'];
		$_POST['id'] = $_SESSION['s']['user']['userid'];

                if(count($_POST) > 1) {
                        $this->dataRecord = $_POST;
                        $this->onSubmit();
                } else {
                        $this->onShow();
                }
        }
        
	function onBeforeInsert() {
		global $app, $conf;
		
		if(!in_array($this->dataRecord['startmodule'],$this->dataRecord['modules'])) {
			$app->tform->errorMessage .= $app->tform->wordbook['startmodule_err'];
		}
	}
        
	function onInsert() {
		die('No inserts allowed.');
	}
		
	function onBeforeUpdate() {
		global $app, $conf;
		
		if($conf['demo_mode'] == true && $this->id <= 3) $app->tform->errorMessage .= 'This function is disabled in demo mode.';
		
		$_SESSION['s']['user']['language'] = $_POST['language'];
		$_SESSION['s']['language'] = $_POST['language'];
                
                if(@is_array($this->dataRecord['modules']) && !in_array($this->dataRecord['startmodule'],$this->dataRecord['modules'])) {
			$app->tform->errorMessage .= $app->tform->wordbook['startmodule_err'];
		}
	}
	
	
}

$page = new page_action;
$page->onLoad();

?>
