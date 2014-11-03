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

use local_cpd\base_event;
use local_cpd\model\activity;

defined('MOODLE_INTERNAL') || die;

abstract class activity_base extends base_event {
    /**
     * Rapidly instantiate the event.
     *
     * @param \local_cpd\model\activity $activity The affected CPD activity.
     * @param \context_user             $context  The parent user's context.
     *
     * @return \local_cpd\event\activity_base The event.
     */
    final public static function instance($activity, $context) {
        return static::create(array(
            'objectid'      => $activity->id,
            'context'       => $context,
            'relateduserid' => $context->instanceid,
        ));
    }

    /**
     * @override \local_cpd\base_event
     */
    public function get_description_subs() {
        $a = parent::get_description_subs();

        $a->relateduserid = $this->relateduserid;
        
        return $a;
    }

    /**
     * @override \local_cpd\base_event
     */
    protected function init() {
        $this->data['edulevel'] = static::LEVEL_PARTICIPATING;

        $this->data['objecttable'] = activity::model_table();
    }
}
