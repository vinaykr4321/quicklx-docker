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
 * Reports to display
 *
 * @package report_userreg  
 * @copyright 2018 Syllametrics | support@syllametrics.com  
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later 
 */

require_once('../../config.php');
require_once('classes/user.php');
require_once($CFG->dirroot.'/lib/moodlelib.php');

require_once($CFG->dirroot.'/report/userreg/filter_form.php');  // used for user report filtering
require_login();
$PAGE->requires->css(new moodle_url('/report/userreg/style.css'),true);
$PAGE->requires->js(new moodle_url('/report/userreg/script.js'),true);
$companyid      = optional_param('company', 0, PARAM_INT);
$selectPerPage  = optional_param('select', 0, PARAM_INT);
$orderbyName    = optional_param('sort', '', PARAM_CLEAN);
$sortingOrder   = optional_param('dir','ASC', PARAM_CLEAN);
$page           = optional_param('page', 0, PARAM_INT);
$perpage        = optional_param('perpage', 10, PARAM_INT);

$PAGE->set_title(get_string('pluginname', 'report_userreg'));

$baseurl = new moodle_url('/report/userreg/index.php', array('sort' => $orderbyName, 'dir' => $sortingOrder, 'perpage' => $perpage, 'company'=>$companyid,'select'=>$selectPerPage));


$userObj = new user();

//require_once($CFG->libdir . '/adminlib.php');

$output = $PAGE->get_renderer('block_iomad_company_admin');

if($companyid != 0)
{
    $selectedcompany = $companyid;  
}

$companylist = company::get_companies_select();
array_unshift($companylist, 'All Companies');
$select = new single_select(new moodle_url('/report/userreg/index.php'), 'company', $companylist,$selectedcompany,'');
$select->label = 'Select a Company';//get_string('selectacompany', 'block_iomad_company_selector');
$select->formid = 'choosecompany';


//Dropdown for page select.
$selectPage = $selectPerPage;
$pagelimit = array('0'=>'Choose...','1'=>'30','2'=>'50','3'=>'100','4'=>'200');


$pageselect = new single_select(new moodle_url("/report/userreg/index.php?company=$companyid&sort=$orderbyName&dir=$sortingOrder&perpage=$perpage"), 'select', $pagelimit,$selectPage,'');
$pageselect->label = 'Show per page';
$pageselect->formid = 'showperpage';




