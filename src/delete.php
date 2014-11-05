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

use local_cpd\event\activity_deleted;
use local_cpd\model\activity;
use local_cpd\url_generator;
use local_cpd\util;

require_once dirname(dirname(__DIR__)) . '/config.php';
require_once __DIR__ . '/lib.php';

$id      = required_param('id',          PARAM_INT);
$sesskey = optional_param('sesskey', '', PARAM_TEXT);

$activity = activity::get_by_id($id);

$isowncpd = $activity->userid === $USER->id;
$user     = $isowncpd
        ? $USER : $DB->get_record('user', array('id' => $activity->userid));

$context = context_user::instance($user->id);
require_login();
require_capability('local/cpd:edituserreport', $context);

$deleteurl = url_generator::delete_activity($activity->id);
$listurl   = url_generator::index();

$titlestr = util::string('deletingx', $activity->activity);

$PAGE->set_context($context);
$PAGE->set_pagelayout('standard');
$PAGE->set_title($titlestr);
$PAGE->set_url($deleteurl);

util::normalise_navigation($user, util::ACTION_ACTIVITY_DELETE, $activity);

if ($sesskey && confirm_sesskey()) {
    $activity->delete();

    activity_deleted::instance($activity, $context)->trigger();

    redirect($listurl);
} else {
    $deleteurl->param('sesskey', sesskey());

    echo $OUTPUT->header(),
         $OUTPUT->confirm(util::string('confirmdeleteofx', $activity->activity),
                          $deleteurl, $listurl),
         $OUTPUT->footer();
}
