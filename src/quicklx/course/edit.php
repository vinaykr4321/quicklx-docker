<?php
// This file is part of Moodle - http://moodle.org/
//test
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
 * Edit course settings
 *
 * @package    core_course
 * @copyright  1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../config.php');
require_once('lib.php');
require_once('edit_form.php');

$id = optional_param('id', 0, PARAM_INT); // Course id.
$categoryid = optional_param('category', 0, PARAM_INT); // Course category - can be changed in edit form.
$returnto = optional_param('returnto', 0, PARAM_ALPHANUM); // Generic navigation return page switch.
$returnurl = optional_param('returnurl', '', PARAM_LOCALURL); // A return URL. returnto must also be set to 'url'.


$doIHaveTagsArr = $_REQUEST["tags"];


if ($returnto === 'url' && confirm_sesskey() && $returnurl) {
    // If returnto is 'url' then $returnurl may be used as the destination to return to after saving or cancelling.
    // Sesskey must be specified, and would be set by the form anyway.
    $returnurl = new moodle_url($returnurl);
} else {
    if (!empty($id)) {
        $returnurl = new moodle_url($CFG->wwwroot . '/course/view.php', array('id' => $id));
    } else {
        $returnurl = new moodle_url($CFG->wwwroot . '/course/');
    }
    if ($returnto !== 0) {
        switch ($returnto) {
            case 'category':
                $returnurl = new moodle_url($CFG->wwwroot . '/course/index.php', array('categoryid' => $categoryid));
                break;
            case 'catmanage':
                $returnurl = new moodle_url($CFG->wwwroot . '/course/management.php', array('categoryid' => $categoryid));
                break;
            case 'topcatmanage':
                $returnurl = new moodle_url($CFG->wwwroot . '/course/management.php');
                break;
            case 'topcat':
                $returnurl = new moodle_url($CFG->wwwroot . '/course/');
                break;
            case 'pending':
                $returnurl = new moodle_url($CFG->wwwroot . '/course/pending.php');
                break;
        }
    }
}

$PAGE->set_pagelayout('admin');
if ($id) {
    $pageparams = array('id' => $id);
} else {
    $pageparams = array('category' => $categoryid);
}
if ($returnto !== 0) {
    $pageparams['returnto'] = $returnto;
    if ($returnto === 'url' && $returnurl) {
        $pageparams['returnurl'] = $returnurl;
    }
}
$PAGE->set_url('/course/edit.php', $pageparams);

// Basic access control checks.
if ($id) {
    // Editing course.
    if ($id == SITEID){
        // Don't allow editing of  'site course' using this from.
        print_error('cannoteditsiteform');
    }

    // Login to the course and retrieve also all fields defined by course format.
    $course = get_course($id);
    require_login($course);
    $course = course_get_format($course)->get_course();

    $category = $DB->get_record('course_categories', array('id'=>$course->category), '*', MUST_EXIST);
    $coursecontext = context_course::instance($course->id);
    require_capability('moodle/course:update', $coursecontext);

} else if ($categoryid) {
    // Creating new course in this category.
    $course = null;
    require_login();
    $category = $DB->get_record('course_categories', array('id'=>$categoryid), '*', MUST_EXIST);
    $catcontext = context_coursecat::instance($category->id);
    require_capability('moodle/course:create', $catcontext);
    $PAGE->set_context($catcontext);

} else {
    require_login();
    print_error('needcoursecategroyid');
}

