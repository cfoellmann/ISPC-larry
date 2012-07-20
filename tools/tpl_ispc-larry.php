<?php
/*
Copyright (c) 2008, Till Brehm, projektfarm Gmbh
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
