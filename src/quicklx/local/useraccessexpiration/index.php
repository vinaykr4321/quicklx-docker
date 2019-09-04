<?php
require_once('../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot.'/'.$CFG->admin.'/user/lib.php');
require_once($CFG->dirroot.'/local/useraccessexpiration/form.php');
$PAGE->requires->js('/local/useraccessexpiration/script.js');
$PAGE->requires->css('/local/useraccessexpiration/style.css');
admin_externalpage_setup('useraccessexpiration');
$newarray = array();
$action_form = new user_access_expiration();
$data = $action_form->get_data();
if (!empty($data->users)) 
{
	foreach ($data->users as $keys => $userId)
	{
	    $newarray[$keys.'a'] = $userId;
	}
}

if (isset($_POST['view']))
{
    $urltogo = new moodle_url('/local/useraccessexpiration/viewexpirydate.php', $newarray);
    redirect($urltogo);
}
if (isset($_POST['set'])) 
{
    $urltogo = new moodle_url('/local/useraccessexpiration/setexpirydate.php', $newarray);
    redirect($urltogo);
}

echo $OUTPUT->header();

$action_form->display();

echo $OUTPUT->footer();
