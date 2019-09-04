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
require_once($CFG->dirroot . '/lib/enrollib.php');

class company_license_form extends company_moodleform
{
    
    protected $context = null;
    protected $selectedcompany = 0;
    protected $potentialcourses = null;
    protected $subhierarchieslist = null;
    protected $currentcourses = null;
    protected $departmentid = 0;
    protected $companydepartment = 0;
    protected $parentid = 0;
    protected $free = 0;
    
    public function __construct($actionurl, $context, $companyid, $departmentid = 0, $licenseid, $parentid = 0, $licenseinfo, $courses = array())
    {
        global $DB, $USER;
        $this->selectedcompany = $companyid;
        $this->context         = $context;
        $this->departmentid    = $departmentid;
        $this->licenseid       = $licenseid;
        $this->parentid        = $parentid;
        $this->selectedcourses = $courses;
        $this->licenseinfo     = $licenseinfo;
        if (!empty($this->parentid)) {
            $this->parentlicense = $DB->get_record('companylicense', array(
                'id' => $parentid
            ));
        } else {
            $this->parentlicense = null;
        }
        if (!$this->license = $DB->get_record('companylicense', array(
            'id' => $licenseid
        ))) {
            $this->license = new stdclass();
        }
        
        $company                 = new company($this->selectedcompany);
        $parentlevel             = company::get_company_parentnode($company->id);
        $this->companydepartment = $parentlevel->id;
        if (empty($parentid)) {
            $this->courses = $company->get_menu_courses(true, false, false, false);
        } else {
            $this->courses = $DB->get_records_sql_menu("SELECT c.id, c.fullname
                    FROM {course} c
                    JOIN {companylicense_courses} lic
                    on (c.id = lic.courseid)
                    WHERE lic.licenseid = :licenseid", array(
                'licenseid' => $parentid
            ));
        }
        
        if (iomad::has_capability('block/iomad_company_admin:edit_licenses', context_system::instance())) {
            $userhierarchylevel = $parentlevel->id;
        } else {
            $userlevel          = $company->get_userlevel($USER);
            $userhierarchylevel = $userlevel->id;
        }
        
        $this->subhierarchieslist = company::get_all_subdepartments($userhierarchylevel);
        if ($this->departmentid == 0) {
            $departmentid = $userhierarchylevel;
        } else {
            $departmentid = $this->departmentid;
        }
        
        $options = array(
            'context' => $this->context,
            'multiselect' => true,
            'companyid' => $this->selectedcompany,
            'departmentid' => $departmentid,
            'subdepartments' => $this->subhierarchieslist,
            'parentdepartmentid' => $parentlevel,
            'selected' => $this->selectedcourses,
            'parentid' => $this->parentid,
            'license' => true
        );
        