// Display the backup report
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string("userreg","report_userreg"));
echo $OUTPUT->box_start();

    // Display select company dropdown
    echo $fwselectoutput = html_writer::tag('div', $OUTPUT->render($select), array('id' => 'iomad_company_selector_new'));
    


    $actionQuery = "index.php?company=$companyid&sort=$orderbyName&dir=$sortingOrder&perpage=$perpage&select=$selectPerPage";
  
    $mform = new userreports($actionQuery);
    $filterData = '';
    if($mform->get_data())
    {
        $filterData = $mform->get_data();
    }
    $mform->display();

    if (!empty($filterData)) 
    {
        $userData = $userObj->user_registration_with_param($filterData->firstname,$filterData->lastname,$filterData->email,$orderbyName,$sortingOrder,$companyid,$selectPerPage,$page,$perpage,$filterData->firstaccessfrom,$filterData->firstaccessto,$filterData->lastaccessfrom,$filterData->lastaccessto);

        $totalUser = $userObj->totalUser($filterData->firstname,$filterData->lastname,$filterData->email,$orderbyName,$sortingOrder,$companyid,$selectPerPage,$page,$perpage,$filterData->firstaccessfrom,$filterData->firstaccessto,$filterData->lastaccessfrom,$filterData->lastaccessto);
        if ($totalUser <= $pagelimit[$selectPerPage]) 
        {
            $totalUser = 0;
        }
        else
        {
            if ($selectPerPage == 0) 
            {
                $perpagenav = 10;
            }
            else
            {
                $perpagenav = $pagelimit[$selectPerPage];
            }
        }
    }
    else
    {
        $userData = $userObj->user_registration($orderbyName,$sortingOrder,$companyid,$selectPerPage,$page,$perpage);

        $totalUser = $userObj->totalUser('','','',$orderbyName,$sortingOrder,$companyid,$selectPerPage,$page,$perpage,'','','','');
        if ($totalUser <= $pagelimit[$selectPerPage]) 
        {
            $totalUser = 0;
        }
        else
        {
            if ($selectPerPage == 0) 
            {
                $perpagenav = 10;
            }
            else
            {
                $perpagenav = $pagelimit[$selectPerPage];
            }
        }
    }
    
    

    if ($sortingOrder == 'ASC') 
    {
        $linkparams['dir'] = 'DESC';
    }
    elseif($sortingOrder == 'DESC')
    {
        $linkparams['dir'] = 'ASC';
    }

    $linkparams['sort'] = 'username';
    $username = new moodle_url('index.php', $linkparams);
    $linkparams['sort'] = 'firstname';
    $firstname = new moodle_url('index.php', $linkparams);
    $linkparams['sort'] = 'lastname';
    $lastname = new moodle_url('index.php', $linkparams);
    $linkparams['sort'] = 'name';
    $organization = new moodle_url('index.php', $linkparams);
    $linkparams['sort'] = 'timecreated';
    $timecreated = new moodle_url('index.php', $linkparams);
    $linkparams['sort'] = 'firstaccess';
    $firstaccess = new moodle_url('index.php', $linkparams);
    $linkparams['sort'] = 'lastaccess';
    $lastaccess = new moodle_url('index.php', $linkparams);
    $linkparams['sort'] = 'email';
    $email = new moodle_url('index.php', $linkparams);
    



    $table = new html_table();
    $table->head = array($output->action_link($username, 'User Name'),$output->action_link($email, 'Email'),$output->action_link($firstname, 'First Name'),$output->action_link($lastname, 'Last Name'),$output->action_link($organization, 'Organization'),$output->action_link($timecreated, 'User created'),$output->action_link($firstaccess, 'First access'),$output->action_link($lastaccess, 'Last access'),'# of Logins');
    $array_excel = array();
    $array_excel[] = array('User Name','Email','First Name','Last Name','Organization','User created','First access','Last access','# of Logins');
 
    //$table->width = "95%";
    $table->data = array();
    if (!empty($userData)) 
    {     
        foreach ($userData as $value) 
        {
        	$value->timecreated = date('m/d/Y',$value->timecreated);
        	$value->firstaccess = date('m/d/Y',$value->firstaccess);
        	$value->lastaccess = date('m/d/Y',$value->lastaccess);
        	if($value->timecreated == '01/01/1970')
        	{
        		$value->timecreated = '';
        	} 

        	if($value->firstaccess == '01/01/1970')
        	{
        		$value->firstaccess = '';
        	} 
        	if($value->lastaccess == '01/01/1970')
        	{
        		$value->lastaccess = '';
        	} 
        	
        	$table->data[] = array($value->username,$value->email,$value->firstname,$value->lastname,$value->name,$value->timecreated,$value->firstaccess,$value->lastaccess,$userObj->countUserLoggedin($value->id));
            $array_excel[] = array($value->username,$value->email,$value->firstname,$value->lastname,$value->name,$value->timecreated,$value->firstaccess,$value->lastaccess,$userObj->countUserLoggedin($value->id));
        }
        
        echo $OUTPUT->paging_bar($totalUser, $page, $perpagenav, $baseurl,'p');
        // Display show per page dropdown
        echo $ppselectoutput = html_writer::tag('div', $OUTPUT->render($pageselect), array('id' => 'selectperpagenew'));
        flush();
        echo html_writer::table($table);
    }
    else
    {
        Echo 'No record found.';
    }

    $PAGE->set_url('/report/userreg/download_excel.php');
    echo "<br>".$OUTPUT->single_button(new moodle_url($PAGE->url, array('exceldata' => json_encode($array_excel))), 'Export Excel');
    
    $PAGE->set_url('/report/userreg/download_pdf.php');
    echo $OUTPUT->single_button(new moodle_url($PAGE->url, array('pdfdata' => json_encode($array_excel))), 'Export PDF');

    $PAGE->set_url('/report/userreg/download_csv.php');
    echo $OUTPUT->single_button(new moodle_url($PAGE->url, array('csvdata' => json_encode($array_excel))), 'Export CSV');

    echo '<button type="button" class="btn btn-inverse mr-5" data-toggle="modal" data-target="#emailModal">Email</button>';

echo $OUTPUT->box_end();
echo $OUTPUT->footer();
?>

<div class="container">
  <!-- Trigger the modal with a button -->

  <!-- Modal -->
  <div class="modal fade" id="emailModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Email Configration</h4>
        </div>
        <div class="modal-body">
            <form action="" method="POST">
                
                <div class="col-md-12">
                    <div>
                        <label>Send to email address:</label>
                    </div>
                    <div>
                        <textarea rows="2" cols="50" class="emailto">
                         
                        </textarea>
                    </div>
                    <div class="margin_top_2">
                        <label>Please select user to email: (first/last/username)</label>
                    </div>
                    <div>
                        <select multiple class="form-control width-75" id="sel2">
                            <option value="">1</option>
                            <option value="">2</option>
                            <option value="">3</option>
                            <option value="">4</option>
                            <option value="">5</option>
                        </select>
                    </div>
                    <div class="margin_top_2">
                        <label>Report Format:</label>
                    </div>
                    <div>
                        <input type="radio" name="report"> PDF
                        <input type="radio" name="report"> XLS
                        <input type="radio" name="report"> CSV
                        <input type="radio" name="report" checked="checked"> HTML
                    </div>

                    <div class="margin_top_2">
                        <label>Email Subject:</label>
                    </div>
                    <div>
                        <input type="text" name="subject" size="50">
                    </div>

                    <div class="margin_top_2">
                        <label>Email Body:</label>
                    </div>
                    <div>
                        <textarea rows="5" cols="50" class="email-name">
                         
                        </textarea>
                    </div>
                    <div>
                        <input type="submit" name="submit" value="Email Report">
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
</div>
