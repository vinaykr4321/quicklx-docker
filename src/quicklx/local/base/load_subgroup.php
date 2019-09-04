<?php

require_once(dirname(__FILE__).'/../../config.php');
require_once('locallib.php');

$companyid = optional_param('companyid', 0, PARAM_RAW);
global $DB;

	$allsubgroups =array('0'=>"Select a Group first");	
	
	if(isset($companyid)){
		$allsubgroups=array('0'=>'Select Subgroup');
		$companyids =explode(',',$companyid);

		foreach($companyids as $key=>$value){	
			if($value != '0'){
				$subgroups =base::selectsubgroup($value);
				foreach($subgroups as $key1=>$value1){
					if($key1 != '0'){
						$allsubgroups[$key1] = $value1;
					}
				}
			}		
		}
	}
	echo json_encode($allsubgroups);
	
	
?>

