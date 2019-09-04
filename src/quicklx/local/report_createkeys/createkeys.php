<?php
require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->libdir . '/completionlib.php');
require_once($CFG->libdir . '/excellib.class.php');
require_once($CFG->libdir . '/tcpdf/tcpdf.php');
require_once($CFG->dirroot . '/blocks/iomad_company_admin/lib.php');
require_once($CFG->dirroot . '/local/base/locallib.php');
require_once('locallib.php');


require_login($SITE);
$context = context_system::instance();
iomad::require_capability('local/report_createkeys:view', $context);

// Url stuff.
$url = new moodle_url('/local/report_createkeys/index.php');
$dashboardurl = new moodle_url('/local/iomad_dashboard/index.php');

// Page stuff:.
$strcompletion = get_string('pluginname', 'local_report_createkeys');
$PAGE->set_url($url);
$PAGE->set_pagelayout('report');
$PAGE->set_title($strcompletion);
$PAGE->requires->css("/local/base/css/styles.css");
$PAGE->requires->css("/local/base/css/chosen.css");
//custom changes_V1
$PAGE->requires->css("/local/report_createkeys/style.css");
$PAGE->requires->jquery();
$PAGE->requires->js('/local/base/js/chosen.jquery.js');
$PAGE->requires->js('/local/base/js/custom.js');
$PAGE->requires->js('/local/report_createkeys/customcreatekeys.js');



// get output renderer
$output = $PAGE->get_renderer('block_iomad_company_admin');
// Set the page heading.
$PAGE->set_heading(get_string('plugintype', 'local_report_createkeys') . " - $strcompletion");
// Get the renderer.
$output = $PAGE->get_renderer('block_iomad_company_admin');
// Set the companyid
$companyid = iomad::get_my_companyid($context);
company_admin_fix_breadcrumb($PAGE, $strcompletion, $url);
$url = new moodle_url('/local/report_createkeys/index.php');
echo $output->header();

$reporthead = 'Generate Keys';
echo '<h2>' . $reporthead . '</h2>';
// Set up the filter form.
$mform = new iomad_createkeys_activity_filter_form();

$mform->display();


$data = $mform->get_data();

if(isset($data) && $data->noofkeys > 50000) {
    redirect(new moodle_url('/local/report_createkeys/createkeys.php?err=limit'));
}
global $DB;
if (isset($data)) {


    $selectlic = $_POST['selectlicense'];

    $batchid = dbkeysettings($data, $selectlic);

    if ($data->keytype == '1' || $data->keytype == '2') {
        $keysloop = 1;
        //$str = 'abcdefghijklmnopqrstuvwxyz0123456789';

        foreach (range(1, $data->noofkeys) as $number) {
            $shuffled = random_string(15);
            $keybatchs .= '('.$batchid.','."'$shuffled'".','.'0'.')'.',';
        }
        $keybatchs_value = rtrim($keybatchs, ',');
        $DB->execute("INSERT INTO {key_batches} (batch_setting_id,keysvalue,used_key) VALUES $keybatchs_value");
            


    }

    redirect(new moodle_url('/local/report_createkeys/index.php?createdkeys=1'));
}

function dbkeysettings($data, $liclist)
{
    global $DB;
  
    if(isset($data->enable) && $data->enable == '1'){
        $exp_date_val = $data->keyexpiredate;
        date_default_timezone_set('America/New_York');
        $exp_date = strtotime(date("Y-m-d 23:59:59",$exp_date_val));
    }else{
        $exp_date = 'NULL';
    }

    if(isset($data->noofused)){
        $noofuse = $data->noofused;
    }else{
        $noofuse = '0';
    }



  


    $id = $DB->insert_record('key_batch_settings', array(
        'name' => $data->batchname,
        'companyid' => $data->company,
        'keytype' => $data->keytype,
        'noofkeys' => $data->noofkeys,
        'expiry' => $exp_date,
        'timecreated' => time(),
        'batch_status' => '1',
        'noof_batch_uses_allowed' => $noofuse
    ));

    foreach ($liclist as  $lid) {
       $DB->insert_record('key_batch_license', array(
            'key_batches_id' => $id,
            'licenseid' => $lid
    ));
    }

    




    return $id;
}

echo $output->footer();