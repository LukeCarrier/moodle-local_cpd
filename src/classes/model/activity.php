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
        'developmentneed',
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
    protected $objectivefmt;

    /**
     * Identified development need.
     *
     * @var string
     */
    protected $developmentneed;

    /**
     * Identified development need format.
     *
     * @var integer
     */
    protected $developmentneedfmt;

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
    protected $activityfmt;

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
     * @param integer $objectivefmt
     * @param string  $developmentneed
     * @param integer $developmentneedfmt
     * @param integer $activitytypeid
     * @param string  $activity
     * @param integer $activityfmt
     * @param integer $duedate
     * @param integer $enddate
     * @param integer $statusid
     * @param integer $timetaken
     */
    final public function __construct($userid=null, $objective=null,
            $objectivefmt=null, $developmentneed=null,
            $developmentneedfmt=null, $activitytypeid=null, $activity=null,
            $activityfmt=null, $duedate=null, $startdate=null,
            $enddate=null, $statusid=null, $timetaken=null) {
        $this->userid             = $userid;
        $this->objective          = $objective;
        $this->objectivefmt       = $objectivefmt;
        $this->developmentneed    = $developmentneed;
        $this->developmentneedfmt = $developmentneedfmt;
        $this->activitytypeid     = $activitytypeid;
        $this->activity           = $activity;
        $this->activityfmt         = $activityfmt;
        $this->duedate            = $duedate;
        $this->startdate          = $startdate;
        $this->enddate            = $enddate;
        $this->statusid           = $statusid;
        $this->timetaken          = $timetaken;
    }

    /**
     * Get formatted activity name.
     *
     * @return string The formatted activity name.
     */
    public function get_activity_text() {
        return format_text($this->activity, $this->activityfmt);
    }

    /**
     * Get formatted development need.
     *
     * @return string The formatted development need.
     */
    public function get_developmentneed_text() {
        return format_text($this->developmentneed, $this->developmentneedfmt);
    }

    /**
     * Get formatted objective.
     *
     * @return string The formatted objective.
     */
    public function get_objective_text() {
        return format_text($this->objective, $this->objectivefmt);
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
                          $data->developmentneed['text'],
                          $data->developmentneed['format'],
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
                'format' => $this->{"{$field}fmt"},
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
    final public static function model_fields() {
        return array(
            'id',
            'userid',
            'objective',
            'objectivefmt',
            'developmentneed',
            'developmentneedfmt',
            'activitytypeid',
            'activity',
            'activityfmt',
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
    final public static function model_table() {
        return 'cpd';
    }
}
