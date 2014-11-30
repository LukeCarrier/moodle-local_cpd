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

defined('MOODLE_INTERNAL') || die;

/**
 * CPD renderer.
 */
class local_cpd_renderer extends plugin_renderer_base {
    /**
     * Render a list of action buttons.
     *
     * @param \action_link[] $actionbuttons An array of action button components
     *                                      to render.
     *
     * @return string The renderered HTML.
     */
    protected function cpd_action_buttons($actionbuttons) {
        $renderedactionbuttons = array();
        foreach ($actionbuttons as $actionbutton) {
            $renderedactionbuttons[] = $this->render($actionbutton);
        }

        return html_writer::alist($renderedactionbuttons, array(
            'class' => 'cpd-actions',
        ));
    }
    /**
     * Render the "Add CPD activity" select and button.
     *
     * @param \moodle_url $editurl The URL to link edit buttons to.
     *
     * @return string The rendered HTML.
     */
    public function cpd_callout($label, $url, $text) {
        $content = html_writer::tag('span', $label, array('class' => 'cpd-callout-notice'))
                 . $this->single_button($url, $text, 'get');

        return $this->box($content, 'generalbox cpd-callout');
    }

    /**
     * Do the ground work for rendering a table.
     *
     * @param mixed[] $head      An array of html_table_cell objects or strings.
     * @param string  $editurl   The URL to link edit buttons to.
     * @param string  $deleteurl The URL to link delete buttons to.
     *
     * @return mixed[] A numerically-indexed array containing three values:
     *                 [0] => html_table  $table
     *                 [1] => action_link $deletelink
     *                 [2] => action_link $editlink
     */
    protected function cpd_generic_table($head, $editurl, $deleteurl) {
        $table = new html_table();
        $table->head = $head;

        $deleteicon = new pix_icon('t/delete', new lang_string('delete'));
        $deletelink = new action_link($deleteurl, $deleteicon, null,
                                      array('title' => new lang_string('delete')));
        $editicon   = new pix_icon('t/edit', new lang_string('edit'));
        $editlink   = new action_link($editurl, $editicon, null,
                                      array('title' => new lang_string('edit')));

        return array($table, $deletelink, $editlink);
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
        $head = array(
            util::string('objective'),
            util::string('developmentneed'),
            util::string('activitytype'),
            util::string('activity'),
            util::string('datedue'),
            util::string('datestart'),
            util::string('dateend'),
            util::string('activitystatus'),
            util::string('timetaken'),
            new lang_string('actions'),
        );

        list($table, $editlink, $deletelink)
                = $this->cpd_generic_table($head, $editurl, $deleteurl);

        foreach ($activities as $activity) {
            $activitystatus = $activity->status;
            $activitytype   = $activity->activitytype;

            $deletelink->url->param('id', $activity->id);
            $editlink->url->param('id', $activity->id);

            $actionbuttons = $this->cpd_action_buttons(array(
                $editlink,
                $deletelink,
            ));

            $table->data[] = array(
                $activity->objective,
                $activity->developmentneed,
                $activitytype->name,
                $activity->activity,
                userdate($activity->duedate,   util::string('strftimedate', null, 'langconfig')),
                userdate($activity->startdate, util::string('strftimedate', null, 'langconfig')),
                userdate($activity->enddate,   util::string('strftimedate', null, 'langconfig')),
                $activitystatus->name,
                format_time($activity->get_timetaken_seconds()),
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

    /**
     * Render CPD activity status table.
     *
     * @param \local_cpd\activity_status[] $activitystatuses The array of
     *                                                       activity_status
     *                                                       objects for which
     *                                                       to render a table.
     * @param \moodle_url                  $editurl          The URL to link
     *                                                       edit buttons to.
     * @param \moodle_url                  $deleteurl        The URL to link
     *                                                       delete buttons to.
     *
     * @return The generated HTML.
     */
    public function cpd_activity_status_table($activitystatuses, $editurl,
                                              $deleteurl) {
        return $this->cpd_generic_named_item_table('name',
                                                   util::string('activitystatus'),
                                                   $activitystatuses, $editurl,
                                                   $deleteurl);
    }

    /**
     * Render CPD activity type table.
     *
     * @param \local_cpd\activity_type[] $activitytypes The array of
     *                                                  activity_type objects
     *                                                  for which to render a
     *                                                  table.
     * @param \moodle_url                $editurl       The URL to link edit
     *                                                  buttons to.
     * @param \moodle_url                $deleteurl     The URL to link delete
     *                                                  buttons to.
     *
     * @return The generated HTML.
     */
    public function cpd_activity_type_table($activitytypes, $editurl,
                                            $deleteurl) {
        return $this->cpd_generic_named_item_table('name',
                                                   util::string('activitytype'),
                                                   $activitytypes, $editurl,
                                                   $deleteurl);
    }

    /**
     * Render generic CPD named item table.
     *
     * @param string                  $nameproperty The name of the property on
     *                                              each object which contains
     *                                              the desired name value.
     * @param string                  $namestring   A human-readable string for
     *                                              the table's header.
     * @param \local_cpd\base_model[] $items        The items which should
     *                                              populate the table's body.
     * @param \moodle_url             $editurl      The URL to link edit buttons
     *                                              to.
     * @param \moodle_url             $deleteurl    The URL to link delete
     *                                              buttons to.
     *
     * @return The generated HTML.
     */
    public function cpd_generic_named_item_table($nameproperty, $namestring,
                                                 $items, $editurl, $deleteurl) {
        $head = array(
            $namestring,
        );

        list($table, $editlink, $deletelink)
                = $this->cpd_generic_table($head, $editurl, $deleteurl);

        foreach ($items as $item) {
            $deletelink->url->param('id', $item->id);
            $editlink->url->param('id', $item->id);

            $actionbuttons = $this->cpd_action_buttons(array(
                $editlink,
                $deletelink,
            ));

            $table->data[] = array(
                $item->name,
                $actionbuttons,
            );
        }

        return html_writer::table($table);
    }

    /**
     * Render CPD year table.
     *
     * @param \local_cpd\year[] $years     The array of year objects for which to
     *                                     render a table.
     * @param string            $editurl   The URL to link edit buttons to.
     * @param string            $deleteurl The URL to link delete buttons to.
     *
     * @return string The generated HTML.
     */
    public function cpd_year_table($years, $editurl, $deleteurl) {
        $head = array(
            util::string('yearstart'),
            util::string('yearend'),
            util::string('actions', null, 'moodle'),
        );

        list($table, $editlink, $deletelink)
                = $this->cpd_generic_table($head, $editurl, $deleteurl);

        foreach ($years as $year) {
            $deletelink->url->param('id', $year->id);
            $editlink->url->param('id', $year->id);

            $actionbuttons = $this->cpd_action_buttons(array(
                $editlink,
                $deletelink,
            ));

            $table->data[] = array(
                userdate($year->startdate, util::string('strftimedate', null, 'langconfig')),
                userdate($year->enddate,   util::string('strftimedate', null, 'langconfig')),
                $actionbuttons,
            );
        }

        return html_writer::table($table);
    }
}