// Prepare course and the editor.
$editoroptions = array('maxfiles' => EDITOR_UNLIMITED_FILES, 'maxbytes'=>$CFG->maxbytes, 'trusttext'=>false, 'noclean'=>true);
$overviewfilesoptions = course_overviewfiles_options($course);
if (!empty($course)) {
    // Add context for editor.
    $editoroptions['context'] = $coursecontext;
    $editoroptions['subdirs'] = file_area_contains_subdirs($coursecontext, 'course', 'summary', 0);
    $course = file_prepare_standard_editor($course, 'summary', $editoroptions, $coursecontext, 'course', 'summary', 0);
    if ($overviewfilesoptions) {
        file_prepare_standard_filemanager($course, 'overviewfiles', $overviewfilesoptions, $coursecontext, 'course', 'overviewfiles', 0);
    }

    // Inject current aliases.
    $aliases = $DB->get_records('role_names', array('contextid'=>$coursecontext->id));
    foreach($aliases as $alias) {
        $course->{'role_'.$alias->roleid} = $alias->name;
    }

    // Populate course tags.
    $course->tags = core_tag_tag::get_item_tags_array('core', 'course', $course->id);

} else {
    // Editor should respect category context if course context is not set.
    $editoroptions['context'] = $catcontext;
    $editoroptions['subdirs'] = 0;
    $course = file_prepare_standard_editor($course, 'summary', $editoroptions, null, 'course', 'summary', null);
    if ($overviewfilesoptions) {
        file_prepare_standard_filemanager($course, 'overviewfiles', $overviewfilesoptions, null, 'course', 'overviewfiles', 0);
    }
}

// First create the form.
$args = array(
    'course' => $course,
    'category' => $category,
    'editoroptions' => $editoroptions,
    'returnto' => $returnto,
    'returnurl' => $returnurl
);
$editform = new course_edit_form(null, $args);



/* 
    * Custom code 
    * Syllametrics | support@syllametrics.com  
    * This code will update the companylicense, companylicense_course and companylicense_tag tables according to changes in tags
    * Last Update: 11th oct,2018
*/

    $data = $editform->get_data();

/*
    if($data->tagdel==0){
       global $DB;
      $contectids = $coursecontext->id;
      $DB->execute("DELETE FROM {tag_instance} WHERE contextid =  $contectids");
}*/
$companyid = iomad::get_my_companyid(context_system::instance());
    if (!empty($data->tags)) 
    {
        global $DB;
        // Added a tag
        if(!empty($tagToAdd = array_diff($data->tags,$course->tags)))
        {
            $licenseIds = getLicenseIdsFromTagIds($tagToAdd,$companyid);
            if (!empty($licenseIds)) 
            {
                foreach ($licenseIds as $license) 
                {
                    $courseExistes = $DB->record_exists('companylicense_courses', array('licenseid' => $license->licenseid,'courseid' => $course->id)); 
                    //For Parent license add
                    if (!$courseExistes)
                    {
                        $getAllocation = $DB->get_record_sql("SELECT * FROM {companylicense} WHERE id = $license->licenseid");
                        if (empty($getAllocation)) 
                        {
                            deleteCourseAndTags($license->licenseid);
                        }
                        else
                        {
                            updateAndAddWithTags($license->licenseid,$course->id,$tagToAdd,$getAllocation);
                        }
                    }

                    //For shared license add
                    $allSharedLicense = getAllSharedLicense($license->licenseid);
                    if (!empty($allSharedLicense)) 
                    {
                        foreach ($allSharedLicense as $sharedLicenseid) 
                        {
                            $getAllocation = $DB->get_record_sql("SELECT * FROM {companylicense} WHERE id = $sharedLicenseid");
                            if (empty($getAllocation)) 
                            {
                                deleteCourseAndTags($sharedLicenseid);
                            }
                            else
                            {
                                updateAndAdd($sharedLicenseid,$course->id,$getAllocation);
                            }
                        }
                    }
        }
         enrol_current_course_to_users(array_keys($licenseIds), $course->id, $companyid);
            }
        }
        

          // Removed last tag
        if ($doIHaveTagsArr == null) {
           $emptyArr = [];
           $data->tags = $emptyArr;
//           print_r($data->tags);
        }

        // Removed a tag
        if(!empty($tagToDelete = array_diff($course->tags,$data->tags)))
        {
            $licenseIds = getLicenseIdsFromTagIds($tagToDelete, $companyid);
            if (!empty($licenseIds)) 
            {
                foreach ($licenseIds as $license) 
                {
                    //For Parent license remove
                    $getAllocation = $DB->get_record_sql("SELECT * FROM {companylicense} WHERE id = $license->licenseid");
                    if (empty($getAllocation)) 
                    {
                        deleteCourseAndTags($license->licenseid);
                    }
                    else
                    {
                        updateAndRemoveWithTags($license->licenseid,$course->id,$tagToDelete,$getAllocation);
                    }

                    //For shared license remove
                    $allSharedLicense = getAllSharedLicense($license->licenseid);
                    if (!empty($allSharedLicense)) 
                    {
                        foreach ($allSharedLicense as $sharedLicenseid) 
                        {
                            $getAllocation = $DB->get_record_sql("SELECT * FROM {companylicense} WHERE id = $sharedLicenseid");
                            if (empty($getAllocation)) 
                            {
                                deleteCourseAndTags($sharedLicenseid);
                            }
                            else
                            {
                                updateAndRemove($sharedLicenseid,$course->id,$getAllocation);
                            }
                        }
                    }
        }
        unenroll_current_course_from_users(array_keys($licenseIds), $course->id, $companyid, $tagToDelete);
            }
        }
    }

