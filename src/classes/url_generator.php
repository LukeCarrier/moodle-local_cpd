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
     * Get activity deletion URL.
     *
     * @param integer $activityid The ID of the activity to delete.
     * @param string  $sesskey    The session key/nonce, for CSRF prevention.
     *
     * If $sesskey is omitted, the user will be prompted to confirm the deletion
     * of the activity.
     *
     * @return \moodle_url The activity deletion URL.
     */
    public static function delete_activity($activityid=null, $sesskey=null) {
        $url = new moodle_url(static::CPD_URL . '/delete.php', array(
            'id' => $activityid,
        ));

        if ($sesskey !== null) {
            $url->param('sesskey', $sesskey);
        }

        return $url;
    }

    /**
     * Get activity edit URL.
     *
     * @param integer $activityid The ID of the activity to edit.
     *
     * @return \moodle_url The activity edit URL.
     */
    public static function edit_activity($activityid=null) {
        return new moodle_url(static::CPD_URL . '/edit.php', array(
            'id' => $activityid,
        ));
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
