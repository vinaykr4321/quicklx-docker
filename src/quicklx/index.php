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
 * Moodle frontpage.
 *
 * @package    core
 * @copyright  1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if (!file_exists('./config.php')) {
    header('Location: install.php');
    die;
}

require_once('config.php');
require_once($CFG->dirroot .'/course/lib.php');
require_once($CFG->libdir .'/filelib.php');

redirect_if_major_upgrade_required();

$urlparams = array();
if (!empty($CFG->defaulthomepage) && ($CFG->defaulthomepage == HOMEPAGE_MY) && optional_param('redirect', 1, PARAM_BOOL) === 0) {
    $urlparams['redirect'] = 0;
}
$PAGE->set_url('/', $urlparams);
$PAGE->set_pagelayout('frontpage');
$PAGE->set_other_editing_capability('moodle/course:update');
$PAGE->set_other_editing_capability('moodle/course:manageactivities');
$PAGE->set_other_editing_capability('moodle/course:activityvisibility');

// Prevent caching of this page to stop confusion when changing page after making AJAX changes.
$PAGE->set_cacheable(false);

require_course_login($SITE);

$hasmaintenanceaccess = has_capability('moodle/site:maintenanceaccess', context_system::instance());

// If the site is currently under maintenance, then print a message.
if (!empty($CFG->maintenance_enabled) and !$hasmaintenanceaccess) {
    print_maintenance_message();
}

$hassiteconfig = has_capability('moodle/site:config', context_system::instance());

if ($hassiteconfig && moodle_needs_upgrading()) {
    redirect($CFG->wwwroot .'/'. $CFG->admin .'/index.php');
}

// If site registration needs updating, redirect.
\core\hub\registration::registration_reminder('/index.php');

if (get_home_page() != HOMEPAGE_SITE) {
    // Redirect logged-in users to My Moodle overview if required.
    $redirect = optional_param('redirect', 1, PARAM_BOOL);
    if (optional_param('setdefaulthome', false, PARAM_BOOL)) {
        set_user_preference('user_home_page_preference', HOMEPAGE_SITE);
    } else if (!empty($CFG->defaulthomepage) && ($CFG->defaulthomepage == HOMEPAGE_MY) && $redirect === 1) {
        redirect($CFG->wwwroot .'/my/');
    } else if (!empty($CFG->defaulthomepage) && ($CFG->defaulthomepage == HOMEPAGE_USER)) {
        $frontpagenode = $PAGE->settingsnav->find('frontpage', null);
        if ($frontpagenode) {
            $frontpagenode->add(
                get_string('makethismyhome'),
                new moodle_url('/', array('setdefaulthome' => true)),
                navigation_node::TYPE_SETTING);
        } else {
            $frontpagenode = $PAGE->settingsnav->add(get_string('frontpagesettings'), null, navigation_node::TYPE_SETTING, null);
            $frontpagenode->force_open();
            $frontpagenode->add(get_string('makethismyhome'),
                new moodle_url('/', array('setdefaulthome' => true)),
                navigation_node::TYPE_SETTING);
        }
    }
}

// Trigger event.
course_view(context_course::instance(SITEID));

// If the hub plugin is installed then we let it take over the homepage here.
if (file_exists($CFG->dirroot.'/local/hub/lib.php') and get_config('local_hub', 'hubenabled')) {
    require_once($CFG->dirroot.'/local/hub/lib.php');
    $hub = new local_hub();
    $continue = $hub->display_homepage();
    // Function display_homepage() returns true if the hub home page is not displayed
    // ...mostly when search form is not displayed for not logged users.
    if (empty($continue)) {
        exit;
    }
}

$PAGE->set_pagetype('site-index');
$PAGE->set_docs_path('');
$editing = $PAGE->user_is_editing();
$PAGE->set_title($SITE->fullname);
$PAGE->set_heading($SITE->fullname);
$courserenderer = $PAGE->get_renderer('core', 'course');
echo $OUTPUT->header();

