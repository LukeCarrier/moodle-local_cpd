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

use local_cpd\activity;
use local_cpd\util;

require_once dirname(dirname(__DIR__)) . '/config.php';

require_once __DIR__ . '/lib.php';

require_login(); // $USER might not be set
$userid = optional_param('userid', $USER->id, PARAM_INT);

$context = context_user::instance($userid);
require_capability('local/cpd:viewuserreport', $context);

$titlestr = ($USER->id == $userid)
        ? util::string('mycpd') : util::string('cpdforx', fullname($USER));

$PAGE->set_context($context);
$PAGE->set_title($titlestr);
$PAGE->set_url(new moodle_url('/local/cpd/index.php'));

$renderer = $PAGE->get_renderer('local_cpd');

$activities = activity::find_by_userid($userid);

echo
        $OUTPUT->header(),
        $OUTPUT->heading($titlestr),
        $renderer->cpd_activity_table($activities),
        $OUTPUT->footer();
