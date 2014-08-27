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

        $mform->addElement('editor', 'development_need',
                           util::string('developmentneed'));
        $mform->setType('development_need', PARAM_TEXT);

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

        $mform->addElement('select', 'status', util::string('status'),
                           activity_status::menu());
        $mform->setType('status', PARAM_INT);

        $mform->addElement('duration', 'timetaken', util::string('timetaken'));
        $mform->setType('timetaken', PARAM_INT);

        $this->add_action_buttons();
    }
}
