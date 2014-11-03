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

namespace local_cpd\model;

use local_cpd\base_model;

defined('MOODLE_INTERNAL') || die;

/**
 * Activity type.
 *
 * Activity types are attached to activities, and define the type of activity
 * which occurred.
 */
class activity_type extends base_model {
    /**
     * Record ID.
     *
     * @var integer
     */
    protected $id;

    /**
     * Activity type name.
     *
     * @var string
     */
    protected $name;

    /**
     * Initialiser.
     *
     * @param string $name The name of the activity.
     */
    final public function __construct($name=null) {
        $this->name = $name;
    }

    /**
     * Retrieve a menu of activity types.
     *
     * @return string[]
     */
    final public static function menu() {
        $activitytypes = static::all();
        $menu          = array();

        foreach ($activitytypes as $activitytype) {
            $menu[$activitytype->id] = $activitytype->name;
        }

        return $menu;
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
    final public static function model_from_form($data) {
        return new static($data->name);
    }

    /**
     * @override \local_cpd\base_model
     */
    final public static function model_fields() {
        return array(
            'id',
            'name',
        );
    }

    /**
     * @override \local_cpd\base_model
     */
    final public static function model_table() {
        return 'cpd_activity_type';
    }
}
