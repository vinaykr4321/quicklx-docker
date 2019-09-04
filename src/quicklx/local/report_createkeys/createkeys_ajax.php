<?php


require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot . '/' . $CFG->admin . '/user/lib.php');

require_login();
global $USER;
//admin_externalpage_setup('userbulk');
if (is_siteadmin()) {

    $action = $_GET['action'];

    if ($action == 'get_companyid') {
        $company = $_GET['company'];

        get_company_license($company);
    }
    if($action == 'get_maxuse'){
    	$inputvalue = $_GET['inputvalue'];
    	$company = $_GET['company'];
    	check_value($inputvalue,$company);

    }
} else {

    echo $OUTPUT->header();
    echo "You are not authorized to access this page.";
    echo $OUTPUT->footer();
}
function get_company_license($company)
{
    global $DB;
    $companydata = $DB->get_records_sql("SELECT id as cid,name as fullname FROM {companylicense} where companyid = $company");
    $companydata = json_encode($companydata);
    echo $companydata;
}

function check_value($inputvalue,$company)
{
    global $DB;
    //$DB->set_debug(true);
     $companydata = $DB->get_record_sql("SELECT sum(noofkeys) as keysum FROM {key_batch_settings} where companyid = $company");
     $leftkeys_space = (100000 - $companydata->keysum);
     if($leftkeys_space > 0){
       if($inputvalue < 100000){
            if($inputvalue <= 50000){
            if($inputvalue > $leftkeys_space){
                echo "Maximum $leftkeys_space available only"; 
            }
        }else{
            echo "Please enter value less then 50,000";
        }


        }else{
            echo "Please enter value less then 50,000";
        }

    }else{
     	echo "Something wrong";
     }
}
