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

use local_cpd\model\activity_status;
use local_cpd\url_generator;
use local_cpd\util;

require_once dirname(dirname(__DIR__)) . '/config.php';
require_once "{$CFG->libdir}/adminlib.php";
require_once __DIR__ . '/lib.php';

$editurl   = url_generator::edit_activity_status(null);
$deleteurl = url_generator::delete_activity_status(null, sesskey());

admin_externalpage_setup('local_cpd_manageactivitystatuses');

$titlestr = util::string('manageactivitystatuses');
$PAGE->set_title($titlestr);

$PAGE->requires->css(url_generator::CPD_URL . '/style.css');

$renderer = $PAGE->get_renderer('local_cpd');

$statuses = activity_status::all();

echo $OUTPUT->header(),
     $OUTPUT->heading($titlestr),
     $renderer->cpd_activity_status_table($statuses, $editurl, $deleteurl),
     $renderer->cpd_callout(util::string('addactivitystatusdesc'), $editurl,
                            util::string('addactivitystatus')),
     $OUTPUT->footer();
