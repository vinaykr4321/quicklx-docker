<?php
/**
 * @package mod_backupscheduler
 * @copyright 2018 Syllametrics | support@syllametrics.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

defined('MOODLE_INTERNAL') || die();
$tasks = array(
    array(
        'classname' => 'mod_backupscheduler\task\task_backupscheduler',
        'minute' => '*/1', // ...Run after every 10 mins.
        'hour' => '*',
        'day' => '*',
        'dayofweek' => '*',
        'month' => '*')
);