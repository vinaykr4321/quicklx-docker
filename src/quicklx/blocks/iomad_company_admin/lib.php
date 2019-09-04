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
require_once($CFG->dirroot . '/user/selector/lib.php');
require_once($CFG->libdir . '/formslib.php');

require_once($CFG->dirroot . '/local/iomad/lib/company.php');
require_once($CFG->dirroot . '/local/iomad/lib/user.php');

require_once('lib/user_selectors.php');
require_once('lib/course_selectors.php');
require_once('lib/template_selectors.php');
require_once('lib/framework_selectors.php');

/**
 * moodleform subclass that includes simple method for adding company select box
 */

abstract class company_moodleform extends moodleform {
    protected $selectedcompany = 0;

    public function add_company_selector ($required=true) {
        $mform =& $this->_form;

        if ( company_user::is_company_user() ) {
            $mform->addElement('hidden', 'companyid', company_user::companyid());
        } else {
            $companies = company::get_companies_rs();
            $companyoptions = array('' => get_string('selectacompany', 'block_iomad_company_admin'));
            foreach ($companies as $company) {
                if ( company_user::can_see_company( $company->shortname ) ) {
                    $companyoptions[$company->id] = $company->name;
                }
            }
            $companies->close();

            if ( count($companyoptions) == 1 ) {
                $mform->addElement('html', get_string('nocompanies', 'block_iomad_company_admin'));
                return false;
            } else {
                $mform->addElement('select', 'companyid', get_string('company', 'block_iomad_company_admin'), $companyoptions);
                if ($required) {
                    $mform->addRule('companyid', get_string('missingcompany', 'block_iomad_company_admin'),
                                    'required', null, 'client');
                }

                $defaultvalues['companyid'] = array($this->selectedcompany);
                $mform->setDefaults($defaultvalues);
            }
        }
        return true;
    }

    public function add_course_selector($multiselect = true, $rows = 20, $displayevenifnocourses = true) {
        $mform =& $this->_form;

        // Course selector.
        if ( $this->selectedcompany || company_user::is_company_user() ) {
            $courseselector = new current_company_course_selector('courses', array('companyid' => $this->selectedcompany,
                                                                                   'multiselect' => $multiselect,
                                                                                   'departmentid' => $this->departmentid));
        } else {
            $courseselector = new any_course_selector('courses', array('multiselect' => $multiselect,
                                                                       'departmentid' => $this->departmentid));
        }
        $courseselector->set_rows($rows);

        if ( $multiselect ) {
            $label = get_string('selectenrolmentcourses', 'block_iomad_company_admin');
        } else {
            $label = get_string('selectenrolmentcourse', 'block_iomad_company_admin');
        }

        $hascourses = true;
        if (!$displayevenifnocourses) {
            $hascourses = count($courseselector->find_courses(''));
        }

        if ($hascourses) {
            $mform->addElement('html', "<div class='fitem'><div class='fitemtitle'>" . $label . "</div><div class='felement'>");
            $mform->addElement('html', $courseselector->display(true));
            $mform->addElement('html', "</div></div>");

            return $courseselector;
        }

        return false;
    }

