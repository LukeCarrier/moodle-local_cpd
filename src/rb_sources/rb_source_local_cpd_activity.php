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

use local_cpd\report_builder\abstract_source;
use local_cpd\model\activity_status;
use local_cpd\model\activity_type;
use local_cpd\util;

defined('MOODLE_INTERNAL') || die;

/**
 * Activity source for report builder.
 */
class rb_source_local_cpd_activity extends abstract_source {
    /**
     * @override \local_cpd\report_builder\abstract_source
     */
    public function __construct() {
        $this->base = '{cpd}';

        parent::__construct();
    }

    /**
     * @override \rb_base_source
     */
    protected function define_joinlist() {
        $joinlist = array(
            new rb_join(
                'cpdactivitytype',
                'LEFT',
                '{cpd_activity_type}',
                'cpdactivitytype.id = base.activitytypeid',
                REPORT_BUILDER_RELATION_ONE_TO_ONE
            ),

            new rb_join(
                'cpdactivitystatus',
                'LEFT',
                '{cpd_status}',
                'cpdactivitystatus.id = base.statusid',
                REPORT_BUILDER_RELATION_ONE_TO_ONE
            ),
        );

        $this->add_position_tables_to_joinlist($joinlist, 'base', 'userid');
        $this->add_manager_tables_to_joinlist($joinlist, 'position_assignment',
                                              'reportstoid');
        $this->add_user_table_to_joinlist($joinlist, 'base', 'userid');

        return $joinlist;
    }

    /**
     * @override \rb_base_source
     */
    protected function define_columnoptions() {
        $columnoptions = array(
            new rb_column_option(
                'local_cpd_activity', 'objective',
                util::string('objective'),
                'base.objective',
                array(
                    'dbdatatype'   => 'text',
                    'outputformat' => 'text',
                )
            ),

            new rb_column_option(
                'local_cpd_activity', 'developmentneed',
                util::string('developmentneed'),
                'base.developmentneed',
                array(
                    'dbdatatype'   => 'text',
                    'outputformat' => 'text',
                )
            ),

            new rb_column_option(
                'local_cpd_activity', 'activitytype',
                util::string('activitytype'),
                'cpdactivitytype.name',
                array(
                    'dbdatatype' => 'char',
                    'joins'      => 'cpdactivitytype',
                )
            ),

            new rb_column_option(
                'local_cpd_activity', 'activity',
                util::string('activity'),
                'base.activity',
                array(
                    'dbdatatype'   => 'text',
                    'outputformat' => 'text',
                )
            ),

            new rb_column_option(
                'local_cpd_activity', 'duedate',
                util::string('datedue'),
                'base.duedate',
                array(
                    'dbdatatype'  => 'timestamp',
                    'displayfunc' => 'nice_date_in_timezone',
                )
            ),

            new rb_column_option(
                'local_cpd_activity', 'startdate',
                util::string('datestart'),
                'base.startdate',
                array(
                    'dbdatatype'  => 'timestamp',
                    'displayfunc' => 'nice_date_in_timezone',
                )
            ),

            new rb_column_option(
                'local_cpd_activity', 'enddate',
                util::string('dateend'),
                'base.enddate',
                array(
                    'dbdatatype'  => 'timestamp',
                    'displayfunc' => 'nice_date_in_timezone',
                )
            ),

            new rb_column_option(
                'local_cpd_activity', 'activitystatus',
                util::string('activitystatus'),
                'cpdactivitystatus.name',
                array(
                    'dbdatatype' => 'char',
                    'joins'      => 'cpdactivitystatus',
                )
            ),

            new rb_column_option(
                'local_cpd_activity', 'timetaken',
                util::string('timetaken'),
                'base.timetaken',
                array(
                    'dbdatatype'  => 'integer',
                    'displayfunc' => 'hours_minutes',
                )
            ),
        );

        $this->add_position_fields_to_columns($columnoptions);
        $this->add_manager_fields_to_columns($columnoptions);
        $this->add_user_fields_to_columns($columnoptions);

        return $columnoptions;
    }

    /**
     * @override \rb_base_source
     */
    protected function define_filteroptions() {
        $filteroptions = array(
            new rb_filter_option(
                'local_cpd_activity', 'activitytype',
                util::string('activitytype'),
                'select',
                array(
                    'attributes' => rb_filter_option::select_width_limiter(),
                    'selectfunc' => 'cpd_activity_type_list',
                ),
                'base.activitytypeid'
            ),

            new rb_filter_option(
                'local_cpd_activity', 'activitystatus',
                util::string('activitystatus'),
                'select',
                array(
                    'attributes' => rb_filter_option::select_width_limiter(),
                    'selectfunc' => 'cpd_activity_status_list',
                ),
                'base.statusid'
            ),
        );

        $this->add_manager_fields_to_filters($filteroptions);
        $this->add_position_fields_to_filters($filteroptions);
        $this->add_user_fields_to_filters($filteroptions);

        return $filteroptions;
    }

    /**
     * @override \rb_base_source
     */
    protected function define_contentoptions() {
        $contentoptions = array(
            new rb_content_option(
                'current_pos',
                util::string('currentpos', null, 'totara_reportbuilder'),
                'position.path',
                'position'
            ),

            new rb_content_option(
                'current_org',
                util::string('currentorg', null, 'totara_reportbuilder'),
                'organisation.path',
                'organisation'
            ),

            new rb_content_option(
                'user',
                util::string('user', null, 'rb_source_facetoface_sessions'),
                array(
                    'userid'      => 'base.userid',
                    'managerid'   => 'position_assignment.managerid',
                    'managerpath' => 'position_assignment.managerpath',
                    'postype'     => 'position_assignment.type',
                ),
                'position_assignment'
            ),

            new rb_content_option(
                'date',
                util::string('thedate', null, 'rb_source_facetoface_sessions'),
                'sessiondate.timestart',
                'sessiondate'
            ),
        );

        return $contentoptions;
    }

    /**
     * @override \rb_base_source
     */
    protected function define_paramoptions() {
        $paramoptions = array(
            new rb_param_option(
                'userid',
                'base.userid'
            ),
        );

        return $paramoptions;
    }

    /**
     * @override \rb_base_source
     */
    protected function define_defaultcolumns() {
        $defaultcolumns = array(
            array(
                'type'  => 'user',
                'value' => 'namelink',
            ),
        );

        return $defaultcolumns;
    }

    /**
     * @override \rb_base_source
     */
    public function define_requiredcolumns() {
        $requiredcolumns = array();

        return $requiredcolumns;
    }

    /**
     * @override \rb_base_source
     */
    public function define_defaultfilters() {
        $defaultfilters = array(
            array(
                'type'  => 'user',
                'value' => 'fullname',
            ),
        );

        return $defaultfilters;
    }

    /**
     * Get a list of CPD activity statuses.
     *
     * @return string[] An ID-indexed array of CPD activity status names.
     */
    public function rb_filter_cpd_activity_status_list() {
        return activity_status::menu();
    }

    /**
     * Get a list of CPD activity types.
     *
     * @return string[] An ID-indexed array of CPD activity type names.
     */
    public function rb_filter_cpd_activity_type_list() {
        return activity_type::menu();
    }
}
