<?php

require_once(dirname(__FILE__).'/../../config.php');
require_once(dirname(__FILE__).'/locallib.php');

$student = optional_param('student', '', PARAM_RAW);
$selecteduser = optional_param('selecteduser', '', PARAM_RAW);
$departmentid = optional_param('departmentid', '', PARAM_RAW);
global $DB;
	if($student == 0 )
		$selecteduser = $selecteduser;
	else
		$selecteduser = $selecteduser.','.$student;
		
	$studentarray=explode(',',$selecteduser);

	$usernames =base::selectusernames($departmentid,$studentarray);

	echo json_encode($usernames);


	?>