/* custom code end */











if ($editform->is_cancelled()) {
    // The form has been cancelled, take them back to what ever the return to is.
    redirect($returnurl);
} else if ($data = $editform->get_data()) {

      // Removed last tag
    if ($doIHaveTagsArr == null) {
        $emptyArr = [];
        $data->tags = $emptyArr;
        print_r($data->tags);
    }
    
    // Process data if submitted.
    if (empty($course->id)) {
        // In creating the course.
        $course = create_course($data, $editoroptions);

        /* 
            * Custom code 
            * Syllametrics | support@syllametrics.com  
            * Add course to existing licenses when user creates new course and license includes tag assigned to course
            * Last Update: March 24, 2019
        */

        if (!empty($data->tags)) 
        {
            global $DB;
            $licenseIds = getLicenseIdsFromTagIds($data->tags, $companyid);
            if (!empty($licenseIds)) 
            {
                foreach ($licenseIds as $license) 
                {
                    $courseExists = $DB->record_exists('companylicense_courses', array('licenseid' => $license->licenseid,'courseid' => $course->id));  

                     if (!$courseExists)
                    {
                        $getAllocation = $DB->get_record_sql("SELECT * FROM {companylicense} WHERE id = $license->licenseid");

                        if ($getAllocation) 
                        {
                            updateAndAddWithTags($license->licenseid,$course->id,$data->tags,$getAllocation);
                        }

                        create_enrol_current_course_to_users($license->licenseid, $course->id, $companyid);
                    }
                }
            }
        }
        //End of custom code

        // Get the context of the newly created course.
        $context = context_course::instance($course->id, MUST_EXIST);

        if (!empty($CFG->creatornewroleid) and !is_viewing($context, NULL, 'moodle/role:assign') and !is_enrolled($context, NULL, 'moodle/role:assign')) {
            // Deal with course creators - enrol them internally with default role.
            enrol_try_internal_enrol($course->id, $USER->id, $CFG->creatornewroleid);
        }

        // The URL to take them to if they chose save and display.
        $courseurl = new moodle_url('/course/view.php', array('id' => $course->id));

        // If they choose to save and display, and they are not enrolled take them to the enrolments page instead.
        if (!is_enrolled($context) && isset($data->saveanddisplay)) {
            // Redirect to manual enrolment page if possible.
            $instances = enrol_get_instances($course->id, true);
            foreach($instances as $instance) {
                if ($plugin = enrol_get_plugin($instance->enrol)) {
                    if ($plugin->get_manual_enrol_link($instance)) {
                        // We know that the ajax enrol UI will have an option to enrol.
                        $courseurl = new moodle_url('/user/index.php', array('id' => $course->id, 'newcourse' => 1));
                        break;
                    }
                }
            }
        }
    } else {
        // Save any changes to the files used in the editor.
        update_course($data, $editoroptions);
        // Set the URL to take them too if they choose save and display.
        $courseurl = new moodle_url('/course/view.php', array('id' => $course->id));
    }
 



    if (isset($data->saveanddisplay)) {
        // Redirect user to newly created/updated course.
        redirect($courseurl);
    } else {
        // Save and return. Take them back to wherever.
        redirect($returnurl);
    }
}

// Print the form.

$site = get_site();

