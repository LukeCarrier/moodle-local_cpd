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

// Module metadata
$string['pluginname'] = 'CPD';

// Menu items and page titles
$string['cpd']                 = 'CPD';
$string['cpdforx']             = '{$a}\'s CPD';
$string['deletingx']           = 'Deleting "{$a}"';
$string['editingx']            = 'Editing "{$a}"';
$string['logging']             = 'Logging CPD activity';
$string['manageactivitytypes'] = 'Manage activity types';
$string['manageyears']         = 'Manage CPD years';
$string['mycpd']               = 'My CPD';

// Activity table
$string['activity']        = 'Activity';
$string['activitytype']    = 'Activity Type';
$string['datedue']         = 'Due Date';
$string['dateend']         = 'End Date';
$string['datestart']       = 'Start Date';
$string['developmentneed'] = 'Development Need';
$string['nocpdactivities'] = 'There are no activities to display.';
$string['objective']       = 'Objective';
$string['status']          = 'Status';
$string['timetaken']       = 'Time Taken';

// Year management table
$string['yearend']   = 'End date';
$string['yearstart'] = 'Start date';

// Year filter table
$string['filterby']      = 'Filter by';
$string['year']          = 'CPD year';
$string['yeardaterange'] = '{$a->startdate} - {$a->enddate}';

// Add activity callout
$string['logactivity']    = 'Log CPD activity';
$string['whylogactivity'] = 'Please ensure your CPD activity log is up to date by logging your CPD activities.';

// Confirmation prompts
$string['confirmdeleteofx'] = 'Are you sure you wish to delete "{$a}"?';

// Access capabilities
$string['cpd:edituserreport']      = 'Edit a user\'s CPD report';
$string['cpd:manageactivitytypes'] = 'Manage activity types';
$string['cpd:manageyears']         = 'Manage CPD years';
$string['cpd:viewuserreport']      = 'View a user\'s CPD report';

// Model exceptions
$string['model:incompleteimplementation'] = 'Attempt to use model "{$a}" which is incomplete';
$string['model:nosuchmethod']             = 'Attempt to call nonexistent method "$a"';