        parent::__construct($actionurl);
    }
    
    public function definition()
    {
        $this->_form->addElement('hidden', 'companyid', $this->selectedcompany);
        $this->_form->addElement('hidden', 'departmentid', $this->departmentid);
        $this->_form->addElement('hidden', 'licenseid', $this->licenseid);
        $this->_form->addElement('hidden', 'parentid', $this->parentid);
        $this->_form->setType('companyid', PARAM_INT);
        $this->_form->setType('departmentid', PARAM_INT);
        $this->_form->setType('licenseid', PARAM_INT);
        $this->_form->setType('parentid', PARAM_INT);
    }
    
    public function definition_after_data()
    {
        global $DB, $CFG;
        
        $mform =& $this->_form;
        
        // Adding the elements in the definition_after_data function rather than in the definition function
        // so that when the currentcourses or potentialcourses get changed in the process function, the
        // changes get displayed, rather than the lists as they are before processing.
        
        $company = new company($this->selectedcompany);
        if (empty($this->parentid)) {
            if (!empty($this->licenseid)) {
                $mform->addElement('header', 'header', get_string('edit_licenses', 'block_iomad_company_admin'));
            } else {
                $mform->addElement('header', 'header', get_string('createlicense', 'block_iomad_company_admin'));
            }
            $mform->addElement('hidden', 'designatedcompany', 0);
            $mform->setType('designatedcompany', PARAM_INT);
        } else {
            $licenseinfo = $DB->get_record('companylicense', array(
                'id' => $this->parentid
            ));
            
            // If this is a program, sort out the displayed used and allocated.
            if (!empty($licenseinfo->program)) {
                $used = $licenseinfo->used / count($this->courses);
                $free = ($licenseinfo->allocation - $licenseinfo->used) / count($this->courses);
            } else {
                $used = $licenseinfo->used;
                $free = $licenseinfo->allocation - $licenseinfo->used;
            }

            $company     = new company($licenseinfo->companyid);
            $companylist = $company->get_child_companies_select(false);
            $mform->addElement('header', 'header', get_string('split_licenses', 'block_iomad_company_admin'));
            $this->free = $licenseinfo->allocation - $licenseinfo->used;
            $mform->addElement('static', 'parentlicensename', get_string('parentlicensename', 'block_iomad_company_admin') . ': ' . $licenseinfo->name);
            $mform->addElement('static', 'parentlicenseused', get_string('parentlicenseused', 'block_iomad_company_admin') . ': ' . $used);
            $mform->addElement('static', 'parentlicenseavailable', get_string('parentlicenseavailable', 'block_iomad_company_admin') . ': ' . $free);
            
            // Add in the selector for the company the license will be for.
            $designatedcompanyselect = $mform->addElement('select', 'designatedcompany', get_string('designatedcompany', 'block_iomad_company_admin'), $companylist);
            if (!empty($this->license->companyid)) {
                $designatedcompanyselect->setSelected($this->license->companyid);
            }
        }
        
        
        
        
        /* Custom changes --------- Additions Fields Section Start---------------- */
        
        
        
        
        /* ---   License format  - New or shared license  --- */
        
        if (!$licenseidget = optional_param('licenseid', '', PARAM_INT)) {
            
            $mform->addElement('select', 'licenseformat', get_string('licenseformat', 'block_iomad_company_admin'), array(
                'New License',
                'Shared License Copy'
            ));
            $mform->addHelpButton('licenseformat', 'licenseformat', 'block_iomad_company_admin');
        } else {
            $mform->addElement('hidden', 'licenseformat', $licenseinfo->licenseformat);
        }
        
        
        /* ---- Open Sharing , Close Sharing , No sharing   ----- */
        
        $sharingtypes = array(
            'Open Sharing',
            'Close Sharing',
            'No'
        );
        $mform->addElement('select', 'sharingtypes', get_string('ishared', 'block_iomad_company_admin'), $sharingtypes);
        
        
        
        /* ----Close sharing -   Select companies ----- */
        
        $companyrecord = $DB->get_records_sql("SELECT * FROM {company} where id != $this->selectedcompany");
        //$companyrecord = $DB->get_records_sql("SELECT * FROM {company}");
        
        $allcompany = array();
        
        foreach ($companyrecord as $key => $value) {
            $allcompany[$value->id] = $value->name;
        }
        
        $mform->addElement('autocomplete', 'permittedcompanies', get_string('permittedcompanies', 'block_iomad_company_admin'), $allcompany, array(
            'multiple' => true
        ));
        $mform->addRule('permittedcompanies', 'Select at least one company', '', null, 'client');
        
        
        
        
        /* --- Shared license copy selected --- select parent license   --- */
        
        $parentlicense = $DB->get_records_sql("SELECT cl.*,clp.licenseid from {companylicense} cl LEFT JOIN {companylicense_permittedcompanies} clp ON cl.id = clp.licenseid where cl.sharingtypes = 0 OR (cl.sharingtypes = 1 AND clp.companyid = $this->selectedcompany) OR (cl.sharingtypes = 1 AND cl.companyid = $this->selectedcompany)");
        
        
        $alllicense = array();
        //$alllicense[''] = "Select";
        foreach ($parentlicense as $key => $value) {
            $alllicense[$value->id] = $value->name;
        }
        
        natcasesort($alllicense);

        $mform->addElement('html', '<div class="row"><div class="col-md-3"></div><div class="col-md-9"><span class="btn btn-secondary selall marginclass">Select all License</span><span class="btn btn-secondary marginclass deselall">Clear all License</span></div></div>');
        $multi = $mform->addElement('select', 'parentlicenseid', get_string('parentlicense', 'block_iomad_company_admin'),  $alllicense, array(
            'style' => 'width:100%;',
            'disabled'=>'disabled',
            'class' => 'parentselect'
        ));

        $multi->setMultiple(true);
        //$mform->addRule('parentlicenseid', 'Please select the license', 'required', null, 'client');
        
        
        
        
        /* -----validations----- */
        
        
        
        $mform->hideIf('sharingtypes', 'licenseformat', 'eq', 1);
        $mform->hideIf('removecourse', 'licenseformat', 'eq', 1);
        $mform->hideIf('tag', 'licenseformat', 'eq', 1);
        $mform->hideIf('removetags', 'licenseformat', 'eq', 1);
        $mform->hideIf('permittedcompanies', 'licenseformat', 'eq', 1);
        $mform->hideIf('parentlicenseid', 'licenseformat', 'eq', 0);
        
        
        $mform->hideIf('permittedcompanies', 'sharingtypes', 'eq', 0);
        $mform->hideIf('permittedcompanies', 'sharingtypes', 'eq', 2);
        
        $mform->hideIf('licensecourses', 'licenseformat', 'eq', 1);
        
        
        if (!$licenseidget = optional_param('licenseid', '', PARAM_INT)) { // Insert license case   
            $mform->setDefault('program', 1);
            
            $mform->hideIf('type', 'licenseformat', 'eq', 1);
            $mform->hideIf('instant', 'licenseformat', 'eq', 1);
            $mform->hideIf('program', 'licenseformat', 'eq', 1);
        } else {
            
            $mform->disabledif('type', 'licenseformat', 'eq', 1);
            $mform->disabledif('instant', 'licenseformat', 'eq', 1);
            $mform->disabledif('program', 'licenseformat', 'eq', 1);
        }
        
        
        
        
        
        
        /* -------------------- End Additions Fields Section  -------------------- */
        
        $licenseid = optional_param('licenseid', '0', PARAM_INT);
        
        
        
        
        $mform->addElement('text', 'name', get_string('licensename', 'block_iomad_company_admin'), 'maxlength="254" size="50"');
        $mform->addHelpButton('name', 'licensename', 'block_iomad_company_admin');
       // $mform->addRule('name', get_string('missinglicensename', 'block_iomad_company_admin'), 'required', null, 'client');
        $mform->setType('name', PARAM_ALPHANUMEXT);
        /*if ($licenseid ==0) {
            $mform->setDefault('name', $company->get_name());
        }*/
        
        
        $mform->addElement('text', 'reference', get_string('licensereference', 'block_iomad_company_admin'), 'maxlength="100" size="50"');
        $mform->addHelpButton('reference', 'licensereference', 'block_iomad_company_admin');
        $mform->setType('reference', PARAM_ALPHANUMEXT);
        
        if (empty($this->parentid)) {
            if ($CFG->iomad_autoenrol_managers) {
                $licensetypes = array(
                    get_string('standard', 'block_iomad_company_admin'),
                    get_string('reusable', 'block_iomad_company_admin')
                );
            } else {
                $licensetypes = array(
                    get_string('standard', 'block_iomad_company_admin'),
                    get_string('reusable', 'block_iomad_company_admin'),
                    get_string('educator', 'block_iomad_company_admin'),
                    get_string('educatorreusable', 'block_iomad_company_admin')
                );
            }
            $mform->addElement('select', 'type', get_string('licensetype', 'block_iomad_company_admin'), $licensetypes);
            $mform->addHelpButton('type', 'licensetype', 'block_iomad_company_admin');
            $mform->addElement('select', 'program', get_string('licenseprogram', 'block_iomad_company_admin'), array(
                '1' => 'Yes'
            ));
            $mform->addHelpButton('program', 'licenseprogram', 'block_iomad_company_admin');
            $mform->addElement('selectyesno', 'instant', get_string('licenseinstant', 'block_iomad_company_admin'));
            $mform->addHelpButton('instant', 'licenseinstant', 'block_iomad_company_admin');
            
            if ($licenseid == 0) 
            {
                $mform->setDefault('instant', 1);
            }

            $mform->addElement('date_selector', 'startdate', get_string('licensestartdate', 'block_iomad_company_admin'));
            
            $mform->addHelpButton('startdate', 'licensestartdate', 'block_iomad_company_admin');
            $mform->addRule('startdate', get_string('missingstartdate', 'block_iomad_company_admin'), 'required', null, 'client');
            
            $mform->addElement('date_selector', 'expirydate', get_string('licenseexpires', 'block_iomad_company_admin'));
            $mform->addHelpButton('expirydate', 'licenseexpires', 'block_iomad_company_admin');
            $mform->addRule('expirydate', get_string('missinglicenseexpires', 'block_iomad_company_admin'), 'required', null, 'client');
            
            $mform->addElement('text', 'validlength', get_string('licenseduration', 'block_iomad_company_admin'), 'maxlength="254" size="50"');
            $mform->addHelpButton('validlength', 'licenseduration', 'block_iomad_company_admin');
            $mform->addRule('validlength', get_string('missinglicensevaliddays', 'block_iomad_company_admin'), 'required', null, 'client');
            $mform->setType('validlength', PARAM_INTEGER);
        } else {
            $mform->addElement('hidden', 'type', $this->parentlicense->type);
            $mform->setType('type', PARAM_INT);
            $mform->addElement('hidden', 'startdate', $licenseinfo->startdate);
            $mform->setType('expirydate', PARAM_INT);
            $mform->addElement('hidden', 'expirydate', $licenseinfo->expirydate);
            $mform->setType('expirydate', PARAM_INT);
            $mform->addElement('hidden', 'validlength', $licenseinfo->validlength);
            $mform->setType('validlength', PARAM_INTEGER);
            $mform->addElement('hidden', 'program', $this->parentlicense->program);
            $mform->setType('program', PARAM_INTEGER);
            $mform->addElement('hidden', 'parentid', $this->parentlicense->id);
            $mform->setType('parentid', PARAM_INTEGER);
        }
        

        /* 
            * Custom code 
            * Syllametrics | support@syllametrics.com  
            * This code will add a checkbox which is used to add the functionality to give unlimited seats to a license
            * Last Update: 9th oct,2018
        */
        
        $mform->addElement('advcheckbox', 'unlimitedseats','Unlimited license', '', array('group' => 1), array(0, 1));
        $mform->addHelpButton('unlimitedseats', 'unlimitedseats', 'block_iomad_company_admin');
        $mform->hideIf('allocation', 'unlimitedseats', 'checked');
        //$mform->disabledIf('allocation', 'unlimitedseats', 'checked');
        
        /* custom code end */
        
        
        /* 
            * Custom code 
            * Syllametrics | support@syllametrics.com  
            * This code will add a text-box which is used to allocate the seats to a license
            * Last Update: 9th oct,2018
        */

        $mform->addElement('text', 'allocation', get_string('licenseallocation', 'block_iomad_company_admin'), 'maxlength="254" size="50"');
        $mform->addHelpButton('allocation', 'licenseallocation', 'block_iomad_company_admin');
        //$mform->addRule('allocation', get_string('missinglicenseallocation', 'block_iomad_company_admin'), 'required', null, 'client');
        //$mform->setType('allocation', PARAM_MULTILANG);
        
        if ($licenseid == 0) 
        {
            $mform->setDefault('allocation', 1);
        }
        else
        {
            $rec = $DB->get_records('companylicense_courses',array("licenseid"=>$licenseid));
            $totalAllocationToDisplay = $parentlicense[$licenseid]->allocation / count($rec);
            //$mform->setDefault('allocation', $parentlicense[$licenseid]->allocation);
            $mform->setDefault('allocation', $totalAllocationToDisplayn);
        }
        /* custom code end */


        $mform->addElement('hidden', 'courseselector', 0);
        $mform->setType('expirydate', PARAM_INT);
        
        if (!empty($this->parentlicense->program)) {
            $mform->addElement('html', "<div style='display:none'>");
        }


        /* 
            * Custom code 
            * Syllametrics | support@syllametrics.com  
            * This code used to add checkbox which is used to add the functionality to remove all courses (except tag courses) from a license
            * Last Update: 9th oct,2018
        */


        $mform->addElement('advcheckbox', 'removecourse','Remove all license courses', '', array('group' => 1), array(0, 1));
        $mform->addHelpButton('removecourse', 'removecourse', 'block_iomad_company_admin');
        $mform->hideIf('licensecourses', 'removecourse', 'checked');

        $mform->addElement('html', '<div class="row"><div class="col-md-3"></div><div class="col-md-9"><span class="btn btn-secondary selallcour marginclass">Select all Course</span><span class="btn btn-secondary marginclass deselallcour">Clear all Course</span></div></div>');


        $autooptions = array('multiple' => true );
        $multi_license = $mform->addElement('select', 'licensecourses', get_string('selectlicensecourse', 'block_iomad_company_admin'), $this->courses, array('style' => 'width:100%;','size'=>'10','class'=>'courseselect'));
        $multi_license->setMultiple(true);

        $selectedCourseList = getAllCoursesOfALicense($licenseid);
        if (!empty($selectedCourseList)) 
        {
            $multi_license->setSelected($selectedCourseList);
        }
        //$mform->addElement('autocomplete', 'licensecourses', get_string('selectlicensecourse', 'block_iomad_company_admin'), $this->courses, $autooptions);
        //$mform->addRule('licensecourses', get_string('missinglicensecourses', 'block_iomad_company_admin'),'required', null, 'client');

        /* custom code end */



        /* 
            * Custom code 
            * Syllametrics | support@syllametrics.com  
            * This code used to remove all tags from a license
            * Last Update: 9th oct,2018
        */

        $mform->addElement('advcheckbox', 'removetags','Remove all tags', '', array('group' => 1), array(0, 1));
        $mform->addHelpButton('removetags', 'removetags', 'block_iomad_company_admin');
        $mform->hideIf('tag', 'removetags', 'checked');

        $mform->addElement('html', '<div class="row"><div class="col-md-3"></div><div class="col-md-9"><span class="btn btn-secondary selalltag marginclass">Select all tags</span><span class="btn btn-secondary marginclass deselalltag">Clear all tags</span></div></div>');
        $allTags = getAllTags();
        //$options = array('multiple' => true,'noselectionstring' => 'No selected tags'); 
     
        natcasesort($allTags);
        $tagList = $mform->addElement('select', 'tag', get_string('selecttags', 'block_iomad_company_admin'), $allTags, array('style' => 'width:100%;','size'=>'10','class'=>'tagselect'));
        $tagList->setMultiple(true);


        //$tagList = $mform->addElement('autocomplete', 'tag', get_string('tags'), $allTags,$options);
        //$tagList->setMultiple(true);
        if ($licenseid !=0) 
        {
            $selectedTagList = getTags($licenseid);
            $tagList->setSelected($selectedTagList);
        }

        /* custom code end */






        
        
        
        if (($this->licenseinfo->licenseformat == 1) && ($licenseidget)) 
        {
            $allTagsForCourse = array();
            $parentlicense = $DB->get_records_sql("SELECT lc.courseid,lc.licenseid,c.fullname FROM mdl_companylicense_courses as lc JOIN mdl_course as c ON c.id = lc.courseid where lc.licenseid = '" . $this->licenseinfo->parentlicenseid . "'");
            $parentLicenseTags = $DB->get_records_sql("SELECT t.rawname, clt.tagid FROM mdl_tag AS t JOIN mdl_companylicense_tags AS clt ON clt.tagid = t.id WHERE clt.licenseid = '" . $this->licenseinfo->parentlicenseid . "'");

            foreach ($parentLicenseTags as $tagidsForShared) 
            {
                $allTagsForCourse[] = $tagidsForShared->tagid;
            }
            foreach ($parentlicense as $course) 
            {
                $allCourses[] = $course->courseid;
            }
            $courseFromTags = getCoursesFromTagId($allTagsForCourse);
            //$courseIdsToDisplay = array_diff($allCourses, $courseFromTags);
            $courseIdsToDisplay = array_unique(array_merge($allCourses, $courseFromTags));
            $courseToDisplay = getCourseNameFromId($courseIdsToDisplay);
            if (!empty($courseToDisplay)) 
            {
                $mform->addElement("html", "<div class='col-md-3'>" . get_string('selectedcourses', 'block_iomad_company_admin') . " </div> <div class='col-md-9 interests'>");          
                    foreach ($courseToDisplay as $value) {
                        $mform->addElement("html", "<span style='color: #fff;padding: 0px 5px;border-radius: 2px;' role='listitem' data-value='3' aria-selected='true' class='tag tag-info m-b-1 m-r-1' style='font-size: 100%'> $value</span>");
                    }
                $mform->addElement("html", "</div>");
            }

            if (!empty($parentLicenseTags)) 
            {
                $mform->addElement("html", "<div class='col-md-3'>" . get_string('selectedtags', 'block_iomad_company_admin') . " </div> <div class='col-md-9 interests'>");
                    foreach ($parentLicenseTags as $tag) 
                    {
                        $mform->addElement("html", "<span style='color: #fff;padding: 0px 5px;border-radius: 2px;' role='listitem' data-value='3' aria-selected='true' class='tag tag-info m-b-1 m-r-1' style='font-size: 100%'> $tag->rawname </span>");
                    }
                $mform->addElement("html", "</div>");
            }
        }
        
        
        
        
        // If we are not a child of a program license then show all of the courses.
        if (!empty($this->parentlicense->program)) {
            $mform->addElement('html', "</div>");
        }
        if ($this->courses) {
            $this->add_action_buttons(true, get_string('updatelicense', 'block_iomad_company_admin'));
        } else {
            $mform->addElement('html', get_string('nocourses', 'block_iomad_company_admin'));
        }
    }
    
    public function validation($data, $files)
    {
        global $CFG, $DB;

       
        $errors = array();
        
        $name = optional_param('name', '', PARAM_ALPHANUMEXT);

	 /* 
                 * Custom code
                 * Author: Syllametrics | support@syllametrics.com
                 * Update: 19th March, 2019
                */
        
        /**
	if (empty($name)) {
            $errors['name'] = get_string('invalidlicensename', 'block_iomad_company_admin');
        }
	**/
        
        
        /* ------------ Custom Changes-----------------  */
        
        
        
        if ((empty($data['parentlicenseid'])) && ($data['licenseformat'] == 1)) {
            $errors['parentlicenseid'] = "Select Parent License";
        }
        
        
        if (!empty($data['licenseid'])) {
            // check that the amount of free licenses slots is more than the amount being allocated.
            $currentlicense = $DB->get_record('companylicense', array(
                'id' => $data['licenseid']
            ));
            
            /* --------------- Custom changes-------------------  */
            
            if ($currentlicense->licenseformat == 1) {
                
                if ($currentcourses = $DB->get_records('companylicense_courses', array(
                    'licenseid' => $currentlicense->id
                ), null, 'courseid')) {
                    foreach ($currentcourses as $currentcourse) {
                        $data['licensecourses'][] = $currentcourse->courseid;
                    }
                }
            }
            
            
            if (!empty($currentlicense->program)) 
            {
                if (empty($data['licensecourses'])) 
                {
                    $data['licensecourses'] = array();
                }
                if (!empty($data['tag'])) 
                {
                    $tagCoursesList = getCoursesFromTagId($data['tag']);
                }
                else
                {
                    $tagCoursesList = array();
                }    
                $allCoursesToUpdate = array_unique(array_merge($tagCoursesList,$data['licensecourses']));
                $used = $currentlicense->used / count($allCoursesToUpdate);
            } 
            else 
            {
                $used = $currentlicense->used;
            }
            if ($used > $data['allocation']) {
                $errors['allocation'] = get_string('licensenotenough', 'block_iomad_company_admin');
            }
        }

        if ($data['startdate'] > $data['expirydate']) {
            $errors['startdate'] = get_string('invalidstartdate', 'block_iomad_company_admin');
        }
        
        if (!empty($data['parentid'])) {
            // check that the amount of free licenses slots is more than the amount being allocated.
            $parentlicense = $DB->get_record('companylicense', array(
                'id' => $data['parentid']
            ));
            
            // Check if this is a new license or we are updating it.
            if (!empty($data['licenseid'])) {
                $currlicenseinfo = $DB->get_record('companylicense', array(
                    'id' => $data['licenseid']
                ));
                $weighting       = $currlicenseinfo->allocation;
            } else {
                $weighting = 0;
            }
            $free = $parentlicense->allocation - $parentlicense->used + $weighting;
            
            // How manay license do we actually need?
            /*if (!empty($data['program'])) {
                $required = $data['allocation'] * count($data['licensecourses']);
            } else {
                $required = $data['allocation'];
            }*/
            $required = $data['allocation'];
            // Check if we have enough.
            if ($required > $free) {
                $errors['allocation'] = get_string('licensenotenough', 'block_iomad_company_admin');
            }
            
            // Check if we have a designated company.
            if (empty($data['designatedcompany'])) {
                $errors['designatedcompany'] = get_string('invalid_company', 'block_iomad_company_admin');
            }
        }
        
        // Allocation needs to be an integer.
        /*if (!preg_match('/^\d+$/', $data['allocation'])) {
            $errors['allocation'] = get_string('notawholenumber', 'block_iomad_company_admin');
        }*/
        
        
        /* --------------  Custom changes----------------  */
        
        /*if ($data['licenseformat'] != 1) {
            
            // Did we get passed any courses?
            if (empty($data['licensecourses'])) {
                $errors['licensecourses'] = get_string('select_license_courses', 'block_iomad_company_admin');
            }
        }*/
        
        if (($data['type'] == 1 || $data['type'] == 3) && empty($data['validlength'])) {
            $errors['validlength'] = get_string('missinglicenseduration', 'block_iomad_company_admin');
        }
        
        // Is the value for length appropriate?
        if (empty($data['type']) && $data['validlength'] < 1) {
            $errors['validlegth'] = get_string('invalidnumber', 'block_iomad_company_admin');
        }
        
        // Did we get passed any courses?
        if ($data['allocation'] < 1) {
            $errors['allocation'] = get_string('invalidnumber', 'block_iomad_company_admin');
        }
        
        // Is expiry date valid?
        if ($data['expirydate'] < time()) {
            $errors['expirydate'] = get_string('errorinvaliddate', 'calendar');
        }
        
        if ($CFG->iomad_autoenrol_managers && $data['type'] > 1) {
            $errors['type'] = get_string('invalid');
        }
        
        return $errors;
    }
    
}

