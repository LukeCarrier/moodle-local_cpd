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

// Menu items and page titles
$string['logging']   = 'Logging CPD activity';
$string['cpdforx']   = '{$a}\'s CPD';
$string['deletingx'] = 'Deleting "{$a}"';
$string['editingx']  = 'Editing "{$a}"';
$string['mycpd']     = 'My CPD';

// Table
$string['activity']        = 'Activity';
$string['activitytype']    = 'Activity Type';
$string['developmentneed'] = 'Development Need';
$string['datedue']         = 'Due Date';
$string['dateend']         = 'End Date';
$string['objective']       = 'Objective';
$string['datestart']       = 'Start Date';
$string['status']          = 'Status';
$string['timetaken']       = 'Time Taken';
$string['nocpdactivities'] = 'There are no activities to display.';

// Add activity section of list
$string['whylogactivity'] = 'Please ensure your CPD activity log is up to date by logging your CPD activities.';
$string['logactivity']    = 'Log CPD activity';

// Confirmation prompts
$string['confirmdeleteofx'] = 'Are you sure you wish to delete "{$a}"?';

// Access capabilities
$string['cpd:edituserreport'] = 'Edit a user\'s CPD report';
$string['cpd:viewuserreport'] = 'View a user\'s CPD report';

// Model exceptions
$string['model:nosuchmethod']             = 'Attempt to call nonexistent method "$a"';
$string['model:incompleteimplementation'] = 'Attempt to use model "{$a}" which is incomplete';
