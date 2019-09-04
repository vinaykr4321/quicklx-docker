<?php

namespace block_iomad_company_admin\task;

class cron_task extends \core\task\scheduled_task {

    /**
     * Get a descriptive name for this task (shown to admins).
     *
     * @return string
     */
    public function get_name() {
        return get_string('activatehostname', 'block_iomad_company_admin');
    }

    /**
     * Run email cron.
     */
    public function execute() {
        global $CFG;
        require_once($CFG->dirroot . '/blocks/iomad_company_admin/lib.php');
		create_activate_domain();
		populate_htaccess();
    }

}

