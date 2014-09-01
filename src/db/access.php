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
);
