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

use local_cpd\event\activity_created;
use local_cpd\event\activity_updated;
use local_cpd\form\activity_form;
use local_cpd\model\activity;
use local_cpd\url_generator;
use local_cpd\util;

require_once dirname(dirname(__DIR__)) . '/config.php';
require_once __DIR__ . '/lib.php';

require_login();

$id     = optional_param('id',     0,         PARAM_INT);
$userid = optional_param('userid', $USER->id, PARAM_INT);

$editurl = url_generator::edit_activity();
$listurl = url_generator::index();

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
$user     = $isowncpd
        ? $USER : $DB->get_record('user', array('id' => $userid));

$context = context_user::instance($userid);
require_capability('local/cpd:edituserreport', $context);

$PAGE->set_context($context);
$PAGE->set_pagelayout('standard');
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
    $activity->id = ($id === 0) ? null : $id;
    $activity->save();

    $event = ($id === 0)
            ? activity_created::instance($activity, $context)
            : activity_updated::instance($activity, $context);
    $event->trigger();

    redirect($listurl);
} else {
    $mform->set_data($activity->model_to_form());

    echo $OUTPUT->header(),
         $OUTPUT->heading($titlestr);
    $mform->display();
    echo $OUTPUT->footer();
}
