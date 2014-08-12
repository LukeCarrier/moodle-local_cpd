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
     * Render the "Add CPD activity" select and button.
     *
     * @param \moodle_url $editurl The URL to link edit buttons to.
     *
     * @return string The rendered HTML.
     */
    public function cpd_activity_add($editurl) {
        $content = html_writer::tag('span', util::string('whylogactivity'), array('class' => 'cpd-why-log'))
                 . $this->single_button($editurl, util::string('logactivity'), 'get');

        return $this->box($content, 'generalbox cpd-add-box');
    }

    /**
     * Render CPD activity table from a list of entries.
     *
     * @param \local_cpd\activity[] $activities The records to render.
     * @param \moodle_url           $editurl    The URL to link edit buttons to.
     * @param \moodle_url           $deleteurl  the URL to link delete buttons
     *                                          to.
     *
     * @return string The rendered HTML.
     */
    public function cpd_activity_table($activities, $editurl, $deleteurl) {
        $table = new html_table();
        $table->head = array(
            util::string('objective'),
            util::string('developmentneed'),
            util::string('activitytype'),
            util::string('activity'),
            util::string('datedue'),
            util::string('datestart'),
            util::string('dateend'),
            util::string('status'),
            util::string('timetaken'),
            new lang_string('actions'),
        );

        $deleteicon = new action_link(new moodle_url('/local/cpd/delete.php', array(
            'id'      => null,
            'sesskey' => sesskey(),
        )), new pix_icon('t/delete', new lang_string('delete')));
        $editicon   = new action_link(new moodle_url('/local/cpd/edit.php',   array(
            'id' => null,
        )), new pix_icon('t/edit', new lang_string('edit')));

        foreach ($activities as $activity) {
            $activitystatus = $activity->status;
            $activitytype   = $activity->activitytype;

            $deleteicon->url->param('id', $activity->id);
            $editicon->url->param('id', $activity->id);

            $actionbuttons = $this->render($editicon)
                           . $this->render($deleteicon);

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
                $actionbuttons,
            );
        }

        if (count($activities) === 0) {
            $emptycell = new html_table_cell(util::string('nocpdactivities'));
            $emptycell->colspan = count($table->head);
            $table->data[] = array($emptycell);
        }

        return html_writer::table($table);
    }
}
