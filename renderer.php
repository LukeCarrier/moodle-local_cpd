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

use local_cpd\util;

defined('MOODLE_INTERNAL') || die;

/**
 * CPD renderer.
 */
class local_cpd_renderer extends plugin_renderer_base {
    /**
     * Render CPD activity table from a list of entries.
     *
     * @param \local_cpd\activity[] $activities The records to render.
     */
    public function cpd_activity_table($activities) {
        $table = new html_table();
        $table->head = array(
            'objective'        => util::string('objective'),
            'development_need' => util::string('developmentneed'),
            'activity_type'    => util::string('activitytype'),
            'activity'         => util::string('activity'),
            'due_date'         => util::string('datedue'),
            'start_date'       => util::string('datestart'),
            'end_date'         => util::string('dateend'),
            'status'           => util::string('status'),
            'timetaken'        => util::string('timetaken'),
        );

        foreach ($activities as $activity) {
            $activitystatus = $activity->status;
            $activitytype   = $activity->activitytype;

            $table->data[] = array(
                $activity->objective,
                $activity->development_need,
                $activitytype->name,
                $activity->activity,
                $activity->duedate,
                $activity->startdate,
                $activity->enddate,
                $activitystatus->name,
                $activity->timetaken,
            );
        }

        return html_writer::table($table);
    }
}
