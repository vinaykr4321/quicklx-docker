<?php
require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot . '/' . $CFG->admin . '/user/lib.php');


if (!is_siteadmin()) {

    $action = $_GET['action'];

      if($action == 'key_validate'){
        $keyvalue = $_GET['getkeyvalue'];
        $company = $_GET['company'];
        validate_key($keyvalue,$company);
    }
}


function validate_key($keyvalue,$companyid){
	global $DB;
        //$keyvalue = urlencode($keyvalue);

     $keyexists = $DB->get_record_sql("SELECT @s:=@s+1 n,kb.*,kbs.* FROM mdl_key_batches kb JOIN mdl_key_batch_settings kbs ON kb.batch_setting_id = kbs.id, (SELECT @s:=0) n where kb.keysvalue = '".$keyvalue."' AND kbs.companyid = '".$companyid."'");
  

if($keyexists){

     $licenseid_value = $DB->get_record_sql("SELECT GROUP_CONCAT(licenseid) as licenseids FROM {key_batch_license} where key_batches_id = $keyexists->id");

     if(isset($keyexists) && ($keyexists->expiry > time()) && ($keyexists->expiry != 'NULL')){

        $licenseids = explode(',', $licenseid_value->licenseids);

         $valid = 0;
        foreach ($licenseids as  $licenseid) {
            $licensedata = $DB->get_record('companylicense',array(
                'id' => $licenseid
            ));
            if(($licensedata->expirydate > time()) && ($licensedata->allocation > $licensedata->used || $licensedata->unlimitedseats == '1'  ) ){
                $valid = 1;
            }

        }


        $batchid = $DB->get_record_sql("SELECT SUM(used_key) as all_used FROM {key_batches} where batch_setting_id = $keyexists->batch_setting_id");


        if($keyexists->keytype == '1' && $keyexists->used_key == '1'){
            echo "The key you entered could not be found. Please try again or contact support for help.";

        }elseif($keyexists->keytype == '2' && $keyexists->noof_batch_uses_allowed <= $batchid->all_used){
              echo "The key you entered could not be found. Please try again or contact support for help.";
        }

        if($valid != '1'){
             echo "The key you entered could not be found. Please try again or contact support for help.";
        }


     }else if(($keyexists->expiry < time()) && ($keyexists->expiry != 'NULL')){
        echo "The key you entered is expired. Please try a different key or contact support.";
     }





     if(isset($keyexists) && ($keyexists->expiry == 'NULL')){

         $licenseids = explode(',', $licenseid_value->licenseids);

         $valid = 0;
        foreach ($licenseids as  $licenseid) {
            $licensedata = $DB->get_record('companylicense',array(
                'id' => $licenseid
            ));
            if(($licensedata->expirydate > time()) && ($licensedata->allocation > $licensedata->used || $licensedata->unlimitedseats == '1'  ) ){
                $valid = 1;
            }

        }

        $batchid = $DB->get_record_sql("SELECT SUM(used_key) as all_used FROM {key_batches} where batch_setting_id = $keyexists->batch_setting_id");


        if($keyexists->keytype == '1' && $keyexists->used_key == '1'){
             echo "The key you entered could not be found. Please try again or contact support for help.";

        }elseif($keyexists->keytype == '2' && $keyexists->noof_batch_uses_allowed <= $batchid->all_used){
              echo "The key you entered could not be found. Please try again or contact support for help.";
        }

        if($valid != '1'){
             echo "The key you entered could not be found. Please try again or contact support for help.";
        }


     }
 }

 if(!$keyexists){
     echo    "The key you entered could not be found. Please try again or contact support for help.";
 }


    }
