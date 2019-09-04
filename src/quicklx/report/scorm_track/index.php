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
 * ("This plugin will display tracking result")
 *
 * @package report_scorm_track
 * @copyright 2018 Syllametrics | support@syllametrics.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */
require_once('../../config.php');
require_once($CFG->dirroot . '/report/scorm_track/filter_form.php'); // Used for scorm_track report filtering.

$page = optional_param('page', 0, PARAM_INT);   // which page to show.
$perpage = optional_param('perpage', 30, PARAM_INT);   // Default per page number of item to show.
$selectpage = optional_param('select', 0, PARAM_INT);   // Show per page drop down.
$lastvisitfrom = optional_param('lastvisitfrom', '', PARAM_RAW);
$lastvisitto = optional_param('lastvisitto', '', PARAM_RAW);
$company = optional_param('company', '', PARAM_RAW);
$coursename = optional_param('coursename', '', PARAM_RAW);
$clientsite = optional_param('clientsite', '', PARAM_RAW);
$clientip = optional_param('clientip', '', PARAM_RAW);
$sort = optional_param('sort', '', PARAM_RAW);
$dir = optional_param('dir', 'ASC', PARAM_RAW);



$cn_get = implode("','", $coursename);
$coursename =  "('".$cn_get."')";
if(isset($_GET['coursename'])){
	$coursename  = $_GET['coursename'];
}





$url = new moodle_url('/report/scorm_track/index.php');
$PAGE->requires->css($CFG->dirroot . '/report/scorm_track/style.css');

$mform = new scormtrack();


$limit = '';
$query_param = '';
$pagelimit  = array('0' => 'Choose...','1' => '30','2' => '50','3' => '100','4' => '200');

if ($selectpage != 0) {
    $perpage = $pagelimit[$selectpage];
}

$from  = $page*$perpage;
$to =  ($page+1)*$perpage;
$limit .= " LIMIT $from,$to";
    $filterdata = $mform->get_data();

   
    if ($filterdata->lastvisitfrom != 0 || $filterdata->lastvisitto != 0 || ($lastvisitfrom != '') || ($lastvisitto != '')) {
        if(isset($filterdata->lastvisitfrom))
     {
        $lastvisitfrom = $filterdata->lastvisitfrom;
         }
          if(isset($filterdata->lastvisitto))
     {
        $lastvisitto = $filterdata->lastvisitto;
         }

    if(!isset($lastvisitfrom) || !isset($lastvisitto)){
        if($filterdata->lastvisitfrom == 0 )
        {
            $query_param .= " where timemodified <= $lastvisitto ";
        }
        elseif($filterdata->lastvisitto == 0)
        {
            $query_param .= " where timemodified >= $lastvisitfrom ";
        }
        else
        {
            $query_param .= " where timemodified BETWEEN $lastvisitfrom AND $lastvisitto ";
        }
    }else{
          if($lastvisitfrom == 0 )
        {
            $query_param .= " where timemodified <= $lastvisitto ";
        }
        elseif($lastvisitto == 0)
        {
            $query_param .= " where timemodified >= $lastvisitfrom ";
        }
        else
        {
            $query_param .= " where timemodified BETWEEN $lastvisitfrom AND $lastvisitto ";
        }
    }
        
        
        
    }
    

    if(($filterdata->company != NULL && $filterdata->company != 'All') || ($company != NULL && $company != 'All'))
    {
       
         if($filterdata->company != NULL)
           {
                $company = $filterdata->company;
            }
        if(empty($query_param))
        {
             $query_param .= " where company = '".$company."'";
        
        }
        else
        {
             $query_param .= " AND company = '".$company."'";
            
        }

    }


    if ((!empty($filterdata->coursename) && ($coursename != "('All')") && ($coursename != "('')")) || ($_GET['coursename'] != '') && ($coursename != "('All')" && ($coursename != "('')"))) 
    {
        $cn_set = implode("','", $filterdata->coursename);

        if(isset($filterdata->coursename))
            {
                
                $cn =  "('".$cn_set."')";
            }
       if(isset($_GET['coursename']))
            {
                
                $cn= $_GET['coursename'];
               
            }
     
     

        if(empty($query_param))
        {
             $query_param .= "where coursename IN ".$cn;
         }
        else
        {
             $query_param .= " AND coursename IN ".$cn;
        
        }
       
    }

    if (!empty($filterdata->clientsite) || ($clientsite != '')) {
         if(isset($filterdata->clientsite))
            {
                $clientsite = $filterdata->clientsite;
            }
        if(empty($query_param))
        {
            $query_param .= " where sitelink LIKE '%" . trim($clientsite) . "%'";
        }
        else
        {
            $query_param .= " AND sitelink LIKE '%" . trim($clientsite) . "%'";
        }
    }

    if (!empty($filterdata->clientip) || ($clientip != '')) {
        if(isset($filterdata->clientip))
            {
                $clientip = $filterdata->clientip;
            }
        if(empty($query_param))
        {
            $query_param .= " where publicip LIKE '%" . trim($clientip) . "%'";
        }
        else
        {
             $query_param .= " AND publicip LIKE '%" . trim($clientip) . "%'";
            
        }
       
    }


    if($sort != ''){

        switch ($sort) {
            case "id":
            $query_param .= "order by id  $dir ";
            break;
            case "coursename":
            $query_param .= "order by coursename  $dir ";
            break;
            case "company":
            $query_param .= "order by company $dir ";
            break;
            case "clientsite":
            $query_param .= "order by sitelink $dir ";
            break;
            case "sitetitle":
            $query_param .= "order by sitetitle $dir ";
            break;
            case "privateip":
            $query_param .= "order by privateip $dir ";
            break;
            case "publicip":
            $query_param .= "order by publicip $dir ";
            break;
            case "lastvisit":
            $query_param .= "order by timemodified $dir ";
            break;
            case "pagehit":
            $query_param .= "order by count $dir ";
            break;
        }
   //$columndir =  $dir == "ASC" ? "DESC" : "ASC";
  // $dir = $columndir;
    }

