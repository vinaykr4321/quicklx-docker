<?php

// This file is part of the Certificate module for Moodle - http://moodle.org/
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

require_once("$CFG->libdir/formslib.php");

class bulkcertificate_form extends moodleform {

    //Add elements to form
    public function definition() {
        global $DB;

        $mform = $this->_form; // Don't forget the underscore! 
        $companyarray = array();
        $coursearray = '';

        if (is_siteadmin()) {
            $data = $DB->get_records_sql('SELECT * FROM {company} ORDER BY name ASC', array());
        } else {
            $companyid = iomad::companyid();
            $data = $DB->get_records('company', array('id' => $companyid));
        }
        foreach ($data as $company) {
            $companyarray[$company->id] = $company->name; //array($company->id => $company->name);
        }

        $multiple = $mform->addElement('select', 'company', 'Select Company', $companyarray, array('style' => 'width:90%;height:150px;'));
        $multiple->setMultiple(true);


        $multiple = $mform->addElement('select', 'course', 'Select Course', $coursearray, array('style' => 'width:90%;height:150px;'));
        $multiple->setMultiple(true);

        $this->add_action_buttons(false, 'Show Certificate');

        $mform->display();
    }

}
