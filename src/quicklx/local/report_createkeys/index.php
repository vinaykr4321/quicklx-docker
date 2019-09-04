<?php
require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->libdir . '/completionlib.php');
require_once($CFG->libdir . '/excellib.class.php');
require_once($CFG->libdir . '/tcpdf/tcpdf.php');
require_once($CFG->dirroot . '/blocks/iomad_company_admin/lib.php');
require_once($CFG->dirroot . '/local/base/locallib.php');
require_once('locallib.php');



// Params.
$page = optional_param('page', 0, PARAM_INT);
$perpage = optional_param('perpage', 20, PARAM_INT);        // How many user per page.
$daterange = optional_param('daterange', 'no', PARAM_TEXT);
$urldatefrom = optional_param('urldatefrom', null, PARAM_INT);
$urldateto = optional_param('urldateto', null, PARAM_INT);
$createdkeys = optional_param('createdkeys', 0, PARAM_INTEGER);

if (!$urldatefrom)
    $datefromraw = optional_param_array('datefrom', null, PARAM_INT);
if (!$urldateto)
    $datetoraw = optional_param_array('dateto', null, PARAM_INT);


require_login($SITE);
$context = context_system::instance();
iomad::require_capability('local/report_createkeys:view', $context);


$params = array();
$urlsubgroup = optional_param('urlsubgroup', null, PARAM_RAW);
if (!$urlsubgroup)
    $subgroupraw = optional_param_array('subgroup', null, PARAM_INT);

if (isset($subgroupraw)) {
    if (is_array($subgroupraw)) {
        $subgroup = implode(',', $subgroupraw);
    }
} else {
    if (isset($urlsubgroup)) {
        $subgroup = $urlsubgroup;
    }
}
if (isset($subgroup)) {
    $params['subgroup'] = $subgroup;
    $params['urlsubgroup'] = $subgroup;
}
$urlorganization = optional_param('urlorganization', null, PARAM_RAW);
if (!$urlorganization)
    $organizationraw = optional_param_array('organization', null, PARAM_INT);

if (isset($organizationraw)) {
    if (is_array($organizationraw)) {
        $organization = implode(',', $organizationraw);
    }
} else {
    if (isset($urlorganization)) {
        $organization = $urlorganization;
    }
}




if ($page) {
    $params['page'] = $page;
}
if ($perpage) {
    $params['perpage'] = $perpage;
}

if ($daterange) {
    $params['daterange'] = $daterange;
}



if (isset($datefromraw)) {
    if (is_array($datefromraw)) {
        $datefrom = mktime(0, 0, 0, $datefromraw['month'], $datefromraw['day'], $datefromraw['year']);
    } else {
        $datefrom = $datefromraw;
    }
} else {
    if (isset($urldatefrom)) {
        $params['urldatefrom'] = $urldatefrom;
        $datefrom = $urldatefrom;
    } else
        $datefrom = strtotime(date('Y-m-d', strtotime('today - 30 days')));
}
$params['datefrom'] = $datefrom;
$params['urldatefrom'] = $datefrom;

if (isset($datetoraw)) {
    if (is_array($datetoraw)) {
        $dateto = mktime(23, 59, 59, $datetoraw['month'], $datetoraw['day'], $datetoraw['year']);
    } else {
        $dateto = $datetoraw;
    }
} else {
    if (isset($urldateto)) {
        $params['urldateto'] = $urldateto;
        $dateto = $urldateto;
    } else
        $dateto = time();
}
$params['dateto'] = $dateto;
$params['urldateto'] = $dateto;
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
$PAGE->requires->css("/local/base/css/bootstrap-timepicker.min.css");
$PAGE->requires->js('/local/base/js/bootstrap-timepicker.min.js');
$PAGE->requires->js('/local/report_createkeys/customcreatekeys.js');



// get output renderer                                                                                                                                                                                         
$output = $PAGE->get_renderer('block_iomad_company_admin');

// Javascript for fancy select.
// Parameter is name of proper select form element followed by 1=submit its form
$PAGE->requires->js_call_amd('block_iomad_company_admin/department_select', 'init', array('departmentid', 1, optional_param('departmentid', 0, PARAM_INT)));

// Set the page heading.
$PAGE->set_heading(get_string('plugintype', 'local_report_createkeys') . " - $strcompletion");

// Get the renderer.
$output = $PAGE->get_renderer('block_iomad_company_admin');

// Set the companyid


// Set the url.
company_admin_fix_breadcrumb($PAGE, $strcompletion, $url);

$url = new moodle_url('/local/report_createkeys/index.php', $params);

// Only print the header if we are not downloading.
echo $output->header();




