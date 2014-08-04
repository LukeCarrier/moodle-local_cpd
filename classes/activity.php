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
 * CPD activity.
 *
 * Activities contain the bulk of information in a user's CPD report. They are
 * connected to status and activity type records.
 */
class activity extends base_model {
    /**
     * Record ID.
     *
     * @var integer
     */
    protected $id;

    /**
     * Owning user ID.
     *
     * @var integer
     */
    protected $userid;

    /**
     * Activity objective.
     *
     * @var string
     */
    protected $objective;

    /**
     * Identified development need.
     *
     * @var string
     */
    protected $development_need;

    /**
     * Activity type ID.
     *
     * @var integer
     */
    protected $activitytypeid;

    /**
     * Activity description.
     *
     * @var string
     */
    protected $activity;

    /**
     * Due date (timestamp).
     *
     * @var integer
     */
    protected $duedate;

    /**
     * Start date (timestamp).
     *
     * @var integer
     */
    protected $startdate;

    /**
     * End date (timestamp).
     *
     * @var integer
     */
    protected $enddate;

    /**
     * Activity status ID.
     *
     * @var integer
     */
    protected $statusid;

    /**
     * CPD year ID.
     *
     * @var integer
     */
    protected $cpdyearid;

    /**
     * Time taken (seconds).
     *
     * @var integer
     */
    protected $timetaken;

    /**
     * @override \local_cpd\base_model
     */
    final protected static function model_accessors() {
        return array(
            'activitytype',
            'status',
        );
    }

    /**
     * Get the status.
     *
     * @return \local_cpd\status The CPD activity's status.
     */
    final protected function model_accessor_activitytype() {
        return activity_type::get_by_id($this->statusid);
    }

    /**
     * Get the status.
     *
     * @return \local_cpd\status The CPD activity's status.
     */
    final protected function model_accessor_status() {
        return status::get_by_id($this->statusid);
    }

    /**
     * @override \local_cpd\base_model
     */
    final protected static function model_fields() {
        return array(
            'id',
            'userid',
            'objective',
            'development_need',
            'activitytypeid',
            'activity',
            'duedate',
            'startdate',
            'enddate',
            'statusid',
            'cpdyearid',
            'timetaken',
        );
    }

    /**
     * @override \local_cpd\base_model
     */
    final protected static function model_table() {
        return 'cpd';
    }
}
