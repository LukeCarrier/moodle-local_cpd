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
 * CPD activity.
 *
 * Activities contain the bulk of information in a user's CPD report. They are
 * connected to status and activity type records.
 */
class activity extends base_model {
    /**
     * Editor fields.
     *
     * @var string[]
     */
    protected static $editorfields = array(
        'activity',
        'development_need',
        'objective',
    );

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
     * Activity objective format.
     *
     * @var integer
     */
    protected $objective_fmt;

    /**
     * Identified development need.
     *
     * @var string
     */
    protected $development_need;

    /**
     * Identified development need format.
     *
     * @var integer
     */
    protected $development_need_fmt;

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
     * Activity description.
     *
     * @var integer
     */
    protected $activity_fmt;

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
     * Time taken (seconds).
     *
     * @var integer
     */
    protected $timetaken;

    /**
     * Initialiser.
     *
     * @param integer $userid
     * @param string  $objective
     * @param integer $objective_fmt
     * @param string  $development_need
     * @param integer $development_need_fmt
     * @param integer $activitytypeid
     * @param string  $activity
     * @param integer $activity_fmt
     * @param integer $duedate
     * @param integer $enddate
     * @param integer $statusid
     * @param integer $timetaken
     */
    final public function __construct($userid=null, $objective=null,
            $objective_fmt=null, $development_need=null,
            $development_need_fmt=null, $activitytypeid=null, $activity=null,
            $activity_format=null, $duedate=null, $startdate=null,
            $enddate=null, $statusid=null, $timetaken=null) {
        $this->userid               = $userid;
        $this->objective            = $objective;
        $this->objective_fmt        = $objective_fmt;
        $this->development_need     = $development_need;
        $this->development_need_fmt = $development_need_fmt;
        $this->activitytypeid       = $activitytypeid;
        $this->activity             = $activity;
        $this->activity_fmt         = $activity_format;
        $this->duedate              = $duedate;
        $this->startdate            = $startdate;
        $this->enddate              = $enddate;
        $this->statusid             = $statusid;
        $this->timetaken            = $timetaken;
    }

    /**
     * Get formatted activity name.
     *
     * @return string The formatted activity name.
     */
    public function get_activity_text() {
        return format_text($this->activity, $this->activity_fmt);
    }

    /**
     * Get formatted development need.
     *
     * @return string The formatted development need.
     */
    public function get_development_need_text() {
        return format_text($this->development_need, $this->development_need_fmt);
    }

    /**
     * Get formatted objective.
     *
     * @return string The formatted objective.
     */
    public function get_objective_text() {
        return format_text($this->objective, $this->objective_fmt);
    }

    /**
     * Get time taken value in seconds.
     *
     * Most date functions depend on this, but for some reason report_cpd stores
     * a minute value.
     */
    public function get_timetaken_seconds() {
        return $this->timetaken * MINSECS;
    }

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
        return activity_type::get_by_id($this->activitytypeid);
    }

    /**
     * Get the status.
     *
     * @return \local_cpd\activity_status The CPD activity's status.
     */
    final protected function model_accessor_status() {
        return activity_status::get_by_id($this->statusid);
    }

    /**
     * @override \local_cpd\base_model
     */
    final public static function model_from_form($data) {
        return new static($data->userid, $data->objective['text'],
                          $data->objective['format'],
                          $data->development_need['text'],
                          $data->development_need['format'],
                          $data->activitytypeid, $data->activity['text'],
                          $data->activity['format'], $data->duedate,
                          $data->startdate, $data->enddate,
                          $data->status, $data->timetaken / MINSECS);
    }

    /**
     * @override \local_cpd\base_model
     */
    final public function model_to_form() {
        $formdata = parent::model_to_form();

        foreach (static::$editorfields as $field) {
            $formdata->{$field} = array(
                'format' => $this->{"{$field}_fmt"},
                'text'   => $this->{$field},
            );
        }

        /* XXX: the duration form element in Moodle forms is pretty bad at
         *      discerning an appropriate unit of time to represent this value */
        $formdata->timetaken = $this->get_timetaken_seconds();

        return $formdata;
    }

    /**
     * @override \local_cpd\base_model
     */
    final protected static function model_fields() {
        return array(
            'id',
            'userid',
            'objective',
            'objective_fmt',
            'development_need',
            'development_need_fmt',
            'activitytypeid',
            'activity',
            'activity_fmt',
            'duedate',
            'startdate',
            'enddate',
            'statusid',
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
