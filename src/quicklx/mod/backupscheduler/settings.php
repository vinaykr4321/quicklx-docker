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
 *
 *
 * @package mod_backupscheduler
 * @copyright 2018 Syllametrics | support@syllametrics.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

defined('MOODLE_INTERNAL') || die;


$settings->add(new admin_setting_heading('header',
get_string('header', 'mod_backupscheduler'),
get_string('descheader', 'mod_backupscheduler')));

$settings->add(new admin_setting_configtext('mod_backupscheduler_moodle',
get_string('moodlename', 'mod_backupscheduler'),
get_string('descmoodlename', 'mod_backupscheduler'), null, PARAM_TEXT));

$settings->add(new admin_setting_configtext('mod_backupscheduler_moodle_dir',
get_string('moodledir', 'mod_backupscheduler'),
get_string('descmoodledir', 'mod_backupscheduler'), null, PARAM_TEXT));

$settings->add(new admin_setting_configtext('mod_backupscheduler_moodledata',
get_string('moodledata', 'mod_backupscheduler'),
get_string('descmoodledata', 'mod_backupscheduler'), null, PARAM_TEXT));

$settings->add(new admin_setting_configtext('mod_backupscheduler_moodledata_dir',
get_string('moodledatadir', 'mod_backupscheduler'),
get_string('descmoodledatadir', 'mod_backupscheduler'), null, PARAM_TEXT));

$settings->add(new admin_setting_configtext('mod_backupscheduler_backupdir',
get_string('backupdir', 'mod_backupscheduler'),
get_string('descbackupdir', 'mod_backupscheduler'), null, PARAM_TEXT));

$settings->add(new admin_setting_configtext('mod_backupscheduler_dbname',
get_string('dbname', 'mod_backupscheduler'),
get_string('descdbname', 'mod_backupscheduler'), null, PARAM_TEXT));

$settings->add(new admin_setting_configtext('mod_backupscheduler_dbuser',
get_string('dbuser', 'mod_backupscheduler'),
get_string('descuser', 'mod_backupscheduler'), null, PARAM_TEXT));

$settings->add(new admin_setting_configtext('mod_backupscheduler_dbpass',
get_string('dbpass', 'mod_backupscheduler'),
get_string('descdbpass', 'mod_backupscheduler'), null, PARAM_TEXT));

$settings->add(new admin_setting_configtext('mod_backupscheduler_mysqldump',
get_string('mysqldump', 'mod_backupscheduler'),
get_string('descmysqldump', 'mod_backupscheduler'), "/usr/bin/mysqldump", PARAM_TEXT));

$settings->add(new admin_setting_configtext('mod_backupscheduler_email',
get_string('email', 'mod_backupscheduler'),
get_string('descemail', 'mod_backupscheduler'), null, PARAM_TEXT));

$settings->add(new admin_setting_configselect('mod_backupscheduler_backuptime',
get_string('backuptime', 'mod_backupscheduler'),
get_string('descbackuptime', 'mod_backupscheduler'), null, array(1,2,3,4,5,6,7,8,9,10)));