$returnurl    = optional_param('returnurl', '', PARAM_LOCALURL);
$companyid    = optional_param('companyid', 0, PARAM_INTEGER);
$courseid     = optional_param('courseid', 0, PARAM_INTEGER);
$departmentid = optional_param('departmentid', 0, PARAM_INTEGER);
$licenseid    = optional_param('licenseid', 0, PARAM_INTEGER);
$parentid     = optional_param('parentid', 0, PARAM_INTEGER);

$context = context_system::instance();
require_login();

// Set the companyid
$companyid = iomad::get_my_companyid($context);
$company   = new company($companyid);

if (empty($parentid)) {
    if (!empty($licenseid) && $company->is_child_license($licenseid)) {
        iomad::require_capability('block/iomad_company_admin:edit_my_licenses', $context);
    } else {
        iomad::require_capability('block/iomad_company_admin:edit_licenses', $context);
    }
} else {
    iomad::require_capability('block/iomad_company_admin:edit_my_licenses', $context);
}

$PAGE->set_context($context);

$urlparams = array(
    'companyid' => $companyid
);
if ($returnurl) {
    $urlparams['returnurl'] = $returnurl;
}
if ($courseid) {
    $urlparams['courseid'] = $courseid;
}

// Correct the navbar .
// Set the name for the page.
$linktext = get_string('managelicenses', 'block_iomad_company_admin');
// Set the url.
$linkurl  = new moodle_url('/blocks/iomad_company_admin/company_license_edit_form.php');