//}

$sortb = $_GET['sort'];
$dirg = $_GET['dir'];
if(isset($sortb) && isset($dirg)){
$newurl = "index.php?lastvisitfrom=".$lastvisitfrom."&lastvisitto=".$lastvisitto."&perpage=".$perpage."&company=".urlencode($company)."&coursename=".urlencode($coursename)."&clientsite=".urlencode($clientsite)."&clientip=".$clientip."&page=".$page."&sort=".$sortb."&dir=".$dirg;
}else{
    $newurl = "index.php?lastvisitfrom=".$lastvisitfrom."&lastvisitto=".$lastvisitto."&perpage=".$perpage."&company=".urlencode($company)."&coursename=".urlencode($coursename)."&clientsite=".urlencode($clientsite)."&clientip=".$clientip."&page=".$page;
}
//echo $dir;
//exit;
if(!isset($_GET['sort'])){
    $dir = "DESC";
    $sortSno = $newurl."&sort=id&dir=".$dir;
}elseif(isset($_GET['sort']) && $_GET['sort'] != 'id'){
    $dir = "DESC";
    $sortSno = $newurl."&sort=id&dir=".$dir;
}elseif(isset($_GET['sort']) && $_GET['sort'] == 'id' && $_GET['dir'] == "ASC"){
    $dir = "DESC";
    $sortSno = $newurl."&sort=id&dir=".$dir;
}else{
    $dir = "ASC";
    $sortSno = $newurl."&sort=id&dir=".$dir;
}

if(!isset($_GET['sort'])){
    $dir = "DESC";
    $sortCname = $newurl."&sort=coursename&dir=".$dir;
}elseif(isset($_GET['sort']) && $_GET['sort'] != 'coursename'){
    $dir = "DESC";
    $sortCname = $newurl."&sort=coursename&dir=".$dir;
}elseif(isset($_GET['sort']) && $_GET['sort'] == 'coursename' && $_GET['dir'] == "ASC"){
    $dir = "DESC";
    $sortCname = $newurl."&sort=coursename&dir=".$dir;
}else{
    $dir = "ASC";
    $sortCname = $newurl."&sort=coursename&dir=".$dir;
}

