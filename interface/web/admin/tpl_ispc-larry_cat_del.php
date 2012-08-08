<?php

/******************************************
* Begin Form configuration
******************************************/

$list_def_file = "list/tpl_ispc-larry_cat.list.php";
$tform_def_file = "formtpl_ispc-larry_cat.tform.php";

/******************************************
* End Form configuration
******************************************/

require_once('../../lib/config.inc.php');
require_once('../../lib/app.inc.php');

//* Check permissions for module
$app->auth->check_module_permissions('admin');

$app->uses('tpl,tform,tform_actions');
$app->load('tform_actions');

class page_action extends tform_actions {

	function onBeforeDelete() {
		global $app; $conf;
		
		if($app->tform->checkPerm($this->id,'d') == false) $app->error($app->lng('error_no_delete_permission'));
		
		// Delete all records that belog to this zone.
		$records = $app->db->queryAllRecords("SELECT cat_id FROM ispc_larry_cat WHERE cat_id = '".intval($this->cat_id)."'");
		foreach($records as $rec) {
			$app->db->datalogDelete('tpl_ispc_larry_cat','cat_id',$rec['cat_id']);
		}
	}
}

$page = new page_action;
$page->onDelete();

?>