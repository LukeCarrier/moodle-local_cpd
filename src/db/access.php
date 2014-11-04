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

defined('MOODLE_INTERNAL') || die;

$capabilities = array(

    /*
     * View a user's CPD report.
     */
    'local/cpd:viewuserreport' => array(
        'captype'      => 'write',
        'contextlevel' => CONTEXT_USER,

        'archetypes' => array(
            'user' => CAP_ALLOW,
        ),
    ),

    /*
     * Modify a user's CPD report.
     */
    'local/cpd:edituserreport' => array(
        'captype'      => 'write',
        'contextlevel' => CONTEXT_USER,

        'archetypes' => array(
            'user' => CAP_ALLOW,
        ),
    ),

    /*
     * Manage activity types.
     */
    'local/cpd:manageactivitytypes' => array(
        'captype'      => 'write',
        'contextlevel' => CONTEXT_SYSTEM,

        'archetypes' => array(
            'manager' => CAP_ALLOW,
        ),

        'clonepermissionsfrom' => 'moodle/site:config',
    ),

    /*
     * Manage statuses.
     */
    'local/cpd:manageactivitystatuses' => array(
        'captype'      => 'write',
        'contextlevel' => CONTEXT_SYSTEM,

        'archetypes' => array(
            'manager' => CAP_ALLOW,
        ),

        'clonepermissionsfrom' => 'moodle/site:config',
    ),

    /*
     * Manage CPD years.
     */
    'local/cpd:manageyears' => array(
        'captype'      => 'write',
        'contextlevel' => CONTEXT_SYSTEM,

        'archetypes' => array(
            'manager' => CAP_ALLOW,
        ),

        'clonepermissionsfrom' => 'moodle/site:config',
    ),

);
