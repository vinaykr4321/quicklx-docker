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
 * A two column layout for the Edwiser RemUI theme.
 *
 * @package   theme_remui
 * @copyright WisdmLabs
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
use core_completion\progress;
global $CFG, $PAGE, $USER, $SITE, $COURSE, $DB;
require_once ('common.php');
require_once ($CFG->dirroot . '/completion/classes/progress.php');
$GLOBALS['licencename'] = '';
$GLOBALS['licencecoursepopup'] = '';
/*
  $percentage = progress::get_course_progress_percentage($course, $USER->id);
  print_object($percentage);exit;
*/
// prepare course archive context
$hascourses = false;
$mycourses = optional_param('mycourses', 0, PARAM_INT);
$search = optional_param('search', '', PARAM_ALPHANUMEXT);
$category = optional_param('categoryid', 0, PARAM_INT);
$page = optional_param('page', 0, PARAM_INT);
$stop = optional_param('stop', 0, PARAM_INT);
$training = optional_param('training', 1, PARAM_INT);
$categorysort = optional_param('categorysort', 0, PARAM_ALPHANUMEXT) == 'default' ? '' : optional_param('categorysort', 0, PARAM_ALPHANUMEXT);
$pageurl = new moodle_url('/course/index.php');
if ($mycourses == 2) {
    $training = 0;
}
//if no course is available in my training courses tab.
$templatecontext['nocoursetrainingstudent'] = 'Courses assigned to you will be listed here. Currently no courses are assigned to you.';
// Message for popup after clicking on "Add License Seats"
$templatecontext['addlicenseseatmessage'] = 'Please reach out to your TrainingSoftware sales representative for more information on purchasing additional license seats.';
if (!empty($search)) {
    $pageurl->param('search', $search);
}
if (!empty($category)) {
    $pageurl->param('categoryid', $category);
}
if (!empty($mycourses)) {
    $pageurl->param('mycourses', $mycourses);
}
$courseperpage = \theme_remui\toolbox::get_setting('courseperpage');
if (empty($courseperpage)) {
    $courseperpage = 12;
}
$startfrom = $page * $courseperpage;
$courses = \theme_remui\utility::get_courses_custom(false, $search, $category, $startfrom, $courseperpage, $mycourses);
$totalcourses = \theme_remui\utility::get_courses_custom(true, $search, $category, 0, 0, $mycourses);
$totalpages = ceil($totalcourses / $courseperpage);
$pagingbar = new paging_bar($totalcourses, $page, $courseperpage, $pageurl, 'page');
if (count($courses) > 0) {
    $hascourses = true;
}
$templatecontext['hascourses'] = $hascourses;
$templatecontext['categoryfilter'] = \theme_remui\utility::get_course_category_selector($category, $categorysort, $search, $mycourses, $pageurl);
//$templatecontext['categorydescription'] = \theme_remui\utility::get_category_description($category);
$templatecontext['searchfilter'] = $PAGE->get_renderer('core', 'course')->course_search_form($search, '', $category, $mycourses);
//$templatecontext['pagination'] = $OUTPUT->render($pagingbar);
if ($categorysort == 'SORT_ASC' || $categorysort == 'SORT_DESC') {
    $courses = \theme_remui\utility::array_msort($courses, array('coursename' => $categorysort));
}
$temp = array();
$start = array();
$continue = array();
$review = array();
$register = array();
$accesscourseexpired = array();
$noLicense = array();
$tempSort = array();
$finalSort = array();
$templatecontext['mycourses'] = $mycourses;
$templatecontext['training'] = $CFG->wwwroot . '/course/index.php?mycourses=2';
$templatecontext['viewtoggler'] = \theme_remui\utility::get_courses_view_toggler($category);
// This will get the user preference for view state
// and add classes appropriately
$view = get_user_preferences('course_view_state');
if (empty($view)) {
    $view = set_user_preference('course_view_state', 'grid');
    $view = 'grid';
}
if ($view == 'grid') {
    $viewClasses = 'col-md-6 col-lg-3';
    $imgStyle = 'gridStyle';
    $listbuttons = '';
    $listprogress = '';
} else {
    $viewClasses = 'col-md-12 col-lg-12 listview';
    $imgStyle = 'listStyle';
    $listbuttons = 'list-activity-buttons';
    $listprogress = "list-progress";
}
$templatecontext['viewClasses'] = $viewClasses;
$templatecontext['imgStyle'] = $imgStyle;
$templatecontext['listbuttons'] = $listbuttons;
$templatecontext['listprogress'] = $listprogress;
$templatecontext['userid'] = $USER->id;
$templatecontext['wwwroot'] = $CFG->wwwroot;
//if(checkIsUserManager($USER->id))
$companyid = iomad::get_my_companyid(context_system::instance());
$display_course_expiry = $DB->get_record_sql("SELECT expiredcourse FROM {company}  where id = $companyid");