    // This is very loosely based on the admin_setting_configcolourpicker class in adminlib.php.
    public function add_colour_picker($name, $previewconfig) {
        global $PAGE, $OUTPUT;
        $mform =& $this->_form;
        $id = "id_" . $name;

        // Variable $cptemplate is adapted from the 'default' template in formslib.php's MoodleQuickForm_Renderer
        // function in MoodleQuickForm_Renderer class.
        // It is adds a {colourpicker} and {preview} tag that is replaced with the $colourpicker and $preview
        // variables below before being passed to the renderer the {advancedimg} {help} bits have been taken
        // out as the rendered doesn't appear to use them in this case.
        $cptemplate = "\n\t\t".'<div class="fitem {advanced}<!-- BEGIN required --> required<!-- END required -->">
                       <div class="fitemtitle"><label>{label}<!-- BEGIN required -->{req}<!-- END required -->
                       </label></div><div class="felement {type}<!-- BEGIN error --> error<!-- END error -->">
                       {colourpicker}<!-- BEGIN error --><span class="error">{error}</span><br />
                       <!-- END error -->{element}{preview}</div></div>';

        // Variable $colourpicker contains the colour picker bits that are to be displayed above the input box.
        $colourpicker = html_writer::start_tag('div', array('class' => 'form-colourpicker defaultsnext'));
        $colourpicker .= html_writer::tag('div', $OUTPUT->pix_icon('i/loading', get_string('loading', 'admin'),
                                          'moodle', array('class' => 'loadingicon')),
                                          array('class' => 'admin_colourpicker clearfix'));

        // Preview contains the bits that are to be displayed below the input box (may just be a div end tag).
        $preview = '';
        if (!empty($previewconfig)) {
            $preview .= html_writer::empty_tag('input', array('type' => 'button',
                                                              'id' => $id.'_preview',
                                                              'value' => get_string('preview'),
                                                              'class' => 'admin_colourpicker_preview'));
        }
        $preview .= html_writer::end_tag('div');

        // Replace {colourpicker} and {preview} in $cptemplate.
        $cptemplate = preg_replace('/\{colourpicker\}/', $colourpicker, $cptemplate);
        $cptemplate = preg_replace('/\{preview\}/', $preview, $cptemplate);

        // Add the input element to the form.
        $PAGE->requires->js_init_call('M.util.init_colour_picker', array($id, $previewconfig));
        $mform->addElement('text', $name, get_string($name, 'block_iomad_company_admin'), array('size' => 7, 'maxlength' => 7));
        $mform->defaultRenderer()->setElementTemplate($cptemplate, $name);
        $mform->setType('shortname', PARAM_NOTAGS);
        $mform->addRule($name, get_string('css_color_format', 'block_iomad_company_admin'), 'regex', '/^#([A-F0-9]{3}){1,2}$/i');
    }
}

/**
 * Form to use as company selector on company_managers_form and company_courses_form
 */
class company_select_form extends company_moodleform {
    protected $title = '';
    protected $description = '';
    protected $submitlabel = null;

    public function __construct($actionurl, $companyid, $submitlabelstring) {
        $this->selectedcompany = $companyid;

        $this->submitlabel = get_string($submitlabelstring, 'block_iomad_company_admin');

        parent::__construct($actionurl);
    }

    public function definition() {
        global $PAGE, $USER;

        if ( !company_user::is_company_user() ) {
            $mform =& $this->_form;

            // Then show the fields about where this block appears.
            $mform->addElement('header', 'header', get_string('company', 'block_iomad_company_admin'));

            if ($this->add_company_selector()) {

                // Make form auto submit on change of selected company.
                $formid = $mform->getAttribute("id");
                $PAGE->requires->js_init_call('M.util.init_select_autosubmit', array($formid, "id_companyid", null));
            }
        }
    }
}

function company_admin_fix_breadcrumb(&$PAGE, $linktext, $linkurl) {

    $PAGE->navbar->ignore_active();
    // Updated - Dec 3 2018 - Remove Site Admin - not needed in breadcrumbs
    // $PAGE->navbar->add(get_string('administrationsite'));
    $PAGE->navbar->add(get_string('dashboard', 'block_iomad_company_admin'), new moodle_url('/local/iomad_dashboard/index.php'));
    $PAGE->navbar->add($linktext, $linkurl);
}