// Print the page header.
$PAGE->set_context($context);
$PAGE->set_url($linkurl);
$PAGE->set_pagelayout('admin');
$PAGE->set_title($linktext);
$PAGE->set_heading(get_string('edit_licenses_title', 'block_iomad_company_admin'));

// Build the nav bar.
company_admin_fix_breadcrumb($PAGE, $linktext, $linkurl);

// If we are editing a license, check that the parent id is set.
if (!empty($licenseid)) {
    $licenseinfo = $DB->get_record('companylicense', array(
        'id' => $licenseid
    ));
    $parentid    = $licenseinfo->parentid;
}



/*  Create the object of class */


// Set up the form.
$mform = new company_license_form($PAGE->url, $context, $companyid, $departmentid, $licenseid, $parentid, $licenseinfo);
if ($licenseinfo = $DB->get_record('companylicense', array(
    'id' => $licenseid
))) {
    if ($currentcourses = $DB->get_records('companylicense_courses', array(
        'licenseid' => $licenseid
    ), null, 'courseid')) {
        foreach ($currentcourses as $currentcourse) {
            $licenseinfo->licensecourses[] = $currentcourse->courseid;
        }
    }
    
    if ($permittedcompanies = $DB->get_records('companylicense_permittedcompanies', array(
        'licenseid' => $licenseid
    ))) {
        foreach ($permittedcompanies as $percompany) {
            $licenseinfo->permittedcompanies[] = $percompany->companyid;
        }
    }
    
    // Deal with the amount for program courses.
    if (!empty($licenseinfo->program)) {
        $licenseinfo->allocation = $licenseinfo->allocation / count($currentcourses);
    }
    
    $mform->set_data($licenseinfo);
} else {
    
    $licenseinfo             = new stdclass();
    $licenseinfo->expirydate = strtotime('+ 1 year');
    
    
    if (!empty($parentid)) {
        if ($currentcourses = $DB->get_records('companylicense_courses', array(
            'licenseid' => $parentid
        ), null, 'courseid')) {
            foreach ($currentcourses as $currentcourse) {
                $licenseinfo->licensecourses[] = $currentcourse->courseid;
            }
        }
    }
    
    
    $mform->set_data($licenseinfo);
}

