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

class activity_filter_form extends moodleform {
    /**
     * @override \moodleform
     */
    public function definition() {
        $mform = $this->_form;

        $mform->addElement('hidden', 'userid');
        $mform->setType('userid', PARAM_INT);

        $select = $mform->addElement('select', 'filteryearids',
                                     util::string('year'), year::menu());
        $select->setMultiple(true);
        $mform->setType('filteryearid', PARAM_INT);
        
        $mform->addElement('header', 'daterangefilters',
                           util::string('filterby'));
        $mform->addElement('date_selector', 'filteryearstartdate',
                           util::string('datestart'),
                           array('optional' => true));
        $mform->addElement('date_selector', 'filteryearenddate',
                           util::string('dateend'),
                           array('optional' => true));

        $this->add_action_buttons(false, util::string('view', null, 'moodle'));
    }
}