// Custom Code - Syllametrics - Updated Jan 29 2019
function company_cond($company){

	$content = '#companyid'.$company->id.'-' . "\n";
	$content .= '# '.$company->name.'' . "\n";
	$content .= 'RewriteCond %{HTTP_HOST} ^'.$company->hostname.'\.trainingsoftware\.com$ [NC]' . "\n";
	$content .= 'RewriteCond %{REQUEST_URI} !(iomad_signup)' . "\n";
	$content .= 'RewriteCond %{QUERY_STRING} ^id='.$company->id.'&code='.$company->shortname.'$' . "\n";
	$content .= '# Rewrite for /start shortlink' . "\n";
	$content .= 'RewriteRule ^start$ local/iomad_signup/login.php?id='.$company->id.'&code='.$company->shortname.' [QSA,L]' . "\n";
	$content .= '# Rewrite only if user is visiting root subdomain' . "\n";
	$content .= 'RewriteCond %{HTTP_HOST} ^'.$company->hostname.'\.trainingsoftware\.com$ [NC]' . "\n";
	$content .= 'RewriteCond %{REQUEST_URI} ^/$' . "\n";
	$content .= 'RewriteRule ^(.*)$  local/iomad_signup/login.php?id='.$company->id.'&code='.$company->shortname.' [QSA,L]' . "\n\n";
	return 	$content;
}

function updatetrac($tracid){

	global $DB, $CFG;
	$host_data = new stdClass();
	$host_data->id = $tracid;
	$host_data->htaccess=1;
	$host_data->timemodified=time();
	$DB->update_record('iomad_company_hostname_trac', $host_data);
}

function get_linenumber($search){
	global $CFG;
	$filepath =$CFG->dirroot."/.htaccess";
	$line_number = 0;
	if ($handle = fopen($filepath, "r")) {
	   $count = 0;
	   while (($line = fgets($handle, 4096)) !== FALSE and !$line_number) {
		  $count++;
		  $line_number = (strpos($line, $search) !== FALSE) ? $count : $line_number;
	   }
	   fclose($handle);
	}
	return $line_number;
}


function populate_htaccess(){
     // write the code here.
	global $DB, $CFG;
	//echo $_SERVER['DOCUMENT_ROOT'];
	//echo $CFG->dataroot;
	 echo $filepath =$CFG->dirroot."/.htaccess";
	 $sql = "select ch.* from {iomad_company_hostname_trac} ch
		join {company} c on ch.companyid = c.id 

where ch.status='active' and ch.htaccess=0 and c.suspended=0";
	$host_trac = $DB->get_records_sql($sql);
	//	$host_trac = $DB->get_records_sql("select * from {iomad_company_hostname_trac} where  status='active' and htaccess=0");
	$content ='';

     if(!file_exists($filepath))
	{
		$content = '<IfModule mod_rewrite.c>' . "\n";
		$content .= '# Enable mod_rewrite' . "\n";
		$content .= 'RewriteEngine On' . "\n\n";
		$content .= '# Path to app instance' . "\n";
		$content .= 'RewriteBase /' . "\n\n";

		$content .= '#Rewrite to HTTPS /' . "\n";
		$content .= '#RewriteCond %{HTTPS} !on /' . "\n";
		$content .= '#RewriteRule (.*) https://%{HTTP_HOST}%{REQUEST_URI} /' . "\n\n";

		$content .= '# Custom subdomains and "short" login URLs - note: requires config.php to pick up URL and adjust wwwroot /' . "\n\n";

		if($host_trac){
			foreach($host_trac as $trac){

				$company = $DB->get_record("company",array('id'=>$trac->companyid));
				if($company){
					$content .= company_cond($company);
					updatetrac($trac->id);
				}
			}
		}

		$content .= '#newline' . "\n";
		$content .= '#Default subdomain and "short" login URL' . "\n";
		$content .= 'RewriteCond %{REQUEST_FILENAME} !-f' . "\n";
		$content .= 'RewriteCond %{REQUEST_FILENAME} !-d' . "\n";
		$content .= 'RewriteCond %{REQUEST_URI} !(iomad_signup)' . "\n";
		$content .= 'RewriteRule ^login$ login/index.php [QSA,L]' . "\n\n";

		$content .= '</IfModule>' . "\n";
		@chmod($filepath, 0777);
		file_put_contents($filepath, $content);
	}
	else{

		if($host_trac){
			$content ='';
			foreach($host_trac as $trac){
				$company = $DB->get_record("company",array('id'=>$trac->companyid));
				if($company){
					$line_number = get_linenumber('companyid'.$company->id.'-');
					if($line_number > 0 ){
						$lines       = file($filepath);
						for ($x = $line_number-1; $x <= $line_number+10; $x++) {
							unset($lines[$x]);
						}
						file_put_contents($filepath, $lines);
					}
					$content .= company_cond($company);
					updatetrac($trac->id);
				}
			}
		}

		$line_number = get_linenumber('newline');
		$specificLine = $line_number-1;
		$f = fopen($filepath, "r+");
		$currentcontent       = file($filepath);
		array_splice($currentcontent, $specificLine, 0, array($content)); // arrays start at zero index
		$contents = implode("", $currentcontent);
		@chmod($filepath, 0777);
		file_put_contents($filepath, $contents);

	}

}