$reporthead = 'Registration Keys';
    $reportdaterange = 'Date Range: ';
    $reportdate = date('m/d/Y', $params['urldatefrom']) . ' to ' . date('m/d/Y', $params['urldateto']);


echo '<h2>' . $reporthead . '</h2>';
// Set up the filter form.
$mform = new iomad_login_activity_filter_form($params);
$mform->set_data($params);
//$data = $mform->get_data();
// Display a message if keys are created..
if ($createdkeys) {
    echo html_writer::start_tag('div', array('class' => "alert alert-success"));
    echo get_string('successmessage', 'local_report_createkeys');
    echo "</div>";
}

//print_r($data );
$mform->display();

echo '<hr style="border-top: 1px solid #151414;">';


$getdata = report_createkeys::getdata($params);
$params['download'] = 'download';
$exportdata = report_createkeys::getdata($params);


if ($exportdata) {
    $array_excel = array();
    $array_pdf = array();
    $array_excel[] = $array_pdf[] = array($reporthead);
    $array_excel[] = array();
    $array_excel[] = array($reportdaterange);
    $array_excel[] = array($reportdate);
    $array_excel[] = array();
    $array_pdf[] = array($reportdaterange . '<br>' . $reportdate);
    $array_excel[] = array(
        get_string('key', 'local_report_createkeys'),
        get_string('batchname', 'local_report_createkeys'),
        get_string('dateissued', 'local_report_createkeys'),
        get_string('dateused', 'local_report_createkeys'),
        get_string('company', 'local_report_createkeys'),
        get_string('licenses', 'local_report_createkeys'),
        get_string('used', 'local_report_createkeys')
    );

    $tablepdf = '<table class="generaltable" id="ReportTable">
                    <thead >
                    <tr style="background-color: rgb(203, 205, 208);">
                    <th  style="text-align:left;" >' . get_string('key', 'local_report_createkeys') . '</th>
                    <th  style="text-align:center;" >' . get_string('batchname', 'local_report_createkeys') . '</th>
                    <th  style="text-align:left;" >' . get_string('dateissued', 'local_report_createkeys') . '</th>
                    <th  style="text-align:left;" >' . get_string('dateused', 'local_report_createkeys') . '</th>
                    <th colspan="2"  style="text-align:left;" >' . get_string('company', 'local_report_createkeys') . '</th>
                    <th  style="text-align:left;" >' . get_string('licenses', 'local_report_createkeys') . '</th>
                    <th  style="text-align:left;" >' . get_string('used', 'local_report_createkeys') . '</th>
                    </tr>
                    </thead>
                    <tbody> ';
    $i = 1;

    foreach ($exportdata as $datarecords) {
        $companyname = $DB->get_record_sql("SELECT id,name from {company} where id = $datarecords->companyid");
        $licenseids = $DB->get_record_sql("SELECT GROUP_CONCAT(licenseid) as licenseid FROM {key_batch_license} where key_batches_id = $datarecords->id");

         $licensename = $DB->get_record_sql("SELECT GROUP_CONCAT(name) as licenselist from {companylicense} where id IN ($licenseids->licenseid)");

          if($datarecords->keytype == '2'){
            $type = 'Unlimited';
        }elseif($datarecords->keytype == '1'){
            $type = 'Limited';
        }

         if(isset($datarecords->expiry) && $datarecords->expiry != 'NULL'){
            $expirydate = date("m/d/Y", $datarecords->expiry);
        }else{
            $expirydate = 'NULL';
        }


        $array_excel[] = array(
            $datarecords->keysvalue,
            $datarecords->name.'('.$type.')',
            date("m/d/Y", $datarecords->timecreated),
            $expirydate,
            $companyname->name,
            $licensename->licenselist,
            $datarecords->used_key,

        );


        if ($i % 2 == 0)
            $style = 'background-color: #ece9e9;';
        else
            $style = '';
        $tablepdf  .= '<tr style="' . $style . '">
                                <td  style="text-align:left;">' . $datarecords->keysvalue . ' </td>
                                <td  style="text-align:center;">' . $datarecords->name.'('.$type.')' . '</td>
                                <td  style="text-align:left;">' . $datarecords->timecreated . ' </td>
                                <td colspan="2"  style="text-align:left;">' . $expirydate . ' </td>
                                <td style="text-align:left;">' . $companyname->name . '</td>
                                <td style="text-align:left;">' . $licensename->licenselist . '</td>
                                <td style="text-align:left;">' . $datarecords->used_key . '</td>
                                </tr>';

        $i++;
    }
    $tablepdf .= '  </tbody>
                </table>
                ';
    $array_pdf[] = $tablepdf;
}



