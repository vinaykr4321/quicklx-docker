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
 * @copyright 2019 Syllametrics | support@syllametrics.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

defined('MOODLE_INTERNAL') || die;

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/moodlelib.php');
#define moodle directory
$moodle_dir = $CFG->mod_backupscheduler_moodle_dir;
$moodle = $CFG->mod_backupscheduler_moodle;

#define moodledata directory
$moodledata_dir = $CFG->mod_backupscheduler_moodledata_dir;
$moodledata = $CFG->mod_backupscheduler_moodledata;

#define date with time
$date = date('Y-m-d', time());

#define backup directories
$backup_dir = $CFG->mod_backupscheduler_backupdir;

#define database name
$dbname = $CFG->mod_backupscheduler_dbname;

#define database username
$dbuser = $CFG->mod_backupscheduler_dbuser;

#define database password
$dbpass = $CFG->mod_backupscheduler_dbpass;

$backuptime = ($CFG->mod_backupscheduler_backuptime + 1) * 1440;

#define mysqldump location 
if (!empty($CFG->mod_backupscheduler_mysqldump)) {
    $mysqldump = $CFG->mod_backupscheduler_mysqldump;
} else {
    $mysqldump = "/usr/bin/mysqldump";
}
$email = $CFG->mod_backupscheduler_email;
$noreplyaddress = $CFG->noreplyaddress;
$admin = $CFG->admin;
if (!empty($moodle_dir) && !empty($moodledata_dir) && !empty($backup_dir) && !empty($dbname) && !empty($dbuser)) {
    #Mail configuration start
    #checking disk space for / partition 
    $availablespace = shell_exec("df -h | grep /$ | awk '{print $5}' | awk -F% '{print $1}'");

    if (!is_dir($backup_dir)) {
        mkdir($backup_dir);
    }

    if (!is_dir($backup_dir)) {
        mail_dir($email, $noreplyaddress, $admin);
    }

    $backuplog = fopen("$backup_dir/mdl_backup.log", "w");
    $backuplogtxt = "$date | " . get_string('backupstarted', 'mod_backupscheduler') . " \r\n";
    fwrite($backuplog, $backuplogtxt);
    fclose($backuplog);

    if (is_dir($backup_dir)) {
        backup($moodle_dir, $date, $moodle, $moodledata, $backup_dir, $mysqldump, $dbuser, $dbpass, $dbname, $moodledata_dir, $backuptime);
        shell_exec("find $backup_dir -mtime +5 -exec rm -rf {} \\");
    } else {
        shell_exec("mkdir -p $backup_dir");
        backup($moodle_dir, $date, $moodle, $moodledata, $backup_dir, $mysqldump, $dbuser, $dbpass, $dbname, $moodledata_dir, $backuptime);
    }

    #limit log file size to about 6 months
    exec("tail -n 330 $backup_dir/mdl_backup.log", $logtail);
    file_put_contents("$backup_dir/mdl_backup.log", PHP_EOL.implode(PHP_EOL, $logtail));

    $backuplogcomptxt = "$date | " . get_string('backupcompleted', 'mod_backupscheduler') . " \r\n";
    file_put_contents("$backup_dir/mdl_backup.log", $backuplogcomptxt . PHP_EOL, FILE_APPEND | LOCK_EX);

    #Mail code executes here
    mailer($availablespace, $email, $noreplyaddress, $admin);
}

function backup($moodle_dir, $date, $moodle, $moodledata, $backup_dir, $mysqldump, $dbuser, $dbpass, $dbname, $moodledata_dir, $backuptime) {

    chdir($backup_dir);
    mkdir("backup_$date");
    chdir("backup_$date");

    shell_exec("tar -zcvf moodle_$date.tar.gz $moodle_dir/$moodle");
    shell_exec("tar -zcvf moodledata_$date.tar.gz $moodledata_dir/$moodledata");
    shell_exec("$mysqldump -u $dbuser -p$dbpass --databases $dbname > moodle_$date.sql");
    
    chdir($backup_dir);
    shell_exec("find ./ -maxdepth 1 -mmin +$backuptime   -exec rm -r {} +");
}

function mailer($availablespace, $email, $noreplyaddress, $admin) {
    if ($availablespace <= 80) {
        $to = new stdClass();
        $to->email = $email;
        $to->firstname = $admin;
        $to->lastname = '';
        $to->maildisplay = true;
        $to->mailformat = 0; // 0 (zero) text-only emails, 1 (one) for HTML/Text emails.
        $to->id = '-11';
        $to->firstnamephonetic = '';
        $to->lastnamephonetic = '';

        $from = new stdClass();
        $from->email = $noreplyaddress;
        $from->firstname = $admin;
        $from->lastname = '';
        $from->maildisplay = true;
        $from->mailformat = 0; // 0 (zero) text-only emails, 1 (one) for HTML/Text emails.
        $from->id = '-11';
        $from->firstnamephonetic = '';
        $from->lastnamephonetic = '';

        #mail message
        $msg = get_string('msg', 'mod_backupscheduler');
        $subject = get_string('subject', 'mod_backupscheduler');

        email_to_user($to, $from, $subject, $msg);
        //send mail
    }
}

function mail_dir($email, $noreplyaddress, $admin) {
    $to = new stdClass();
    $to->email = $email;
    $to->firstname = $admin;
    $to->lastname = '';
    $to->maildisplay = true;
    $to->mailformat = 0; // 0 (zero) text-only emails, 1 (one) for HTML/Text emails.
    $to->id = '-11';
    $to->firstnamephonetic = '';
    $to->lastnamephonetic = '';

    $from = new stdClass();
    $from->email = $noreplyaddress;
    $from->firstname = $admin;
    $from->lastname = '';
    $from->maildisplay = true;
    $from->mailformat = 0; // 0 (zero) text-only emails, 1 (one) for HTML/Text emails.
    $from->id = '-11';
    $from->firstnamephonetic = '';
    $from->lastnamephonetic = '';

    #mail message
    $dirmsg = get_string('dirmsg', 'mod_backupscheduler');
    $dirsubject = get_string('dirsubject', 'mod_backupscheduler');

    email_to_user($to, $from, $dirsubject, $dirmsg);
    //backup folder not created
}