$streditcoursesettings = get_string("editcoursesettings");
$straddnewcourse = get_string("addnewcourse");
$stradministration = get_string("administration");
$strcategories = get_string("categories");

if (!empty($course->id)) {
    // Navigation note: The user is editing a course, the course will exist within the navigation and settings.
    // The navigation will automatically find the Edit settings page under course navigation.
    $pagedesc = $streditcoursesettings;
    $title = $streditcoursesettings;
    $fullname = $course->fullname;
} else {
    // The user is adding a course, this page isn't presented in the site navigation/admin.
    // Adding a new course is part of course category management territory.
    // We'd prefer to use the management interface URL without args.
    $managementurl = new moodle_url('/course/management.php');
    // These are the caps required in order to see the management interface.
    $managementcaps = array('moodle/category:manage', 'moodle/course:create');
    if ($categoryid && !has_any_capability($managementcaps, context_system::instance())) {
        // If the user doesn't have either manage caps then they can only manage within the given category.
        $managementurl->param('categoryid', $categoryid);
    }
    // Because the course category management interfaces are buried in the admin tree and that is loaded by ajax
    // we need to manually tell the navigation we need it loaded. The second arg does this.
    navigation_node::override_active_url($managementurl, true);

    $pagedesc = $straddnewcourse;
    $title = "$site->shortname: $straddnewcourse";
    $fullname = $site->fullname;
    $PAGE->navbar->add($pagedesc);
}

$PAGE->set_title($title);
$PAGE->set_heading($fullname);

echo $OUTPUT->header();
echo $OUTPUT->heading($pagedesc);

$editform->display();

echo $OUTPUT->footer();


/* 
    * Custom code 
    * Syllametrics | support@syllametrics.com  
    * These functions are used in above code.
    * Last Update: 11th oct,2018
*/


function getLicenseIdsFromTagIds($tagToAdd, $companyid = ''){

    global $DB;
    $allTagIds = '';
    $id = optional_param('id', 0, PARAM_INT);
    if($id == 0) //id=0 means course is new 
    {
        foreach ($tagToAdd as $tagCheck){
            if (tag_get_id($tagCheck) == '') {
                // if tag is not in the database then insert it.
                insertTag($tagCheck);
            }
        }
    }
    $tagid=array_filter(tag_get_id($tagToAdd));
    $allTagIds = implode(",", $tagid);
    if(!empty($allTagIds)){

        if (empty($companyid)) {
            $licenseData = $DB->get_records_sql("SELECT DISTINCT(licenseid) FROM {companylicense_tags} WHERE tagid IN ($allTagIds)");
        } else {
            $licenseData = $DB->get_records_sql("SELECT DISTINCT(licenseid) FROM {companylicense_tags} AS clt JOIN {companylicense} AS cl ON cl.id=clt.licenseid WHERE cl.companyid = $companyid AND tagid IN ($allTagIds)");
        }
        return $licenseData;
    }   
}

function insertTag($tagName)
{
    global $DB,$USER;
    $tag_records=$DB->get_record_sql("SELECT * FROM {tag} WHERE rawname = '$tagName'");
    if(empty($tag_records))
    {
    
        $record = new stdClass();
        $record->userid     = $USER->id;
        $record->tagcollid  = 1;
        $record->name       = $tagName;
        $record->rawname    = $tagName;
        $record->isstandard = 1;
        $record->timemodified = time();
        $lastinsertid = $DB->insert_record('tag', $record);
    }
}

function getAllSharedLicense($licenseid)
{
    global $DB;
    $ids = array();
    $parentLicenseId = $DB->get_records('companylicense', array('parentlicenseid' => $licenseid)); 
    foreach ($parentLicenseId as $value) 
    {
        $ids[] = $value->id;
    }
    return $ids;
}

function deleteCourseAndTags($licenseid)
{
    global $DB;
    $DB->execute("DELETE FROM {companylicense_courses} WHERE licenseid = $licenseid");
    $DB->execute("DELETE FROM {companylicense_tags} WHERE licenseid = $licenseid");
}

