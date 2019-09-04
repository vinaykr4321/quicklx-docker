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

require_once(dirname(__FILE__).'/../../config.php');
 
$id = optional_param('id', '', PARAM_RAW);
$plainpassword = optional_param('password', '', PARAM_RAW);
$wantsurl = optional_param('wantsurl', '', PARAM_RAW);
//Custom Code - Syllametrics - Updated March 26, 2019
$returnurl = optional_param('returnurl', '', PARAM_RAW);

global $USER,$DB,$CFG;
$SESSION->wantsurl = $wantsurl;
//Custom Code - Syllametrics - Updated March 26. 2019
$SESSION->returnurl = $returnurl;
if($id){
	$user = $DB->get_record('user',array('id'=>$id));
	$url = $CFG->wwwroot.'/login/index.php';
	$username = $user->username;
	$password = $plainpassword;
		echo '<form style="display:none;" name="moodlelogin" id="moodlelogin" action='.$url.' method="post">
		<input id="anchor" type="hidden" name="anchor" value="#">
	<input type="text" name="username" id="username" size="15" value='.$username.' />
	<input type="password" name="password" id="password" size="15" value='.$password.' />
	</form>';
}

?>
<script>
document.getElementById('moodlelogin').submit();
</script>
