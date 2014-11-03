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
 * CPD activity status.
 */
class activity_status extends base_model {
    /**
     * Record ID.
     *
     * @var integer
     */
    protected $id;

    /**
     * Name.
     *
     * @var string
     */
    protected $name;

    /**
     * Display order.
     *
     * @var integer
     */
    protected $display_order;

    /**
     * Initialiser.
     *
     * @param string  $name          Name.
     * @param integer $display_order Display order.
     */
    final public function __construct($name=null, $display_order=null) {
        $this->name          = $name;
        $this->display_order = $display_order;
    }

    /**
     * Retrieve a menu of activity statuses.
     *
     * @return string[]
     */
    final public static function menu() {
        $activitystatuses = static::all('display_order');
        $menu             = array();

        foreach ($activitystatuses as $activitystatus) {
            $menu[$activitystatus->id] = $activitystatus->name;
        }

        return $menu;
    }

    /**
     * @override \local_cpd\base_model
     */
    final public static function model_accessors() {
        return array();
    }

    /**
     * @override \local_cpd\base_model
     */
    final public static function model_fields() {
        return array(
            'id',
            'name',
            'display_order',
        );
    }

    /**
     * @override \local_cpd\base_model
     */
    final public static function model_table() {
        return 'cpd_status';
    }
}
