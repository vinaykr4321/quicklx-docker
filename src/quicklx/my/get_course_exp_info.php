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
 * My Moodle -- a user's personal dashboard
 *
 * - Support display of access to course certificates and grades on dashboard
 *
 * @package    moodlecore
 * @subpackage my
 * @copyright  Syllametrics (support@syllametrics.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot.'/'.$CFG->admin.'/user/lib.php');
use core_completion\progress;

require_login();
global $USER;

$action = $_GET['action'];

if($action == 'get_courseid'){
	$company = $_GET['couserurl'];
	$query_str = parse_url($company, PHP_URL_QUERY);
	parse_str($query_str, $query_params);
	$course_id = $query_params['id'];
	get_company_info($course_id);
}

function get_company_info($course_id) {
	global $DB,$USER,$CFG;
	$array_value = array();

	$courseexpiry_info = $DB->get_record_sql("SELECT ue.timeend as timeend FROM {enrol} AS e JOIN {user_enrolments} AS ue ON ue.enrolid = e.id WHERE e.courseid =$course_id AND ue.userid=$USER->id");

	$course_last_access = $DB->get_record_sql("SELECT * FROM `mdl_user_lastaccess` where userid = $USER->id and courseid = $course_id");

	if(!empty($course_last_access)){
		$course_access = $CFG->wwwroot . "/grade/report/user/index.php?id=$course_id";
		//print_r($course_access);
		$array_value['course_access'] = $course_access;
	}

	// $getcertificate = getCertificateLink($course_id);


	$courseData = get_course($course_id);
	$percentage = progress::get_course_progress_percentage($courseData, $USER->id);
	if($percentage == '100'){
		$getcertificate = getCertificateLink($course_id);
	}else{
		$getcertificate = '';
	}
	if(!empty($getcertificate)){
		$course_certificate = $getcertificate;
		//print_r($course_certificate);
		$array_value['course_certificate'] = $course_certificate;
	}

	if($courseexpiry_info->timeend > time()){
		 $course_id=0;
	} 
	$array_value['course_id'] = $course_id;
	echo json_encode($array_value);
 }



function getCertificateLink($courseid) {
    global $DB,$CFG;
    $data = $DB->get_record("iomadcertificate",array('course' => $courseid));

	if($data){
	    $coursemodule = getCourseModuleId($data->id,$courseid);
	    if($coursemodule) {
	        return $CFG->wwwroot."/mod/iomadcertificate/view.php?id=$coursemodule&action=get";
	    }
	    else {
	    	return '';
	    }
	} else {
		return '';
	}
}

function getCourseModuleId($certificateId,$courseid) {
    global $DB;
    $data = $DB->get_record("course_modules",array('course' => $courseid,'instance' => $certificateId, 'module' => 12));
    if($data) {
        return $data->id;
    }
    else {
        return '';
    }
}