if ($mform->is_cancelled() || optional_param('cancel', false, PARAM_BOOL)) {
    if ($returnurl) {
        redirect($returnurl);
    } else {
        redirect(new moodle_url('/blocks/iomad_company_admin/company_license_list.php'));
    }
} else {
    if ($data = $mform->get_data()) {

        /* 
            * Custom code 
            * Syllametrics | support@syllametrics.com  
            * After form submit handling
            * Last Update: 9th oct,2018
        */

        //print_object($data);exit;

        global $DB, $USER;
        $companyid = iomad::get_my_companyid(context_system::instance());
        $companyrecord = $DB->get_record("company",array("id"=>$companyid));

        //print_object($data);exit;
     

        if (empty($data->licensecourses) || $data->removecourse == 1) 
        {
            $data->licensecourses = array();
        }

        if (!empty($data->tag) && $data->removetags == 0) 
        {
            $tagCoursesList = getCoursesFromTagId($data->tag);
            $tagIds = implode(',', $data->tag);
        }
        else
        {
            $tagCoursesList = array();
            $tagIds = 0;
        }


        if ($data->licenseformat != 1) 
        {






        
         $allUsersOfALicense = array_keys($DB->get_records('companylicense_users', array('licenseid' => $licenseid), null, 'licensecourseid'));
         
        $allFormCourses = array_unique(array_merge($tagCoursesList,$data->licensecourses));

        

        $licenseidsToRemove =  array_diff($allUsersOfALicense, $allFormCourses);
        $licenseCoursesToAdd = array_diff($allFormCourses,$allUsersOfALicense);

       // print_r($licenseidsToRemove);exit;

       

       // $allUsersListOfALicense = array_keys($DB->get_records('companylicense_users', array('licenseid' => $licenseid), null, 'userid'));
      $allUsersListOfALicense = array_keys($DB->get_records_sql("SELECT cu.userid as userid FROM `mdl_companylicense_users` cu join `mdl_companylicense` cl ON cu.licenseid = cl.id where cu.licenseid = $licenseid and cl.companyid = $companyid"));


        $timeend = time();

        if (!empty($licenseidsToRemove)) 
        {
            foreach ($allUsersListOfALicense as $userid) 
            {
                foreach ($licenseidsToRemove as $courseid) 
                {
                   
                    $DB->execute("DELETE FROM {companylicense_users} WHERE licenseid = $licenseid AND licensecourseid = $courseid and userid = $userid");

                    $enrolid = getenrolid($courseid);
                  
                    //$getexpirydate = unenrol_course_expiry_data($courseid,$companyid,$userid,$licenseid);
		      $getexpirydate = course_end_date_final($courseid,$userid);

                    if(isset($getexpirydate)){
                
                          $timeend = $getexpirydate;
                    }
                   updateuserenrolment($enrolid,$userid,$timeend);
                    
                }
            }
        }

        if (!empty($licenseCoursesToAdd)) 
        {
            if (!empty($allUsersListOfALicense)) 
            {
           /* $shared = array_keys($DB->get_records('companylicense', array('parentlicenseid' => $licenseid), null, 'id'));
            array_push($shared, $licenseid);
          
                foreach ($shared as $sharedLicenseid) 
                {*/
                    foreach ($allUsersListOfALicense as $userid) 
                    {
                        foreach ($licenseCoursesToAdd as $courseid) 
                        {
                            if(!$DB->record_exists('companylicense_users', array('licenseid' => $licenseid,'licensecourseid' => $courseid,'userid' => $userid)))
                            {
                                $DB->insert_record('companylicense_users', array('licenseid' => $licenseid,'licensecourseid' => $courseid,'userid' => $userid,'issuedate' => time()));
                                if ($data->instant == 1) 
                                {
                                    enrolUser($userid,$courseid);
                                }
                            }


                            $enrolid = getenrolid($courseid);

                            if ($ue = $DB->get_record('user_enrolments', array('enrolid'=>$enrolid, 'userid'=>$userid))) 
                            {
                                if (!empty($ue)) {

                                    $getexpirydate = course_end_date_final($courseid,$userid);   
               
                           		if(isset($getexpirydate))
						{
						      $timeend = $getexpirydate;
                                    		}


					           $timeend = strtotime(date("Y-m-d 23:59:59", $timeend));
					
                            	    updateuserenrolment($enrolid,$userid,$timeend);
                                }
                            } 
                        }
                    }
                //}
            }
        }
    }

        /*print_object($allUsersOfALicense);
        print_object($allCoursesToUpdate);
        var_dump($licenseidsToRemove);exit;*/

        //company_user::unenrol($removeuser, array($this->course->id),$this->selectedcompany);


        //print_object($tagCoursesList);exit;


















        $new         = false;
        $licensedata = array();
        
        $licensedata['reference']       = trim($data->reference);
        $licensedata['licenseformat']   = $data->licenseformat;
        $licensedata['sharingtypes']    = $data->sharingtypes;
        $licensedata['expirydate']      = $data->expirydate;
        $licensedata['startdate']       = $data->startdate;
        $licensedata['validlength']     = $data->validlength;
        $licensedata['unlimitedseats']  = $data->unlimitedseats;
        $licensedata['removetags']      = $data->removetags;
        $licensedata['removecourse']    = $data->removecourse;
        $licensedata['type']            = $data->type;
        
        if ($data->licenseformat != 0 || $data->sharingtypes != 1) {
            $data->permittedcompanies = NULL;
        }
        
        /* ---------------Custom changes-----------------  */
        
        // If shared license
        if ($data->licenseformat == 1) {
            $licensedata['licenseformatname'] = 'shared_copy';
            $allParentIds                     = implode(',', $data->parentlicenseid);
            $parentrecord                     = $DB->get_records_sql("SELECT * FROM {companylicense} WHERE id IN ($allParentIds)");
            $data->sharingtypes               = NULL;
        } else {
            $licensedata['licenseformatname'] = 'new';
            $data->parentlicenseid            = NULL;
        }
        
        if (empty($data->languages)) {
            $data->languages = array();
        }
        if (empty($data->parentid)) {
            $licensedata['companyid'] = $data->companyid;
        } else {
            $licensedata['companyid'] = $data->designatedcompany;
            $licensedata['parentid']  = $data->parentid;
        }
        
        // update the record of a license
        if (!empty($licenseid) && $currlicensedata = $DB->get_record('companylicense', array(
            'id' => $licenseid))) {
            $new                            = false;
            $parentcoursesobject = $DB->get_records('companylicense_courses', array('licenseid' => $licenseid), null, 'courseid');

            
            // Already in the table update it.
            $licensedata['id']              = $currlicensedata->id;
            $licensedata['used']            = $currlicensedata->used;
            $licensedata['parentlicenseid'] = $currlicensedata->parentlicenseid;
            $licensedata['name']            = $data->name;
            if ($data->unlimitedseats == 1) 
            {
                if(count($parentcoursesobject) > 0){
                
                $licensedata['allocation']      = 9999999 * count($parentcoursesobject);
            }else{
                $licensedata['allocation']      = 9999999;

            }

            }
            else
            {
                // If shared license
                if ($data->licenseformat == 1) 
                {

                    $parentCour = getParentLicenseCourses($licenseid);
                    $allCoursesToUpdate = array_unique(array_merge($tagCoursesList,$parentCour)); // all Parent Courses including tag courses
                    $licensedata['allocation']      = $data->allocation * count($allCoursesToUpdate);
                }
                else
                {
                    $allCoursesToUpdate = array_unique(array_merge($tagCoursesList,$data->licensecourses)); // all Parent Courses including tag courses
                    $licensedata['allocation']      = $data->allocation * count($allCoursesToUpdate);
                }
            }

            //$licensedata['allocation']      = $data->allocation * count($parentcoursesobject);
            $licensedata['program']         = $currlicensedata->program;
            //$licensedata['type']            = $currlicensedata->type;
            $licensedata['instant']         = $currlicensedata->instant;
            $licensedata['validlength']     = $data->validlength;

            $DB->update_record('companylicense', $licensedata);
            $allCoursesToUpdate = array();
            $allCoursesToUpdate = array_unique(array_merge($tagCoursesList,$data->licensecourses)); // all Parent Courses including tag courses
            /*$usedToUpdate = ($currlicensedata->used/count($parentcoursesobject)) * (count($allCoursesToUpdate));
                $DB->execute("UPDATE {companylicense} SET used = $usedToUpdate WHERE id = $licenseid");*/

            
            /* ----------  Custom Changes - Updated all the child when parent update ---------------- */
            
            // If parent license update the records

            if ($data->licenseformat != 1) 
            {
                //$allCoursesToUpdate = array();
                $sharedLicenseId = array();
                //$allParentCourses = array();
                $sharedLicenseTagIds = array();
                $allSharedLicense = $DB->get_records('companylicense', array('parentlicenseid' => $licenseid));
            

                

                $DB->execute("DELETE FROM {companylicense_courses} WHERE licenseid = $licenseid");
                $DB->execute("DELETE FROM {companylicense_tags} WHERE licenseid = $licenseid");

                //insert/update courses to parent
                if (!empty($allCoursesToUpdate)) 
                {
                    foreach ($allCoursesToUpdate as $courseToUpdate) 
                    {
                        $DB->insert_record('companylicense_courses', array(
                            'licenseid' => $licenseid,
                            'courseid' => $courseToUpdate
                            ));
                        
                        if (!empty($data->tag) && $data->removetags == 0) 
                        {
                            foreach ($data->tag as $tagid) 
                            {
                                $DB->insert_record('companylicense_tags', array(
                                'licenseid' => $licenseid,
                                'courseid' => $courseToUpdate,
                                'tagid' => $tagid
                                ));
                            }
                        }
                    }
                }

                // insert/update courses to shared courses
                foreach ($allSharedLicense as $sharedLicenses)
                {
                    $sharedlicensecourses[$sharedLicenses->id] = $DB->get_records('companylicense_courses', array(
                    'licenseid' => $sharedLicenses->id), null, 'courseid');
                    $sharedLicenseTags = $DB->get_records('companylicense_tags', array('licenseid' => $sharedLicenses->id), null, 'tagid');
                    // creating an array of all tag ids of a shared course
                    if (!empty($sharedLicenseTags)) 
                    {
                        foreach ($sharedLicenseTags as $tagid) 
                        {
                            $sharedLicenseTagIds[] = $tagid->tagid;
                        }
                        $sharedLicenseTagCourses = getCoursesFromTagId($sharedLicenseTagIds);
                    }
                    else
                    {
                        $sharedLicenseTagCourses = array();
                    }
 
                    $allCoursesOfSharedLicense = array_unique(array_merge($allCoursesToUpdate,$sharedLicenseTagCourses));// courses which we want to upload in shared/child license
    
                 
                    $DB->execute("DELETE FROM {companylicense_courses} WHERE licenseid = $sharedLicenses->id");
                    $DB->execute("DELETE FROM {companylicense_tags} WHERE licenseid = $sharedLicenses->id");

                
 
                    
                    
                    // Update allocation of child if changes made in parent courses
                    if (!empty(array_diff(array_keys($sharedlicensecourses[$sharedLicenses->id]),$allCoursesOfSharedLicense)) || !empty(array_diff($allCoursesOfSharedLicense,array_keys($sharedlicensecourses[$sharedLicenses->id])))) 
                    {
 
                        $allocationToUpdate = ($sharedLicenses->allocation/count($sharedlicensecourses[$sharedLicenses->id])) * count($allCoursesOfSharedLicense);
                        $DB->execute("UPDATE {companylicense} SET allocation = $allocationToUpdate WHERE id = $sharedLicenses->id");
                    }


                    foreach ($allCoursesOfSharedLicense as $courseToUpdate) 
                    {
                        $DB->insert_record('companylicense_courses', array(
                            'licenseid' => $sharedLicenses->id,
                            'courseid' => $courseToUpdate
                            ));
                        
                        if (!empty($sharedLicenseTags) && $data->removetags == 0) 
                        {
                            foreach ($sharedLicenseTags as $tagid) 
                            {
                                $DB->insert_record('companylicense_tags', array(
                                'licenseid' => $sharedLicenses->id,
                                'courseid' => $courseToUpdate,
                                'tagid' => $tagid->tagid
                                ));
                            }
                        }
                    }
                }



                /*if ($sharedlicenserecords = $DB->get_records('companylicense', array(
                    'parentlicenseid' => $licenseid
                ))) {
                    $parentcoursesobject = $DB->get_records('companylicense_courses', array(
                        'licenseid' => $licenseid
                    ), null, 'courseid');
                    
                    // if ($licenseinfo->program != $data->program) {  ------------ }   
                    foreach ($sharedlicenserecords as $sharedlicenserecord) {
                        
                        
                        /* Using events -- shared license courses *
                        $sharedlicensecourses[$sharedlicenserecord->id] = $DB->get_records('companylicense_courses', array(
                            'licenseid' => $sharedlicenserecord->id
                        ), null, 'courseid');
                        
                        $sharedlicenserecord->program = $data->program;
                        $sharedlicenserecord->type    = $data->type;
                        $sharedlicenserecord->instant = $data->instant;
                        
                        
                        $sharedlicenserecord->allocation = (($sharedlicenserecord->allocation / count($parentcoursesobject)) * count($data->licensecourses));
                        
                        $DB->update_record('companylicense', $sharedlicenserecord);
                        
                        $allsharedlicense .= $sharedlicenserecord->id . ",";
                    } // foreach
                    
                    
                    $allsharedlicense = rtrim($allsharedlicense, ","); // allshared license
                    
                    
                    foreach ($parentcoursesobject as $parentcourse) {
                        $parentcourses[] = $parentcourse->courseid; // all parent courses
                    }
                    
                    $sharedcourses = $data->licensecourses; // all shared courses
                    
                    
                    $coursedifference = array_merge(array_diff($parentcourses, $sharedcourses), array_diff($sharedcourses, $parentcourses)); // coursedifference is if -- courses which are delete or newly added
                    
                    $deletedcourse = array_intersect($coursedifference, $parentcourses);
                    $deletedcourse = implode(",", $deletedcourse);
                    
                    $insertedcourses = array_diff($sharedcourses, $parentcourses);
                    

                    if (!empty($deletedcourse)) {
                        $DB->execute("DELETE FROM {companylicense_courses} WHERE licenseid IN ($allsharedlicense) AND courseid IN ($deletedcourse) ");
                    }
                    
                    if (!empty($insertedcourses)) {
                        foreach ($sharedlicenserecords as $sharedlicenserecord) {
                            // Add the course license allocations.
                            foreach ($insertedcourses as $selectedcourse) {
                                $DB->insert_record('companylicense_courses', array(
                                    'licenseid' => $sharedlicenserecord->id,
                                    'courseid' => $selectedcourse
                                ));
                            }
                        }
                    }
                }*/
            }
            else
            {
               // If Shared license update the records
                $parentCourse = getParentLicenseCourses($licenseid);

                $allCoursesToUpdate = array_unique(array_merge($tagCoursesList,$parentCourse));

             


              

                if (!empty($allCoursesToUpdate)) {
                     //delete existing data.
                   $DB->execute("DELETE FROM {companylicense_courses} WHERE licenseid = $licenseid");
                   $DB->execute("DELETE FROM {companylicense_tags} WHERE licenseid = $licenseid");

                    
                    // Add the course license allocations.
                    foreach ($allCoursesToUpdate as $courseToUpdate) {

                        $DB->insert_record('companylicense_courses', array(
                            'licenseid' => $licenseid,
                            'courseid' => $courseToUpdate
                            ));

                        if (!empty($data->tag) && $data->removetags == 0) 
                        {
                            foreach ($data->tag as $tagid) 
                            {
                                $DB->insert_record('companylicense_tags', array(
                                'licenseid' => $licenseid,
                                'courseid' => $courseToUpdate,
                                'tagid' => $tagid
                                ));
                            }
                        }
                    }
                }
            }
            /* 
            * Custom code 
            * Syllametrics | support@syllametrics.com  
            * Last Update: 2th July,2019
            * Event hit when license length changed.
            */

             $coursealllist = array_keys($DB->get_records_sql("SELECT courseid FROM {companylicense_courses} where licenseid = $data->licenseid"));

            $allUsersListOfALicense = array_keys($DB->get_records_sql("SELECT DISTINCT(cu.userid) as userid FROM `mdl_companylicense_users` cu  join `mdl_company_users` cl ON cu.userid = cl.userid  where cu.licenseid = $data->licenseid and cl.companyid = $companyid"));

          if($currlicensedata->expirydate >= time()){
             if (($currlicensedata->validlength > $data->validlength) || ($currlicensedata->expirydate > $data->expirydate)) {
                if (!empty($allUsersListOfALicense)) 
                        {
                        foreach ($allUsersListOfALicense as $userid) 
                            {
                                $eventshortened = \block_iomad_company_admin\event\user_license_shortened::create(array(
                                            'context' => context_system::instance(),
                                            'userid' => $userid,
                                            'objectid' => $data->licenseid
                                            ));
                                            $eventshortened->trigger();
                                
                            }
                        }
                    }

                     if (($currlicensedata->validlength < $data->validlength) || ($currlicensedata->expirydate < $data->expirydate)) {

                        if (!empty($allUsersListOfALicense)) 
                            {
                            foreach ($allUsersListOfALicense as $userid) 
                                {
                                    $eventshortened = \block_iomad_company_admin\event\user_license_extended::create(array(
                                            'context' => context_system::instance(),
                                            'userid' => $userid,
                                            'objectid' => $data->licenseid
                                            ));
                                            $eventshortened->trigger();
                                }
                            }
                        }
                    }elseif($currlicensedata->expirydate <= time()){

                         if (!empty($allUsersListOfALicense)) 
                            {
                            foreach ($allUsersListOfALicense as $userid) 
                                {
                                    $eventshortened = \block_iomad_company_admin\event\user_license_renewed::create(array(
                                            'context' => context_system::instance(),
                                            'userid' => $userid,
                                            'objectid' => $data->licenseid
                                            ));
                                            $eventshortened->trigger();
                                }
                            }

                    }


                    
                    



         
        }
        else 
        {
            // Insert the record
            $new                 = true;
            // New license being created.
            $licensedata['used'] = 0;
            //If condition is for shared license insert
            if ($data->licenseformat == 1) 
            {
                foreach ($parentrecord as $licenseparentid) 
                {
                    $allCourse = array();
                    if ($currentcourses = $DB->get_records('companylicense_courses', array('licenseid' => $licenseparentid->id), null, 'courseid')) 
                    {
                        foreach ($currentcourses as $currentcourse) {
                            $allCourse[] = $currentcourse->courseid;
                        }
                        $allCourses = array_unique(array_merge($tagCoursesList,$allCourse));
                    }

                    
                    $licensedata['parentlicenseid'] = $licenseparentid->id;

		 /* 
                 * Custom code
                 * Author: Syllametrics | support@syllametrics.com
                 * Update: 19th March, 2019
                 * Convert "combine license identifier name if inserted" dropdown to multiselect
                */

  		if($data->name){
                    $licensedata['name']            = $data->name . '-' . $licenseparentid->name;
		}else{
 		      $licensedata['name']            =  $licenseparentid->name;
		}


                    if ($data->unlimitedseats == 1) 
                    {
                        $licensedata['allocation']  = 9999999;
                    }
                    else
                    {
                        // If shared license
                        if ($data->licenseformat == 1) 
                        {
                            $parentCour = getAllCoursesOfALicense($licenseparentid->id);
                            $parentCourseToMerge = explode(',', $parentCour);
                            $allCoursesToUpdate = array_unique(array_merge($tagCoursesList,$parentCourseToMerge)); // all Parent Courses including tag courses
                            $licensedata['allocation']      = $data->allocation * count($allCoursesToUpdate);
                            /*print_object($parentCour);
                            print_object($parentCourseToMerge);
                            print_object($allCoursesToUpdate);
                            print_object($licensedata['allocation']);
                            exit;*/

                        }
                    }
                    //$licensedata['allocation']      = $data->allocation * count($allCourses);
                    $licensedata['program']         = $licenseparentid->program;
                    $licensedata['type']            = $licenseparentid->type;
                    $licensedata['instant']         = $licenseparentid->instant;
                    $licensedata['validlength']     = $data->validlength;

                    $licenseid = $DB->insert_record('companylicense', $licensedata);
                    $DB->delete_records('companylicense_courses', array(
                        'licenseid' => $licenseid
                    ));
                    $DB->delete_records('companylicense_tags', array(
                        'licenseid' => $licenseid
                    ));
                    if (!empty($allCourses)) {
                        // Add the course license allocations.
                        foreach ($allCourses as $selectedcourse) {
                            $DB->insert_record('companylicense_courses', array(
                                'licenseid' => $licenseid,
                                'courseid' => $selectedcourse
                                ));
                           
                            if (!empty($data->tag) && $data->removetags == 0) 
                            {
                                foreach ($data->tag as $tagid) 
                                {
                                    $DB->insert_record('companylicense_tags', array(
                                    'licenseid' => $licenseid,
                                    'courseid' => $selectedcourse,
                                    'tagid' => $tagid
                                    ));
                                }
                            }
                        }
                    }
                }
            } 
            else 
            {
                //ELSE condition is for parent license insert
                $allCourses = array_unique(array_merge($tagCoursesList,$data->licensecourses));
                $licensedata['parentlicenseid'] = NULL;
                $licensedata['name']            = $data->name;
                if ($data->unlimitedseats == 1) 
                {
                    $licensedata['allocation']      = 9999999;
                }
                else
                {
                    // If shared license
                    /*if ($data->licenseformat == 1) 
                    {
                        $parentCour = getParentLicenseCourses($licenseid);
                        $allCoursesToUpdate = array_unique(array_merge($tagCoursesList,$parentCour)); // all Parent Courses including tag courses
                        $licensedata['allocation']      = $data->allocation * count($allCoursesToUpdate);

                    }*/
                    //else
                    //{
                        $allCoursesToUpdate = array_unique(array_merge($tagCoursesList,$data->licensecourses)); // all Parent Courses including tag courses
                        $licensedata['allocation']      = $data->allocation * count($allCoursesToUpdate);
                    //}
                }
                //$licensedata['allocation']      = $data->allocation * count($allCourses);
                $licensedata['program']         = $data->program;
                $licensedata['type']            = $data->type;
                $licensedata['instant']         = $data->instant;
                $licensedata['validlength']     = $data->validlength;
                
                $licenseid = $DB->insert_record('companylicense', $licensedata);
                if (!empty($allCourses)) {
                    // Add the course license allocations.
                    foreach ($allCourses as $selectedcourse) {
                        //$DB->set_debug(true);
                        $DB->insert_record('companylicense_courses', array(
                            'licenseid' => $licenseid,
                            'courseid' => $selectedcourse
                            ));
                        
                        if (!empty($data->tag) && $data->removetags == 0) 
                        {
                            foreach ($data->tag as $tagid) 
                            {
                                $DB->insert_record('companylicense_tags', array(
                                'licenseid' => $licenseid,
                                'courseid' => $selectedcourse,
                                'tagid' => $tagid
                                ));
                            }            
                        }
                    }
                }
            }
        } // Insert record   //else

        
        
        //exit;
        
        // Deal with course allocations if there are any.
        // Capture them for checking.
        $oldcourses = $DB->get_records('companylicense_courses', array(
            'licenseid' => $licenseid
        ), null, 'courseid');
        
        
        /* ------------- Custom Changes -- Insert courses  ---------------- */   
        
        
        if (!empty($data->permittedcompanies)) {
            $DB->delete_records('companylicense_permittedcompanies', array(
                'licenseid' => $licenseid
            ));
            
            // Add the course license allocations.
            foreach ($data->permittedcompanies as $permittedcompany) {
                $DB->insert_record('companylicense_permittedcompanies', array(
                    'licenseid' => $licenseid,
                    'companyid' => $permittedcompany
                ));
            }
        }
        
        
        // Create an event to deal with an parent license allocations.
        $eventother = array(
            'licenseid' => $licenseid,
            'parentid' => $data->parentid
        );
        
        if ($new) {
           /* $event = \block_iomad_company_admin\event\company_license_created::create(array(
                'context' => context_system::instance(),
                'userid' => $USER->id,
                'objectid' => $licenseid,
                'other' => $eventother
            ));*/
        } else {
            $eventother['oldcourses'] = json_encode($oldcourses);
            
            
            if ($currlicensedata->program != $data->program) {
                $eventother['programchange'] = true;
            }
            
            
            if ($currlicensedata->startdate != $data->startdate) {
                $eventother['oldstartdate'] = $currlicensedata->startdate;
            }
            /*if ($currlicensedata->educator != $data->educator) {
                $eventother['educatorchange'] = true;
            }*/
            /*$event = \block_iomad_company_admin\event\company_license_updated::create(array(
                'context' => context_system::instance(),
                'userid' => $USER->id,
                'objectid' => $licenseid,
                'other' => $eventother
            ));*/
        }
       // $event->trigger();
        
        if (optional_param('licenseid', 0, PARAM_INTEGER) != 0) 
        {
            // Updating companylicense here because events are updating the same table, so we have to update it here.
	    $currlicense_course_list = $DB->get_record('companylicense_courses', array('licenseid' => $licenseid));
            $usedToUpdate = ($currlicensedata->used/count($currlicense_course_list)) * (count($allCoursesToUpdate));
            $DB->execute("UPDATE {companylicense} SET used = $usedToUpdate WHERE id = $licenseid");
        }
        /* Event function for all the child */
        
        if ($data->licenseformat != 1) {
         // If parent license update the records
            if ($sharedlicenserecords = $DB->get_records('companylicense', array(
                'parentlicenseid' => $licenseid
            ))) {
                
                foreach ($sharedlicensecourses as $sharelicenseid => $sharedlicensecourse) {
                    
                    $eventothershared = array(
                        'licenseid' => $sharelicenseid,
                        'parentid' => 0
                    );
                    
                    $eventothershared['oldcourses'] = json_encode($sharedlicensecourse);
                    
                    
                   /* $eventshared = \block_iomad_company_admin\event\company_license_updated::create(array(
                        'context' => context_system::instance(),
                        'userid' => $USER->id,
                        'objectid' => $sharelicenseid,
                        'other' => $eventothershared
                    ));*/
                    
                    
                    //$eventshared->trigger();
                    
                }
               // exit;

                

                 if (!empty($data->tag) && $data->removetags == 0) 
                    {
                        $tagCoursesList = getCoursesFromTagId($data->tag);
                        $tagIds = implode(',', $data->tag);
                    }
                    else
                    {
                        $tagCoursesList = array();
                        $tagIds = 0;
                    }


               
                   $sharedLicenseidval = array_keys($DB->get_records('companylicense', array('parentlicenseid' => $data->licenseid), null, 'id'));
                
                foreach ($sharedLicenseidval as $sharelicenseid) {

                   $allUsersOfALicense = array_keys($DB->get_records('companylicense_users', array('licenseid' => $sharelicenseid), null, 'licensecourseid'));

                   //print_r($allUsersOfALicense);

                    



                 
                   
     
                    $allFormCourses = array_unique(array_merge($tagCoursesList,$data->licensecourses));
                   

                    $sharedlicenseidsToRemove =  array_diff($allUsersOfALicense, $allFormCourses);
                    $sharedlicenseCoursesToAdd = array_diff($allFormCourses,$allUsersOfALicense);

                     if (!empty($sharedlicenseCoursesToAdd)) 
                         {
                		    $scompanyid = $DB->get_record_sql("SELECT companyid from {companylicense} where id = $sharelicenseid");
                		    $allUsersListOfSharedALicenseadd = array_keys($DB->get_records_sql("SELECT DISTINCT(cu.userid) as userid FROM `mdl_companylicense_users` cu  join `mdl_company_users` cl ON cu.userid = cl.userid  where cu.licenseid = $sharelicenseid and cl.companyid = $scompanyid->companyid"));
                        }elseif(!empty($sharedlicenseidsToRemove)) 
                            {
                            $allUsersListOfSharedALicenseremove = array_keys($DB->get_records('companylicense_users', array('licenseid' => $sharelicenseid), null, 'userid'));
                            }
		           

		           
		    
		   // print_r($allUsersListOfSharedALicense);exit;

                     //$allUsersListOfSharedALicense = array_keys($DB->get_records('companylicense_users', array('licenseid' => $sharelicenseid), null, 'userid'));


                     
                     
                    $timeend = time();

                     if (!empty($sharedlicenseCoursesToAdd)) 
                         {
                             foreach ($allUsersListOfSharedALicenseadd as $userid) 
                                {


                                    foreach ($sharedlicenseCoursesToAdd as $sharedcourseslist) 
                                    {

                                         if(!$DB->record_exists('companylicense_users', array('licenseid' => $sharelicenseid,'licensecourseid' => $sharedcourseslist,'userid' => $userid)))
                                        {
                                            $DB->insert_record('companylicense_users', array('licenseid' => $sharelicenseid,'licensecourseid' => $sharedcourseslist,'userid' => $userid,'issuedate' => time()));

                                            enrolUser($userid,$sharedcourseslist);
                                            
                                        }




                                         $enrolid = getenrolid($sharedcourseslist);

                                    
                                     if ($ue = $DB->get_record('user_enrolments', array('enrolid'=>$enrolid, 'userid'=>$userid))) 
                                        {
                                        if (!empty($ue)) 
                                            {
                                            
                                              $timeend = course_end_date_final($sharedcourseslist,$userid);
                                         
                                                $timeend = strtotime(date("Y-m-d 23:59:59", $timeend));


                                               updateuserenrolment($enrolid,$userid,$timeend);

                                               
                                                }
                                            }

                                    }
                                }
                                
                            }

                            if (!empty($sharedlicenseidsToRemove)) 
                            {
                                foreach ($allUsersListOfSharedALicenseremove as $userid)
                                {
                                    foreach ($sharedlicenseidsToRemove as $courseid) 
                                    {
                                       
                                        $DB->execute("DELETE FROM {companylicense_users} WHERE licenseid = $sharelicenseid AND licensecourseid = $courseid and userid = $userid");

                                        $enrolid = getenrolid($courseid);
                                      
                                  $getexpirydate = course_end_date_final($courseid,$userid);

                                        if(isset($getexpirydate)){
                                    
                                              $timeend = $getexpirydate;
                                        }
                                       updateuserenrolment($enrolid,$userid,$timeend);
                                        
                                    }
                                }
                            }
                        }                
            }
        }
        //exit;
        
        redirect(new moodle_url('/blocks/iomad_company_admin/company_license_list.php'));
    }
    
    // Display the form.
    echo $OUTPUT->header();
    
    // Check the department is valid.
    if (!empty($departmentid) && !company::check_valid_department($companyid, $departmentid)) {
        print_error('invaliddepartment', 'block_iomad_company_admin');
    }
    
    // Check the license is valid.
    if (!empty($licenseid) && !company::check_valid_company_license($companyid, $licenseid)) {
        print_error('invalidlicense', 'block_iomad_company_admin');
    }
    
    $company = new company($companyid);
    echo "<h3>" . $company->get_name() . "</h3>";
    $mform->display();
    echo $OUTPUT->footer();


}
function getCoursesFromTagId($tagids)
{
    global $DB;
    if ($tagids) {
	$tagsid = implode(',', $tagids);
    } else {
        $tagsid = 0;
    }
    $courseIds = array();
    $courseData = $DB->get_records_sql("SELECT * FROM {tag_instance} WHERE tagid IN ($tagsid)");
    foreach ($courseData as $courseValue) 
    {
        $courseIds[] = $courseValue->itemid;
    }
    return $courseIds;
}