function updateAndRemove($licenseid,$courseid,$getAllocation)
{
    global $DB;
    $getAllCourses = $DB->get_records_sql("SELECT courseid FROM {companylicense_courses} WHERE licenseid = $licenseid");
    $allocationToUpdate = ($getAllocation->allocation/count($getAllCourses))*(count($getAllCourses)-1);
    $DB->execute("DELETE FROM {companylicense_courses} WHERE licenseid = $licenseid AND courseid = $courseid");
    $DB->execute("UPDATE {companylicense} SET allocation = $allocationToUpdate WHERE id = $licenseid");
}

function updateAndRemoveWithTags($licenseid,$courseid,$tagToDelete,$getAllocation)
{
    global $DB;
    $getAllCourses = $DB->get_records_sql("SELECT courseid FROM {companylicense_courses} WHERE licenseid = $licenseid");
    $allocationToUpdate = ($getAllocation->allocation/count($getAllCourses))*(count($getAllCourses)-1);
    $DB->execute("DELETE FROM {companylicense_courses} WHERE licenseid = $licenseid AND courseid = $courseid");
    $DB->execute("UPDATE {companylicense} SET allocation = $allocationToUpdate WHERE id = $licenseid");
    foreach (tag_get_id($tagToDelete) as $tagid) 
    {
        $DB->execute("DELETE FROM {companylicense_tags} WHERE licenseid = $licenseid AND courseid = $courseid AND tagid = $tagid");
    }
}

function updateAndAdd($licenseid,$courseid,$getAllocation)
{
    global $DB;
    $getAllCourses = $DB->get_records_sql("SELECT courseid FROM {companylicense_courses} WHERE licenseid = $licenseid");
    $allocationToUpdate = ($getAllocation->allocation/count($getAllCourses))*(count($getAllCourses)+1);
    $DB->insert_record('companylicense_courses', array('licenseid' => $licenseid,'courseid' => $courseid));
    $DB->execute("UPDATE {companylicense} SET allocation = $allocationToUpdate WHERE id = $licenseid");
}

function updateAndAddWithTags($licenseid,$courseid,$tagToAdd,$getAllocation)
{
    global $DB;
    $getAllCourses = $DB->get_records_sql("SELECT courseid FROM {companylicense_courses} WHERE licenseid = $licenseid");
    $allocationToUpdate = ($getAllocation->allocation/count($getAllCourses))*(count($getAllCourses)+1);
    $DB->insert_record('companylicense_courses', array('licenseid' => $licenseid,'courseid' => $courseid));
    $DB->execute("UPDATE {companylicense} SET allocation = $allocationToUpdate WHERE id = $licenseid");
    foreach (tag_get_id($tagToAdd) as $tagid) 
    {
        $tagExistes = $DB->record_exists('companylicense_tags', array('licenseid' => $licenseid,'courseid' => $courseid,'tagid' => $tagid)); 
        if (!$tagExistes) 
        {
            $DB->insert_record('companylicense_tags', array('licenseid' => $licenseid,'courseid' => $courseid,'tagid' => $tagid));
        }
    }
}

/* custom code end */

