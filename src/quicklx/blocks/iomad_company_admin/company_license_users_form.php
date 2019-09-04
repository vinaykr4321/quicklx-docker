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

require_once(dirname(__FILE__) . '/../../config.php'); // Creates $PAGE.
require_once('lib.php');
require_once($CFG->libdir . '/formslib.php');
require_once($CFG->dirroot . '/local/email/lib.php');
require_once($CFG->dirroot . '/lib/enrollib.php');
require_once($CFG->dirroot . '/local/iomad/lib/company.php');

class company_license_users_form extends moodleform {

    protected $context = null;
    protected $selectedcompany = 0;
    protected $potentialusers = null;
    protected $currentusers = null;
    protected $course = null;
    protected $departmentid = 0;
    protected $companydepartment = 0;
    protected $subhierarchieslist = null;
    protected $parentlevel = null;
    protected $license = array();
    protected $selectedcourses = array();
    protected $courseselect = array();
    protected $firstcourseid = 0;

    public function __construct($actionurl, $context, $companyid, $licenseid, $departmentid, $selectedcourses, $error, $output, $chosenid = 0) {
        global $USER, $DB;
        $this->selectedcompany = $companyid;
        $this->context = $context;
        $company = new company($this->selectedcompany);
        $this->parentlevel = company::get_company_parentnode($company->id);
        $this->companydepartment = $this->parentlevel->id;
        $this->licenseid = $licenseid;
        $this->license = $DB->get_record('companylicense', array('id' => $licenseid));
        $this->selectedcourses = $selectedcourses;
        $this->error = $error;

        // Get the courses to send to if emails are configured.
        if (!empty($this->license)) {
            $courses = company::get_courses_by_license($this->license->id);
        } else {
            $courses = array();
        }
        $courseselect = array();
        $first = true;
        foreach ($courses as $courseid => $course) {
            $courseselect[$course->id] = $course->fullname;
            if ($first) {
                $this->firstcourseid = $courseid;
                $first = false;
            }
        }
        $this->courseselect = $courseselect;

        if (iomad::has_capability('block/iomad_company_admin:allocate_licenses', context_system::instance())) {
            $userhierarchylevel = $this->parentlevel->id;
        } else {
            $userlevel = $company->get_userlevel($USER);
            $userhierarchylevel = $userlevel->id;
        }

        if ($departmentid == 0) {
            $this->departmentid = $userhierarchylevel;
            $this->subhierarchieslist = company::get_all_subdepartments($userhierarchylevel);
        } else {
            $this->departmentid = $departmentid;
            $this->subhierarchieslist = company::get_all_subdepartments($departmentid);
        }

        $this->output = $output;
        $this->chosenid = $chosenid;
        parent::__construct($actionurl);
    }

    public function set_course($courses) {
        $keys = array_keys($courses);
        $this->course = $courses[$keys[0]];
    }

    public function create_user_selectors() {
        if (!empty($this->licenseid)) {

            if (count($this->courseselect) > 1) {
                $multiple = true;
            } else {
                $multiple = false;
            }
            $options = array('context' => $this->context,
                'companyid' => $this->selectedcompany,
                'licenseid' => $this->licenseid,
                'departmentid' => $this->departmentid,
                'subdepartments' => $this->subhierarchieslist,
                'parentdepartmentid' => $this->parentlevel,
                'program' => $this->license->program,
                'selectedcourses' => $this->selectedcourses,
                'multiple' => $multiple);
            if (empty($this->potentialusers)) {
                $this->potentialusers = new potential_license_user_selector('potentialcourseusers', $options);
            }
            if (empty($this->currentusers)) {
                $this->currentusers = new current_license_user_selector('currentlyenrolledusers', $options);
            }
        } else {
            return;
        }
    }

    public function definition() {
        $this->_form->addElement('hidden', 'companyid', $this->selectedcompany);
        $this->_form->addElement('hidden', 'licenseid', $this->licenseid);
        $this->_form->setType('companyid', PARAM_INT);
        $this->_form->setType('licenseid', PARAM_INT);
    }

