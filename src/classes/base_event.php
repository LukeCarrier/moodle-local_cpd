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
     * Throw an "incomplete event" exception.
     *
     * @throws \moodle_exception Always.
     */
    protected static function event_throw_incomplete() {
        throw new moodle_exception('event:incompleteimplementation',
                                   'local_cpd', '', get_called_class());
    }
}