function enrol_current_course_to_users($licenseids, $courseid, $companyid) {
    global $DB, $USER; 
    $alllicenseid = implode(',', $licenseids);
    $allusers = $DB->get_records_sql("SELECT DISTINCT(userid) FROM {companylicense_users} AS clu JOIN {companylicense} AS cl ON clu.licenseid = cl.id  WHERE cl.companyid = $companyid AND clu.licenseid IN ($alllicenseid)");
    $roleid = $DB->get_record_sql("SELECT id FROM {role} WHERE shortname = 'student' AND archetype = 'student' ");

    $role = $roleid->id;
    user_exists($licenseids, $allusers, $courseid);
    $context = context_course::instance($courseid);

    $timestart = time();
    $timeend = time();

    foreach ($allusers as $user) {
        
        $count_license = course_license_count($courseid, $companyid, $user->userid);
        if (!empty($count_license)) {
            if ($count_license == 1) {
                $timeend = course_min_expiry_data($courseid, $companyid, $user->userid);
                $timeend = strtotime(date("Y-m-d 23:59:59", $timeend));
            } else {
                $timeend = course_expiry_data($courseid, $companyid, $user->userid);
                $timeend = strtotime(date("Y-m-d 23:59:59", $timeend));
            }
        }

        $exists = $DB->get_record_sql("SELECT ue.* FROM {user_enrolments} AS ue JOIN {enrol} AS e ON ue.enrolid = e.id WHERE e.courseid = $courseid AND ue.userid = $user->userid");
        $enrol = $DB->get_record_sql("SELECT id FROM {enrol} WHERE enrol = 'license' AND courseid = $courseid");
        $role_exist = $DB->get_record_sql("SELECT id FROM {role_assignments} WHERE roleid = $role AND contextid = $context->id AND userid = $user->userid");
        if (!$exists) {
            // Insert in to User Enrolments table.
            $DB->insert_record('user_enrolments', array('enrolid' => $enrol->id, 'userid' => $user->userid, 'timestart' => time(), 'timeend' => $timeend, 'modifierid' => $USER->id, 'timecreated' => $timestart , 'timemodified' => $timestart));
            if (empty($role_exist)) {
                // Insert in to Role Assignment table.
                $DB->insert_record('role_assignments', array('roleid' => $role, 'contextid' => $context->id, 'userid' => $user->userid, 'timemodified' => time(), 'modifierid' => $USER->id));
            }
        } else {
            //update user_enrolment
            $DB->execute("UPDATE {user_enrolments} SET timeend = $timeend WHERE userid = $user->userid AND enrolid = $enrol->id");

            if (empty($role_exist)) {
                // Insert in to Role Assignment table.
                $DB->insert_record('role_assignments', array('roleid' => $role, 'contextid' => $context->id, 'userid' => $user->userid, 'timemodified' => time(), 'modifierid' => $USER->id));
            }
        }
//        
    }
}

function unenroll_current_course_from_users($licenseids, $courseid, $companyid, $tagstodelete) {
    global $DB;
    $context = context_course::instance($courseid);
    $timeend = time();
    
    $tag_count = $DB->get_record_sql("SELECT count(*) as count FROM {tag_instance} WHERE itemid = $courseid");

    foreach ($licenseids as $licenseid) {
        $allusers = $DB->get_records_sql("SELECT DISTINCT(userid) FROM {companylicense_users} WHERE licenseid = $licenseid");
    
        foreach ($allusers as $user) {
            $count_license = course_license_count($courseid, $companyid, $user->userid);
            if (!empty($count_license)) {
                if ($count_license == 1) {
                    $DB->execute("UPDATE {user_enrolments} ue JOIN {enrol} en ON ue.enrolid=en.id SET ue.timeend=$timeend WHERE en.courseid = $courseid AND ue.userid = $user->userid");
                }

                if ($count_license > 1) {

                    
                    if ($tag_count->count <= 1) 
                    {
                        $timeend = time();
                    }
                    else
                    {
                        $timeend = course_end_date_final($courseid,$user->userid);
                        $timeend = strtotime(date("Y-m-d 23:59:59", $timeend));
                    }
                    //$timeend = unenrol_course_expiry_data($courseid, $companyid, $user->userid, $licenseid);
                    if (!empty($timeend)) {
                        $unenrol_update_query = "UPDATE {user_enrolments} ue JOIN {enrol} en ON ue.enrolid=en.id SET ue.timeend=$timeend WHERE en.courseid = $courseid AND ue.userid = $user->userid";
                        $DB->execute($unenrol_update_query);
                    }
                }
                $DB->delete_records('companylicense_users', array('licensecourseid' => $courseid, 'userid' => $user->userid, 'licenseid' => $licenseid));
     
                $DB->delete_records('role_assignments', array('contextid' => $context->id, 'userid' => $user->userid));
            }
            $DB->execute("UPDATE {companylicense} SET used = used - 1 WHERE id = $licenseid");
        }
    }
}