if(!isset($_GET['sort'])){
    $dir = "DESC";
    $sortCompany = $newurl."&sort=company&dir=".$dir;
}elseif(isset($_GET['sort']) && $_GET['sort'] != 'company'){
    $dir = "DESC";
    $sortCompany = $newurl."&sort=company&dir=".$dir;
}elseif(isset($_GET['sort']) && $_GET['sort'] == 'company' && $_GET['dir'] == "ASC"){
    $dir = "DESC";
    $sortCompany = $newurl."&sort=company&dir=".$dir;
}else{
    $dir = "ASC";
    $sortCompany = $newurl."&sort=company&dir=".$dir;
}

if(!isset($_GET['sort'])){
    $dir = "DESC";
    $clientsite = $newurl."&sort=clientsite&dir=".$dir;
}elseif(isset($_GET['sort']) && $_GET['sort'] != 'clientsite'){
    $dir = "DESC";
    $clientsite = $newurl."&sort=clientsite&dir=".$dir;
}elseif(isset($_GET['sort']) && $_GET['sort'] == 'clientsite' && $_GET['dir'] == "ASC"){
    $dir = "DESC";
    $clientsite = $newurl."&sort=clientsite&dir=".$dir;
}else{
    $dir = "ASC";
    $clientsite = $newurl."&sort=clientsite&dir=".$dir;
}

if(!isset($_GET['sort'])){
    $dir = "DESC";
    $sitetitle = $newurl."&sort=sitetitle&dir=".$dir;
}elseif(isset($_GET['sort']) && $_GET['sort'] != 'sitetitle'){
    $dir = "DESC";
    $sitetitle = $newurl."&sort=sitetitle&dir=".$dir;
}elseif(isset($_GET['sort']) && $_GET['sort'] == 'sitetitle' && $_GET['dir'] == "ASC"){
    $dir = "DESC";
    $sitetitle = $newurl."&sort=sitetitle&dir=".$dir;
}else{
    $dir = "ASC";
    $sitetitle = $newurl."&sort=sitetitle&dir=".$dir;
}

if(!isset($_GET['sort'])){
    $dir = "DESC";
    $privateip = $newurl."&sort=privateip&dir=".$dir;
}elseif(isset($_GET['sort']) && $_GET['sort'] != 'privateip'){
    $dir = "DESC";
    $privateip = $newurl."&sort=privateip&dir=".$dir;
}elseif(isset($_GET['sort']) && $_GET['sort'] == 'privateip' && $_GET['dir'] == "ASC"){
    $dir = "DESC";
    $privateip = $newurl."&sort=privateip&dir=".$dir;
}else{
    $dir = "ASC";
    $privateip = $newurl."&sort=privateip&dir=".$dir;
}

if(!isset($_GET['sort'])){
    $dir = "DESC";
    $publicip = $newurl."&sort=publicip&dir=".$dir;
}elseif(isset($_GET['sort']) && $_GET['sort'] != 'publicip'){
    $dir = "DESC";
    $publicip = $newurl."&sort=publicip&dir=".$dir;
}elseif(isset($_GET['sort']) && $_GET['sort'] == 'publicip' && $_GET['dir'] == "ASC"){
    $dir = "DESC";
    $publicip = $newurl."&sort=publicip&dir=".$dir;
}else{
    $dir = "ASC";
    $publicip = $newurl."&sort=publicip&dir=".$dir;
}

if(!isset($_GET['sort'])){
    $dir = "DESC";
    $lastvisit = $newurl."&sort=lastvisit&dir=".$dir;
}elseif(isset($_GET['sort']) && $_GET['sort'] != 'lastvisit'){
    $dir = "DESC";
    $lastvisit = $newurl."&sort=lastvisit&dir=".$dir;
}elseif(isset($_GET['sort']) && $_GET['sort'] == 'lastvisit' && $_GET['dir'] == "ASC"){
    $dir = "DESC";
    $lastvisit = $newurl."&sort=lastvisit&dir=".$dir;
}else{
    $dir = "ASC";
    $lastvisit = $newurl."&sort=lastvisit&dir=".$dir;
}

