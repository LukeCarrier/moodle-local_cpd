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

require_once dirname(__DIR__) . '/upgradelib.php';

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
            $field = new xmldb_field($fieldname, XMLDB_TYPE_INTEGER, 10);
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

    if ($oldversion < 2014110700) {
        /* Whilst Moodle's code style recommends that underscores are avoided in
         * variable and property names, Kineo neglected to follow this in the
         * report_cpd module's code. These fields are now being renamed for
         * consistency with Moodle. */
        $fields = array(
            array(
                'table'     => 'cpd',
                'oldname'   => 'activity_fmt',
                'newname'   => 'activityfmt',
                'type'      => XMLDB_TYPE_INTEGER,
                'precision' => 10,
                'notnull'   => XMLDB_NOTNULL,
                'default'   => null,
            ),
            array(
                'table'     => 'cpd',
                'oldname'   => 'development_need',
                'newname'   => 'developmentneed',
                'type'      => XMLDB_TYPE_TEXT,
                'precision' => 'big',
                'notnull'   => null,
                'default'   => '',
            ),
            array(
                'table'     => 'cpd',
                'oldname'   => 'development_need_fmt',
                'newname'   => 'developmentneedfmt',
                'type'      => XMLDB_TYPE_INTEGER,
                'precision' => 10,
                'notnull'   => XMLDB_NOTNULL,
                'default'   => null,
            ),
            array(
                'table'     => 'cpd',
                'oldname'   => 'objective_fmt',
                'newname'   => 'objectivefmt',
                'type'      => XMLDB_TYPE_INTEGER,
                'precision' => 10,
                'notnull'   => XMLDB_NOTNULL,
                'default'   => null,
            ),

            array(
                'table'     => 'cpd_status',
                'oldname'   => 'display_order',
                'newname'   => 'sortorder',
                'type'      => XMLDB_TYPE_INTEGER,
                'precision' => 10,
                'notnull'   => null,
                'default'   => 0,
            ),
        );

        foreach ($fields as $fieldinfo) {
            $table    = new xmldb_table($fieldinfo['table']);
            $oldfield = new xmldb_field($fieldinfo['oldname'],
                                        $fieldinfo['type'], $fieldinfo['precision'],
                                        null,
                                        $fieldinfo['notnull'], $fieldinfo['default']);

            $dbmgr->rename_field($table, $oldfield, $fieldinfo['newname']);
        }

        local_cpd_xmldb_savepoint(2014110700);
    }

    return true;
}