function user_exists($licenseids, $allusers, $courseid) {
    global $DB;

    foreach ($licenseids as $licenseid) {
        foreach ($allusers as $user) {
            $exists = '';
            $exists = $DB->get_record_sql("SELECT DISTINCT(userid) FROM {companylicense_users} WHERE licenseid = $licenseid AND licensecourseid = $courseid AND userid = $user->userid");
            if (empty($exists)) {

                $DB->insert_record('companylicense_users', array('licenseid' => $licenseid, 'licensecourseid' => $courseid, 'issuedate' => time(), 'userid' => $user->userid));
                $DB->execute("UPDATE {companylicense} SET used = used + 1 WHERE id = $licenseid");
            }
        }
    }
}

function course_license_count($courseid, $companyid, $userid) {
    global $DB;
    $license_count = $DB->get_record_sql("SELECT count(cl.id) AS licensecount  FROM {companylicense} cl INNER JOIN {company} c  ON cl.companyid=c.id INNER JOIN {companylicense_users} clu ON clu.licenseid=cl.id WHERE clu.userid=$userid AND c.id=$companyid AND clu.licensecourseid=$courseid AND cl.expirydate > UNIX_TIMESTAMP()");
    if (!empty($license_count)) {
        return $license_count->licensecount;
    }
    return;
}


function course_min_expiry_data($courseid, $companyid, $userid) {
    global $DB;
    $expiry_date = $DB->get_record_sql("SELECT  MIN(IF(((UNIX_TIMESTAMP() + (cl.validlength *24*60*60)) < cl.expirydate), (UNIX_TIMESTAMP() + (cl.validlength *24*60*60)),cl.expirydate) )AS expiry   FROM {companylicense} cl INNER JOIN {company} c  ON cl.companyid=c.id INNER JOIN {companylicense_users} clu ON clu.licenseid=cl.id WHERE clu.userid=$userid AND c.id=$companyid AND clu.licensecourseid=$courseid AND cl.expirydate > UNIX_TIMESTAMP()");
    if (!empty($expiry_date)) {
        return $expiry_date->expiry;
    }
    return;
}


function course_expiry_data($courseid, $companyid, $userid) {
    global $DB;
    $expiry_date = $DB->get_record_sql("SELECT  MAX(IF(((UNIX_TIMESTAMP() + (cl.validlength *24*60*60)) < cl.expirydate), (UNIX_TIMESTAMP() + (cl.validlength *24*60*60)),cl.expirydate) )AS expiry   FROM {companylicense} cl INNER JOIN {company} c  ON cl.companyid=c.id INNER JOIN {companylicense_users} clu ON clu.licenseid=cl.id WHERE clu.userid=$userid AND c.id=$companyid AND clu.licensecourseid=$courseid AND cl.expirydate > UNIX_TIMESTAMP()");
    if (!empty($expiry_date)) {
        return $expiry_date->expiry;
    }
    return;
}

function unenrol_course_expiry_data($courseid, $companyid, $userid, $licenseid) {
    global $DB;
    $expiry_date = $DB->get_record_sql("SELECT  MAX(IF(((UNIX_TIMESTAMP() + (cl.validlength *24*60*60)) < cl.expirydate), (UNIX_TIMESTAMP() + (cl.validlength *24*60*60)),cl.expirydate) )AS expiry   FROM {companylicense} cl INNER JOIN {company} c  ON cl.companyid=c.id INNER JOIN {companylicense_users} clu ON clu.licenseid=cl.id WHERE clu.userid=$userid AND c.id=$companyid AND clu.licensecourseid=$courseid AND cl.expirydate > UNIX_TIMESTAMP() AND cl.id NOT IN ($licenseid)");
    if (!empty($expiry_date)) {
        return $expiry_date->expiry;
    }
    return;
}

