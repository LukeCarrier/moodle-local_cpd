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

// Module metadata
$string['pluginname'] = 'CPD';

// Model exceptions
$string['model:nosuchmethod']             = 'Attempt to call nonexistent method "$a"';
$string['model:incompleteimplementation'] = 'Attempt to use model "$a" which is incomplete';
