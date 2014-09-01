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
use local_cpd\activity_form;
use local_cpd\util;

require_once dirname(dirname(__DIR__)) . '/config.php';

require_login();

$id     = optional_param('id',     null,      PARAM_INT);
$userid = optional_param('userid', $USER->id, PARAM_INT);

$editurl = new moodle_url('/local/cpd/edit.php');
$listurl = new moodle_url('/local/cpd/index.php');

/* We need to handle the user context instance with care.
 *
 * We allow viewing and modifying another user's CPD report by people holding
 * the appropriate capabilities within the target user's context.
 *
 * -> When creating a new CPD activity record, we allow userid to be passed as a
 *    GET parameter.
 *
 * -> When editing an existing CPD record, we need to use the userid property on
 *    the CPD activity record, as using the GET parameter would allow users to
 *    supply the ID of another user whilst editing a record belonging to a
 *    third. */

if ($id) {
    $activity = activity::get_by_id($id);
    $titlestr = util::string('editingx', $activity->activity);
    $userid   = $activity->userid;

    $editurl->param('id', $activity->id);
    if ($userid !== $USER->id) {
        $editurl->param('userid', $activity->userid);
    }
} else {
    $activity = new activity();
    $titlestr = util::string('logging');
}

$isowncpd = $userid === $USER->id;
$user     = $isowncpd ? $USER : core_user::get_user($userid);

$context = context_user::instance($userid);
require_capability('local/cpd:edituserreport', $context);

$PAGE->set_context($context);
$PAGE->set_title($titlestr);
$PAGE->set_url($editurl);

if ($id) {
    util::normalise_navigation($user, util::ACTION_ACTIVITY_EDIT, $activity);
} else {
    util::normalise_navigation($user, util::ACTION_ACTIVITY_LOG);
}

$mform = new activity_form();

if ($mform->is_cancelled()) {
    redirect($listurl);
} elseif ($data = $mform->get_data()) {
    $data->userid = $userid;
    $activity = activity::model_from_form($data);
    $activity->save();
    redirect($listurl);
} else {
    $mform->set_data($activity->model_to_form());

    echo
            $OUTPUT->header(),
            $OUTPUT->heading($titlestr);
    $mform->display();
    echo
            $OUTPUT->footer();
}
