<?php

require_once('../../config.php');
global $DB;
extract($_POST);

$companyids = json_decode($companyid);
$allcompanyids = implode(',', $companyids);
$data = $DB->get_records_sql("SELECT c.* from {company_course} as cc join {course} as c on c.id=cc.courseid where cc.companyid IN ($allcompanyids) ORDER BY c.fullname ASC");
$option = '';
if ($data) {

    foreach ($data as $value) {
        $option .= "<option value=$value->id>$value->fullname</option>";
    }
    echo $option;
}