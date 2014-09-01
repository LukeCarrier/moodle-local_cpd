<?php

/**
 * Moodle/Totara LMS CPD plugin.
 *
 * A modern replacement for the legacy report_cpd module, which has numerous
 * security issues. Supports the existing tables without any fuss.
 *
 * @author Luke Carrier <luke@carrier.im> <luke@tdm.co>
 * @copyright 2014 Luke Carrier, The Development Manager Ltd
 * @license GPL v3
 */

defined('MOODLE_INTERNAL') || die;

function xmldb_local_cpd_upgrade($oldversion) {
    global $DB;
    $dbmgr = $DB->get_manager();

    if ($oldversion < 2014073100) {
        $table      = new xmldb_table('cpd');
        $fieldnames = array(
            'activity_fmt',
            'development_need_fmt',
            'objective_fmt',
        );
        foreach ($fieldnames as $fieldname) {
            $field = new xmldb_field($fieldname, XMLDB_TYPE_INTEGER, 10,
                                     XMLDB_UNSIGNED);
            $dbmgr->add_field($table, $field);
        }

        upgrade_plugin_savepoint(true, 2014073100, 'local', 'cpd');
    }

    return true;
}
