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

/*
 * Support enrollment of user into courses
 *
 * @copyright  Syllametrics (support@syllametrics.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
global $CFG;
require_once($CFG->dirroot . '/lib/enrollib.php');

$userid = $_POST['userid'];
$courseid = $_POST['courseid'];
$companyid = $_POST['companyid'];

$allRelatedLicense = getCompanyLicenseId($companyid, $courseid);
// This process will get the finnal licence id whose courses will be used in the enrollment process.
if (count($allRelatedLicense) > 1) {
    $i = 0;
    foreach ($allRelatedLicense as $licenseExpiry) {
        if ($i == 0) {
            $expiry = $licenseExpiry->expirydate;
            $licenseId = $licenseExpiry->id;
        } elseif ($licenseExpiry->expirydate < $expiry) {
            $expiry = $licenseExpiry->expirydate;
            $licenseId = $licenseExpiry->id;
        }
        $i++;
    }
    $allCourses = getAllCoursesOfLicense($licenseId);
    foreach ($allCourses as $course) {
        $check = checkCompanyCourseAccess($course->courseid, $companyid);

        if ($check) {
            updateCompanyLicenseUsed($licenseId); // increasing 1 in used
            if (enrolUser($userid, $course->courseid)) {
                insertCompanyData($userid, $course->courseid, $licenseId);
                updateEnrolmentEndDate($userid, $course->courseid, $licenseId);
//gnuwings start - Create an courseeventuser event.
                $eventother = array('licenseid' => $licenseId,
                    'duedate' => 0);
                $event = \block_iomad_company_admin\event\courseeventuser_license_selfreg::create(array('context' => context_system::instance(),
                            'objectid' => $licenseId,
                            'courseid' => $course->courseid,
                            'userid' => $userid,
                            'other' => $eventother));
                $event->trigger();
//gnuwings end
            }
        }
    }
} else {
    // This for each will run only once because it has only 1 data all the time
    foreach ($allRelatedLicense as $licenseDetail) {
        $allCourses = getAllCoursesOfLicense($licenseDetail->id);
        foreach ($allCourses as $course) {
            $check = checkCompanyCourseAccess($course->courseid, $companyid);
            if ($check) {
                updateCompanyLicenseUsed($licenseDetail->id); // increasing 1 in used
                if (enrolUser($userid, $course->courseid)) {
                    insertCompanyData($userid, $course->courseid, $licenseDetail->id);
                    updateEnrolmentEndDate($userid, $course->courseid, $licenseDetail->id);
//gnuwings start - Create an courseeventuser event.
                    $eventother = array('licenseid' => $licenseDetail->id,
                        'duedate' => 0);
                    $event = \block_iomad_company_admin\event\courseeventuser_license_selfreg::create(array('context' => context_system::instance(),
                                'objectid' => $licenseDetail->id,
                                'courseid' => $course->courseid,
                                'userid' => $userid,
                                'other' => $eventother));
                    $event->trigger();
//gnuwings end
                }
            }
        }
    }
}

function checkCompanyCourseAccess($courseid, $companyid) {
    global $DB;
    $access = $DB->get_record_sql("SELECT c.*,cc.courseid FROM {companylicense} AS c JOIN {companylicense_courses} AS cc ON c.id=cc.licenseid WHERE cc.courseid=$courseid AND c.companyid=$companyid");
    if ($access) {
        if ($access->expirydate > time()) {
            if ($access->allocation >= $access->used) {
                return true;
            } else {
                echo 'Seats full';
        exit;
            }
        } else {
            echo 'Your license is expired';
        exit;
        }
    } else {
        echo 'No access in this course for your company';
    exit;
    }
}

function enrolUser($userid, $courseid) {
    global $DB;
    if (!is_enrolled_custom($courseid, $userid)) {
        $result = $DB->get_records('enrol', array('courseid' => $courseid, 'enrol' => "license"));

        $enrolplugin = enrol_get_plugin('license');
        foreach ($result as $instance) {
            if ($instance->enrol === 'license') {
                break;
            }
        }
        if ($instance->enrol !== 'license') {
            throw new coding_exception('No license enrol plugin in course');
        }

        $role = $DB->get_record('role', array('shortname' => 'student'), '*', MUST_EXIST);
        $enrolplugin->enrol_user($instance, $userid, $role->id);
        return true;
    }else{      
         return true;       
    }
    return false;
}

function insertCompanyData($userid, $courseid, $licenseId) {
    $companyUserExists = insertCompanyUser($userid, $courseid, $licenseId);
    //updateCompanyLicenseUsed($licenseId);
    /* if ($companyUserExists) 
      {
      } */
}

function insertCompanyUser($userid, $courseid, $licenseid) {
    global $DB;
    $recordarray = array('licensecourseid' => $courseid, 'userid' => $userid, 'licenseid' => $licenseid, 'isusing' => 0);

    // Check if we are not assigning multiple times.
    if (!$DB->get_record('companylicense_users', $recordarray)) {
        $recordarray['issuedate'] = time();
        $DB->insert_record('companylicense_users', $recordarray);
        return true;
    }
    return false;
}

function updateCompanyLicenseUsed($licenseId) {
    global $DB;
    $DB->execute("UPDATE {companylicense} SET used = used+1 WHERE id = $licenseId");
}

