<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Custom SSO - Create and assign users to IOMAD companies/licenses
 *
 * @package    local_custom_wservice
 * @copyright  2019 Syllametrics (support@syllametrics.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

require_once(dirname(__FILE__).'/../../config.php');
 
$id = optional_param('id', '', PARAM_RAW);
$wantsurl = optional_param('wantsurl', '', PARAM_RAW);

global $USER,$DB,$CFG;
if($id){
	$user = $DB->get_record('user',array('id'=>$id));
	$url = $CFG->wwwroot.'/login/index.php';
	$username = $user->username;
	$strmymoodle = get_string('myhome');
    $pagetitle = $strmymoodle;
    $header = "$SITE->shortname: $strmymoodle (GUEST)";
    $PAGE->set_context(context_user::instance($user->id));

	//$PAGE->set_context(null);
	$PAGE->set_url('/local/custom_wservice/set_user.php');
	$PAGE->set_pagelayout('mydashboard');
	$PAGE->set_pagetype('my-index');
	$PAGE->blocks->add_region('content');
	$PAGE->set_title($pagetitle);
	$PAGE->set_heading($header);
	echo $OUTPUT->header();

	echo $OUTPUT->custom_block_region('content');
			print_r($SESSION);
			print_r($USER);


	echo $OUTPUT->footer();
	//redirect($wantsurl);
}

?>
<script>
document.getElementById('moodlelogin').submit();
</script>
