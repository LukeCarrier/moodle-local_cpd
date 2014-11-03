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

use core\event\base;

/**
 * Base event class.
 *
 * Moodle's new event system is incredibly powerful, but has a pretty clunky
 * interface. In the interests of avoiding duplication, we'll wrap events in our
 * own base class and extend it.
 */
abstract class base_event extends base {
    /**
     * Legacy log data key: course ID.
     *
     * @var integer
     */
    const LEGACY_LOGDATA_COURSEID = 0;

    /**
     * Legacy log data key: module.
     *
     * @var integer
     */
    const LEGACY_LOGDATA_MODULE = 1;

    /**
     * Legacy log data key: action.
     *
     * @var integer
     */
    const LEGACY_LOGDATA_ACTION = 2;

    /**
     * Legacy log data key: URL.
     *
     * @var integer
     */
    const LEGACY_LOGDATA_URL = 3;

    /**
     * Legacy log data key: additional information.
     *
     * @var integer
     */
    const LEGACY_LOGDATA_INFO = 4;

    /**
     * Legacy log data key: user ID.
     *
     * @var integer
     */
    const LEGACY_LOGDATA_USERID = 5;

    /**
     * Throw an "incomplete event" exception.
     *
     * @throws \moodle_exception Always.
     */
    protected static function event_throw_incomplete() {
        throw new moodle_exception('event:incompleteimplementation',
                                   'local_cpd', '', get_called_class());
    }

    /**
     * @override \core\event\base
     */
    public static function get_name() {
        static::event_throw_incomplete();
    }

    /**
     * @override \core\event\base
     */
    public function get_description() {
        static::event_throw_incomplete();
    }

    /**
     * Get description substitutions.
     *
     * @return \stdClass An object containing the objectid and userid properties
     *                   for substitution into language strings.
     */
    public function get_description_subs() {
        return (object) array(
            'objectid' => $this->objectid,
            'userid'   => $this->userid,
        );
    }

    /**
     * @override \core\event\base
     */
    public function get_legacy_logdata() {
        return array(
            static::LEGACY_LOGDATA_COURSEID => get_site()->id,
            static::LEGACY_LOGDATA_MODULE   => 'local_cpd',
            static::LEGACY_LOGDATA_ACTION   => '',
            static::LEGACY_LOGDATA_URL      => $this->get_url(),
            static::LEGACY_LOGDATA_INFO     => $this->get_description(),
            static::LEGACY_LOGDATA_USERID   => $this->userid,
        );
    }

    /**
     * @override \core\event\base
     */
    public function get_url() {
        static::event_throw_incomplete();
    }

    /**
     * @override \core\event\base
     */
    protected function init() {
        static::event_throw_incomplete();
    }
}
