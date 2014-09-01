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
use local_cpd\activity_filter_form;
use local_cpd\activity_type;
use local_cpd\util;
use local_cpd\year;

require_once dirname(dirname(__DIR__)) . '/config.php';

require_once __DIR__ . '/lib.php';

require_login();

$userid = optional_param('userid', $USER->id, PARAM_INT);

$isowncpd = $userid === $USER->id;
$user     = $isowncpd ? $USER : core_user::get_user($userid);

$deleteurl = new moodle_url('/local/cpd/delete.php');
$editurl   = new moodle_url('/local/cpd/edit.php');
$listurl   = new moodle_url('/local/cpd/index.php');

$context = context_user::instance($user->id);
require_capability('local/cpd:viewuserreport', $context);

if ($isowncpd) {
    $titlestr = util::string('mycpd');
} else {
    $titlestr = util::string('cpdforx', fullname($user));
    $editurl->param('userid', $user->id);
}

$PAGE->set_context($context);
$PAGE->set_title($titlestr);
$PAGE->set_url($listurl);

util::normalise_navigation($user, util::ACTION_REPORT_VIEW);

$filterform = new activity_filter_form(null, null, 'get');
$filterform->set_data(array('userid' => $user->id));

$PAGE->requires->css(new moodle_url('/local/cpd/style.css'));

$renderer = $PAGE->get_renderer('local_cpd');

if ($filters = $filterform->get_data()) {
    if ($filters->filteryearstartdate || $filters->filteryearenddate) {
        $years = year::find_between($filters->filteryearstartdate,
                                    $filters->filteryearenddate);
    } elseif (!empty($filters->filteryearids)) {
        $years = array();
        foreach ($filters->filteryearids as $id) {
            $years[] = year::get_by_id($id);
        }
    } else {
        $years = year::all();
    }
} else {
    $years = year::all();
}

if (count($years)) {
    $yearids = util::reduce($years, 'id');
    list($sql, $params) = $DB->get_in_or_equal($yearids);
    $params[] = $user->id;
    $activities = activity::find_select("cpdyearid {$sql} AND userid = ?", $params);
} else {
    $activities = array();
}

echo
        $OUTPUT->header(),
        $OUTPUT->heading($titlestr);
$filterform->display();
echo
        $renderer->cpd_activity_table($activities, $editurl, $deleteurl),
        $renderer->cpd_activity_add($editurl),
        $OUTPUT->footer();