if(!isset($_GET['sort'])){
    $dir = "DESC";
    $pagehit = $newurl."&sort=pagehit&dir=".$dir;
}elseif(isset($_GET['sort']) && $_GET['sort'] != 'pagehit'){
    $dir = "DESC";
    $pagehit = $newurl."&sort=pagehit&dir=".$dir;
}elseif(isset($_GET['sort']) && $_GET['sort'] == 'pagehit' && $_GET['dir'] == "ASC"){
    $dir = "DESC";
    $pagehit = $newurl."&sort=pagehit&dir=".$dir;
}else{
    $dir = "ASC";
    $pagehit = $newurl."&sort=pagehit&dir=".$dir;
}


//echo $query_param;

//asc & desc icon
if($_GET['dir'] == "ASC" && $_GET['sort'] == "id"){
    $columnicon = "up";
      $columniconid = " <img src=\"" . $OUTPUT->image_url('t/' . $columnicon) . "\" alt=\"\" />";
     

 }elseif($_GET['dir'] == "DESC" && $_GET['sort'] == "id"){
    $columnicon = "down";
    $columniconid = " <img src=\"" . $OUTPUT->image_url('t/' . $columnicon) . "\" alt=\"\" />";
   
 }elseif($_GET['dir'] == "ASC" && $_GET['sort'] == "coursename"){
    $columnicon = "up";
      $columniconcn = " <img src=\"" . $OUTPUT->image_url('t/' . $columnicon) . "\" alt=\"\" />";
     

 }elseif($_GET['dir'] == "DESC" && $_GET['sort'] == "coursename"){
    $columnicon = "down";
    $columniconcn = " <img src=\"" . $OUTPUT->image_url('t/' . $columnicon) . "\" alt=\"\" />";
   
 }elseif($_GET['dir'] == "ASC" && $_GET['sort'] == "company"){
    $columnicon = "up";
      $columniconco = " <img src=\"" . $OUTPUT->image_url('t/' . $columnicon) . "\" alt=\"\" />";
     

 }elseif($_GET['dir'] == "DESC" && $_GET['sort'] == "company"){
    $columnicon = "down";
    $columniconco = " <img src=\"" . $OUTPUT->image_url('t/' . $columnicon) . "\" alt=\"\" />";
   
 }elseif($_GET['dir'] == "ASC" && $_GET['sort'] == "clientsite"){
    $columnicon = "up";
      $columniconcs = " <img src=\"" . $OUTPUT->image_url('t/' . $columnicon) . "\" alt=\"\" />";
     

 }elseif($_GET['dir'] == "DESC" && $_GET['sort'] == "clientsite"){
    $columnicon = "down";
    $columniconcs = " <img src=\"" . $OUTPUT->image_url('t/' . $columnicon) . "\" alt=\"\" />";
   
 }elseif($_GET['dir'] == "ASC" && $_GET['sort'] == "sitetitle"){
    $columnicon = "up";
      $columniconsite = " <img src=\"" . $OUTPUT->image_url('t/' . $columnicon) . "\" alt=\"\" />";
     

 }elseif($_GET['dir'] == "DESC" && $_GET['sort'] == "sitetitle"){
    $columnicon = "down";
    $columniconsite = " <img src=\"" . $OUTPUT->image_url('t/' . $columnicon) . "\" alt=\"\" />";
   
 }elseif($_GET['dir'] == "ASC" && $_GET['sort'] == "privateip"){
    $columnicon = "up";
      $columniconpi = " <img src=\"" . $OUTPUT->image_url('t/' . $columnicon) . "\" alt=\"\" />";
     

 }elseif($_GET['dir'] == "DESC" && $_GET['sort'] == "privateip"){
    $columnicon = "down";
    $columniconpi = " <img src=\"" . $OUTPUT->image_url('t/' . $columnicon) . "\" alt=\"\" />";
   
 }elseif($_GET['dir'] == "ASC" && $_GET['sort'] == "publicip"){
    $columnicon = "up";
      $columniconpuip = " <img src=\"" . $OUTPUT->image_url('t/' . $columnicon) . "\" alt=\"\" />";
     

 }elseif($_GET['dir'] == "DESC" && $_GET['sort'] == "publicip"){
    $columnicon = "down";
    $columniconpuip = " <img src=\"" . $OUTPUT->image_url('t/' . $columnicon) . "\" alt=\"\" />";
   
 }elseif($_GET['dir'] == "ASC" && $_GET['sort'] == "lastvisit"){
    $columnicon = "up";
      $columniconlv = " <img src=\"" . $OUTPUT->image_url('t/' . $columnicon) . "\" alt=\"\" />";
     

 }elseif($_GET['dir'] == "DESC" && $_GET['sort'] == "lastvisit"){
    $columnicon = "down";
    $columniconlv = " <img src=\"" . $OUTPUT->image_url('t/' . $columnicon) . "\" alt=\"\" />";
   
 }elseif($_GET['dir'] == "ASC" && $_GET['sort'] == "pagehit"){
    $columnicon = "up";
      $columniconph = " <img src=\"" . $OUTPUT->image_url('t/' . $columnicon) . "\" alt=\"\" />";
     

 }elseif($_GET['dir'] == "DESC" && $_GET['sort'] == "pagehit"){
    $columnicon = "down";
    $columniconph = " <img src=\"" . $OUTPUT->image_url('t/' . $columnicon) . "\" alt=\"\" />";
   
 }