function is_enrolled_custom($courseid, $userid) {
    global $DB;
    $data = $DB->get_records_sql("SELECT ue.* FROM {user_enrolments} ue JOIN {enrol} e ON (e.id = ue.enrolid AND e.courseid = $courseid) JOIN {user} u ON u.id = ue.userid WHERE ue.userid = $userid");
    if (!empty($data)) {
        return true;
    } else {
        return false;
    }
}

function getCompanyLicenseId($companyid, $courseid) {
    global $DB;
    $data = $DB->get_records_sql("SELECT cl.* FROM {companylicense} cl JOIN {companylicense_courses} clc ON cl.id=clc.licenseid WHERE cl.companyid = $companyid AND clc.courseid = $courseid");
    return $data;
}

function getAllCoursesOfLicense($licenseid) {
    global $DB;
    $data = $DB->get_records_sql("SELECT courseid FROM {companylicense_courses} WHERE licenseid = $licenseid");
    return $data;
}

function updateEnrolmentEndDate($userid, $courseid, $licenseId) {
    global $DB;
    $enrolment = getUserEnrolmentId($userid, $courseid);
    $licenseData = getLicenseValidlength($licenseId);

    $record = new stdClass();
    $record->id = $enrolment->id;
    $record->timestart = $enrolment->timecreated;
/*

    $timeendcheck = $licenseData->expirydate > strtotime("+$licenseData->validlength days", time()) ? strtotime("+$licenseData->validlength days", time()) : $licenseData->expirydate;
    $timeend = strtotime(date("Y-m-d 23:59:59", $timeendcheck));
*/
    $expirydatedata =  course_end_date_final($courseid,$userid);
    $timeend = strtotime(date("Y-m-d 23:59:59", $expirydatedata));

    $record->timeend = $timeend;


    $DB->update_record('user_enrolments', $record);
}

function getUserEnrolmentId($userid, $courseid) {
    global $DB;
    $data = $DB->get_record_sql("SELECT ue.id,ue.timecreated from {enrol} e JOIN {user_enrolments} ue on e.id=ue.enrolid where e.enrol='license' and e.courseid=$courseid and ue.userid=$userid");
    return $data;
}

function getLicenseValidlength($licenseId) {
    global $DB;
    $data = $DB->get_record_sql("SELECT validlength,expirydate FROM {companylicense} WHERE id = $licenseId");
    return $data;
}
function course_end_date_final($courseid, $userid)
{
    global $DB;

    $user_courses_licensids = $DB->get_record_sql("SELECT GROUP_CONCAT(DISTINCT(licenseid)) as licenseid FROM {companylicense_users} where licensecourseid = $courseid  AND userid = $userid");

    if (!empty($user_courses_licensids->licenseid)) {

        $user_licensidss = explode(',', $user_courses_licensids->licenseid);

        $licenddate = array();

        foreach ($user_licensidss as $lic_id) {
            $user_course_enddate = $DB->get_record_sql("SELECT min(issuedate) as issuedate FROM {companylicense_users} where userid =  $userid and licenseid = $lic_id");


            $minimum_date = date('m/d/Y', $user_course_enddate->issuedate);


            $licensedata = $DB->get_record_sql("SELECT * FROM {companylicense} where id = $lic_id");


            $validlength = strtotime($minimum_date  . "+$licensedata->validlength days");

            if ($validlength < $licensedata->expirydate) {
                $getexpirydate_value = $validlength;
            } else {
                $getexpirydate_value = $licensedata->expirydate;
            }
            $licenddate[] = $getexpirydate_value;
        }
        $timeend = max($licenddate);
    } else {
        $timeend = time();
    }

    return $timeend;
}

/*
function enrolUser($userId,$courseId)
{
    global $DB;
    $enrolId = getEnrolId($courseId,'manual');
    $contextId = getContextId($courseId,50);
    $input = array('userid'=>$userId,'enrolid'=>$enrolId);
    $ifNotEnrolled = $DB->get_record('user_enrolments',$input);
    if (empty($ifNotEnrolled)) //if user is not enrolled in the new course, assign user to the new course
    {
        enrolUserWithStudentRole($enrolId,$contextId,$userId);
    }
}

function getEnrolId($courseId,$enrolType)
{
    global $DB;
    $input = array('courseid'=>$courseId,'enrol'=>$enrolType);
    $enrolId = $DB->get_record('enrol',$input);
    return $enrolId->id;
}

function getContextId($instanceId,$contextLevel)
{
    global $DB;
    $input = array('instanceid'=>$instanceId,'contextlevel'=>$contextLevel);
    $contextId = $DB->get_record('context',$input);
    return $contextId->id;
}

function enrolUserWithStudentRole($enrolId,$contextId,$userId)
{
    global $DB;
    $rolId = 5; // 5 is for student
    $timestamp = time();
    $timeEnd = strtotime('+1 years', time()); // Set expiry date of course

    $insertUe = array('enrolid'=>$enrolId,'userid'=>$userId,'timestart'=>$timestamp,'timeend'=>$timeEnd,'timecreated'=>$timestamp,'timemodified'=>$timestamp);

    $DB->insert_record('user_enrolments', $insertUe); //Enrol user in course

    $insertRa = array('roleid'=>$rolId,'contextid'=>$contextId,'userid'=>$userId,'timemodified'=>$timestamp);
    $DB->insert_record('role_assignments', $insertRa); //Assign student role to user
}
*/