function create_enrol_current_course_to_users($licenseid, $courseid, $companyid) {
    global $DB, $USER; 
    $allusers = $DB->get_records_sql("SELECT DISTINCT(userid) FROM {companylicense_users} AS clu JOIN {companylicense} AS cl ON clu.licenseid = cl.id  WHERE cl.companyid = $companyid AND clu.licenseid = $licenseid");
    $roleid = $DB->get_record_sql("SELECT id FROM {role} WHERE shortname = 'student' AND archetype = 'student' ");

    $role = $roleid->id;
   // user_exists($licenseids, $allusers, $courseid);//exit;

    $context = context_course::instance($courseid);

    $timestart = time();
    $timeend = time();
    foreach ($allusers as $user) {


        $exists = $DB->get_record_sql("SELECT DISTINCT(userid) FROM {companylicense_users} WHERE licenseid = $licenseid AND licensecourseid = $courseid AND userid = $user->userid");
            if (empty($exists)) {

                $DB->insert_record('companylicense_users', array('licenseid' => $licenseid, 'licensecourseid' => $courseid, 'issuedate' => time(), 'userid' => $user->userid));
                $DB->execute("UPDATE {companylicense} SET used = used + 1 WHERE id = $licenseid");
            }
        
        $count_license = course_license_count($courseid, $companyid, $user->userid);
        if (!empty($count_license)) {
            if ($count_license == 1) {
                $timeend = course_min_expiry_data($courseid, $companyid, $user->userid);
                $timeend = strtotime(date("Y-m-d 23:59:59", $timeend));
            } else {
                $timeend = course_expiry_data($courseid, $companyid, $user->userid);
                $timeend = strtotime(date("Y-m-d 23:59:59", $timeend));
            }
        }

        $exists = $DB->get_record_sql("SELECT ue.* FROM {user_enrolments} AS ue JOIN {enrol} AS e ON ue.enrolid = e.id WHERE e.courseid = $courseid AND ue.userid = $user->userid");
        $enrol = $DB->get_record_sql("SELECT id FROM {enrol} WHERE enrol = 'license' AND courseid = $courseid");
        $role_exist = $DB->get_record_sql("SELECT id FROM {role_assignments} WHERE roleid = $role AND contextid = $context->id AND userid = $user->userid");
        if (!$exists) {

            // Insert in to User Enrolments table.
            $DB->insert_record('user_enrolments', array('enrolid' => $enrol->id, 'userid' => $user->userid, 'timestart' => $timestart, 'timeend' => $timeend, 'modifierid' => $USER->id, 'timecreated' => $timestart , 'timemodified' => $timestart));
            if (empty($role_exist)) {
                // Insert in to Role Assignment table.
                $DB->insert_record('role_assignments', array('roleid' => $role, 'contextid' => $context->id, 'userid' => $user->userid, 'timemodified' => $timestart, 'modifierid' => $USER->id));
            }//exit;
        } else {
            //update user_enrolment
            $DB->execute("UPDATE {user_enrolments} SET timeend = $timeend WHERE userid = $user->userid AND enrolid = $enrol->id");

            if (empty($role_exist)) {
                // Insert in to Role Assignment table.
                $DB->insert_record('role_assignments', array('roleid' => $role, 'contextid' => $context->id, 'userid' => $user->userid, 'timemodified' => $timestart, 'modifierid' => $USER->id));
            }
        }
//        
    }
}

function course_end_date_final($courseid,$userid){
    global $DB;

    $user_courses_licensids = $DB->get_record_sql("SELECT GROUP_CONCAT(DISTINCT(licenseid)) as licenseid FROM {companylicense_users} where licensecourseid = $courseid AND userid = $userid");

    if(!empty($user_courses_licensids->licenseid)){

        $user_licensidss = explode(',',$user_courses_licensids->licenseid);

        $licenddate = array();

        foreach ($user_licensidss as $lic_id) {

            $user_course_enddate = $DB->get_record_sql("SELECT min(issuedate) as issuedate FROM {companylicense_users} where userid = $userid and licenseid = $lic_id");
            $minimum_date = date('m/d/Y', $user_course_enddate->issuedate);
            $licensedata = $DB->get_record_sql("SELECT * FROM {companylicense} where id = $lic_id");

            $validlength = strtotime("+$licensedata->validlength day");

            if($validlength < $licensedata->expirydate){
                $getexpirydate_value = strtotime( $minimum_date . "+$licensedata->validlength days");
            }else{
                $getexpirydate_value = $licensedata->expirydate;
            }

                $licenddate[] = $getexpirydate_value;

        }
        $timeend = max($licenddate);
    }else{
        $timeend = time();
    }
    return $timeend;
}