$PAGE->set_url($url);
$PAGE->set_pagelayout(get_string('report', 'report_scorm_track'));

require_login();

$PAGE->set_title(get_string('scormtrackreport', 'report_scorm_track'));
$PAGE->set_heading(get_string('scormtrackreport', 'report_scorm_track'));

echo $OUTPUT->header();
//print_object($OUTPUT);

//echo $query_param;
//echo $limit;
global $DB;
$data = $DB->get_records_sql("SELECT * FROM {report_scorm_download} $query_param $limit ");
$sno = $from+1;
$table = new html_table();
//$idurl             = new moodle_url('/blocks/iomad_company_admin/company_user_create_form.php');
$table->head = array(
    $OUTPUT->action_link($sortSno,'S.No'.$columniconid), 
    $OUTPUT->action_link($sortCname,'Course Name'.$columniconcn),
    $OUTPUT->action_link($sortCompany,'Company'.$columniconco),  
    $OUTPUT->action_link($clientsite,'Client\'s Site'.$columniconcs),
    $OUTPUT->action_link($sitetitle,'Site Title'.$columniconsite) , 
    $OUTPUT->action_link($privateip,'Client\'s Private IP'.$columniconpi),
    $OUTPUT->action_link($publicip,'Client\'s Public IP'.$columniconpuip),
    $OUTPUT->action_link($lastvisit,'Last Visit'.$columniconlv),
    $OUTPUT->action_link($pagehit, '# of Page Hit'.$columniconph));
foreach ($data as $records) {
    $tabcoursename = htmlspecialchars_decode($records->coursename);
    $tabcompany = htmlspecialchars_decode($records->company);
    $tabsitelink = htmlspecialchars_decode($records->sitelink);
    $tabsitetitle = $records->sitetitle;
    $tabprivateip = $records->privateip;
    $tabpublicip = $records->publicip;
    $tabtimemodified = $records->timemodified;
    $tabpagehit = $records->count;
    $table->data[] = array($sno, $tabcoursename, $tabcompany, $tabsitelink, $tabsitetitle, $tabprivateip,
        $tabpublicip, date("d M, Y", $tabtimemodified), $tabpagehit);
    $sno++;
}

$mform->display(); // Display Form.

$totalcount =  count($DB->get_records_sql("SELECT * FROM {report_scorm_download} $query_param "));

if($totalcount > $perpage)
{
    echo $OUTPUT->paging_bar($totalcount, $page, $perpage, $newurl);
}


//Dropdown for page select.


$pageselect         = new single_select(new moodle_url($newurl), 'select', $pagelimit, "$selectpage", '');
$pageselect->label  = get_string('showperpage', 'report_scorm_track');
$selectoutput     = html_writer::tag('div', $OUTPUT->render($pageselect), array('id' => 'selectperpage','class' => 'showperpageclass'));

echo $selectoutput;













echo html_writer::table($table);
echo $OUTPUT->footer();
