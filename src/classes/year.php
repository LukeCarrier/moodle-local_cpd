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
                                   array($startdate, $enddate));
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
            $menu[$year->id] = util::string('yeardaterange', (object) array(
                'startdate' => userdate($year->startdate,
                                        util::string('strftimedate', null,
                                                     'langconfig')),
                'enddate'   => userdate($year->enddate,
                                        util::string('strftimedate', null,
                                                     'langconfig')),
            ));
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
