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

/**
 * XMLDB installation function.
 *
 * This function is called by XMLDB post-table creation during the installation
 * process, and is used to populate the created tables with data.
 *
 * For safety and code footprint reasons, we avoid using our model classes here.
 *
 * @return boolean Always true.
 */
function xmldb_local_cpd_install() {
    global $DB;

    $activitytypes = array(
        array('Attendence in college/university'),
        array('Computer based training'),
        array('Conferences'),
        array('Discussions'),
        array('Examination'),
        array('Individual informal study'),
        array('Mentoring'),
        array('On-the-job training'),
        array('Professional Institute'),
        array('Reading'),
        array('Self-managed learning'),
        array('Seminars'),
        array('Structured discussions'),
        array('Training course'),
    );
    foreach ($activitytypes as $activitytype) {
        $DB->insert_record('cpd_activity_type', (object) array(
            'name' => $activitytype[0],
        ));
    }

    $statuses = array(
        array('Started',       2),
        array('Objective met', 3),
        array('Not started',   1),
    );
    foreach ($statuses as $status) {
        $DB->insert_record('cpd_status', (object) array(
            'name'      => $status[0],
            'sortorder' => $status[1],
        ));
    }

    $years = array(
        array(1262304000, 1293839999),
        array(1293840000, 1325375999),
        array(1325376000, 1356998399),
    );
    foreach ($years as $year) {
        $DB->insert_record('cpd_year', (object) array(
            'startdate' => $year[0],
            'enddate'   => $year[1],
        ));
    }

    return true;
}