function getAllTags()
{
    global $DB;
    $tags = $DB->get_records_sql('SELECT t.id,t.rawname FROM {tag} t JOIN {tag_instance} ti ON t.id=ti.tagid WHERE itemtype = "course" AND component = "core" ');
    $allTags = array();
    foreach ($tags as $tagValue) 
    {
        $allTags[$tagValue->id] = $tagValue->rawname;            
    }
    return $allTags;
}

function getTags($licenseid)
{
    global $DB;
    $tags = $DB->get_records_sql("SELECT tagid FROM {companylicense_tags} WHERE licenseid = $licenseid");
    $allTags = array();
    foreach ($tags as $tagValue) 
    {
        $allTags[] = $tagValue->tagid;            
    }
    $finalTagId = implode(',', $allTags);
    return $finalTagId;
}

function getAllCoursesOfALicense($licenseid)
{
    global $DB;
    $courseids = $DB->get_records_sql("SELECT courseid FROM {companylicense_courses} WHERE licenseid = $licenseid");
    $courseid = array();
    foreach ($courseids as $courseValue) 
    {
        $courseid[] = $courseValue->courseid;            
    }
    $finalCourseId = implode(',', $courseid);
    return $finalCourseId;
}

function getParentLicenseCourses($licenseid)
{
    global $DB;
    $sharedLicRec = $DB->get_record('companylicense', array('id' => $licenseid));
    $allParentCourses = $DB->get_records('companylicense_courses', array('licenseid' => $sharedLicRec->parentlicenseid), null, 'courseid');
    $parentCourseInSharedLicense = array();
    foreach ($allParentCourses as $parentCourse) 
    {
        $parentCourseInSharedLicense[] = $parentCourse->courseid;
    }
    return $parentCourseInSharedLicense;
}

