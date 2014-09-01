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

$id = required_param('id', PARAM_INT);

$context = context_user::instance($activity->userid);
require_login();
require_capability('local/cpd:edituserreport', $context);

$activity = activity::get_by_id($id);

$deleteurl = new moodle_url('/local/cpd/delete.php', array(
    'id' => $activity->id,
));
$listurl = new moodle_url('/local/cpd/index.php');

$titlestr = util::string('deletingx', $activity->activity);

$PAGE->set_context($context);
$PAGE->set_title($titlestr);
$PAGE->set_url($deleteurl);

$PAGE->navigation->find('local_cpd-mycpd')->make_active();
$PAGE->navbar->add($titlestr);

if (confirm_sesskey()) {
    $activity->delete();
    redirect($listurl);
} else {
    $deleteurl->param('sesskey', sesskey());

    echo
            $OUTPUT->header(),
            $OUTPUT->confirm(util::string('confirmdeleteofx', $activity->activity),
                             $deleteurl, $listurl),
            $OUTPUT->footer();
}
