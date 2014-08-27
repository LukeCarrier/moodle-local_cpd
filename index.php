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
use local_cpd\activity_type;
use local_cpd\util;

require_once dirname(dirname(__DIR__)) . '/config.php';

require_once __DIR__ . '/lib.php';

require_login(); // $USER might not be set
$userid = optional_param('userid', $USER->id, PARAM_INT);

$isowncpd = $userid === $USER->id;

$context = context_user::instance($userid);
require_capability('local/cpd:viewuserreport', $context);

$deleteurl = new moodle_url('/local/cpd/delete.php');
$editurl   = new moodle_url('/local/cpd/edit.php');
$listurl   = new moodle_url('/local/cpd/index.php');

if ($isowncpd) {
    $titlestr = util::string('mycpd');
} else {
    $titlestr = util::string('cpdforx', fullname($USER));
    $editurl->param('userid', $userid);
}

$PAGE->set_context($context);
$PAGE->set_title($titlestr);
$PAGE->set_url($listurl);

$PAGE->requires->css(new moodle_url('/local/cpd/style.css'));

$renderer = $PAGE->get_renderer('local_cpd');

$activities = activity::find_by_userid($userid);

echo
        $OUTPUT->header(),
        $OUTPUT->heading($titlestr),
        $renderer->cpd_activity_table($activities, $editurl, $deleteurl),
        $renderer->cpd_activity_add($editurl),
        $OUTPUT->footer();