function enrolUser($userid,$courseid)
{
    global $DB;
    if (!is_enrolled_custom($courseid, $userid)) 
    {
        $result = $DB->get_records('enrol', array('courseid'=>$courseid, 'enrol'=>"license"));

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
    }
    return false;
}

function unenrolUser($userid,$courseid)
{
    $instances = $DB->get_records('enrol', array('courseid' => $courseid));
    foreach ($instances as $instance) 
    {
        $plugin = enrol_get_plugin($instance->enrol);
        $plugin->unenrol_user($instance, $userid);
    }
}

function is_enrolled_custom($courseid, $userid)
{
    global $DB;
    $data = $DB->get_records_sql("SELECT ue.* FROM {user_enrolments} ue JOIN {enrol} e ON (e.id = ue.enrolid AND e.courseid = $courseid) JOIN {user} u ON u.id = ue.userid WHERE ue.userid = $userid");
    if (!empty($data)) 
    {
        return true;
    }
    else
    {
        return false;
    }
}

function getCourseNameFromId($courseIds)
{
    global $DB;
    $allNames = array();
    $allCourse = implode(',', $courseIds);
    $data = $DB->get_records_sql("SELECT fullname FROM {course} WHERE id IN($allCourse)");
    foreach ($data as $value) 
    {
        $allNames[] = $value->fullname;
    }
    return $allNames;
}


