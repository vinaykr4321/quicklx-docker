<?php

defined('MOODLE_INTERNAL') || die();

function xmldb_local_base_upgrade($oldversion) {
    global $CFG, $DB;

    $result = true;
    $dbman = $DB->get_manager();

    if ($oldversion < 2018120302) {

        $table = new xmldb_table('schedule_report_filter');
        $field = new xmldb_field('subgroup', XMLDB_TYPE_CHAR, '255', null, XMLDB_UNSIGNED, null, null, 'organization');

        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        upgrade_plugin_savepoint(true, 2018120302, 'local', 'schedule_report_filter');
    }

    return $result;
}

