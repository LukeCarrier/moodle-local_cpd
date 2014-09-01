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