function getUserList($licenseId)
{
    global $DB;
    $data = $DB->get_records('companylicense_users', array('licenseid' => $licenseId), null, 'userid');
    foreach ($data as $value) 
    {
        $userids[] = $value->userid;
    }
    return $userids;
}

function getenrolid($courseid)
{
    global $DB;
    $data = $DB->get_record_sql("SELECT * FROM {enrol} WHERE courseid=$courseid AND enrol='license'");
    
    return $data->id;
    
}

function updateuserenrolment($enrolid,$userid,$timeend)
{

    global $DB;
    //$DB->set_debug(true);
    $DB->execute("UPDATE {user_enrolments} SET timeend = $timeend WHERE enrolid=$enrolid AND userid = $userid");
   // exit;
    //return $timeend;

}

function get_user_enrolment_date($userid,$licenseid,$courseid)
{
    global $DB;
    $data = $DB->get_record_sql("SELECT issuedate FROM {companylicense_users} WHERE userid = $userid AND licenseid = $licenseid AND licensecourseid = $courseid");
    return $data->issuedate;
    
}

function unenrol_course_expiry_data($courseid,$companyid,$userid,$licenseid)
{
	global $DB;
	//$DB->set_debug(true);
	$expiry_date=$DB->get_record_sql("SELECT MAX(IF(((UNIX_TIMESTAMP() + (cl.validlength *24*60*60)) < cl.expirydate), (UNIX_TIMESTAMP() + (cl.validlength *24*60*60)),cl.expirydate) )AS expiry FROM {companylicense} cl INNER JOIN {company} c ON cl.companyid=c.id INNER JOIN {companylicense_users} clu ON clu.licenseid=cl.id WHERE clu.userid=$userid AND c.id=$companyid AND clu.licensecourseid=$courseid AND cl.expirydate > UNIX_TIMESTAMP() AND cl.id NOT IN ($licenseid)");

	if (!empty($expiry_date)) 
	{
	return $expiry_date->expiry;
	}
	return false;
}

function course_expiry_data($courseid,$companyid,$userid)
{
	global $DB;
	$expiry_date=$DB->get_record_sql("SELECT MAX(IF(((UNIX_TIMESTAMP() + (cl.validlength *24*60*60)) < cl.expirydate), (UNIX_TIMESTAMP() + (cl.validlength *24*60*60)),cl.expirydate) )AS expiry FROM {companylicense} cl INNER JOIN {company} c ON cl.companyid=c.id INNER JOIN {companylicense_users} clu ON clu.licenseid=cl.id WHERE clu.userid=$userid AND c.id=$companyid AND clu.licensecourseid=$courseid AND cl.expirydate > UNIX_TIMESTAMP()");

	if (!empty($expiry_date)) 
	{
	return $expiry_date->expiry;
	}
	return false;
}



function course_license_course_count($courseid,$userid) {
    global $DB;
    $count_licenseid=$DB->get_record_sql("SELECT count(*) as count FROM {companylicense_users} where userid = $userid and licensecourseid = $courseid");
    return $count_licenseid->count;
}


function course_end_date_final($courseid,$userid){
    global $DB;
   // $DB->set_debug(true);

        $user_courses_licensids = $DB->get_record_sql("SELECT GROUP_CONCAT(DISTINCT(licenseid)) as licenseid FROM {companylicense_users} where licensecourseid = $courseid  AND userid = $userid");

    if(!empty($user_courses_licensids->licenseid)){

     $user_licensidss = explode(',',$user_courses_licensids->licenseid);

     $licenddate = array();

     foreach ($user_licensidss as $lic_id) {


        // $user_courseid = $DB->get_record_sql("SELECT GROUP_CONCAT(DISTINCT(licensecourseid)) as licensecourseid FROM {companylicense_users} where licenseid = $lic_id");

      /*  $user_course_enddate = $DB->get_record_sql("SELECT min(ue.timecreated) as timecreated FROM mdl_user_enrolments as ue join mdl_enrol as e on e.id=ue.enrolid where ue.userid = $userid and e.courseid IN ($user_courseid->licensecourseid) and e.enrol = 'license'");*/

      $user_course_enddate = $DB->get_record_sql("SELECT min(issuedate) as issuedate FROM {companylicense_users} where userid =  $userid and licenseid = $lic_id");


                                $minimum_date = date('m/d/Y', $user_course_enddate->issuedate);

                                
                                 $licensedata = $DB->get_record_sql("SELECT * FROM {companylicense} where id = $lic_id");


                                $validlength = strtotime( $minimum_date  . "+$licensedata->validlength days");

                                if($validlength < $licensedata->expirydate){
                                        $getexpirydate_value = $validlength;
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
