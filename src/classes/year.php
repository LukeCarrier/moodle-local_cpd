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

namespace local_cpd;

use userdate;

defined('MOODLE_INTERNAL') || die;

/**
 * Individual CPD year.
 *
 * Years are administrator-configurable time periods which individual CPD
 * activities are contained within.
 */
class year extends base_model {
    /**
     * Record ID.
     *
     * @var integer
     */
    protected $id;

    /**
     * Start date.
     *
     * @var integer
     */
    protected $startdate;

    /**
     * End date.
     *
     * @var integer
     */
    protected $enddate;

    /**
     * Find CPD years with start and end dates within the specified thresholds.
     *
     * @param integer $startdate
     * @param integer $enddate
     *
     * @return \local_cpd\year[]
     */
    final public static function find_between($startdate, $enddate) {
        return static::find_select('startdate > ? AND enddate < ?',
                                   array($startdate, $enddate), 'startdate');
    }

    /**
     * Retrieve a menu of years.
     *
     * @return string[]
     */
    final public static function menu() {
        $years = static::all('startdate');
        $menu  = array();

        foreach ($years as $year) {
            $menu[$year->id] = $year->get_friendly_range();
        }

        return $menu;
    }

    /**
     * Get the year's friendly date range.
     *
     * @return string The friendly date range.
     */
    public function get_friendly_range() {
        return util::string('yeardaterange', (object) array(
            'startdate' => $this->get_friendly_startdate(),
            'enddate'   => $this->get_friendly_enddate(),
        ));
    }

    /**
     * Get the year's friendly end date.
     *
     * @return string The friendly end date.
     */
    public function get_friendly_enddate() {
        return userdate($this->enddate,
                        util::string('strftimedate', null, 'langconfig'));
    }

    /**
     * Get the year's friendly start date.
     *
     * @return string The friendly start date.
     */
    public function get_friendly_startdate() {
        return userdate($this->startdate,
                        util::string('strftimedate', null, 'langconfig'));
    }

    /**
     * @override \local_cpd\base_model
     */
    final protected static function model_accessors() {
        return array();
    }

    /**
     * @override \local_cpd\base_model
     */
    final protected static function model_fields() {
        return array(
            'id',
            'startdate',
            'enddate',
        );
    }

    /**
     * @override \local_cpd\base_model
     */
    final protected static function model_table() {
        return 'cpd_year';
    }
}
