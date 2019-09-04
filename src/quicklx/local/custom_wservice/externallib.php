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
 * Custom SSO - Create and assign users to IOMAD companies/licenses
 *
 * @package    local_custom_wservice
 * @copyright  2019 Syllametrics (support@syllametrics.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/config.php');
require_once($CFG->libdir . '/externallib.php');
require_once($CFG->libdir . '/setup.php');
require_once($CFG->libdir . '/authlib.php');
require_once($CFG->libdir . '/classes/session/manager.php');
require_once($CFG->dirroot . '/local/iomad/lib/iomad.php');
require_once($CFG->dirroot . '/local/iomad/lib/user.php');
require_once($CFG->dirroot . '/local/iomad/lib/company.php');
require_once($CFG->dirroot . '/login/lib.php');
require_once($CFG->dirroot . '/auth/manual/auth.php');

class local_custom_wservice_external extends external_api {

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function usersso_parameters() {
        return new external_function_parameters(
            array(
                  'email' => new external_value(PARAM_TEXT, 'User email'),
                  'wsusername' => new external_value(PARAM_TEXT, 'ws user\'s username'),
                  'firstname' => new external_value(PARAM_TEXT, 'user firstname', VALUE_DEFAULT, ''),
                  'lastname' => new external_value(PARAM_TEXT, 'user lastname', VALUE_DEFAULT, ''),
				  'courseid' => new external_value(PARAM_INT, 'Course ID', VALUE_DEFAULT, 0),
				  'licenseid' => new external_value(PARAM_INT, 'License ID', VALUE_DEFAULT, 0)
            )
        );
    }

    /** 
     * Returns description of method result
     * @return external_description
     */
    public static function usersso_returns() {
		 return new external_value(PARAM_BOOL, 'True if users sso implimented');
    }

    /**
     * impliment user sso
     * @param int $courseid
     * @throws invalid_parameter_exception
     */
    public static function usersso($email,$wsusername, $firstname="",$lastname="",$courseid=0,$licenseid=0) {
        global $DB,$CFG,$SESSION;

    	//Custom Code - Syllametrics - Updated March 26, 2019
		$SESSION->returnurl = $_SERVER['HTTP_REFERER'];//

        // Validate params
        $params = self::validate_parameters(self::usersso_parameters(), 
											['email' => $email, 
											'wsusername' => $wsusername,
											'firstname' => $firstname,
											'lastname' => $lastname,
											'courseid' => $courseid,
											'licenseid' => $licenseid											
											]);

        if (!$client = $DB->get_record('user', ['username' => $wsusername,'suspended' => 0])) {
            throw new invalid_parameter_exception("The user provided is not currently authorized to access training. Please report this to your training manager or support team for further assistance.");
        }
        $sql = "select distinct(companyid) as companyid from {company_users} 
				where suspended=0 and userid=".$client->id;
		if (!$clientcompany = $DB->get_record_sql($sql)) {
			throw new invalid_parameter_exception("The user provided is not currently part of a group that is authorized to access training. Please report this to your training manager or support team for further assistance.");
		}
		
		// Find/validate company
        if (!$company = $DB->get_record('company', ['id' => $clientcompany->companyid,'suspended' => 0])) {
            throw new invalid_parameter_exception("The group ID provided could not be found, so access can not be provided at this time. Please report this to your training manager or support team for further assistance.");
        }
        
        $user = $DB->get_record('user', ['email' => $email,'suspended' => 0]);
		// Check if the user already has a Moodle account
		if (isset($user) && $DB->get_record('company_users', array('userid' => $user->id,'companyid'=>$clientcompany->companyid))) {

			// Follow the "Existing User" logic 
			// Throw new invalid_parameter_exception("user exist");
			// Assign licenses.
			if($licenseid){
				enrol_to_license($licenseid,$user,$company);
				
			}
			 // Enrol the user on the courses.
			if($courseid){
				if (!$DB->get_record('course', array('id' => $courseid))){ 
					//throw new invalid_parameter_exception("invalid Course ID  ");
					$message = "The course ID you provided is not currently valid. Please report this to your training manager or support team for further assistance.";
					$url ='/my';

				}
				else if (!$DB->get_record('companylicense_users', array('userid' => $user->id,
																	'licensecourseid' => $courseid))){ 
					// Check for valid license to access it			
					// Throw new invalid_parameter_exception("The user does not have a valid license to access course");
					$message = "The license you provided to access a course is not currently valid. Please report this to your training manager or support team for further assistance.";
					// go to  the user's logged-in dashboard
							$url ='/my';

				}
				else{
					$createcourses = array( 0 => $courseid);
					company_user::enrol($user, $createcourses, $company->id);
					
					// Take user to course page for course ID specified in SSO request
					$url ='/course/view.php?id='.$courseid;

				}
			}
			else{
				// Go to the user's logged-in dashboard
						$url ='/my';
			}

                        //$newpassword = generate_password();
                        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
                        $newpassword= substr(str_shuffle($alphabet),0,8);
                        $password = hash_internal_user_password($newpassword);
                        $DB->set_field('user', 'password', $password, array('id' => $user->id));
                        $SESSION->wantsurl = $url;


			//Custom Code - Syllametrics - Updated March 26, 2019
			redirect($CFG->wwwroot.'/local/custom_wservice/authenticate_user.php?id='.$user->id.'&password='.$newpassword.'&wantsurl='.$SESSION->wantsurl.'&returnurl='.$SESSION->returnurl);            	

			
			/*
			user_autologin($user);
			
			$url = new moodle_url($CFG->wwwroot.$url);
			$SESSION->wantsurl = $url;
			redirect($SESSION->wantsurl);
			*/
			//redirect(new moodle_url(get_login_url(), array('testsession'=>$user->id)));
			
			//redirect($CFG->wwwroot.'/local/custom_wservice/set_user.php?id='.$user->id.'&wantsurl='.$SESSION->wantsurl);

		}
        else{

			//Create the new user account within the IOMAD group/company
			if (empty($firstname) || empty($lastname) ) {
				throw new invalid_parameter_exception("First and last name are required to create a new user.");
			}
			 $data->email = trim($email);
			 $data->username = trim($email);
			 $data->firstname = trim($firstname);
			 $data->lastname = trim($lastname);
			 $data->companyid = $company->id;
			 $data->sendnewpasswordemails = false;
			 $data->preference_auth_forcepasswordchange = 0;
			 $data->newpassword = generate_password();
			 $data->managertype =0;
			 if (!$userid = company_user::create($data)) {
					$this->verbose("Error adding a new user account. Please report this to your training manager or support team for further assistance.");
					if (!$this->get('ignore_errors')) {
						die();
					}
				}
				
			$user = new stdclass();
			$user->id = $userid ;
			$company = new company($company->id);
			
			// Create an event for this.
			$managertypes = $company->get_managertypes();
			$eventother = array('companyname' => $company->get_name(),
								'companyid' => $company->id,
								'usertype' => $data->managertype,
								'usertypename' => $managertypes[$data->managertype]);
			$event = \block_iomad_company_admin\event\company_user_assigned::create(array('context' => context_system::instance(),
                                                                                  'objectid' => $company->id,
                                                                                  'userid' => $user->id,
                                                                                  'other' => $eventother));
			$event->trigger();
			
			$userdata = $DB->get_record('user', array('id' => $userid));

			// Assign licenses.
			if($licenseid){
				enrol_to_license($licenseid,$userdata,$company);
				
			}
			
			 // Enrol the user on the courses.
			if($courseid){
				if (!$DB->get_record('course', array('id' => $courseid))){ 
					
					// Throw new invalid_parameter_exception("invalid Course ID  ");
					$message = "The course ID you provided is not valid. Please report this to your training manager or support team for further assistance.";
					$url ='/my';

				}
				else if (!$DB->get_record('companylicense_users', array('userid' => $userid,
																	'licensecourseid' => $courseid))){ 
					// Check for a valid license to access it

					// Throw new invalid_parameter_exception("The user does not have a valid license to access course");
					$message = "The license for the course you are trying to access does not allow access at this time. Please report this to your training manager or support team for further assistance.";
					// Go to the user's logged-in dashboard
							$url ='/my';

				}
				else{
					$createcourses = array( 0 => $courseid);
					company_user::enrol($userdata, $createcourses, $company->id);
					// Take user to course page for course ID specified in SSO request
					$url ='/course/view.php?id='.$courseid;

				}
			}
			else{
				// Go to the user's logged-in dashboard
						$url ='/my';

			}
			$SESSION->wantsurl = $url;
			redirect($CFG->wwwroot.'/local/custom_wservice/authenticate_user.php?id='.$user->id.'&password='.$data->newpassword.'&wantsurl='.$SESSION->wantsurl.'&returnurl='.$SESSION->returnurl);            	//Custom Code - Syllametrics - Updated (26 th march,2019)

			
		}
	

        return true;
    }

}
function user_autologin($user){
	global $CFG,$DB,$SESSION;
	
	$username = $user->username;
	$email = $user->email;
	$user = get_complete_user_data('username', $username, $CFG->mnet_localhost_id);
	$user->auth = $DB->get_field('user', 'auth', array('id' => $user->id));
	
	if ($email = clean_param($username, PARAM_EMAIL)) {
		$select = "mnethostid = :mnethostid AND LOWER(email) = LOWER(:email) AND deleted = 0";
		$params = array('mnethostid' => $CFG->mnet_localhost_id, 'email' => $email);
		$users = $DB->get_records_select('user', $select, $params, 'id', 'id', 0, 2);
		if (count($users) === 1) {
			// Use email for login only if unique.
			$user = reset($users);
			$user = get_complete_user_data('id', $user->id);
			$username = $user->username;
		}
		unset($users);
	}
	
    $authsenabled = get_enabled_auth_plugins();

	if ($user) {
		// Use manual if auth not set.
		$auth = empty($user->auth) ? 'manual' : $user->auth;

		if (in_array($user->auth, $authsenabled)) {
			$authplugin = get_auth_plugin($user->auth);
			$authplugin->pre_user_login_hook($user);
		}
        $auths = array($auth);

	}
	foreach ($auths as $auth) {
		  $authplugin = get_auth_plugin($auth);
		  if ($user->id) {
			// User already exists in database.
			if (empty($user->auth)) {
				// For some reason auth isn't set yet.
				$DB->set_field('user', 'auth', $auth, array('id' => $user->id));
				$user->auth = $auth;
			}
			if ($authplugin->is_synchronised_with_external()) {
				// Update user record from external DB.
				$user = update_user_record_by_id($user->id);
			}
		}
	}
	
	$authplugin->sync_roles($user);
	login_attempt_valid($user);
	$failurereason = AUTH_LOGIN_OK;


	 if ($user) {
		 if (!empty($user->lang)) {
			// Unset previous session language - use user preference instead
			unset($SESSION->lang);
		}

		complete_user_login($user);
	 	\core\session\manager::apply_concurrent_login_limit($user->id, session_id());
		set_moodle_cookie($user->username);
		
		session_start();
		$record = new \stdClass();
		$record->state       = 0;
		$record->sid         = session_id();
		$record->sessdata    = null;
		$record->userid      = $user->id;
		$record->timecreated = $record->timemodified = time();
		$record->firstip     = $record->lastip = getremoteaddr();
		
		$sid = session_id();
		session_regenerate_id(true);
		$DB->delete_records('sessions', array('sid'=>$sid));
		$DB->delete_records('sessions', array('userid'=>$user->id));
		
		$record->id = $DB->insert_record('sessions', $record);


	 }
 
}

function enrol_to_license($licenseid,$userdata,$company){
	global $DB;
	//error_log("a: " . $licenseid);
	//error_log("b: " . $company->id);
	if (!$licenserecord = (array) $DB->get_record('companylicense', array('id' => $licenseid,'companyid'=>$company->id))) {
		// Throw new invalid_parameter_exception("An invalid license ID");
		//error_log("b");
		$message = "The license ID you provided is not valid. Please report this to your training manager or support team for further assistance.";
	}
	else if ($licenserecord['expirydate'] < time()) {
		// Throw new invalid_parameter_exception($licenserecord['name']."License Expired");
		//error_log(print_r($licenserecord, TRUE));
		//error_log("t: " . time());
		$message = "The license you provided is currently expired. Please report this to your training manager or support team for further assistance.";
	}
	else{
		//error_log("d");
		if (!empty($licenserecord['program'])) {
			//error_log("e");
			// If so the courses are not passed automatically.
			$licensecourses =  $DB->get_records_sql_menu("SELECT c.id, clc.courseid FROM {companylicense_courses} clc
																   JOIN {course} c ON (clc.courseid = c.id
																   AND clc.licenseid = :licenseid)",
																   array('licenseid' => $licenserecord['id']));
		}
		else{
			//error_log("f");
			// Throw new invalid_parameter_exception("License assign not permitted");
			$message = "The license you provided is not currently assignable to you. Please report this to your training manager or support team for further assistance.";
			}

		if (!empty($licensecourses)) {
			//error_log("h");
			$count = $licenserecord['used'];
			$numberoflicenses = $licenserecord['allocation'];
			foreach ($licensecourses as $licensecourse) {
				if(!$DB->get_record('companylicense_users',array('userid' => $userdata->id,
												  'licenseid' => $licenseid,
												  'licensecourseid' => $licensecourse))){
					if ($count >= $numberoflicenses) {
						// Set the used amount.
						$licenserecord['used'] = $count;
						$DB->update_record('companylicense', $licenserecord);
					}
					$allow = true;
		
					if ($allow) {
						$count++;
						$DB->insert_record('companylicense_users',
											array('userid' => $userdata->id,
												  'licenseid' => $licenseid,
												  'issuedate' => time(),
												  'licensecourseid' => $licensecourse));
					}

					// Create an event.
					//error_log("i");
					$eventother = array('licenseid' => $licenseid,
										'duedate' => 0);
					$event = \block_iomad_company_admin\event\user_license_assigned::create(array('context' => context_course::instance($licensecourse),
																								  'objectid' => $licenseid,
																								  'courseid' => $licensecourse,
																								  'userid' => $userdata->id,
																								  'other' => $eventother));
					$event->trigger();
				
				}
			}
		}
	}
}
