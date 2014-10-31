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

use local_cpd\util;

require_once __DIR__ . '/lib.php';

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {
    $ADMIN->add('localplugins', new admin_category(
        'local_cpd',
        util::string('cpd')
    ));

    $ADMIN->add('local_cpd', new admin_externalpage(
        'local_cpd_manageactivitytypes',
        util::string('manageactivitytypes'),
        new moodle_url('/local/cpd/manageactivitytypes.php'),
        'local/cpd:manageactivitytypes'
    ));

    $ADMIN->add('local_cpd', new admin_externalpage(
        'local_cpd_manageyears',
        util::string('manageyears'),
        new moodle_url('/local/cpd/manageyears.php'),
        'local/cpd:manageyears'
    ));
}
