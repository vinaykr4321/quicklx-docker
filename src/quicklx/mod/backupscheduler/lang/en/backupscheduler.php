<?php

/**
 *
 *
 * @package mod_backupscheduler
 * @copyright 2018 Syllametrics | support@syllametrics.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */
$string['pluginname'] = 'Backup Scheduler';
$string['taskbackupschedule'] = 'Task backup schedule';
$string['header'] = 'Please fill all the values.';
$string['descheader'] = 'All the (*) values are necessary to make this plugin work without any intruption.';
$string['moodledir'] = 'Moodle Directory *';
$string['descmoodledir'] = 'Enter the path where your moodle folder is located (ex: /var/www/html).';
$string['moodlename'] = 'Moodle Name *';
$string['descmoodlename'] = 'Enter the name of your moodle folder (ex: moodle).';
$string['moodledata'] = 'Moodle Data *';
$string['descmoodledata'] = 'Enter the name of your moodledata folder (ex: moodledata).';
$string['moodledatadir'] = 'Moodle Data Directory *';
$string['descmoodledatadir'] = 'Enter the path where your moodledata folder is located (ex: /var/www).';
$string['backupdir'] = 'Backup Directory *';
$string['descbackupdir'] = 'Enter the path where your Backup Directory folder is located (ex: /var/www/backup).';
$string['dbname'] = 'Database Name *';
$string['descdbname'] = 'Enter the name of your database.';
$string['dbuser'] = 'Database User Name *';
$string['descuser'] = 'Enter the user name of your database.';
$string['dbpass'] = 'Database Password *';
$string['descdbpass'] = 'Enter the password of your database.';
$string['ziplocation'] = 'Zip Location';
$string['descziplocation'] = 'Enter the zip location.';
$string['mysqldump'] = 'MYSQLDUMP';
$string['descmysqldump'] = 'Enter the MYSQLDUMP location.';
$string['email'] = 'Email Address';
$string['descemail'] = 'Enter your email address where you want to receive emails related to backups.';
$string['msg'] = "The volume  on server  has reached 80% of capacity. Review disk usage for possible problems or consider adding disk space soon.";
$string['dirmsg'] = "The backup directory cannot be created at your preferred location, please check the permission or create the folder manually and give the permission to write in it.";
$string['backupstarted'] = "Moodle backup started.";
$string['backupcompleted'] = "Moodle backup completed.";
$string['subject'] = "Low disk space.";
$string['dirsubject'] = "Backup folder.";
$string['backuptime'] = "Storage Period";
//$string['descbackuptime'] = "Select how many weeks backups are stored. Backups older than the storage period are removed daily at 3 AM in default system time zone for your Moodle instance.";
$string['descbackuptime'] = "Select how many days backups are stored. Backups older than the storage period are removed daily at 3 AM in default system time zone for your Moodle instance.";