if ($getdata) {

    // Set up the course  table.
    $keysvalue = new html_table();
    $keysvalue->id = 'ReportTable';
    $keysvalue->head = array(
        get_string('key', 'local_report_createkeys'),
        get_string('batchname', 'local_report_createkeys'),
        get_string('dateissued', 'local_report_createkeys'),
        get_string('dateused', 'local_report_createkeys'),
        get_string('company', 'local_report_createkeys'),
        get_string('licenses', 'local_report_createkeys'),
        get_string('used', 'local_report_createkeys'),
    );
    $keysvalue->align = array('left', 'center', 'center', 'center', 'center', 'center', 'center');

    foreach ($getdata as $datarecords) {

        $companyname = $DB->get_record_sql("SELECT id,name from {company} where id = $datarecords->companyid");
        $licenseids = $DB->get_record_sql("SELECT GROUP_CONCAT(licenseid) as licenseid FROM {key_batch_license} where key_batches_id = $datarecords->id");
        $licensename = $DB->get_record_sql("SELECT GROUP_CONCAT(name) as licenselist from {companylicense} where id IN ($licenseids->licenseid)");
        if($datarecords->keytype == '2'){
            $type = '<b>Unlimited</b>';
        }elseif($datarecords->keytype == '1'){
            $type = '<b>Limited</b>';
        }

        if(isset($datarecords->expiry) && $datarecords->expiry != 'NULL'){
            $expirydate = date("m/d/Y", $datarecords->expiry);
        }else{
            $expirydate = 'NULL';
        }


        $keysvalue->data[] = array(
            $datarecords->keysvalue,
            $datarecords->name.'('.$type.')',
            date("m/d/Y", $datarecords->timecreated),
            $expirydate,
            $companyname->name,
            $licensename->licenselist,
            $datarecords->used_key,
        );
    }

    echo '<div id="exportbuttons" style="text-align: center;">  ';

    $PAGE->set_url('/local/base/download_excel.php', array('name' => 'createkeys'));
    echo "<br>" . $OUTPUT->single_button(new moodle_url($PAGE->url, array('exceldata' => json_encode($array_excel))),  get_string("downloadexcel", 'local_base'));

    $PAGE->set_url('/local/base/download_csv.php', array('name' => 'createkeys'));
    echo $OUTPUT->single_button(new moodle_url($PAGE->url, array('csvdata' => json_encode($array_excel))), get_string("downloadcsv", 'local_base'));

    $PAGE->set_url('/local/base/download_pdf.php', array('name' => 'createkeys'));
    echo $OUTPUT->single_button(new moodle_url($PAGE->url, array('pdfdata' => json_encode($array_pdf))),  get_string("downloadpdf", 'local_base'));


    //echo $report_button = base::report_button();

    echo "</div><br />";

    $perpageopt = array('20' => 20, '30' => 30, '40' => 40, '50' => 50, '75' => 75, '100' => 100);
    echo '<div class="show-per-page" >
        <label  for="id_perpage">
            Show per page:
        </label>
    
        <select name="perpage" id="id_perpage">';
    foreach ($perpageopt as $key => $value) {
        if ($key == $perpage)
            echo ' <option value="' . $key . '" selected>' . $value . '</option>';
        else
            echo ' <option value="' . $key . '" >' . $value . '</option>';
    }

    echo  '</select>           
                </div>';

    $countdata = report_createkeys::getdatacount($params);


    echo $output->paging_bar($countdata, $page, $perpage, new moodle_url('/local/report_createkeys/index.php', $params));
    echo "<br />";
    echo html_writer::table($keysvalue);
    echo "<br />";
    echo $output->paging_bar($countdata, $page, $perpage, new moodle_url('/local/report_createkeys/index.php', $params));
    echo "<br />";
    echo '<div id="exportbuttons" style="text-align: center;">  ';

    $PAGE->set_url('/local/base/download_excel.php', array('name' => 'learnertranscript'));
    echo "<br>" . $OUTPUT->single_button(new moodle_url($PAGE->url, array('exceldata' => json_encode($array_excel))),  get_string("downloadexcel", 'local_base'));

    $PAGE->set_url('/local/base/download_csv.php', array('name' => 'learnertranscript'));
    echo $OUTPUT->single_button(new moodle_url($PAGE->url, array('csvdata' => json_encode($array_excel))), get_string("downloadcsv", 'local_base'));

    $PAGE->set_url('/local/base/download_pdf.php', array('name' => 'learnertranscript'));
    echo $OUTPUT->single_button(new moodle_url($PAGE->url, array('pdfdata' => json_encode($array_pdf))),  get_string("downloadpdf", 'local_base'));


    //echo $report_button = base::report_button();

    echo "</div><br />";
} else {

    echo 'There are no results. Please try a different search.';
}

echo $output->footer();
