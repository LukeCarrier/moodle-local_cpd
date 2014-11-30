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

global $CFG;

require_once "{$CFG->dirroot}/lib/testing/generator/component_generator_base.php";

/**
 * Behat data generator.
 *
 * This code has been kept intentionally separate from the Behat step
 * definitions to ease future use of PHPUnit, and in the hope that we get a
 * patch for MDL-48024 into core soon.
 */
class local_cpd_generator extends component_generator_base {
    /**
     * Mapping of element field friendly names to field names.
     *
     * @var string[]
     */
    protected static $fields = array(
        'activity'        => array(
            'User'                 => 'userid',
            'Objective'            => 'objective',
            'Development Need'     => 'developmentneed',
            'Activity Type'        => 'activitytypeid',
            'Activity Description' => 'activity',
            'Status'               => 'statusid',
            'Due Date'             => 'duedate',
            'Start Date'           => 'startdate',
            'End Date'             => 'enddate',
            'Time Taken'           => 'timetaken',
        ),
        'activity_status' => array(
        ),
        'activity_type'   => array(
        ),
        'year'            => array(
            'Start Date' => 'startdate',
            'End Date'   => 'enddate',
        ),
    );

    /**
     * Map of element names to their corresponding database tables.
     *
     * @var string[]
     */
    protected static $tables = array(
        'activity'        => 'cpd',
        'activity_status' => 'cpd_status',
        'activity_type'   => 'cpd_activity_type',
        'year'            => 'cpd_year',
    );

    /**
     * Create a CPD activity record.
     *
     * @param mixed[] $options Raw row data from a Gherkin TableNode.
     *
     * @return void
     */
    public function create_activity($options=null) {
        global $DB;

        $record = new stdClass();

        foreach ($options as $label => $value) {
            $field = static::$fields['activity'][$label];

            switch ($field) {
                case 'objective':
                case 'developmentneed':
                case 'activity':
                    $record->{"{$field}fmt"} = FORMAT_HTML;
                    break;

                case 'activitytypeid':
                    $value = $DB->get_field(static::$tables['activity_type'],
                                            'id', array('name' => $value));
                    break;

                case 'statusid':
                    $value = $DB->get_field(static::$tables['activity_status'],
                                            'id', array('name' => $value));
                    break;

                case 'userid':
                    $value = $DB->get_field('user', 'id',
                                            array('username' => $value));
                    break;

                case 'duedate':
                case 'startdate':
                case 'enddate':
                    $value = strtotime($value);
                    break;
            }

            $record->{$field} = $value;
        }

        $DB->insert_record(static::$tables['activity'], $record);
    }

    public function create_activity_type($options=null) {
    }

    public function create_activity_status($options=null) {
    }

    /**
     * Create a CPD year.
     *
     * @param mixed[] $options Raw row data from a Gherkin TableNode.
     *
     * @return void
     */
    public function create_year($options=null) {
        global $DB;

        $record = new stdClass();

        foreach ($options as $label => $value) {
            $field = static::$fields['activity'][$label];

            switch ($field) {
                case 'startdate':
                case 'enddate':
                    $value = strtotime($value);
            }

            $record->{$field} = $value;
        }

        $DB->insert_record(static::$tables['year'], $record);
    }
}
