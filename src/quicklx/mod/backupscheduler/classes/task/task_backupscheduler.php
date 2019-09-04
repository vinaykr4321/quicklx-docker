<?php

namespace mod_backupscheduler\task;

require_once(dirname(__FILE__) . '/../../../../config.php');
require_once($CFG->dirroot . '/lib/moodlelib.php');
defined('MOODLE_INTERNAL') || die();

/**
 *
 * @package mod_backupscheduler
 * @copyright 2018 Syllametrics | support@syllametrics.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */
class task_backupscheduler extends \core\task\scheduled_task {

    public function get_name() {
        return get_string('taskbackupschedule', 'mod_backupscheduler');
    }

    public function execute() {
        global $CFG;
        include($CFG->dirroot . '/mod/backupscheduler/backupfile.php');
    }

}