    public function definition_after_data() {
        global $USER;

        $mform = & $this->_form;

        if (!empty($this->course->id)) {
            $this->_form->addElement('hidden', 'courseid', $this->course->id);
        }
        $this->create_user_selectors();

        // Adding the elements in the definition_after_data function rather than in the definition function
        // so that when the currentcourses or potentialcourses get changed in the process function, the
        // changes get displayed, rather than the lists as they are before processing.

        if (!$this->licenseid) {
            die('No license selected.');
        }

        $company = new company($this->selectedcompany);

        $subdepartmenthtml = "";
        $userdepartment = $company->get_userlevel($USER);
        $departmentslist = company::get_all_subdepartments($userdepartment->id);
        $departmenttree = company::get_all_subdepartments_raw($userdepartment->id);

        /*
         * Custom Code
         * 2018 Syllametrics | support@syllametrics.com
         * Last Update : 29 Nov, 2018
         * Updating code for department tree.
         */

        /* if ($userdepartment->parent != 0) 
          {
          $departmentslist = '';
          $departmenttree = '';
          $departmentslist = company::get_all_subdepartments($userdepartment->parent);
          $departmenttree = company::get_all_subdepartments_raw($userdepartment->parent);
          } */
        $treehtml = $this->output->department_tree($departmenttree, optional_param('deptid', 0, PARAM_INT));

        $mform->addElement('html', '<p>' . get_string('updatedepartmentusersselection', 'block_iomad_company_admin') . '</p>');
        $mform->addElement('html', $treehtml);
        //$mform->addElement('html', $subdepartmenthtml);
        // This is getting hidden anyway, so no need for label
        $mform->addElement('html', '<div class="display:none;">');
        $mform->addElement('select', 'deptid', ' ', $departmentslist, array('class' => 'iomad_department_select', 'onchange' => 'this.form.submit()'));
        $mform->disabledIf('deptid', 'action', 'eq', 1);
        $mform->addElement('html', '</div>');

        if ($this->license->expirydate > time()) {
            // Add in the courses selector.
            if (empty($this->license->program)) {
                $courseselector = $mform->addElement('select', 'courses', get_string('courses', 'block_iomad_company_admin'), $this->courseselect, array('id' => 'courseselector'));
                $courseselector->setMultiple(true);
                $courseselector->setSelected($this->firstcourseid);
            } else {
                $mform->addElement('hidden', 'courses');
                $mform->setType('courses', PARAM_INT);
            }

            $mform->addElement('header', 'header', get_string('license_users_for', 'block_iomad_company_admin', $this->license->name));

            /*
             * Custom code 
             * Syllametrics | support@syllametrics.com  
             * This code will add a text-box which is used to allocate the seats to a license
             * Last Update: 9th oct,2018
             */

            /**
              if ($this->license->allocation == '9999999')
              {
              $mform->addElement('html', get_string("unlimitedlicensetotal", "block_iomad_company_admin"));
              }
              else
              {
              if (!$this->license->program) {
              $mform->addElement('html', '('.($this->license->allocation - $this->license->used).' / '.
              $this->license->allocation.get_string('licensetotal', 'block_iomad_company_admin').')');
              } else {
              $mform->addElement('html', '('.($this->license->allocation - $this->license->used) / count($this->courseselect) .' / '.
              $this->license->allocation / count($this->courseselect) . get_string('licensetotal', 'block_iomad_company_admin').')');
              }
              }
             * */
            global $DB;
            $licid = $this->license->id;
            if (count($this->courseselect) > 0) {

                $allocationtotal = (9999999 * count($this->courseselect));
                $DB->execute("Update {companylicense} set allocation = $allocationtotal WHERE id = $licid AND unlimitedseats = 1");
            } else {

                $DB->execute("Update {companylicense} set allocation = 9999999 WHERE id = $licid AND unlimitedseats = 1");
            }

            $licenseused_value = $DB->get_records('companylicense', array('id' => $this->license->id));


            $t = array();
            foreach ($licenseused_value as $lic) {
                $t = $lic->allocation;
            }
            $this->license->allocation = $t;



            if ($this->license->unlimitedseats == '1') {

                $mform->addElement('html', get_string("unlimitedlicensetotal", "block_iomad_company_admin"));
            } else {

                if (!$this->license->program) {

                    $mform->addElement('html', '(' . ($this->license->allocation - $this->license->used) . ' / ' .
                            $this->license->allocation . get_string('licensetotal', 'block_iomad_company_admin') . ')');
                } else {

                  /*  $licenseuserall = $DB->get_record_sql("SELECT count(DISTINCT userid) as userid FROM {companylicense_users}
                                         WHERE licenseid = $licid");

                    $totallicenseuser = $licenseuserall->userid;

                    $totalenrolluser = $totallicenseuser * count($this->courseselect);

                    $mform->addElement('html', '(' . ($this->license->allocation - $totalenrolluser) / count($this->courseselect) . ' / ' .
                            $this->license->allocation / count($this->courseselect) . get_string('licensetotal', 'block_iomad_company_admin') . ')');*/


                    $mform->addElement('html', '(' . (  ($this->license->allocation / count($this->courseselect)) - ($this->license->used / count($this->courseselect))  ). ' / ' .
                            $this->license->allocation / count($this->courseselect) . get_string('licensetotal', 'block_iomad_company_admin') . ')');
                }
            }


            /* custom code end */
        } else {
            $mform->addElement('header', 'header', get_string('license_users_for', 'block_iomad_company_admin', $this->license->name) . ' *Expired* ');
            $mform->addElement('html', '(' . ($this->license->used) . ' / ' .
                    $this->license->allocation . get_string('licensetotal', 'block_iomad_company_admin') . ')');
        }

        $mform->addElement('date_time_selector', 'due', get_string('senddate', 'block_iomad_company_admin'));
        $mform->addHelpButton('due', 'senddate', 'block_iomad_company_admin');
        if ($this->license->startdate > time()) {
            $mform->setDefault('due', $this->license->startdate);
        }

        if ($this->error == 1) {
            $mform->addElement('html', "<div class='form-group row has-danger fitem'>
                <div class='form-inline felement' data-fieldtype='text'>
                <div class='form-control-feedback'>" .
                    get_string('licensetoomanyusers', 'block_iomad_company_admin') .
                    "</div></div>");
        }

        $mform->addElement('html', '<table summary=""
           class="companycourseuserstable addremovetable generaltable generalbox boxaligncenter"
           cellspacing="0">
           <tr>
           <td id="existingcell">');

        /*
         * Custom code 
         * Syllametrics | support@syllametrics.com  
         * This code will change the style,now allocate license to a user from left to right prior it was right to left,also changed the arrow.
         * Last Update: 18th oct,2018
         */

        //$mform->addElement('html', $this->currentusers->display(true));
        $mform->addElement('html', $this->potentialusers->display(true));
        if ($this->license->expirydate > time()) {
            $mform->addElement('html', '
              </td>
              <td id="buttonscell">
              <div id="addcontrols">
              <input name="add" id="add" type="submit" value="&nbsp;' .
                    $this->output->rarrow() . '&nbsp;' . get_string('licenseallocate', 'block_iomad_company_admin') .
                    '" title="Enrol" />

              <input name="addall" id="addall" type="submit" value="&nbsp;' .
                    $this->output->rarrow() . '&nbsp;' . get_string('licenseallocateall', 'block_iomad_company_admin') .
                    '" title="Enrolall" />

              </div>

              <div id="removecontrols"><input name="remove" id="remove" type="submit" value="' .
                    $this->output->larrow() . '&nbsp;' . get_string('licenseremove', 'block_iomad_company_admin') .
                    '" title="Unenrol" />
              <input name="removeall" id="removeall" type="submit" value="' .
                    $this->output->larrow() . '&nbsp;' . get_string('licenseremoveall', 'block_iomad_company_admin') .
                    '" title="Unenrolall" />
              </div>
              </td>
              <td id="potentialcell">');
            $mform->addElement('html', $this->currentusers->display(true));
            //$mform->addElement('html', $this->potentialusers->display(true));
        }

        /* custom code end */

        $mform->addElement('html', '
          </td>
          </tr>
          </table>');
        if ($this->error == 1) {
            $mform->addElement('html', '</div>');
        }
        // Custom code - Syllametrics - Hide asterisk - override license unassignment display
        // $mform->addElement('html', get_string('licenseusedwarning', 'block_iomad_company_admin'));
        // End custom code
    }

    public function validation($data, $files) {

        $errors = array();

        // if we are removing we don't care about the date.
        if (optional_param('removeall', false, PARAM_BOOL) || optional_param('remove', false, PARAM_BOOL)) {
            $removing = true;
        } else {
            $removing = false;
        }

        // Is the due date valid
        if ($data['due'] > $this->license->expirydate && !$removing) {
            $errors['due'] = get_string('licensedueafterexpirywarning', 'block_iomad_company_admin');
        }
        if ($data['due'] < $this->license->startdate && !$removing) {
            $errors['due'] = get_string('licenseduebeforestartwarning', 'block_iomad_company_admin');
        }

        return $errors;
    }

    public function process() {
        global $DB, $CFG, $USER;


        if ($this->is_validated()) {
            $this->create_user_selectors();
            $courses = array();
            if (empty($this->selectedcourses)) {
                $this->selectedcourses = array_keys($this->courseselect);
            }
            if (!is_array($this->selectedcourses)) {
                $courses[] = $this->selectedcourses;
            } else {
                $courses = $this->selectedcourses;
            }
            $addall = false;
            $add = false;
            if (optional_param('addall', false, PARAM_BOOL) && confirm_sesskey()) {
                $search = optional_param('potentialcourseusers_searchtext', '', PARAM_RAW);
                // Process incoming allocations.
                $potentialusers = $this->potentialusers->find_users($search, true);
                $userstoassign = array_pop($potentialusers);
                $addall = true;
            }
            if (optional_param('add', false, PARAM_BOOL) && confirm_sesskey()) {
                $userstoassign = $this->potentialusers->get_selected_users();
                $add = true;
            }

            /*
             * Custom code 
             * Syllametrics | support@syllametrics.com  
             * change the enrollment process of license from user
             * Last Update: 27th april,2019
             */

            if ($add || $addall) 
            {
                //$companyid = iomad::companyid();
                
                $numberoflicenses = $this->license->allocation;
                $count = $this->license->used;
                $licenserecord = (array) $this->license;
                $company_id = $licenserecord['companyid']; 

                //print_object($licenserecord);
                //$counter = 0;
                if (!empty($userstoassign) && !empty($courses)) 
                {
                    $required = count($userstoassign) * count($courses);
                    if ($count + $required > $numberoflicenses) 
                    { //correct no chnage
                        redirect(new moodle_url("/blocks/iomad_company_admin/company_license_users_form.php", array('licenseid' => $this->licenseid, 'error' => 1)));
                    }

                    $count_users = $DB->get_records_sql("SELECT count(DISTINCT(userid)) as count FROM {companylicense_users} WHERE licenseid=$this->licenseid");
                    $user = array_pop($count_users);

                    /*$enrol_query = "INSERT INTO {user_enrolments} (enrolid, userid, timestart, timeend, modifierid,timecreated,timemodified) VALUES";*/

                   /* $enrol_update_query="";*/
                    //current_time =strtotime(date("Y-m-d 23:59:59", time()));
                    $current_time =time();

                   // $role = $DB->get_record('role', array('shortname' => 'student'), '*', MUST_EXIST);

                    $due = optional_param_array('due', array(), PARAM_INT);
                    if (!empty($due)) 
                    {
                      $duedate = strtotime($due['year'] . '-' . $due['month'] . '-' . $due['day'] . ' ' . $due['hour'] . ':' . $due['minute']);
                    } 
                    else 
                    {
                      $duedate = 0;
                    }

                    foreach ($userstoassign as $adduser) 
                    {
                     
                        // if ($this->check_seat_availability($numberoflicenses, $count, $user->count, count($courses)) && $licenserecord['expirydate'] > $current_time)
                        if ($this->check_seat_availability($numberoflicenses, $count) && $licenserecord['expirydate'] > $current_time)
                        {
                            
                            foreach ($courses as $courseid) 
                            {
                                $recordarray = array('licensecourseid' => $courseid,
                                    'userid' => $adduser->id,
                                    'licenseid' => $this->licenseid,
                                    'issuedate' => $current_time);

                                $recordarray['id'] = $DB->insert_record('companylicense_users', $recordarray);
                                /*$enrol = $DB->get_records_sql("SELECT id FROM {enrol} where courseid = $courseid AND enrol='license'");
                                $enrolid = array_pop($enrol);
                                $user_enrol = $DB->get_record_sql("SELECT * FROM {user_enrolments} WHERE userid= $adduser->id AND enrolid=$enrolid->id");*/
                                $user_enrol="";
                                $user_enrol = $DB->get_record_sql("SELECT ue.* FROM {user_enrolments} ue INNER JOIN {enrol} e ON ue.enrolid=e.id WHERE userid= $adduser->id AND e.enrol='license' AND e.courseid = $courseid");
                                $clu_table_id=$recordarray['id'];

                                if (empty($user_enrol)) 
                                {
                                   $user_enrol_timeend=0;
                                   /* $end_date = $current_time + ($licenserecord['validlength'] * 24 * 60 * 60);
                                    $end_date=strtotime(date("Y-m-d 23:59:59", $end_date));

                                    $licenserecord['expirydate'];
                                    if ($end_date < $licenserecord['expirydate']) {
                                        $enrol_query.="($enrolid->id, $adduser->id, $current_time,$end_date,$USER->id,$current_time,$current_time),";
                                    } else if ($end_date > $licenserecord['expirydate']) {
                                        $end_date=strtotime(date("Y-m-d 23:59:59", $licenserecord['expirydate']));
                                        $enrol_query.="($enrolid->id, $adduser->id, $current_time,$end_date,$USER->id,$current_time,$current_time),";
                                    } else {
                                        $enrol_query.="($enrolid->id, $adduser->id, $current_time,$end_date,$USER->id,$current_time,$current_time),";
                                    }

                                    $counter++;*/
                                    
                                     $this->user_license_assigned($adduser->id, $clu_table_id, $this->licenseid, $courseid, $duedate,$action=0,$company_id,$user_enrol_timeend);
                                }
                                else
                                {
                                    $user_enrol_timeend=$user_enrol->timeend;
                                    /*$count_license=$this->course_license_count($courseid,$companyid,$adduser->id);
                                    //if($user_enrol->timeend <= time())
                                    if(!empty($count_license))
                                    {
                                        if($count_license > 1)
                                        {
                                            $enddate=$this->course_expiry_data($courseid,$companyid,$adduser->id);
                                            $enddate =strtotime(date("Y-m-d 23:59:59", $enddate));
                                            if(!empty($enddate))
                                            {   
                                                $enrol_update_query="UPDATE {user_enrolments} SET timeend=$enddate WHERE id=$user_enrol->id";
                                                $DB->execute($enrol_update_query);
                                            }   
                                        }   
                                        if($count_license == 1 && $user_enrol->timeend <= time())
                                        {
                                            $enddate=$this->course_min_expiry_data($courseid,$companyid,$adduser->id);
                                            $enddate =strtotime(date("Y-m-d 23:59:59", $enddate));
                                            if(!empty($enddate))
                                            {   
                                                $enrol_update_query="UPDATE {user_enrolments} SET timeend=$enddate WHERE id=$user_enrol->id";
                                                $DB->execute($enrol_update_query);
                                            }   
                                        }   
                                    }*/
                                    
                                     $this->user_license_assigned($adduser->id, $clu_table_id, $this->licenseid, $courseid, $duedate,$action=1,$company_id,$user_enrol_timeend);
                                }

                               
                               /* $context = context_course::instance($courseid);
                                $contextid = $context->id;
                               
                                $this->enrolUser($role->id, $contextid, $adduser->id);*/        
                               
                            }
                            /*$usercount = count($userstoassign);
                            $coursecount = count($courses);

                            $DB->execute("UPDATE {companylicense} SET used= ($user->count * $coursecount) + ($usercount * $coursecount) WHERE id=$this->licenseid");*/



                            $eventother = array('licenseid' => $this->licenseid,
                                'duedate' => $licenserecord['expirydate']);

                            $context =  context_course::instance($courseid);

                            //print_object($context);
                            // IMPORTANT: This event typically fires the standard IOMAD observer for license assignment. The observer calls the standard license assignment handler.
                            // This would normally create duplication with enrollment code above, but for deliberate tracking purposes, we pass -100 for the courseid, which
                            // also prevents execution of the standard handler function.
                            $event = \block_iomad_company_admin\event\user_license_assigned::create
                                            (
                                            array
                                                (
                                                'context' => $context,
                                                'objectid' => $recordarray['id'],
                                                'courseid' => -100,
                                                'userid' => $adduser->id,
                                                'other' => $eventother
                                            )
                            );
                            $event->trigger();

                            $allcoursescount = $DB->get_record_sql("select count(DISTINCT(courseid)) as courseid from mdl_companylicense_courses where licenseid = $this->licenseid");
                            $context = context_system::instance();
                            $companyid = iomad::get_my_companyid(context_system::instance()); 

                            $allUsersListOfSharedALicense = array_keys($DB->get_records_sql("SELECT DISTINCT(cu.userid) as userid FROM `mdl_companylicense_users` cu  join `mdl_company_users` cl ON cu.userid = cl.userid  where cu.licenseid =  $this->licenseid and cl.companyid = $companyid"));

                            $usercal = (count($allUsersListOfSharedALicense) * ($allcoursescount->courseid));
                                //echo $usercal;exit;
                            
                            $DB->execute("UPDATE {companylicense} SET used= $usercal WHERE id=$this->licenseid"); 
                        }
                    }
                   /* if ($counter > 0) {
                        $trim_query = rtrim($enrol_query, ',');
                        $DB->execute($trim_query);
                    }*/
                }
            }
            
            //old code
           /*  if ($add || $addall) {
              $numberoflicenses = $this->license->allocation;
              $count = $this->license->used;
              $licenserecord = (array) $this->license;

              if (!empty($userstoassign) && !empty($courses)) {
              $required = count($userstoassign) * count($courses);
              if ($count + $required > $numberoflicenses) {
              redirect(new moodle_url("/blocks/iomad_company_admin/company_license_users_form.php",
              array('licenseid' => $this->licenseid, 'error' => 1)));

              }
              foreach ($userstoassign as $adduser) {

              // Check the userid is valid.
              if (!company::check_valid_user($this->selectedcompany, $adduser->id, $this->departmentid)) {
              print_error('invaliduserdepartment', 'block_iomad_company_management');
              }
              foreach ($courses as $courseid) {
              $allow = true;
              if ($allow) {
              $recordarray = array('licensecourseid' => $courseid,
              'userid' => $adduser->id,
              'licenseid' => $this->licenseid);

              //--- Custom Changes ---

              $usersrecordexist = $DB->get_record('companylicense_users', $recordarray);

              // Check if we are not assigning multiple times.
              if (!$usersrecordexist) {
              $recordarray['issuedate'] = time();
              $recordarray['id'] = $DB->insert_record('companylicense_users', $recordarray);
              $count++;
              $due = optional_param_array('due', array(), PARAM_INT);
              if (!empty($due)) {
              $duedate = strtotime($due['year'] . '-' . $due['month'] . '-' . $due['day'] . ' ' . $due['hour'] . ':' . $due['minute']);
              } else {
              $duedate = 0;
              }

              // Create an event.
              $eventother = array('licenseid' => $this->license->id,
              'duedate' => $duedate);
$DB->set_debug(true);
              $event = \block_iomad_company_admin\event\user_license_assigned::create(array('context' => context_course::instance($courseid),
              'objectid' => $recordarray['id'],
              'courseid' => $courseid,
              'userid' => $adduser->id,
              'other' => $eventother));
              $event->trigger();

              }else{

              //--- Custom Changes ---

              if(!$usersrecordexist->isusing){

              $recordarray['issuedate'] = time();
              $recordarray['id'] = $usersrecordexist->id;


              $due = optional_param_array('due', array(), PARAM_INT);
              if (!empty($due)) {
              $duedate = strtotime($due['year'] . '-' . $due['month'] . '-' . $due['day'] . ' ' . $due['hour'] . ':' . $due['minute']);
              } else {
              $duedate = 0;
              }

              // Create an event.
              $eventother = array('licenseid' => $this->license->id,
              'duedate' => $duedate);
$DB->set_debug(true);
              $event = \block_iomad_company_admin\event\user_license_assigned::create(array('context' => context_course::instance($courseid),
              'objectid' => $recordarray['id'],
              'courseid' => $courseid,
              'userid' => $adduser->id,
              'other' => $eventother));
              $event->trigger();

              }
              }
              }
              }
              }

              $this->potentialusers->invalidate_selected_users();
              $this->currentusers->invalidate_selected_users();
              }
              }*/
             

            $removeall = false;
            $remove = false;
            $licensestounassign = array();
            $licenserecords = array();

            if (optional_param('removeall', false, PARAM_BOOL) && confirm_sesskey()) {
                $search = optional_param('currentlyenrolledusers_searchtext', '', PARAM_RAW);
                // Process incoming allocations.
                $potentialusers = $this->currentusers->find_users($search, true);
                $licenserecords = array_pop($potentialusers);
                $removeall = true;
            }



            /*
             * Custom code 
             * Syllametrics | support@syllametrics.com  
             * change the removal process of license from user
             * Last Update: 16th april,2019
             */
            if (optional_param('remove', false, PARAM_BOOL) && confirm_sesskey()) {
                $search = optional_param('currentlyenrolledusers_searchtext', '', PARAM_RAW);
                $alluser = $this->currentusers->find_users($search, true);
                $allusercount = count($alluser['License seat registrations']);

                $currentlyenrolledusers_ids = optional_param('currentlyenrolledusers_ids', '', PARAM_RAW);
                $decode_currentlyenrolledusers_ids = json_decode($currentlyenrolledusers_ids);

               
                $join_currentlyenrolledusers_ids = implode(',', $decode_currentlyenrolledusers_ids);
                $remove = true;
            }

            if ($remove || $removeall) 
            {
                //$companyid = iomad::companyid();
                //$companyid = iomad::get_my_companyid(context_system::instance()); 
                $licenserecord = (array) $this->license;
                $companyid = $licenserecord['companyid']; 
                

                if (!empty($licenserecord['program']) && !empty($decode_currentlyenrolledusers_ids)) 
                {
                    $records = $DB->get_record_sql("SELECT GROUP_CONCAT(DISTINCT(lc.courseid)) AS courseids,GROUP_CONCAT(DISTINCT(lu.userid)) AS userids FROM {companylicense_courses} lc JOIN {companylicense_users} lu ON lc.licenseid= lu.licenseid WHERE lu.licenseid=$this->licenseid and lu.id IN ($join_currentlyenrolledusers_ids)");
                    $userids = explode(',', $records->userids);
                    $current_courseids = explode(',', $records->courseids);

                    $delete_query="DELETE  FROM {companylicense_users} WHERE licenseid= $this->licenseid AND userid IN (";
                    //$current_time =strtotime(date("Y-m-d 23:59:59", time()));
                    $current_time =time();
                    foreach ($userids as $userid) 
                    {
                        $delete_query.="$userid,";
                    foreach ($current_courseids as $courseid) 
                        {
                            
                            $count_license=$this->course_license_count($courseid,$companyid,$userid);
                            if(!empty($count_license))
                            { 
                                if($count_license == 1 )
                                {
                                    $DB->execute("UPDATE {user_enrolments} ue JOIN {enrol} en ON ue.enrolid=en.id SET ue.timeend=$current_time WHERE en.courseid IN ($courseid) AND ue.userid = $userid");
                                    //$DB->execute("DELETE ue.* FROM {user_enrolments} ue JOIN {enrol} en ON ue.enrolid=en.id WHERE en.courseid IN ($courseid) AND ue.userid = $userid");
                                }

                                if($count_license > 1)
                                {
                                    $enddate=$this->unenrol_course_expiry_data($courseid,$companyid,$userid,$this->licenseid);
                                    $enddate =strtotime(date("Y-m-d 23:59:59", $enddate));
                                    if(!empty($enddate))
                                    {
                                        $unenrol_update_query="UPDATE {user_enrolments} ue JOIN {enrol} en ON ue.enrolid=en.id SET ue.timeend=$enddate WHERE en.courseid IN ($courseid) AND ue.userid = $userid";
                                        $DB->execute($unenrol_update_query);
                                    }   
                                }   
                            }
                            $context = context_course::instance($courseid);
                            $contextid = $context->id;
                               
                            $this->unenrolUser($contextid, $userid);
                        }

                        $usercount = count($decode_currentlyenrolledusers_ids);
                        $coursecount = count($current_courseids);
            

                        $eventother = array('licenseid' => $this->licenseid,'duedate' => 0);

                        $context =  context_course::instance($courseid);

                        // IMPORTANT: This event typically fires the standard IOMAD observer for license assignment. The observer calls the standard license assignment handler.
                        // This would normally create duplication with enrollment code above, but for deliberate tracking purposes, we pass -100 for the courseid, which
                        // also prevents execution of the standard handler function.
                        $event = \block_iomad_company_admin\event\user_license_unassigned::create
                                    (
                                        array
                                        (
                                            'context' => $context,
                                            'objectid' => $this->license->id,
                                            'courseid' => -100,
                                            'userid' => $userid,
                                            'other' => $eventother
                                        )
                                    );
            $event->trigger();

            
                    }                     
                    $trim_unenrol_query=rtrim($delete_query,',');
                    $trim_unenrol_query=$trim_unenrol_query.")";
                    $DB->execute($trim_unenrol_query);

                    $allcoursescount = $DB->get_record_sql("select count(DISTINCT(courseid)) as courseid from mdl_companylicense_courses where licenseid = $this->licenseid");

                    $context = context_system::instance();
                    $companyid = iomad::get_my_companyid(context_system::instance()); 

                    $allUsersListOfSharedALicense = array_keys($DB->get_records_sql("SELECT DISTINCT(cu.userid) as userid FROM `mdl_companylicense_users` cu  join `mdl_company_users` cl ON cu.userid = cl.userid  where cu.licenseid =  $this->licenseid and cl.companyid = $companyid"));

                    $usercal = (count($allUsersListOfSharedALicense) * ($allcoursescount->courseid));
                        //echo $usercal;exit;
                    
                    $DB->execute("UPDATE {companylicense} SET used= $usercal WHERE id=$this->licenseid"); 

                }

            }

      



            /* foreach($licenserecords as $licenserecord) {
              $licensestounassign[$licenserecord->licenseid] = $licenserecord->licenseid;
              } */


            // Process incoming unallocations.
          /*  if ($remove || $removeall) {
                $licenserecord = (array) $this->license;
                if (!empty($licenserecord['program']) && !empty($decode_currentlyenrolledusers_ids)) 
                {

                    $records = $DB->get_record_sql("SELECT GROUP_CONCAT(DISTINCT(lc.courseid)) AS courseids,GROUP_CONCAT(DISTINCT(lu.userid)) AS userids FROM {companylicense_courses} lc JOIN {companylicense_users} lu ON lc.licenseid= lu.licenseid WHERE lu.licenseid=$this->licenseid and lu.id IN ($join_currentlyenrolledusers_ids)");

                    if ($this->license->type == 0 || $this->license->type == 3) {
                        $DB->execute("DELETE  FROM {companylicense_users} WHERE userid IN ($records->userids) and licenseid= $this->licenseid");
                        $userids = explode(',', $records->userids);
                        $current_courseids = explode(',', $records->courseids);
                        foreach ($userids as $userid) 
                        {
                            $all_courseids = $DB->get_record_sql("SELECT GROUP_CONCAT(distinct(clc.courseid)) as courseids  FROM {companylicense_users} clu JOIN {companylicense_courses} clc 
ON clu.licenseid=clc.licenseid WHERE clu.userid = $userid AND clu.licenseid NOT IN ($this->licenseid)");
                            $joinallcourseids = explode(',', $all_courseids->courseids);
                            foreach ($current_courseids as $key) {
                                if (!in_array($key, $joinallcourseids)) {
                                    $DB->execute("DELETE ue.* FROM {user_enrolments} ue JOIN {enrol} en ON ue.enrolid=en.id WHERE en.courseid IN ($key) AND ue.userid IN ($userid)");
                                }

       
                            }
                        }
                        $usercount = count($decode_currentlyenrolledusers_ids);
                        $coursecount = count($current_courseids);

                        $DB->execute("UPDATE {companylicense} SET used= ($allusercount * $coursecount) - ($usercount * $coursecount) WHERE id=$this->licenseid");
                    }
                }
            }    */
                /* if (!empty($licenserecord['program'])) {
                  $userrecords = array();
                  foreach ($licensestounassign as $licenserecid) {

                  // Get the user from the initial license ID passed.
                  $userlic = $DB->get_record('companylicense_users',array('id' => $licenserecid), '*', MUST_EXIST);
                  $userrecords = $userrecords + array_keys($DB->get_records_sql("SELECT id FROM {companylicense_users}
                  WHERE licenseid = :licenseid
                  AND userid IN (
                  SELECT userid FROM {companylicense_users}
                  WHERE id IN
                  (" . implode(',', $licensestounassign) . "))",
                  array('licenseid' => $this->license->id)));
                  }
                  $licensestounassign = $userrecords;
                  if ($licenserecord['type'] == 1 || $licenserecord['type'] == 3) {
                  $canremove = true;
                  } else {
                  $canremove = true;
                  foreach ($licensestounassign as $unassignid) {
                  if ($DB->get_record('companylicense_users' ,array('id' => $unassignid, 'isusing' => 1))) {

                  //Custom changes - Always set canrtemove yes and  $this->license->type == 1 to 0 - Standard
                  $canremove = true;
                  }
                  }
                  }
                  if (!$canremove) {
                  $licensestounassign = array();
                  }
                  } */

                /* if (!empty($licensestounassign)) {
                  foreach ($licensestounassign as $unassignid) {
                  $licensedata = $DB->get_record('companylicense_users' ,array('id' => $unassignid), '*', MUST_EXIST);

                  // Check the userid is valid.
                  if (!company::check_valid_user($this->selectedcompany, $licensedata->userid, $this->departmentid)) {
                  print_error('invaliduserdepartment', 'block_iomad_company_management');
                  }

                  if (!$licensedata->isusing || $this->license->type == 0 || $this->license->type == 3) {
                  $DB->delete_records('companylicense_users', array('id' => $unassignid));

                  // Create an event.
                  $eventother = array('licenseid' => $this->license->id,
                  'duedate' => 0);
                  $event = \block_iomad_company_admin\event\user_license_unassigned::create(array('context' => context_course::instance($licensedata->licensecourseid),
                  'objectid' => $this->license->id,
                  'courseid' => $licensedata->licensecourseid,
                  'userid' => $licensedata->userid,
                  'other' => $eventother));
                  $event->trigger();
                  }
                  }

                  $this->potentialusers->invalidate_selected_users();
                  $this->currentusers->invalidate_selected_users();
                  } */
            
        }
    }


    /**  function check_seat_availability($allocation, $used, $users, $courses) {
        $total_remaining_seats = $allocation - $used;
        $seats_to_be_allocated = $users * $courses;
        if ($total_remaining_seats >= $seats_to_be_allocated) {
            return true;
        }
        return false;
    }
    **/
    function check_seat_availability($allocation, $used) {
        if ($allocation > $used) {
            return true;
        }
        return false;
    }

    function course_expiry_data($courseid,$companyid,$userid)
    {
        global $DB;
        $expiry_date=$DB->get_record_sql("SELECT  MAX(IF(((UNIX_TIMESTAMP() + (cl.validlength *24*60*60)) < cl.expirydate), (UNIX_TIMESTAMP() + (cl.validlength *24*60*60)),cl.expirydate) )AS expiry   FROM {companylicense} cl INNER JOIN {company} c  ON cl.companyid=c.id INNER JOIN {companylicense_users} clu ON clu.licenseid=cl.id WHERE clu.userid=$userid AND c.id=$companyid AND clu.licensecourseid=$courseid AND cl.expirydate > UNIX_TIMESTAMP()");
        if (!empty($expiry_date)) 
        {
            return $expiry_date->expiry;
        }
        return ;    
    }

    function course_min_expiry_data($courseid,$companyid,$userid)
    {
        global $DB;
        $expiry_date=$DB->get_record_sql("SELECT  MIN(IF(((UNIX_TIMESTAMP() + (cl.validlength *24*60*60)) < cl.expirydate), (UNIX_TIMESTAMP() + (cl.validlength *24*60*60)),cl.expirydate) )AS expiry   FROM {companylicense} cl INNER JOIN {company} c  ON cl.companyid=c.id INNER JOIN {companylicense_users} clu ON clu.licenseid=cl.id WHERE clu.userid=$userid AND c.id=$companyid AND clu.licensecourseid=$courseid AND cl.expirydate > UNIX_TIMESTAMP()");
        if (!empty($expiry_date)) 
        {
            return $expiry_date->expiry;
        }
        return ;    
    }

    function course_license_count($courseid,$companyid,$userid)
    {
        global $DB;
        $license_count=$DB->get_record_sql("SELECT count(cl.id) AS licensecount  FROM {companylicense} cl INNER JOIN {company} c  ON cl.companyid=c.id INNER JOIN {companylicense_users} clu ON clu.licenseid=cl.id WHERE clu.userid=$userid AND c.id=$companyid AND clu.licensecourseid=$courseid AND cl.expirydate > UNIX_TIMESTAMP()");
        if (!empty($license_count)) 
        {
            return $license_count->licensecount;
        }
        return ;    
    }

    function unenrol_course_expiry_data($courseid,$companyid,$userid,$licenseid)
    {
        global $DB;
        $expiry_date=$DB->get_record_sql("SELECT  MAX(IF(((UNIX_TIMESTAMP() + (cl.validlength *24*60*60)) < cl.expirydate), (UNIX_TIMESTAMP() + (cl.validlength *24*60*60)),cl.expirydate) )AS expiry   FROM {companylicense} cl INNER JOIN {company} c  ON cl.companyid=c.id INNER JOIN {companylicense_users} clu ON clu.licenseid=cl.id WHERE clu.userid=$userid AND c.id=$companyid AND clu.licensecourseid=$courseid AND cl.expirydate > UNIX_TIMESTAMP() AND cl.id NOT IN ($licenseid)");
        if (!empty($expiry_date)) 
        {
            return $expiry_date->expiry;
        }
        return ;
    }

    function enrolUser($roleid, $contextid, $userid, $component = '', $itemid = 0, $timemodified = '') 
    {
        global $DB,$USER;
        $ra = new stdClass();
        $ra->roleid = $roleid;
        $ra->contextid = $contextid;
        $ra->userid = $userid;
        $ra->itemid = $itemid;
        $ra->timemodified = $timemodified;
        $ra->modifierid = empty($USER->id) ? 0 : $USER->id;
        $ra->sortorder = 0;

        $ra->id = $DB->insert_record('role_assignments', $ra);
    }
    function unenrolUser($contextid, $userid) 
    {
        global $DB;

        $DB->delete_records('role_assignments', array('contextid' => $contextid ,'userid' =>  $userid));
        
    }
  
    function user_license_assigned($userid, $clu_table_id, $licenseid, $courseid, $duedate,$action,$company_id,$user_enrol_timeend) 
    {
//echo "I am in !!!!!!!!!!!!!!!!!!!!!!";


        global $DB, $CFG;

        $userlicid=$clu_table_id;
        $timestart='';
        $timeend='';
        /*$userid = $event->userid;
        $userlicid = $event->objectid;
        $licenseid = $event->other['licenseid'];
        $courseid = $event->courseid;
        $duedate = $event->other['duedate'];*/
      /*  if (!empty($event->other['noemail'])) {
            $noemail = true;
        } else {
            $noemail = false;
        }*/

        if (!$licenserecord = $DB->get_record('companylicense', array('id'=>$licenseid))) {
           
            return;
        }

        if (!$course = $DB->get_record('course', array('id' => $courseid))) {
              
            return;
        }

        if (!$user = $DB->get_record('user', array('id' => $userid))) {
            
            return;
        }

        $license = new stdclass();
        $license->length = $licenserecord->validlength;
        $license->valid = date($CFG->iomad_date_format, $licenserecord->expirydate);

        /*if (!$noemail) {
        // Send out the email.
            EmailTemplate::send('license_allocated', array('course' => $course,
             'user' => $user,
             'due' => $duedate,
             'license' => $license));
        }*/

        // Update the license usage.
        company::update_license_usage($licenseid);

        // Is this an immediate license?
        if (!empty($licenserecord->instant)) {
            if ($instance = $DB->get_record('enrol', array('courseid' => $course->id, 'enrol' => 'license'))) {
                // Enrol the user on the course.
                $enrol = enrol_get_plugin('license');
                // Enrol the user in the course.
                // Is the license available yet?
              /*  if (!empty($licenserecord->startdate) && $licenserecord->startdate > time()) 
                {
                    // If not set up the enrolment from when it is.
                    $timestart = $licenserecord->startdate;
                } else {
                    // Otherwise start it now.
                    $timestart = time();
                }*/
                $timestart = time();
                if($action == 0) //0- insert 1-update
                {
//                    $timestart = time();
                    $timeend = $timestart + ($licenserecord->validlength * 24 * 60 * 60);
                    $timeend=strtotime(date("Y-m-d 23:59:59", $timeend)); 

                    if($licenserecord->expirydate < $timeend)
                    {
                        $timeend=$licenserecord->expirydate;
                    }
                }

                else
                {
                    $count_license=$this->course_license_count($courseid,$company_id,$userid);
                    //if($user_enrol->timeend <= time())
                    if(!empty($count_license))
                    {
                        if($count_license > 1)
                        {
                            $timeend=$this->course_expiry_data($courseid,$company_id,$userid);
                            $timeend =strtotime(date("Y-m-d 23:59:59", $timeend));
                        }   
                        if($count_license == 1 && $user_enrol_timeend <= time())
                        {
                            $timeend=$this->course_min_expiry_data($courseid,$company_id,$userid);
                            $timeend =strtotime(date("Y-m-d 23:59:59", $timeend));
                        }   
                    }    

                }
                             


/*
                if ($licenserecord->type == 0 || $licenserecord->type == 2) {
                    // Set the timeend to be time start + the valid length for the license in days.
                    $timeend = $timestart + ($licenserecord->validlength * 24 * 60 * 60 );
                } else {
                    // Set the timeend to be when the license runs out.
                    $timeend = $licenserecord->expirydate;
                }*/

                if ($licenserecord->type < 2) {
                   // echo "if";
                    $enrol->enrol_user($instance, $user->id, $instance->roleid, $timestart, $timeend);
                } else {
                   // echo "else";
                    // Educator role.
                    if ($DB->get_record('iomad_courses', array('courseid' => $course->id, 'shared' => 0))) {
                        // Not shared.
                        $role = $DB->get_record('role', array('shortname' => 'companycourseeditor'));
                       
                       // print_object($role);
                    } else {
                        // Shared.
                        $role = $DB->get_record('role', array('shortname' => 'companycoursenoneditor'));
                    }
                    $enrol->enrol_user($instance, $user->id, $role->id, $timestart, $timeend);
                    
                }

                // Get the userlicense record.
                $userlicense = $DB->get_record('companylicense_users', array('id' => $userlicid));

                // Add the user to the appropriate course group.
                if (!empty($course->groupmode)) {
                    $userlicense = $DB->get_record('companylicense_users', array('id' => $userlicid));
                   // company::add_user_to_shared_course($instance->courseid, $user->id, $license->companyid, $userlicense->groupid);
                     company::add_user_to_shared_course($instance->courseid, $user->id, $company_id, $userlicense->groupid);
                }

                // Update the userlicense record to mark it as in use.
                $DB->set_field('companylicense_users', 'isusing', 1, array('id' => $userlicense->id));

                // Send welcome.
               /* if ($instance->customint4) {
                    $enrol->email_welcome_message($instance, $user);
                }*/
            }
        }
       // return true;
    }
}
$returnurl = optional_param('returnurl', '', PARAM_LOCALURL);
$companyid = optional_param('companyid', 0, PARAM_INTEGER);
$courseid = optional_param('courseid', 0, PARAM_INTEGER);
$departmentid = optional_param('deptid', 0, PARAM_INTEGER);
$licenseid = optional_param('licenseid', 0, PARAM_INTEGER);
$error = optional_param('error', 0, PARAM_INTEGER);
$selectedcourses = optional_param('courses', array(), PARAM_INT);
$chosenid = optional_param('chosenid', 0, PARAM_INT);

// if this is a single course then optional_param_array doesn't work.
if (empty($selectedcourses)) {
    $selectedcourses = optional_param('courses', array(), PARAM_INT);
}

$context = context_system::instance();
require_login();
//iomad::require_capability('block/iomad_company_admin:allocate_licenses', $context);
// Correct the navbar.
// Set the name for the page.
$linktext = get_string('company_license_users_title', 'block_iomad_company_admin');
// Set the url.
$linkurl = new moodle_url('/blocks/iomad_company_admin/company_license_users_form.php');

// Print the page header.
$PAGE->set_context($context);
$PAGE->set_url($linkurl);
$PAGE->set_pagelayout('admin');
$PAGE->set_title($linktext);

// Set the page heading.
$PAGE->set_heading(get_string('name', 'local_iomad_dashboard') . " - $linktext");

// get output renderer
$output = $PAGE->get_renderer('block_iomad_company_admin');

// Javascript for fancy select.
// Parameter is name of proper select form element. 
$PAGE->requires->js_call_amd('block_iomad_company_admin/department_select', 'init', array('deptid', 'mform1', $departmentid));

// Build the nav bar.
company_admin_fix_breadcrumb($PAGE, $linktext, $linkurl);

// Set the companyid
$companyid = iomad::get_my_companyid($context);
$company = new company($companyid);

//  Check the license is valid for this company.
if (!empty($licenseid) && !company::check_valid_company_license($companyid, $licenseid)) {
    print_error('invalidcompanylicense', 'block_iomad_company_admin');
}

$urlparams = array('companyid' => $companyid);
if ($returnurl) {
    $urlparams['returnurl'] = $returnurl;
}
if ($courseid) {
    $urlparams['courseid'] = $courseid;
}

// Get the top level department.
$parentlevel = company::get_company_parentnode($companyid);

$availablewarning = '';
$licenselist = array();
if (iomad::has_capability('block/iomad_company_admin:unallocate_licenses', context_system::instance())) {
    $userhierarchylevel = $parentlevel->id;
    // Get all the licenses.
    $licenses = $DB->get_records('companylicense', array('companyid' => $companyid), 'expirydate DESC', 'id,name,startdate,expirydate');
    foreach ($licenses as $license) {
        if ($license->expirydate < time()) {
            $licenselist[$license->id] = $license->name . " (" . get_string('licenseexpired', 'block_iomad_company_admin', date($CFG->iomad_date_format, $license->expirydate)) . ")";
        } else if ($license->startdate > time()) {
            $licenselist[$license->id] = $license->name . " (" . get_string('licensevalidfrom', 'block_iomad_company_admin', date($CFG->iomad_date_format, $license->startdate)) . ")";
            if ($licenseid == $license->id) {
                $availablewarning = get_string('licensevalidfromwarning', 'block_iomad_company_admin', date($CFG->iomad_date_format, $license->startdate));
            }
        } else {
            $licenselist[$license->id] = $license->name;
        }
    }
} else {
    $userlevel = $company->get_userlevel($USER);
    $userhierarchylevel = $userlevel->id;
    if (iomad::has_capability('block/iomad_company_admin:edit_licenses', context_system::instance())) {
        $alllicenses = true;
    } else {
        $alllicenses = false;
    }
    $licenses = $DB->get_records('companylicense', array('companyid' => $companyid), 'expirydate DESC', 'id,name,startdate,expirydate');

    if (!empty($licenses)) {
        foreach ($licenses as $license) {
            if ($alllicenses || $license->expirydate > time()) {
                if ($license->startdate > time()) {
                    $licenselist[$license->id] = $license->name . " (" . get_string('licensevalidfrom', 'block_iomad_company_admin', date($CFG->iomad_date_format, $license->startdate)) . ")";
                    if ($licenseid == $license->id) {
                        $availablewarning = get_string('licensevalidfromwarning', 'block_iomad_company_admin', date($CFG->iomad_date_format, $license->startdate));
                    }
                } else {
                    $licenselist[$license->id] = $license->name;
                }
            }
        }
    }
}

// If we haven't been passed a department level choose the users.
if (empty($departmentid)) {
    $departmentid = $userhierarchylevel;
}

$usersform = new company_license_users_form($PAGE->url, $context, $companyid, $licenseid, $departmentid, $selectedcourses, $error, $output);

echo $output->header();

// Check the department is valid.
if (!empty($departmentid) && !company::check_valid_department($companyid, $departmentid)) {
    print_error('invaliddepartment', 'block_iomad_company_admin');
}

//  Check the license is valid for this company.
if (!empty($licenseid) && !company::check_valid_company_license($companyid, $licenseid)) {
    print_error('invalidcompanylicense', 'block_iomad_company_admin');
}

// Display the license selector.
/*
 * Custom Code
 * 2018 Syllametrics | support@syllametrics.com
 * Last Update : 21st Nov, 2018
 * Set ASC order license list
 */
natcasesort($licenselist);

$select = new single_select($linkurl, 'licenseid', $licenselist, $licenseid);
$select->label = get_string('licenseselect', 'block_iomad_company_admin');
$select->formid = 'chooselicense';
echo html_writer::start_tag('div', array('class' => 'row'));
echo html_writer::tag('span', $output->render($select), array('id' => 'iomad_license_selector', 'class' => 'col-md-3'));
/*
 * Custom code 
 * Syllametrics | support@syllametrics.com  
 * This code will add a button which takes user to licnese edit page.
 * Last Update: 18th oct,2018
 */
if ($licenseid != 0) {
    if (iomad::has_capability('block/iomad_company_admin:edit_licenses', context_system::instance())) {
        echo html_writer::start_tag('span', array('class' => 'col-md-3'));
        echo html_writer::link(new moodle_url($CFG->wwwroot . '/blocks/iomad_company_admin/company_license_edit_form.php', array('licenseid' => $licenseid)), 'View License Settings', array('class' => 'btn btn-primary', 'style' => 'margin-top:10%;'));
        echo html_writer::end_tag('span');
    }
}

/* custom code end */

echo html_writer::end_tag('div');

$fwselectoutput = html_writer::tag('div', $output->render($select), array('id' => 'iomad_license_selector'));

// Do we have any licenses?
if (empty($licenselist)) {
    echo get_string('nolicenses', 'block_iomad_company_admin');
    echo $output->footer();
    die;
}

if ($usersform->is_cancelled() || optional_param('cancel', false, PARAM_BOOL)) {
    if ($returnurl) {
        redirect($returnurl);
    } else {
        redirect(new moodle_url('/local/iomad_dashboard/index.php'));
    }
} else {
    if ($licenseid > 0) {
        //  Work out the courses that the license applies to, if any.
        $courses = company::get_courses_by_license($licenseid);
        $count="";
        $outputstring = "";
        if (!empty($courses)) {
            $outputstring = "<p>" . get_string('licenseassignedto', 'block_iomad_company_admin');
            $count = 1;
            foreach ($courses as $course) {
                if ($count > 1) {
                    $outputstring .= ", " . $course->fullname;
                } else {
                    $outputstring .= $course->fullname;
                }
                $count++;
            }
            $count ++;
        }
        // Custom code - Syllametrics - Hide courses in license
        // echo $outputstring."</p>";
        echo "</p>";
        // End custom code
        $usersform->process();

        if (!empty($availablewarning)) {
            echo html_writer::start_tag('div', array('class' => "alert alert-success"));
            echo $availablewarning;
            echo "</div>";
        }

        // Reload the form.
        $usersform = new company_license_users_form($PAGE->url, $context, $companyid, $licenseid, $departmentid, $selectedcourses, $error, $output);
        $usersform->get_data();
        echo $usersform->display();
    }
}

echo $output->footer();