use Aws\Route53\Route53Client;
function create_activate_domain(){
	global $DB, $CFG;
	require 'vendor/autoload.php';
//	check_hostname('new');
	
$sql = "select ch.* from {iomad_company_hostname_trac} ch
		join {company} c on ch.companyid = c.id 

where ch.status='added' or ch.status='updated' and  c.suspended=0";
	$host_trac = $DB->get_records_sql($sql);
//	$host_trac = $DB->get_records_sql("select * from {iomad_company_hostname_trac} where status='added' or status='updated'");
	$client = new Aws\Route53\Route53Client([
		//      'profile' => 'default',
		'version' => 'latest',
		'region' => $CFG->block_iomad_company_admin_region,
		'credentials' => [
			'key'    => $CFG->block_iomad_company_admin_key,
			'secret' => $CFG->block_iomad_company_admin_secret,
		],
	]);
	//	print_r($client);
//	print_r('region'.$CFG->block_iomad_company_admin_region);
//			print_r('key'.$CFG->block_iomad_company_admin_key);
//			print_r('secret'.$CFG->block_iomad_company_admin_secret);
    if($host_trac){
	foreach($host_trac as $trac){
		// I have commented the domain creation part, since it will create a new domain in the server, but it will cost for the client.
		// This can be tested later.
	
		$result = $client->changeResourceRecordSets([
		'ChangeBatch' => [
			'Changes' => [
				[
					'Action' => 'CREATE',
					'ResourceRecordSet' => [
						'Name' => "$trac->hostname.trainingsoftware.com",
						'ResourceRecords' => [
							[
								'Value' => '52.27.104.188',
							],
						],
						'TTL' => 60,
						'Type' => 'A',
					],
				],
			],
			'Comment' => 'Subdomain for '.$trac->hostname,
		],
		'HostedZoneId' => 'Z28W48SJ56CII5',
	]);
//	error_log(print_r($result));
//	print_r($result);
		// write code to update the status as active iomad_company_hostname_trac
		if($result){
		$host_data = new stdClass();
		$host_data->id = $trac->id;
		$host_data->status="active";
		$host_data->timemodified=time();
		$DB->update_record('iomad_company_hostname_trac', $host_data);
		}


	}
    }
}

function check_hostname($hostname){ 
	global $DB, $CFG;
	require 'vendor/autoload.php';
	
		 $client = new Aws\Route53\Route53Client([
                //      'profile' => 'default',
                'version' => 'latest',
                'region' => $CFG->block_iomad_company_admin_region,
                'credentials' => [
                        'key'    => $CFG->block_iomad_company_admin_key,
                        'secret' => $CFG->block_iomad_company_admin_secret,
                ],
	]);
//	echo $hostname;
        $results = $client->listResourceRecordSets(['HostedZoneId' => 'Z28W48SJ56CII5']);
        foreach($results['ResourceRecordSets'] as $key => $result){
			if($result['Type'] == 'A'){

				$domainname = $result['Name'];
				$mdomainnameArray = explode('.', $domainname);
				echo $existhostname = $mdomainnameArray[0];
			//	echo strcmp($existhostname,$hostname);
			//	echo '<hr>';
				if(strcmp($existhostname,$hostname) == 0)
					return 0;
				
              }

        }
        
        return 1;

}
// till here