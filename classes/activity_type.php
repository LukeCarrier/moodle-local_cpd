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
