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

namespace local_cpd\event;

use context_system;
use local_cpd\base_event;
use local_cpd\model\activity_status;

defined('MOODLE_INTERNAL') || die;

abstract class activity_status_base extends base_event {
    /**
     * Rapidly instantiate the event.
     *
     * @param \local_cpd\model\activity_status $activitytype The affected CPD
     *                                                     activity type.
     *
     * @return \local_cpd\event\activity_status_base The event.
     */
    final public static function instance($activitytype) {
        return static::create(array(
            'objectid' => $activitytype->id,
            'context'  => context_system::instance(),
        ));
    }

    /**
     * @override \core\event\base
     */
    protected function init() {
        $this->data['edulevel'] = static::LEVEL_OTHER;

        $this->data['objecttable'] = activity_status::model_table();
    }
}
