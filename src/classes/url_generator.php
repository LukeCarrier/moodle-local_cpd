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

namespace local_cpd;

use moodle_url;

/**
 * URL generator.
 *
 * Generates URLs for various routes. All methods within this class return
 * Moodle URL instances, allowing for customisation and duplication after
 * generation.
 */
class url_generator {
    /**
     * CPD base URL.
     *
     * @var string
     */
    const CPD_URL = '/local/cpd';

    /**
     * Get item's deletion URL.
     *
     * @param string  $endpoint The URL of the endpoint, relative to CPD_URL.
     * @param integer $itemid   The ID of the item to delete.
     * @param string  $sesskey  The session key/nonce, for CSRF prevention.
     *
     * If $sesskey is omitted, the user will be prompted to confirm the deletion
     * of the item.
     *
     * @return \moodle_url The deletion URL.
     */
    protected static function delete($endpoint, $itemid, $sesskey=null) {
        $url = new moodle_url(static::CPD_URL . $endpoint, array(
            'id' => $itemid,
        ));

        if ($sesskey !== null) {
            $url->param('sesskey', $sesskey);
        }

        return $url;
    }

    /**
     * Get activity deletion URL.
     *
     * @param integer $activityid The ID of the activity to delete.
     * @param string  $sesskey    The session key/nonce, for CSRF prevention.
     *
     * @return \moodle_url The activity deletion URL.
     */
    public static function delete_activity($activityid=null, $sesskey=null) {
        return static::delete('/delete.php', $activityid, $sesskey);
    }

    /**
     * Get activity type deletion URL.
     *
     * @param integer $activitytypeid The ID of the year to delete.
     * @param string  $sesskey        The session key/nonce, for CSRF
     *                                prevention.
     *
     * @return \moodle_url The activity type deletion URL.
     */
    public static function delete_activity_type($activitytypeid=null,
                                                $sesskey=null) {
        return static::delete('/deleteactivitytype.php', $activitytypeid,
                              $sesskey);
    }

    /**
     * Get status deletion URL.
     *
     * @param integer $statusid The ID of the status to delete.
     * @param string  $sesskey  The session key/nonce, for CSRF prevention.
     *
     * @return \moodle_url The status deletion URL.
     */
    public static function delete_activity_status($statusid=null, $sesskey=null) {
        return static::delete('/deleteactivitystatus.php', $statusid, $sesskey);
    }

    /**
     * Get year deletion URL.
     *
     * @param integer $yearid  The ID of the year to delete.
     * @param string  $sesskey The session key/nonce, for CSRF prevention.
     *
     * @return \moodle_url The year deletion URL.
     */
    public static function delete_year($yearid=null, $sesskey=null) {
        return static::delete('/deleteyear.php', $yearid, $sesskey);
    }

    /**
     * Get item edit URL.
     *
     * @param integer $itemid The ID of the activity to edit.
     *
     * @return \moodle_url The item edit URL.
     */
    public static function edit($endpoint, $itemid=null) {
        return new moodle_url(static::CPD_URL . $endpoint, array(
            'id' => $itemid,
        ));
    }

    /**
     * Get activity edit URL.
     *
     * @param integer $activityid The ID of the activity to edit.
     *
     * @return \moodle_url The activity edit URL.
     */
    public static function edit_activity($activityid=null) {
        return static::edit('/edit.php', $activityid);
    }

    /**
     * Get activity type edit URL.
     *
     * @param integer $activitytypeid The ID of the activity type to edit.
     *
     * @return \moodle_url The activity type edit URL.
     */
    public static function edit_activity_type($activitytypeid=null) {
        return static::edit('/editactivitytype.php', $activitytypeid);
    }

    /**
     * Get status edit URL.
     *
     * @param integer $statusid The ID of the status to edit.
     *
     * @return \moodle_url The status edit URL.
     */
    public static function edit_activity_status($statusid=null) {
        return static::edit('/editactivitystatus.php', $statusid);
    }

    /**
     * Get year edit URL.
     *
     * @param integer $yearid The ID of the year to edit.
     *
     * @return \moodle_url The year edit URL.
     */
    public static function edit_year($yearid=null) {
        return static::edit('/edityear.php', $yearid);
    }

    /**
     * Get activity type list URL.
     *
     * @return \moodle_url The activity type list URL.
     */
    public static function list_activity_type() {
        return new moodle_url(static::CPD_URL . '/manageactivitytypes.php');
    }

    /**
     * Get status list URL.
     *
     * @return \moodle_url The status list URL.
     */
    public static function list_activity_status() {
        return new moodle_url(static::CPD_URL . '/manageactivitystatuses.php');
    }

    /**
     * Get year list URL.
     *
     * @return \moodle_url The year list URL.
     */
    public static function list_year() {
        return new moodle_url(static::CPD_URL . '/manageyears.php');
    }

    /**
     * Get CPD index URL.
     *
     * @param integer $userid Optional user ID, if linking to another user's CPD
     *                        report.
     *
     * @return \moodle_url The index URL.
     */
    public static function index($userid=null) {
        $url = new moodle_url(static::CPD_URL . '/index.php');

        if ($userid !== null) {
            $url->param('userid', $userid);
        }

        return $url;
    }
}
