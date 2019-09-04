<?php
include '../../config.php';
global $DB;

$http_origin = $_SERVER['HTTP_ORIGIN'];
if($http_origin) {
	header("Access-Control-Allow-Origin: $http_origin");
	header('Access-Control-Allow-Credentials: true');
}
$clientsite 	= $_GET['clientsite'];
//$userid 	= $_GET['userid'];
$clientprivateip = $_GET['clientprivateip'];
$clientpublicip = $_GET['clientpublicip'];
$clientSiteTitle = $_GET['clientsitetitle'];
$coursename = htmlspecialchars($_GET['coursename']);
$companyname = htmlspecialchars($_GET['company']);

$record = new stdClass();
//$record->userid	= '1';//$userid;
//$record->courseid 	= '1';//$courseid ;
$record->sitelink 	= $clientsite;
$record->privateip 	= $clientprivateip;
$record->publicip 	= $clientpublicip;
$record->sitetitle 	= $clientSiteTitle;
$record->coursename 	= $coursename;
$record->company    	= $companyname;

$record->timemodified = time();
$checkRecord = array('sitelink'=>$clientsite,'privateip'=>$clientprivateip,'publicip'=>$clientpublicip,'sitetitle'=>$clientSiteTitle);

$checkExistance = $DB->get_record('report_scorm_download',$checkRecord);
if (empty($checkExistance)) 
{
	$record->timecreated = time();
	$record->count = 1;
	$lastInsertId = $DB->insert_record('report_scorm_download', $record);
}
else
{
	$record->count = $checkExistance->count+1;
	$record->id = $checkExistance->id;
	$DB->update_record('report_scorm_download', $record);

}
