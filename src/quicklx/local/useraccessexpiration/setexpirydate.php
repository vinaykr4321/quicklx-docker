<?php
require_once('../../config.php');
require_once($CFG->libdir.'/adminlib.php');

$PAGE->requires->css('/local/useraccessexpiration/style.css'); 

require_once($CFG->dirroot.'/local/useraccessexpiration/form.php');
require_login();
admin_externalpage_setup('useraccessexpiration');

global $DB;
echo $OUTPUT->header();

$action_form = new set_expiration();
$action_form->display();
$data = $action_form->get_data();
if (!empty($data))
{
	if ($data->remaccess == '1') 
	{
		if (!empty($data->ids)) 
		{
			$oldData = $DB->get_records_sql("SELECT * FROM {user_expiry} WHERE userid IN ($data->ids)");
			foreach ($oldData as $oldDate)
			{
				$allData = getLicenseDays($oldDate->userid);
				$allData = json_decode(json_encode($allData), True);
				$id_arr = array();
				$final_arr = array();

				checkCourseidArray($allData, $id_arr, $final_arr);

				foreach ($final_arr as $fetchedData) 
				{
					$numDays = $fetchedData['validlength'];
					$startDate = $fetchedData['startdate'];
					$enrolid = $fetchedData['enrolid'];
					$newTimeEnd = strtotime("+$numDays days",$startDate);
					$updated = $DB->execute("UPDATE {user_enrolments} SET timeend = $newTimeEnd WHERE userid = $oldDate->userid AND enrolid = $enrolid");
				}
				$DB->execute("DELETE FROM {user_expiry} WHERE userid = $oldDate->userid");
			}
			echo '<div class="col-md-12 message">';
			echo 'Selected users are set with the original expiry date';
	    	$PAGE->set_url('/local/useraccessexpiration/index.php');
	    	echo "<br>".$OUTPUT->single_button(new moodle_url($PAGE->url, array()), 'Go to user access expiration');
			echo '</div>';
		}
		else
		{
			echo '<div class="col-md-12 message">';
			echo 'Please select the user first';
	    	$PAGE->set_url('/local/useraccessexpiration/index.php');
	    	echo "<br>".$OUTPUT->single_button(new moodle_url($PAGE->url, array()), 'Go to user access expiration');
			echo '</div>';
		}

	}
	else
	{
		if (!empty($data->ids)) 
		{
			//converting selected date to 11:59:59 PM time 
			$beginOfDay = strtotime("midnight", $data->assesstimefinish);
			$endOfDay   = strtotime("tomorrow", $beginOfDay) - 1;

			//var_dump($endOfDay);exit;
			$allRequiredData = $DB->get_records_sql("SELECT id,userid,timeend FROM {user_enrolments} WHERE userid IN ($data->ids)");
			foreach ($allRequiredData as $enrolledData) 
			{
				$DB->execute("DELETE FROM {user_expiry} WHERE userid = $enrolledData->userid");
				$DB->insert_record('user_expiry', array('userid'=>$enrolledData->userid, 'accessexpirydate'=>$endOfDay));
			}

			$updated = $DB->execute("UPDATE {user_enrolments} SET timeend = $endOfDay WHERE userid IN ($data->ids)");
			if ($updated) 
			{
				echo '<div class="col-md-12 message">';
				echo 'All selected users are set with the given expiry date';
		    	$PAGE->set_url('/local/useraccessexpiration/index.php');
		    	echo "<br>".$OUTPUT->single_button(new moodle_url($PAGE->url, array()), 'Go to user access expiration');
				echo '</div>';
			}
		}
		else
		{
			echo '<div class="col-md-12 message">';
			echo 'Please select the user first';
	    	$PAGE->set_url('/local/useraccessexpiration/index.php');
	    	echo "<br>".$OUTPUT->single_button(new moodle_url($PAGE->url, array()), 'Go to user access expiration');
			echo '</div>';
		}
	}

	
}
/*
if (empty($_GET)) 
{
    $urltogo = new moodle_url('/local/useraccessexpiration/index.php', array());
    redirect($urltogo);
}
*/


echo $OUTPUT->footer();

function getLicenseDays($userid)
{
	global $DB;
	$data = $DB->get_records_sql("SELECT @n := @n + 1 n ,cl.startdate,e.courseid,ue.enrolid,ue.timeend,cl.validlength,cl.expirydate FROM (SELECT @n := 0) m JOIN {user_enrolments} ue JOIN {enrol} e ON e.id=ue.enrolid JOIN {companylicense_users} clu ON clu.userid=ue.userid AND clu.licensecourseid = e.courseid JOIN {companylicense} cl ON cl.id = clu.licenseid WHERE ue.userid=$userid ORDER BY cl.expirydate ASC");
	return $data;
	
}

function checkCourseidArray($arr, &$id_arr, &$final_arr)
{
    foreach ($arr as $key => $value)
    {
        if (!in_array($value['courseid'], $id_arr))
        {
            $id_arr[] = $value['courseid'];
            $final_arr[] = $value;
        }
    }
}