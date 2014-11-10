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

namespace local_cpd\form;

use local_cpd\model\activity_status;
use local_cpd\model\activity_type;
use local_cpd\util;
use moodleform;

defined('MOODLE_INTERNAL') || die;

require_once "{$CFG->libdir}/formslib.php";

class activity_form extends moodleform {
    public function definition() {
        $mform = $this->_form;

        /* userid is handled in edit.php instead of being assigned a hidden
         * field within the form to avoid potential security issues .*/

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('editor', 'objective', util::string('objective'));
        $mform->setType('editor', PARAM_TEXT);

        $mform->addElement('date_selector', 'duedate', util::string('datedue'));
        $mform->setType('duedate', PARAM_INT);

        $mform->addElement('editor', 'developmentneed',
                           util::string('developmentneed'));
        $mform->setType('developmentneed', PARAM_TEXT);

        $mform->addElement('select', 'activitytypeid',
                           util::string('activitytype'), activity_type::menu());
        $mform->setType('activitytypeid', PARAM_INT);

        $mform->addElement('editor', 'activity', util::string('activity'));
        $mform->setType('activity', PARAM_TEXT);

        $mform->addElement('date_selector', 'startdate',
                           util::string('datestart'));
        $mform->setType('startdate', PARAM_INT);

        $mform->addElement('date_selector', 'enddate',
                           util::string('dateend'));
        $mform->setType('enddate', PARAM_INT);

        $mform->addElement('select', 'status', util::string('activitystatus'),
                           activity_status::menu());
        $mform->setType('status', PARAM_INT);

        $mform->addElement('duration', 'timetaken', util::string('timetaken'));
        $mform->setType('timetaken', PARAM_INT);

        $this->add_action_buttons();
    }
}