// Print Section or custom info.
$siteformatoptions = course_get_format($SITE)->get_format_options();
$modinfo = get_fast_modinfo($SITE);
$modnames = get_module_types_names();
$modnamesplural = get_module_types_names(true);
$modnamesused = $modinfo->get_used_module_names();
$mods = $modinfo->get_cms();

if (!empty($CFG->customfrontpageinclude)) {
    include($CFG->customfrontpageinclude);

} else if ($siteformatoptions['numsections'] > 0) {
    if ($editing) {
        // Make sure section with number 1 exists.
        course_create_sections_if_missing($SITE, 1);
        // Re-request modinfo in case section was created.
        $modinfo = get_fast_modinfo($SITE);
    }
    $section = $modinfo->get_section_info(1);
    if (($section && (!empty($modinfo->sections[1]) or !empty($section->summary))) or $editing) {
        echo $OUTPUT->box_start('generalbox sitetopic');

        // If currently moving a file then show the current clipboard.
        if (ismoving($SITE->id)) {
            $stractivityclipboard = strip_tags(get_string('activityclipboard', '', $USER->activitycopyname));
            echo '<p><font size="2">';
            echo "$stractivityclipboard&nbsp;&nbsp;(<a href=\"course/mod.php?cancelcopy=true&amp;sesskey=".sesskey()."\">";
            echo get_string('cancel') . '</a>)';
            echo '</font></p>';
        }

        $context = context_course::instance(SITEID);

        // If the section name is set we show it.
        if (trim($section->name) !== '') {
            echo $OUTPUT->heading(
                format_string($section->name, true, array('context' => $context)),
                2,
                'sectionname'
            );
        }

        $summarytext = file_rewrite_pluginfile_urls($section->summary,
            'pluginfile.php',
            $context->id,
            'course',
            'section',
            $section->id);
        $summaryformatoptions = new stdClass();
        $summaryformatoptions->noclean = true;
        $summaryformatoptions->overflowdiv = true;

        echo format_text($summarytext, $section->summaryformat, $summaryformatoptions);

        if ($editing && has_capability('moodle/course:update', $context)) {
            $streditsummary = get_string('editsummary');
            echo "<a title=\"$streditsummary\" " .
                 " href=\"course/editsection.php?id=$section->id\">" . $OUTPUT->pix_icon('t/edit', $streditsummary) .
                 "</a><br /><br />";
        }

        $courserenderer = $PAGE->get_renderer('core', 'course');
        echo $courserenderer->course_section_cm_list($SITE, $section);

        echo $courserenderer->course_section_add_cm_control($SITE, $section->section);
        echo $OUTPUT->box_end();
    }
}
// Include course AJAX.
include_course_ajax($SITE, $modnamesused);

if (isloggedin() and !isguestuser() and isset($CFG->frontpageloggedin)) {
    $frontpagelayout = $CFG->frontpageloggedin;
} else {
    $frontpagelayout = $CFG->frontpage;
}

