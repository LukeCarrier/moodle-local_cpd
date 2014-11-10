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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

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

/**
 * Perform an XMLDB upgrade.
 *
 * This function is a manual transcription of changes made to the database
 * schema.
 *
 * @param integer $oldversion The currently installed version of the plugin.
 *
 * @return boolean Always true -- we track successful upgrades using upgrade
 *                 savepoints for finer-grained checkpointing.
 */
function xmldb_local_cpd_upgrade($oldversion) {
    global $DB;
    $dbmgr = $DB->get_manager();

    if ($oldversion < 2014073100) {
        /* We switched the activity, development need and objective fields from
         * ordinary textareas to Moodle editor fields, allowing HTML markup. As
         * such, we need to add editor fields. */
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

        local_cpd_xmldb_savepoint(2014073100);
    }

    if ($oldversion < 2014100600) {
        /* We considerably changed the CPD year functionality, switching from a
         * learner-configurable relationship between years and CPD activity
         * records to independent records with CPD years as a filtering tool. To
         * reflect this, the CPD year ID field on activities was dropped, as
         * were dependent indexes. */
        $table = new xmldb_table('cpd');

        $dbmgr->drop_key($table, new xmldb_key('cpd_year_fk', XMLDB_KEY_FOREIGN,
                                               array('cpdyearid'),
                                               'cpd_year', array('id')));
        $dbmgr->drop_field($table, new xmldb_field('cpdyearid'));

        local_cpd_xmldb_savepoint(2014100600);
    }

    return true;
}