if (is_siteadmin()) {
    $templatecontext['courses'] = $courses;
} elseif (checkIsUserManager($USER->id)) {
    $allCompanyCourses = $DB->get_records_sql("SELECT clc.courseid, cl.companyid, cl.name FROM {companylicense} cl  JOIN {companylicense_courses} clc ON clc.licenseid=cl.id WHERE cl.companyid = $companyid");


   $userenrolledcourses = $DB->get_record_sql("SELECT GROUP_CONCAT(e.courseid) as courseid FROM `mdl_user_enrolments` as ue join `mdl_enrol` as e ON ue.enrolid = e.id where ue.userid = $USER->id");
    $userenrolledcoursesarray =explode(',',$userenrolledcourses->courseid);





    if ($display_course_expiry->expiredcourse == 0) {
        $allexpiredcourses = $DB->get_record_sql("SELECT GROUP_CONCAT(e.courseid) as courseid FROM `mdl_user_enrolments` as ue join `mdl_enrol` as e ON ue.enrolid = e.id where ue.userid = $USER->id and ue.timeend < unix_timestamp()");
     
        $allexpiredcourseslist = explode(',', $allexpiredcourses->courseid);
    }
    $companyCourseId = array();
    $templatecontext['companyid'] = $companyid;
    foreach ($allCompanyCourses as $companyCourse) {
        $companyCourseIds[] = $companyCourse->courseid;
    }


 $companyCourseId = array_unique(array_merge($companyCourseIds,$userenrolledcoursesarray));



    if ($display_course_expiry->expiredcourse == 0) {
        $companyCourseId = array_diff($companyCourseId, $allexpiredcourseslist);
    }
    if (!empty($courses)) {
        foreach ($courses as $key => $course) {
        
            $licenseMessage = '';
            $startBtn = 0;
            $continueBtn = 0;
            $reviewBtn = 0;
            $registerBtn = 0;
            $accesscourseexpiredBtn = 0;
            $courseid = $course['courseid'];
            
            $course['gradelink'] = $CFG->wwwroot . "/grade/report/user/index.php?id=$courseid";
            $licenseCourses = getCompanyLicenseId($companyid, $courseid);
            if ($licenseCourses != '') {
                $course['licencecoursepopup'] = $licenseCourses;
                $course['licencename'] = $GLOBALS['licencename'];
            } else {
                getCompanyLicenseCourses($courseid);
                $course['licencecoursepopup'] = $GLOBALS['licencecoursepopup'];
            }
            $licenseMessage = countRemainingLicense($companyid, $courseid);
            $course['availableseats'] = preg_replace('/[^0-9]/', '', $licenseMessage);
            if (is_numeric($licenseMessage)) {
                $course['licensedSeats'] = get_string('nolicense', 'theme_remui_child');
            } else {
                $course['licensedSeats'] = $licenseMessage;
            }
            if (!empty($course['coursesummary'])) {
                $course['coursesummary'] = $course['coursesummary'];
            }
      

            if (in_array($courseid, $companyCourseId)) {

                if (is_enrolled_custom($courseid, $USER->id)) { // for start
                  /*  $startContinue = $DB->get_record_sql("SELECT ue.timecreated,ue.timeend,if(cc.timestarted != NULL,'Continue','Start') as coursestatus FROM {enrol} AS e JOIN  {user_enrolments} AS ue ON ue.enrolid = e.id left JOIN {course_completions} AS cc ON ue.userid=cc.userid  WHERE e.courseid =$courseid  AND ue.userid=$USER->id");*/

                  $startContinue = $DB->get_record_sql("SELECT ue.timecreated,ue.timeend FROM mdl_enrol AS e JOIN mdl_user_enrolments AS ue ON ue.enrolid = e.id WHERE e.courseid = $courseid  AND ue.userid=$USER->id");
                    //  echo $startContinue;exit;
                    $courseData = get_course($courseid);
                    $percentage = progress::get_course_progress_percentage($courseData, $USER->id);
	            if ($percentage == 100) {
		           $course['certificatelink'] = getCertificateLink($courseid);
		    }
                    $date = '';
                    $startCont = $DB->get_record_sql("SELECT * FROM {user_lastaccess}  WHERE courseid =$courseid  AND userid=$USER->id");

                    if ($startContinue) {
                        $date = date('m/d/Y', $startContinue->timecreated);
                        $course['date'] = $date;
                        if ($startContinue->timeend == 0) {
                            $course['timeend'] = 'Life time';
                            //print_object($course);exit;
                        } else {
                            if ($startContinue->timeend < time()) {
                                $accesscourseexpiredBtn++;
                                 $course['library'] = true;
                            if ($startCont) {
                                $course['accessexpiredafterstart'] = true;
                            }else{
                                $course['accessexpiredbeforestart'] = true;
                            }
                            }
                            else
                            {
                       if ($percentage != 100) {
                        if ($startCont) {
                            $continueBtn++;
                            $course['library'] = true;
                                $course['lcontinue'] = true;
                        }else {
                            $startBtn++;
                            $course['library'] = true;
                                $course['lstart'] = true;
                        }
                    } else {
                        $reviewBtn++;
                        $course['library'] = true;
                        
                            $course['lreview'] = true;
                        
                        
                    }
                        }
                            
                            $timeEnd = date('m/d/Y', $startContinue->timeend);
                            $course['timeend'] = $timeEnd;
                        }
                    }
                
                } else { // for register button
                    if ($training != 0) {
                        $registerBtn++;
                        $course['library'] = true;
                        $course['lregister'] = true;
                        $templatecontext['companyid'] = $companyid;
                        $templatecontext['courseid'] = $courseid;
                        $templatecontext['userid'] = $USER->id;
                    }
                }
           /* }else{
                  if ($training != 0) {
                        $registerBtn++;
                        $course['library'] = true;
                        $course['lregister'] = true;
                        $templatecontext['companyid'] = $companyid;
                        $templatecontext['courseid'] = $courseid;
                        $templatecontext['userid'] = $USER->id;
                    }
            }*/



            //print_object($accesscourseexpiredBtn);

            } else { // for popup (Add to Library)
                if ($training != 0) {
                    $course['library'] = true;
                }
            }
            if ($startBtn == 1) {
                $start[] = $course;
            } elseif ($continueBtn == 1) {
                $continue[] = $course;
            } elseif ($registerBtn == 1 && $training != 0) {
                $register[] = $course;
            } elseif ($reviewBtn == 1) {
                $review[] = $course;
            }elseif($accesscourseexpiredBtn == 1){
                $accesscourseexpired[] = $course;
            } else {
                if ($training != 0) {
                    $noLicense[] = $course;
                }
            }
            $temp[] = $course;
        }
      // print_object($accesscourseexpired);exit;
        
    }
} else { // FOR STUDENT
    //$companyid = iomad::get_my_companyid(context_system::instance());
    $allCompanyCourses = $DB->get_records_sql("SELECT clc.courseid, cl.companyid, cl.name FROM {companylicense} cl  JOIN {companylicense_courses} clc ON clc.licenseid=cl.id WHERE cl.companyid = $companyid");
    //$allCompanyCourses = $DB->get_records_sql("SELECT courseid FROM {companylicense_courses},{companylicense} c WHERE licenseid = c.id AND companyid = $companyid");

    $userenrolledcourses = $DB->get_record_sql("SELECT GROUP_CONCAT(e.courseid) as courseid FROM `mdl_user_enrolments` as ue join `mdl_enrol` as e ON ue.enrolid = e.id where ue.userid = $USER->id");
    $userenrolledcoursesarray =explode(',',$userenrolledcourses->courseid);

    //custom changes start
    //Display all courses not expired
    if ($display_course_expiry->expiredcourse == 0) {
        $allexpiredcourses = $DB->get_record_sql("SELECT GROUP_CONCAT(e.courseid) as courseid FROM `mdl_user_enrolments` as ue join `mdl_enrol` as e ON ue.enrolid = e.id where ue.userid = $USER->id and ue.timeend < unix_timestamp()");
        $allexpiredcourseslist_user = explode(',', $allexpiredcourses->courseid);
    }
    //Custom code end
    foreach ($allCompanyCourses as $courseids) {
        $companyIds[] = $courseids->courseid;
    }

  $companyId = array_unique(array_merge($companyIds,$userenrolledcoursesarray));
    if ($display_course_expiry->expiredcourse == 0) {
        $companyId = array_diff($companyId, $allexpiredcourseslist_user);
    }
    if (!empty($courses)) {
        foreach ($courses as $course) {
            $startBtn = 0;
            $continueBtn = 0;
            $reviewBtn = 0;
            $registerBtn = 0;
            $accesscourseexpiredBtn = 0;

            if (in_array($course['courseid'], $companyId)) {
                $courseid = $course['courseid'];
             
                $course['gradelink'] = $CFG->wwwroot . "/grade/report/user/index.php?id=$courseid";
                $course['licencecoursepopup'] = getCompanyLicenseId($companyid, $courseid);
                $course['licencename'] = $GLOBALS['licencename'];
              


                if (is_enrolled_custom($courseid, $USER->id)) {
                   // if (in_array($courseid, $liccourseid)){
                        /*$timeData = $DB->get_record_sql("SELECT ue.timecreated,ue.timeend,if(cc.timestarted != NULL,'Continue','Start') as coursestatus FROM {enrol} AS e JOIN  {user_enrolments} AS ue ON ue.enrolid = e.id left JOIN {course_completions} AS cc ON ue.userid=cc.userid  WHERE e.courseid =$courseid  AND ue.userid=$USER->id");*/

                        $timeData = $DB->get_record_sql("SELECT ue.timecreated,ue.timeend FROM mdl_enrol AS e JOIN mdl_user_enrolments AS ue ON ue.enrolid = e.id WHERE e.courseid = $courseid  AND ue.userid=$USER->id");
                         $courseData = get_course($courseid);
                        $percentage = progress::get_course_progress_percentage($courseData, $USER->id);

			if ($percentage == 100) {
			   $course['certificatelink'] = getCertificateLink($courseid);
			}

                        $date = '';
                        $startCont = $DB->get_record_sql("SELECT * FROM {user_lastaccess}  WHERE courseid =$courseid  AND userid=$USER->id");
                        if ($timeData) {
                            $date = date('m/d/Y', $timeData->timecreated);
                            $course['date'] = $date;
                            if ($timeData->timeend == 0) {
                                $course['timeend'] = 'Life time';
                            } else {
                                if ($timeData->timeend < time()) {
                                   // print_r($course);exit;
                                    $accesscourseexpiredBtn++;
                                     if ($startCont) {
                                $course['accessexpiredafterstartst'] = true;
                            }else{
                                $course['accessexpiredbeforestartst'] = true;
                            }
                                }else{

                         if ($percentage != 100) {
                            
                          
                            if ($startCont) {
                                $continueBtn++;
                               
                                    $course['continue'] = true;
                                }
                            else {
                                $startBtn++;
                                    $course['start'] = true;
                            }
                        } else {

                            $reviewBtn++;
                                $course['review'] = true;
                           
                        }
                         }
                                $timeEnd = date('m/d/Y', $timeData->timeend);
                                $course['timeend'] = $timeEnd;
                            }
                        }
                       
                       
                      
                    /*} else {
                        if ($training != 0) {
                            $registerBtn++;
                            if (isset($checkcourseexpiry)) {
                                $course['register'] = true;
                            } else {
                                $course['register'] = true;
                            }
                            $templatecontext['companyid'] = $companyid;
                            $templatecontext['courseid'] = $courseid;
                            $templatecontext['userid'] = $USER->id;
                        } else {
                            unset($course);
                        }
                    }*/
                } else {
                    if ($training != 0) {
                        $registerBtn++;
                        if (isset($checkcourseexpiry)) {
                            $course['register'] = true;
                        } else {
                            $course['register'] = true;
                        }
                        $templatecontext['companyid'] = $companyid;
                        $templatecontext['courseid'] = $courseid;
                        $templatecontext['userid'] = $USER->id;
                    } else {
                        unset($course);
                    }
                }
                if ($startBtn == 1) {
                    $start[] = $course;
                } elseif ($continueBtn == 1) {
                    $continue[] = $course;
                } elseif ($registerBtn == 1 && $training != 0) {
                    $register[] = $course;
                } elseif ($reviewBtn == 1) {
                    $review[] = $course;
                }elseif($accesscourseexpiredBtn == 1){
                $accesscourseexpired[] = $course;
                }  else {
                    $noLicense[] = $course;
                }
                $temp[] = $course;
               // print_object($accesscourseexpired);exit();
                
            }
        }
    }
}
//print_object($temp);exit;
if (count($temp) > 0) {
    $hascourses = true;
}
$templatecontext['hascourses'] = $hascourses;
if (!is_siteadmin()) {
    $companydata = $DB->get_record('company', array('id' => $companyid));
    //$templatecontext['courses'] = $temp;
    if ($categorysort == 'LICENSED_ASC' || empty($categorysort)) {

//print_object($continue);exit;
        if (!empty($continue)) {

            usort($continue, 'compareByName');
            $tempSort[] = $continue;
        }
       /* print_object($tempSort);
        print_object($continue);exit;*/
        if (!empty($start)) {
            usort($start, 'compareByName');
            $tempSort[] = $start;
        }
        if (!empty($review)) {
            usort($review, 'compareByName');
            $tempSort[] = $review;
        }
        if (!empty($accesscourseexpired)) {
            usort($accesscourseexpired, 'compareByName');
            $tempSort[] = $accesscourseexpired;
        }
        if (!empty($register) && $companydata->unassigned == 1) {
            usort($register, 'compareByName');
            $tempSort[] = $register;
        }
        if (!empty($noLicense) && $companydata->unlicensed == 1 && $companydata->unassigned == 1) {
            usort($noLicense, 'compareByName');
            $tempSort[] = $noLicense;
        }


        foreach ($tempSort as $value) {
            foreach ($value as $final) {
                $finalSort[] = $final;
            }
        }
        $templatecontext['courses'] = $finalSort;
    } elseif ($categorysort == 'LICENSED_DESC') {
        if (!empty($noLicense) && $companydata->unlicensed == 1 && $companydata->unassigned == 1) {
            usort($noLicense, 'compareByName');
            $tempSort[] = $noLicense;
        }
        if (!empty($register) && $companydata->unassigned == 1) {
            usort($register, 'compareByName');
            $tempSort[] = $register;
        }
         if (!empty($accesscourseexpired)) {
            usort($accesscourseexpired, 'compareByName');
            $tempSort[] = $accesscourseexpired;
        }
        if (!empty($review)) {
            usort($review, 'compareByName');
            $tempSort[] = $review;
        }
        if (!empty($start)) {
            usort($start, 'compareByName');
            $tempSort[] = $start;
        }
        if (!empty($continue)) {
            usort($continue, 'compareByName');
            $tempSort[] = $continue;
        }
        foreach ($tempSort as $value) {
            foreach ($value as $final) {
                $finalSort[] = $final;
            }
        }
        $templatecontext['courses'] = array_reverse($finalSort);
    } elseif ($categorysort == 'LICENSED_HIGH') {
        usort($temp, 'invenDescSort');
        $templatecontext['courses'] = $temp;
    } elseif ($categorysort == 'LICENSED_LOW') {
        usort($temp, 'invenDescSort');
        $templatecontext['courses'] = array_reverse($temp);
    } else {
        $templatecontext['courses'] = $temp;
    }
} // end course catalog
$templatecontext['courses'] = array_filter($templatecontext['courses']);
echo $OUTPUT->render_from_template('theme_remui/coursecategory', $templatecontext);
function getCertificateLink($courseid) {
    global $DB, $CFG;
    $data = $DB->get_record("iomadcertificate", array('course' => $courseid));
    if ($data) {
        $coursemodule = getCourseModuleId($data->id, $courseid);
        if ($coursemodule) {
            return $CFG->wwwroot . "/mod/iomadcertificate/view.php?id=$coursemodule&action=get";
        } else {
            return '';
        }
    } else {
        return '';
    }
}
function getCourseModuleId($certificateId, $courseid) {
    global $DB;
    $data = $DB->get_record("course_modules", array('course' => $courseid, 'instance' => $certificateId, 'module' => 12));
    if ($data) {
        return $data->id;
    } else {
        return '';
    }
}
function invenDescSort($item1, $item2) {
    if ($item1['availableseats'] == $item2['availableseats']) return 0;
    return ($item1['availableseats'] < $item2['availableseats']) ? 1 : -1;
}
function compareByName($temp, $temp2) {
    return strcmp($temp["coursename"], $temp2["coursename"]);
}
function array_push_assoc($array, $key, $value) {
    $array[$key] = $value;
    return $array;
}
function is_enrolled_custom($courseid, $userid) {
    global $DB;
    //$data = $DB->get_records_sql("SELECT ue.* FROM {user_enrolments} ue JOIN {enrol} e ON (e.id = ue.enrolid AND e.courseid = $courseid) JOIN {user} u ON u.id = ue.userid WHERE ue.userid = $userid AND ue.status = 0 AND e.status = 1 AND u.deleted = 0");
    $data = $DB->get_records_sql("SELECT ue.* FROM {user_enrolments} ue JOIN {enrol} e ON (e.id = ue.enrolid AND e.courseid = $courseid) JOIN {user} u ON u.id = ue.userid WHERE ue.userid = $userid");
    if (!empty($data)) {
        return true;
    } else {
        return false;
    }
}
function countRemainingLicense($companyid, $courseid) {
    global $DB;
    $data = $DB->get_records_sql("SELECT cl.* FROM {companylicense} as cl JOIN {companylicense_courses} as clc ON cl.id = clc.licenseid WHERE clc.courseid=$courseid AND cl.companyid=$companyid");
    if (empty($data)) {
        return 0;
    } else {
        $totalLicense = 0;
        foreach ($data as $value) {
            $totalLicence = $value->allocation;
            $usedLicence = $value->used;
            $numberOfCourses = getNumberOfCourseFromLicense($value->id);
            $remainingLicence = (($totalLicence - $usedLicence) / $numberOfCourses);
            $totalLicense = $totalLicense + $remainingLicence;
            /*if ($value->allocation == '9999999') {
                $totalLicense = 9999999;
            }*/
            if ($value->unlimitedseats == 1) {
                $unlimited = true;
            }
        }
        if ($totalLicense >= 1) {
            if ($unlimited) {
                return get_string('unlimitedlicense', 'theme_remui_child');
            }
            return get_string('numberseatlicense', 'theme_remui_child', floor($totalLicense));
        } else {
            return get_string('lesslicense', 'theme_remui_child');
        }
    }
}
function getNumberOfCourseFromLicense($license) {
    global $DB;
    $data = $DB->get_records_sql("SELECT * FROM {companylicense_courses} WHERE licenseid = $license");
    return count($data);
}
/* function getEnrolledCourses($userid)
  {
  global $DB;
  $courseId = array();
  $data = $DB->get_records_sql("SELECT e.courseid from {user_enrolments} as ue join {enrol} e on ue.enrolid=e.id  where ue.userid=$userid");
  foreach ($data as $courseId)
  {
  $courseId[] = $courseId->courseid;
  }
  return $courseId;
  }
*/
function checkIsUserManager($userid) {
    global $DB;
    $data = $DB->get_records_sql("SELECT r.id from {role_assignments} ra JOIN {role} r on ra.roleid = r.id where ra.userid=$userid");
    foreach ($data as $manager) {
        if ($manager->id == 9) {
            return true;
        }
    }
    return false;
}
function getCompanyLicenseId($companyid, $courseid) {
    global $DB;
    $data = $DB->get_records_sql("SELECT cl.* FROM {companylicense} cl JOIN {companylicense_courses} clc ON cl.id=clc.licenseid WHERE cl.companyid = $companyid AND clc.courseid = $courseid");
    if (count($data) > 1) {
        $i = 0;
        foreach ($data as $licenseExpiry) {
            if ($i == 0) {
                $expiry = $licenseExpiry->expirydate;
                $licenseId = $licenseExpiry->id;
            } elseif ($licenseExpiry->expirydate < $expiry) {
                $expiry = $licenseExpiry->expirydate;
                $licenseId = $licenseExpiry->id;
            }
            $i++;
        }
        $allCourses = getAllCoursesNameOfLicense($licenseId);
        getLicenseNameFromID($licenseId);
    } else {
        foreach ($data as $licenseDetail) {
            $allCourses = getAllCoursesNameOfLicense($licenseDetail->id);
            getLicenseNameFromID($licenseDetail->id);
        }
    }
    return $allCourses;
}
function getCompanyLicenseCourses($courseid) {
    global $DB;
    $licenseid = array();
    $data = $DB->get_records_sql("SELECT * FROM {companylicense_courses} WHERE courseid = $courseid");
    foreach ($data as $value) {
        $licenseid[] = $value->licenseid;
    }
    $licenseids = implode(',', $licenseid);
    if (!empty($licenseids)) {
        getCoursesFromLicense($licenseids);
    }
    //return $allCourses;
    
}
function getCoursesFromLicense($licenseids) {
    global $DB;
    $licencecoursepopup = array();
    $data = $DB->get_records_sql("SELECT c.fullname,cl.name FROM {companylicense_courses} clc JOIN {course} c ON clc.courseid=c.id JOIN {companylicense} cl ON cl.id=clc.licenseid WHERE clc.licenseid IN ($licenseids) ORDER BY clc.licenseid");
    foreach ($data as $value) {
        $licencecoursepopup[] = $value->name . ' : ' . $value->fullname;
    }
    $GLOBALS['licencecoursepopup'] = $licencecoursepopup;
}
function getAllCoursesNameOfLicense($licenseid) {
    global $DB;
    $names = array();
    $data = $DB->get_records_sql("SELECT c.fullname FROM {companylicense_courses} clc JOIN {course} c on c.id=clc.courseid WHERE licenseid = $licenseid");
    foreach ($data as $name) {
        $names[] = $name->fullname;
    }
    return $names;
}
function getLicenseNameFromID($license) {
    global $DB;
    $data = $DB->get_record_sql("SELECT * FROM {companylicense} WHERE id = $license");
    $GLOBALS['licencename'] = $data->name;
    //$course['licencenamepopup'] = $data->name;
    
}
if ($stop != 0) {
    echo '<input type="hidden" class="stop" value=' . $stop . '>';
    echo '<script type="text/javascript">
        var stopclass = $(".stop").val();
        $("html,body").animate({
        scrollTop: $("."+stopclass).offset().top-140},
        "slow");
    </script>';
}
?>
<script type="text/javascript">
    function enrol(courseid, userid, companyid)
    {
        $( '.container' ).after( '<div id="loading_mycourses"><div class="loader_text">Please wait. Registration in progress. <p>Time can increase if the selected course is part of a large license.</p></div></div>' );
        $("#loading_mycourses").show();

        var currurl = window.location.href;
        $.ajax({
            url: "<?php echo $CFG->wwwroot ?>/theme/remui_child/enrolluser.php",
            method: 'post',
            data: {'courseid': courseid, 'userid': userid, 'companyid': companyid},
            success: function (response)
            {
                if (response.trim() == '') {
                    if (window.location.href.indexOf(".php?") > -1) {
                        var finalUrl = removeURLParameter(currurl, 'stop');
                        window.location.replace(finalUrl + '&stop=' + courseid);
                    } else
                    {
                        window.location.replace(currurl + '?stop=' + courseid);
                    }
                    //location.reload();
                } else
                {
                    alert(response);
                    if (window.location.href.indexOf(".php?") > -1) {
                        var finalUrl = removeURLParameter(currurl, 'stop');
                        window.location.replace(finalUrl + '&stop=' + courseid);
                    } else
                    {
                        window.location.replace(currurl + '?stop=' + courseid);
                    }
                    //location.reload();
                }
         
            }
        });
    }
    function removeURLParameter(url, parameter) {
        //prefer to use l.search if you have a location/link object
        var urlparts = url.split('?');
        if (urlparts.length >= 2) {

            var prefix = encodeURIComponent(parameter) + '=';
            var pars = urlparts[1].split(/[&;]/g);

            //reverse iteration as may be destructive
            for (var i = pars.length; i-- > 0; ) {
                //idiom for string.startsWith
                if (pars[i].lastIndexOf(prefix, 0) !== -1) {
                    pars.splice(i, 1);
                }
            }

            url = urlparts[0] + '?' + pars.join('&');
            return url;
        } else {
            return url;
        }
    }
</script>