foreach (explode(',', $frontpagelayout) as $v) {
    switch ($v) {
        // Display the main part of the front page.
        case FRONTPAGENEWS:
            if ($SITE->newsitems) {
                // Print forums only when needed.
                require_once($CFG->dirroot .'/mod/forum/lib.php');

                if (! $newsforum = forum_get_course_forum($SITE->id, 'news')) {
                    print_error('cannotfindorcreateforum', 'forum');
                }

                // Fetch news forum context for proper filtering to happen.
                $newsforumcm = get_coursemodule_from_instance('forum', $newsforum->id, $SITE->id, false, MUST_EXIST);
                $newsforumcontext = context_module::instance($newsforumcm->id, MUST_EXIST);

                $forumname = format_string($newsforum->name, true, array('context' => $newsforumcontext));
                echo html_writer::link('#skipsitenews',
                    get_string('skipa', 'access', core_text::strtolower(strip_tags($forumname))),
                    array('class' => 'skip-block skip'));

                // Wraps site news forum in div container.
                echo html_writer::start_tag('div', array('id' => 'site-news-forum'));

                if (isloggedin()) {
                    $SESSION->fromdiscussion = $CFG->wwwroot;
                    $subtext = '';
                    if (\mod_forum\subscriptions::is_subscribed($USER->id, $newsforum)) {
                        if (!\mod_forum\subscriptions::is_forcesubscribed($newsforum)) {
                            $subtext = get_string('unsubscribe', 'forum');
                        }
                    } else {
                        $subtext = get_string('subscribe', 'forum');
                    }
                    echo $OUTPUT->heading($forumname);
                    $suburl = new moodle_url('/mod/forum/subscribe.php', array('id' => $newsforum->id, 'sesskey' => sesskey()));
                    echo html_writer::tag('div', html_writer::link($suburl, $subtext), array('class' => 'subscribelink'));
                } else {
                    echo $OUTPUT->heading($forumname);
                }

                forum_print_latest_discussions($SITE, $newsforum, $SITE->newsitems, 'plain', 'p.modified DESC');

                // End site news forum div container.
                echo html_writer::end_tag('div');

                echo html_writer::tag('span', '', array('class' => 'skip-block-to', 'id' => 'skipsitenews'));
            }
        break;

        case FRONTPAGEENROLLEDCOURSELIST:
            $mycourseshtml = $courserenderer->frontpage_my_courses();
            if (!empty($mycourseshtml)) {
                echo html_writer::link('#skipmycourses',
                    get_string('skipa', 'access', core_text::strtolower(get_string('mycourses'))),
                    array('class' => 'skip skip-block'));

                // Wrap frontpage course list in div container.
                echo html_writer::start_tag('div', array('id' => 'frontpage-course-list'));

                echo $OUTPUT->heading(get_string('mycourses'));
                echo $mycourseshtml;

                // End frontpage course list div container.
                echo html_writer::end_tag('div');

                echo html_writer::tag('span', '', array('class' => 'skip-block-to', 'id' => 'skipmycourses'));
                break;
            }
            // No "break" here. If there are no enrolled courses - continue to 'Available courses'.

        case FRONTPAGEALLCOURSELIST:
            if (is_siteadmin())
            {
                $availablecourseshtml = $courserenderer->frontpage_available_courses();
                if (!empty($availablecourseshtml)) {
                    echo html_writer::link('#skipavailablecourses',
                        get_string('skipa', 'access', core_text::strtolower(get_string('availablecourses'))),
                        array('class' => 'skip skip-block'));

                    // Wrap frontpage course list in div container.
                    echo html_writer::start_tag('div', array('id' => 'frontpage-course-list'));

                    echo $OUTPUT->heading(get_string('availablecourses'));
        ////////////////////////////////////////////////////////////////////////////////
                    //echo $availablecourseshtml;
        ////////////////////////////////////////////////////////////////////////////////

                    /* Show the courses */
                    $allCourses = $DB->get_records_sql("select * from {course} where id !=1");

                    /* Show the courses of selected company */
                    $companyid = iomad::get_my_companyid(context_system::instance());
                    $allCompanyCourses = $DB->get_records_sql("select c.* from {course} as c JOIN {company_course} as cc ON c.id = cc.courseid JOIN {company_users} as cu ON cu.companyid = cc.companyid where cc.companyid = $companyid and cu.userid = $USER->id");
                    $companyCourseId = array();
                    foreach ($allCompanyCourses as $companyCourse) 
                    {
                        $companyCourseId[] = $companyCourse->id;
                    }
                    foreach ($allCourses as $course) 
                    {
                        if ($course->id != 1 && in_array($course->id, $companyCourseId)) 
                        {
                            $context = context_course::instance($course->id);
                            if(is_enrolled($context, $USER->id, '', true)) // for start
                            {
                                $startContinue = $DB->get_record_sql("select * from {course_completions} where userid= $USER->id AND course = $course->id");
                                if ($startContinue) 
                                {
                                    $startCont = 'Continue';
                                }
                                else
                                {
                                    $startCont = 'Start';
                                }
                                echo '<div class="coursebox clearfix" data-courseid="'.$course->id.'" data-type="1">
                                        <div class="info">
                                            <h3 class="coursename">
                                                '.$course->fullname.'
                                            </h3>
                                            <div class="moreinfo">
                                                <a class="" href="'.$CFG->wwwroot.'/course/view.php?id='.$course->id.'" style="background-color:#F75701;color:#fff;padding:11px 42px;text-decoration:none;border-radius:5px;">
                                                    '.$startCont.'
                                                </a>
                                            </div>
                                        </div>
                                        <div class="content"></div>
                                    </div>';
                            }
                            else // for register
                            {
                                echo '<div class="coursebox clearfix" data-courseid="'.$course->id.'" data-type="1">
                                        <div class="info">
                                            <h3 class="coursename">
                                                '.$course->fullname.'
                                            </h3>
                                            <div class="moreinfo">
                                                <a class="" href="#" style="background-color:#b9aca5;color:#fff;padding:10px 30px;text-decoration:none;border-radius:5px; "onClick=enrol('.$course->id.','.$USER->id.','.$companyid.')>
                                                    Register
                                                </a>
                                            </div>
                                        </div>
                                        <div class="content"></div>
                                    </div>';
                            }
                        }
                        else // for popup
                        {
                            echo '<div class="coursebox clearfix" data-courseid="'.$course->id.'" data-type="1">
                                        <div class="info">
                                            <h3 class="coursename">
                                                '.$course->fullname.'
                                            </h3>
                                            <div class="moreinfo">
                                                <a data-toggle="modal" data-target="#myModal" class="" href="#" style="background-color:#b9aca5;color:#fff;padding:10px 30px;text-decoration:none;border-radius:5px; ">
                                                    Add To Library
                                                </a>
                                            </div>
                                        </div>
                                        <div class="content"></div>
                                    </div>';
                        }
                    }

                    // End frontpage course list div container.
                    echo html_writer::end_tag('div');
                    echo html_writer::tag('span', '', array('class' => 'skip-block-to', 'id' => 'skipavailablecourses'));
                }
            }
            else
            {
                echo html_writer::link('#skipavailablecourses',
                        get_string('skipa', 'access', core_text::strtolower(get_string('availablecourses'))),
                        array('class' => 'skip skip-block'));

                    // Wrap frontpage course list in div container.
                    echo html_writer::start_tag('div', array('id' => 'frontpage-course-list'));

                    echo $OUTPUT->heading(get_string('availablecourses'));
                   // $allCourses = get_courses();


                    /* Show the courses of selected company */
                    $companyid = iomad::get_my_companyid(context_system::instance());
                    $allCourses = $DB->get_records_sql("select c.* from {course} as c JOIN {company_course} as cc ON c.id = cc.courseid JOIN {company_users} as cu ON cu.companyid = cc.companyid where cc.companyid = $companyid and cu.userid = $USER->id");



                    foreach ($allCourses as $course) 
                    {
                        if ($course->id != 1) 
                        {
                            $context = context_course::instance($course->id);
                            if(is_enrolled($context, $USER->id, '', true))
                            {

                        /* echo html_writer::start_tag('div', array('class' => 'coursebox clearfix', 'data-courseid' => $course->id, 'data-type' => 1) );
                         	echo html_writer::start_tag('div', array('class' => 'info'));
                         		
                         		echo html_writer::start_tag('h3', array('class' => 'coursename'));
         						echo $course->fullname;
                                echo html_writer::end_tag('h3');


                         	echo html_writer::end_tag('div');
                         echo html_writer::end_tag('div');*/

                       $timeData = $DB->get_record_sql("SELECT ue.timestart,if(cc.timestarted != NULL,'Continue','Start') as CourseStatus FROM mdl_enrol AS e JOIN  mdl_user_enrolments AS ue ON ue.enrolid = e.id left JOIN mdl_course_completions AS cc ON ue.userid=cc.userid  WHERE e.courseid =$course->id  AND ue.userid=$USER->id");
                       $date ='';
                       if ($timeData) 
                       {
                           $date = date('m/d/Y',$timeData->timestart);
                       }
                            

                                echo '<div class="coursebox clearfix" data-courseid="'.$course->id.'" data-type="1">
                                        <div class="info">
                                            <h3 class="coursename">
                                                '.$course->fullname.'
                                            </h3>
                                            <div class="moreinfo">
                                                <a class="" href="'.$CFG->wwwroot.'/course/view.php?id='.$course->id.'" style="background-color:#F75701;color:#fff;padding:11px 42px;text-decoration:none;border-radius:5px;">
                                                    '.$timeData->coursestatus.'
                                                </a>
                                            </div>
                                        </div>
                                        <div class="content"></div>
                                        <h6>Registration Date: '.$date.'</h6>
                                    </div>';
                            }
                            else
                            {
                                echo '<div class="coursebox clearfix" data-courseid="'.$course->id.'" data-type="1">
                                        <div class="info">
                                            <h3 class="coursename">
                                                '.$course->fullname.'
                                            </h3>
                                            <div class="moreinfo">
                                                <a class="" href="#" style="background-color:#b9aca5;color:#fff;padding:10px 30px;text-decoration:none;border-radius:5px; "onClick=enrol('.$course->id.','.$USER->id.','.$companyid.')>
                                                    Register
                                                </a>
                                            </div>
                                        </div>
                                        <div class="content"></div>
                                    </div>';
                            }
                        }
                    }
                    // End frontpage course list div container.
                    echo html_writer::end_tag('div');

                    echo html_writer::tag('span', '', array('class' => 'skip-block-to', 'id' => 'skipavailablecourses'));
            }
        break;

        case FRONTPAGECATEGORYNAMES:
            echo html_writer::link('#skipcategories',
                get_string('skipa', 'access', core_text::strtolower(get_string('categories'))),
                array('class' => 'skip skip-block'));

            // Wrap frontpage category names in div container.
            echo html_writer::start_tag('div', array('id' => 'frontpage-category-names'));

            echo $OUTPUT->heading(get_string('categories'));
            echo $courserenderer->frontpage_categories_list();

            // End frontpage category names div container.
            echo html_writer::end_tag('div');

            echo html_writer::tag('span', '', array('class' => 'skip-block-to', 'id' => 'skipcategories'));
        break;

        case FRONTPAGECATEGORYCOMBO:
            echo html_writer::link('#skipcourses',
                get_string('skipa', 'access', core_text::strtolower(get_string('courses'))),
                array('class' => 'skip skip-block'));

            // Wrap frontpage category combo in div container.
            echo html_writer::start_tag('div', array('id' => 'frontpage-category-combo'));

            echo $OUTPUT->heading(get_string('courses'));
            echo $courserenderer->frontpage_combo_list();

            // End frontpage category combo div container.
            echo html_writer::end_tag('div');

            echo html_writer::tag('span', '', array('class' => 'skip-block-to', 'id' => 'skipcourses'));
        break;

        case FRONTPAGECOURSESEARCH:
            echo $OUTPUT->box($courserenderer->course_search_form('', 'short'), 'mdl-align');
        break;

    }
    echo '<br />';
}
if ($editing && has_capability('moodle/course:create', context_system::instance())) {
    echo $courserenderer->add_new_course_button();
}
echo $OUTPUT->footer();

?>


<script type="text/javascript">
    function enrol(courseid,userid,companyid)
    {
        //var url1 = window.location.hostname;
        var url2 = window.location.pathname;
        $.ajax({
            url: url2+'enrolluser.php',
            method: 'post',
            data: { 'courseid': courseid, 'userid' : userid, 'companyid' : companyid},
            success: function (response) 
            {
                alert(response);
                location.reload();
            }
        });
    }
</script>



<!--  Custom Modal Popup in moodle   -->

     <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Message</h4>
        </div>
        <div class="modal-body">
          <p>Get access instantly! Call 1-888-888-8888.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
        </div>
      </div>
      
    </div>
  </div>