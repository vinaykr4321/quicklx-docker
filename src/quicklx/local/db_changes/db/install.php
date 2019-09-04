<?php

function xmldb_local_db_changes_install() {
    global $CFG, $DB;

    $dbman = $DB->get_manager();

    $DB->execute("ALTER TABLE {companylicense_permitted} RENAME TO {companylicense_permittedcompanies}");
    
    $company = new xmldb_table('company');
    
    //name, type, size
    $unlicensed = new xmldb_field('unlicensed', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'hostname');
    $unassigned = new xmldb_field('unassigned', XMLDB_TYPE_CHAR, '10', null, null, null, null, 'unlicensed');
    $splashimage = new xmldb_field('splashimage', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'unassigned');
    $leftcolumncolor = new xmldb_field('leftcolumncolor', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'splashimage');
    $loginbackground = new xmldb_field('loginbackground', XMLDB_TYPE_CHAR, '50', null, null, null, null, 'leftcolumncolor');
    $loginbutton = new xmldb_field('loginbutton', XMLDB_TYPE_CHAR, '50', null, null, null, null, 'loginbackground');
    $expiredcourse = new xmldb_field('expiredcourse', XMLDB_TYPE_INTEGER, '11', null, null, null, null, 'loginbutton');

  
    if (!$dbman->field_exists($company, $unlicensed)) {
        $dbman->add_field($company, $unlicensed);
    }
    if (!$dbman->field_exists($company, $unassigned)) {
        $dbman->add_field($company, $unassigned);
    }
    if (!$dbman->field_exists($company, $splashimage)) {
        $dbman->add_field($company, $splashimage);
    }
    if (!$dbman->field_exists($company, $leftcolumncolor)) {
        $dbman->add_field($company, $leftcolumncolor);
    }
    if (!$dbman->field_exists($company, $loginbackground)) {
        $dbman->add_field($company, $loginbackground);
    }
    if (!$dbman->field_exists($company, $loginbutton)) {
        $dbman->add_field($company, $loginbutton);
    }
    if (!$dbman->field_exists($company, $expiredcourse)) {
        $dbman->add_field($company, $expiredcourse);
    }

    $companylicense = new xmldb_table('companylicense');
    
    $licenseformatname = new xmldb_field('licenseformatname', XMLDB_TYPE_CHAR, '30', null, null, null, null, 'instant');
    $licenseformat = new xmldb_field('licenseformat', XMLDB_TYPE_INTEGER, '11', null, null, null, null, 'licenseformatname');
    $sharingtypes = new xmldb_field('sharingtypes', XMLDB_TYPE_INTEGER, '11', null, null, null, null, 'licenseformat');
    $parentlicenseid = new xmldb_field('parentlicenseid', XMLDB_TYPE_INTEGER, '20', null, null, null, null, 'sharingtypes');
    $unlimitedseats = new xmldb_field('unlimitedseats', XMLDB_TYPE_INTEGER, '11', null, null, null, null, 'parentlicenseid');
    $removetags = new xmldb_field('removetags', XMLDB_TYPE_INTEGER, '11', null, null, null, null, 'unlimitedseats');
    $expiredcourse = new xmldb_field('removecourse', XMLDB_TYPE_INTEGER, '11', null, null, null, null, 'removetags');

    if (!$dbman->field_exists($companylicense, $licenseformatname)) {
        $dbman->add_field($companylicense, $licenseformatname);
    }
    if (!$dbman->field_exists($companylicense, $licenseformat)) {
        $dbman->add_field($companylicense, $licenseformat);
    }
    if (!$dbman->field_exists($companylicense, $sharingtypes)) {
        $dbman->add_field($companylicense, $sharingtypes);
    }
    if (!$dbman->field_exists($companylicense, $parentlicenseid)) {
        $dbman->add_field($companylicense, $parentlicenseid);
    }
    if (!$dbman->field_exists($companylicense, $unlimitedseats)) {
        $dbman->add_field($companylicense, $unlimitedseats);
    }
    if (!$dbman->field_exists($companylicense, $removetags)) {
        $dbman->add_field($companylicense, $removetags);
    }
    if (!$dbman->field_exists($companylicense, $expiredcourse)) {
        $dbman->add_field($companylicense, $expiredcourse);
    }


    $companylicense_courses = new xmldb_table('companylicense_courses');

    $tag = new xmldb_field('tag', XMLDB_TYPE_CHAR, '100', null, null, null, null, 'courseid');

    if (!$dbman->field_exists($companylicense_courses, $tag)) {
        $dbman->add_field($companylicense_courses, $tag);
    }


  /*  $report_scorm_download = new xmldb_table('report_scorm_download');

    $coursename varchar = new xmldb_field('coursename', XMLDB_TYPE_CHAR, '100', null, null, null, null, 'count');

    if (!$dbman->field_exists($report_scorm_download, $coursename varchar)) {
        $dbman->add_field($report_scorm_download, $coursename varchar);
    }*/

}