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

namespace local_cpd;

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
     * Status name.
     *
     * @var string
     */
    protected $name;

    /**
     * Initialiser.
     *
     * @param string $name The name of the activity.
     */
    final public function __construct($name) {
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
    final protected static function model_fields() {
        return array(
            'id',
            'name',
        );
    }

    /**
     * @override \local_cpd\base_model
     */
    final protected static function model_table() {
        return 'cpd_activity_type';
    }
}
