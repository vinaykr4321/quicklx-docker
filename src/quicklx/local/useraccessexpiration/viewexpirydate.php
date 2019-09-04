<?php
require_once('../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_login();
admin_externalpage_setup('useraccessexpiration');

echo $OUTPUT->header();

$table = new html_table();
$table->head = array ('Full name', 'Email', 'City', 'Country', 'Access expiry date');
$table->width = "95%";

$i = 1;
$allData = implode(',', $_GET);
if (!empty($allData)) 
{
	$records =  $DB->get_records_sql("SELECT u.firstname,u.lastname,u.email,u.city,u.country,ue.accessexpirydate FROM {user} AS u JOIN {user_expiry} AS ue on ue.userid = u.id WHERE ue.userid IN ($allData) order by u.firstname asc");
	$table->align = array ("center", "center", "center","center", "center", "center");

	foreach ($records as $dataToDisplay) 
	{
		$table->data[] = array($dataToDisplay->firstname.' '.$dataToDisplay->lastname, $dataToDisplay->email, $dataToDisplay->city, $dataToDisplay->country, date('m-d-Y',$dataToDisplay->accessexpirydate));
		$i++;
	}
}
echo html_writer::table($table);

echo $OUTPUT->footer();

