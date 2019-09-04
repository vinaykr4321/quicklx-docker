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
 * Class containing data for courses view in the myoverview block.
 *
 * @package    block_myoverview
 * @copyright  2017 Ryan Wyllie <ryan@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_myoverview\output;

defined('MOODLE_INTERNAL') || die();

use renderable;
use renderer_base;
use templatable;
use core_course\external\course_summary_exporter;
use core_completion\progress;

/**
 * Class containing data for courses view in the myoverview block.
 *
 * @copyright  2017 Simey Lameze <simey@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class courses_view implements renderable, templatable {

    /** Quantity of courses per page. */
    const COURSES_PER_PAGE = 6;

    /** @var array $courses List of courses the user is enrolled in. */
    protected $courses = [];

    /** @var array $coursesprogress List of progress percentage for each course. */
    protected $coursesprogress = [];

    /**
     * The courses_view constructor.
     *
     * @param array $courses list of courses.
     * @param array $coursesprogress list of courses progress.
     */
    public function __construct($courses, $coursesprogress) {
        $this->courses = $courses;
        $this->coursesprogress = $coursesprogress;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return array
     */
    public function export_for_template(renderer_base $output) {
        global $CFG, $USER, $DB;
        require_once($CFG->dirroot . '/course/lib.php');

        /*
         * Custom Code
         * 2018 Syllametrics | support@syllametrics.com
         * Last Update : 09th April, 2019
         * List of all courses which is not expired yet)
         */


 
        $existing_courses = self::display_existing_courses($USER->id);

       if(!empty($existing_courses[0])){
            if (self::check_expired_course_information()) {
                $required_courses = $existing_courses;
            } else {
                $required_courses = array_intersect($existing_courses, array_keys($this->courses));
            }


            $all_courses = array();
            foreach ($required_courses as $value) {
                $courseinfo = $DB->get_record('course', array('id' => $value));
                $all_courses[] = $courseinfo;
            }
            $this->courses = $all_courses;
        }


        $names = array(); 
        foreach ($this->courses as $my_object) {
            $names[] = $my_object->fullname; 
        }
        array_multisort($names,SORT_ASC|SORT_NATURAL|SORT_FLAG_CASE, $this->courses);


        //Custom code end
        // Build courses view data structure.
        $coursesview = [
            'hascourses' => !empty($this->courses)
        ];

        // How many courses we have per status?
        $coursesbystatus = ['past' => 0, 'inprogress' => 0, 'future' => 0];
        foreach ($this->courses as $course) {
            /*
             * Custom Code
             * 2018 Syllametrics | support@syllametrics.com
             * Last Update : 18th Jan, 2019
             * Change the course display on dashboard according to the course completion percentage )
             */
            $percentage = progress::get_course_progress_percentage($course, $USER->id);
            if ($percentage == 100) {
                // Completed
                $classified = COURSE_TIMELINE_PAST;
            } else {
//                $startCont = $DB->get_record_sql("SELECT s.* FROM mdl_scorm AS s 
//                                    JOIN mdl_scorm_scoes AS ss ON s.id=ss.scorm 
//                                    JOIN mdl_scorm_scoes_track AS st ON s.id=st.scormid AND ss.id=st.scoid
//                                    WHERE st.userid=$USER->id AND s.course IN ($course->id)");
                if ($percentage > 0) {
                    $classified = 'inprogress';
                } else {
                    // Not started
                    $classified = COURSE_TIMELINE_FUTURE;
                }
            }

            $courseid = $course->id;
            $context = \context_course::instance($courseid);
            $exporter = new course_summary_exporter($course, [
                'context' => $context
            ]);
            $exportedcourse = $exporter->export($output);
            // Convert summary to plain text.
            $exportedcourse->summary = content_to_text($exportedcourse->summary, $exportedcourse->summaryformat);

            // Include course visibility.
            $exportedcourse->visible = (bool) $course->visible;

            $courseprogress = null;

            //$classified = course_classify_for_timeline($course);

            if (isset($this->coursesprogress[$courseid])) {
                $courseprogress = $this->coursesprogress[$courseid]['progress'];
                $exportedcourse->hasprogress = !is_null($courseprogress);
                $exportedcourse->progress = $courseprogress;
            }

            if ($classified == COURSE_TIMELINE_PAST) {
                // Courses that have already ended.
                $pastpages = floor($coursesbystatus['past'] / $this::COURSES_PER_PAGE);

                $coursesview['past']['pages'][$pastpages]['courses'][] = $exportedcourse;
                $coursesview['past']['pages'][$pastpages]['active'] = ($pastpages == 0 ? true : false);
                $coursesview['past']['pages'][$pastpages]['page'] = $pastpages + 1;
                $coursesview['past']['haspages'] = true;
                $coursesbystatus['past'] ++;
            } else if ($classified == COURSE_TIMELINE_FUTURE) {
                // Courses that have not started yet.
                $futurepages = floor($coursesbystatus['future'] / $this::COURSES_PER_PAGE);

                $coursesview['future']['pages'][$futurepages]['courses'][] = $exportedcourse;
                $coursesview['future']['pages'][$futurepages]['active'] = ($futurepages == 0 ? true : false);
                $coursesview['future']['pages'][$futurepages]['page'] = $futurepages + 1;
                $coursesview['future']['haspages'] = true;
                $coursesbystatus['future'] ++;
            } else {
                // Courses still in progress. Either their end date is not set, or the end date is not yet past the current date.
                $inprogresspages = floor($coursesbystatus['inprogress'] / $this::COURSES_PER_PAGE);

                $coursesview['inprogress']['pages'][$inprogresspages]['courses'][] = $exportedcourse;
                $coursesview['inprogress']['pages'][$inprogresspages]['active'] = ($inprogresspages == 0 ? true : false);
                $coursesview['inprogress']['pages'][$inprogresspages]['page'] = $inprogresspages + 1;
                $coursesview['inprogress']['haspages'] = true;
                $coursesbystatus['inprogress'] ++;
            }
        }

        // Build courses view paging bar structure.
        foreach ($coursesbystatus as $status => $total) {
            $quantpages = ceil($total / $this::COURSES_PER_PAGE);

            if ($quantpages) {
                $coursesview[$status]['pagingbar']['disabled'] = ($quantpages <= 1);
                $coursesview[$status]['pagingbar']['pagecount'] = $quantpages;
                $coursesview[$status]['pagingbar']['first'] = ['page' => '&laquo;', 'url' => '#'];
                $coursesview[$status]['pagingbar']['last'] = ['page' => '&raquo;', 'url' => '#'];
                for ($page = 0; $page < $quantpages; $page++) {
                    $coursesview[$status]['pagingbar']['pages'][$page] = [
                        'number' => $page + 1,
                        'page' => $page + 1,
                        'url' => '#',
                        'active' => ($page == 0 ? true : false)
                    ];
                }
            }
        }

        return $coursesview;
    }

    function display_existing_courses($userid) {

       
        global $DB;
        if (self::check_expired_course_information()) {
            $courselist = $DB->get_record_sql("SELECT GROUP_CONCAT(e.courseid) as courseid FROM {enrol} as e join {user_enrolments} as ue ON e.id = ue.enrolid where e.enrol = 'license' and ue.userid = $userid");
            $courselist=explode(',',$courselist->courseid);
        } else {
            $courselist = $DB->get_record_sql("SELECT GROUP_CONCAT(e.courseid) as courseid FROM {enrol} as e join {user_enrolments} as ue ON e.id = ue.enrolid where e.enrol = 'license' and ue.userid = $userid and ue.timeend > unix_timestamp()");
            $courselist=explode(',',$courselist->courseid);
        }



        return $courselist;
    }

    function check_expired_course_information() {
        global $DB, $USER;
        if (!is_siteadmin()) {

            $data = $DB->get_record_sql("SELECT c.expiredcourse from {company} AS c JOIN {company_users} AS cu on cu.companyid = c.id WHERE cu.userid = $USER->id");
            if ($data->expiredcourse == 1) 
		        {
                return true;
            	}
       		 }return false;
    	}

	}
