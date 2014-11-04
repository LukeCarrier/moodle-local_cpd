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
$string['cpd']                    = 'CPD';
$string['cpdforx']                = '{$a}\'s CPD';
$string['deletingx']              = 'Deleting \'{$a}\'';
$string['editingx']               = 'Editing \'{$a}\'';
$string['editingactivitystatusx'] = 'Editing activity status \'{$a}\'';
$string['editingactivitytypex']   = 'Editing activity type \'{$a}\'';
$string['editingyearx']           = 'Editing CPD year \'{$a}\'';
$string['logging']                = 'Logging CPD activity';
$string['loggingactivitytype']    = 'Logging activity type';
$string['loggingactivitystatus']  = 'Logging activity status';
$string['loggingyear']            = 'Logging CPD year';
$string['manageactivitytypes']    = 'Manage activity types';
$string['manageactivitystatuses'] = 'Manage activity statuses';
$string['manageyears']            = 'Manage CPD years';
$string['mycpd']                  = 'My CPD';

// Activity table
$string['activity']        = 'Activity Description';
$string['activitystatus']  = 'Activity Status';
$string['activitytype']    = 'Activity Type';
$string['datedue']         = 'Due Date';
$string['dateend']         = 'End Date';
$string['datestart']       = 'Start Date';
$string['developmentneed'] = 'Development Need';
$string['nocpdactivities'] = 'There are no activities to display.';
$string['objective']       = 'Objective';
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

// Add activity status callout
$string['addactivitystatus']     = 'Add activity status';
$string['addactivitystatusdesc'] = 'CPD activity statuses demonstrate progression through a given activity to meet a specific development need. Create activity statuses here to match your internal CPD workflow.';

// Add activity type callout
$string['addactivitytype']     = 'Add activity type';
$string['addactivitytypedesc'] = 'Activity types allow you to control the types of activity which count towards CPD.';

// Add year callout
$string['addyear']     = 'Add CPD year';
$string['addyeardesc'] = 'CPD years are used as a tool to filter recorded CPD activities. New CPD years will need to be periodically added in accordance with your CPD calendar.';

// Confirmation prompts
$string['confirmdeleteofx'] = 'Are you sure you wish to delete \'{$a}\'?';

// Access capabilities
$string['cpd:edituserreport']         = 'Edit a user\'s CPD report';
$string['cpd:manageactivitytypes']    = 'Manage activity types';
$string['cpd:manageactivitystatuses'] = 'Manage activity statuses';
$string['cpd:manageyears']            = 'Manage CPD years';
$string['cpd:viewuserreport']         = 'View a user\'s CPD report';

// Model exceptions
$string['model:incompleteimplementation'] = 'Attempt to use model \'{$a}\' which is incomplete';
$string['model:nosuchmethod']             = 'Attempt to call nonexistent method "$a"';

// Event exceptions
$string['event:incompleteimplementation'] = 'Attempt to use event \'{$a}\' which is incomplete';

// CPD activity events
$string['event:activitycreated']     = 'CPD activity created';
$string['event:activitycreateddesc'] = 'The user with ID \'{$a->userid}\' created a CPD activity with ID \'{$a->objectid}\' for subject with ID \'{$a->relateduserid}\'';
$string['event:activitydeleted']     = 'CPD activity deleted';
$string['event:activitydeleteddesc'] = 'The user with ID \'{$a->userid}\' deleted the CPD activity with ID \'{$a->objectid}\' for subject with ID \'{$a->relateduserid}\'';
$string['event:activityupdated']     = 'CPD activity updated';
$string['event:activityupdateddesc'] = 'The user with ID \'{$a->userid}\' updated the CPD activity with ID \'{$a->objectid}\' for subject with ID \'{$a->relateduserid}\'';

// CPD activity status events
$string['event:activitystatuscreated']     = 'CPD activity status created';
$string['event:activitystatuscreateddesc'] = 'The user with ID \'{$a->userid}\' created a CPD activity status with ID \'{$a->objectid}\'';
$string['event:activitystatusdeleted']     = 'CPD activity status deleted';
$string['event:activitystatusdeleteddesc'] = 'The user with ID \'{$a->userid}\' deleted the CPD activity status with ID \'{$a->objectid}\'';
$string['event:activitystatusupdated']     = 'CPD activity status updated';
$string['event:activitystatusupdateddesc'] = 'The user with ID \'{$a->userid}\' updated the CPD activity status with ID \'{$a->objectid}\'';

// CPD activity type events
$string['event:activitytypecreated']     = 'CPD activity type created';
$string['event:activitytypecreateddesc'] = 'The user with ID \'{$a->userid}\' created a CPD activity type with ID \'{$a->objectid}\'';
$string['event:activitytypedeleted']     = 'CPD activity type deleted';
$string['event:activitytypedeleteddesc'] = 'The user with ID \'{$a->userid}\' deleted the CPD activity type with ID \'{$a->objectid}\'';
$string['event:activitytypeupdated']     = 'CPD activity type updated';
$string['event:activitytypeupdateddesc'] = 'The user with ID \'{$a->userid}\' updated the CPD activity type with ID \'{$a->objectid}\'';

// CPD year events
$string['event:yearcreated']     = 'CPD year created';
$string['event:yearcreateddesc'] = 'The user with ID \'{$a->userid}\' created a CPD year with ID \'{$a->objectid}\'';
$string['event:yeardeleted']     = 'CPD year deleted';
$string['event:yeardeleteddesc'] = 'The user with ID \'{$a->userid}\' deleted the CPD year with ID \'{$a->objectid}\'';
$string['event:yearupdated']     = 'CPD year updated';
$string['event:yearupdateddesc'] = 'The user with ID \'{$a->userid}\' updated the CPD year with ID \'{$a->objectid}\'';
