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

use lang_string;
use moodle_url;
use navbar;
use navigation_node;

defined('MOODLE_INTERNAL') || die;

class util {
    /**
     * Action: edit activity.
     *
     * @var int
     */
    const ACTION_REPORT_VIEW = 1;

    /**
     * Action: delete activity.
     *
     * @var int
     */
    const ACTION_ACTIVITY_DELETE = 2;

    /**
     * Action: edit activity.
     *
     * @var int
     */
    const ACTION_ACTIVITY_EDIT = 3;

    /**
     * Action: log new activity.
     *
     * @var int
     */
    const ACTION_ACTIVITY_LOG = 4;

    /**
     * What is the module's name?
     *
     * @var string
     */
    const MOODLE_MODULE = 'local_cpd';

    /**
     * Normalise navigation bar.
     *
     * Moodle's navigation bar and block seem to get lost with our URLs, so
     * we'll manually configure them both here.
     *
     * @param \stdClass           $user     The user whose CPD report is being
     *                                      viewed or edited.
     * @param integer             $action   One of the ACTION_* constants.
     * @param \local_cpd\activity $activity (Optional) activity ID, if editing
     *                                      one.
     *
     * @return void
     */
    public static function normalise_navigation($user, $action, $activity=null) {
        global $PAGE, $USER;

        $PAGE->navbar->ignore_active();

        if ($user->id === $USER->id) {
            $PAGE->navbar->add(static::string('myprofile', null, 'moodle'));
            $PAGE->navbar->add(static::string('mycpd'), url_generator::index());
        } else {
            $PAGE->navbar->add(static::string('users', null, 'moodle'),
                               new moodle_url('/user/index.php',
                                              array('id' => $USER->id)));
            $PAGE->navbar->add(fullname($user),
                               new moodle_url('/user/profile.php',
                                              array('id' => $user->id)));
            $PAGE->navbar->add(static::string('cpd'),
                               url_generator::index($user->id));

            $PAGE->navigation->extend_for_user($user);
            $PAGE->navigation->get('myprofile')->collapse = true;
            $PAGE->navigation->find('local_cpd-my', navigation_node::TYPE_UNKNOWN)->make_inactive();
            $PAGE->navigation->find("local_cpd-{$user->id}", navigation_node::TYPE_UNKNOWN)->make_active();
        }

        switch ($action) {
            case static::ACTION_ACTIVITY_DELETE:
                $PAGE->navbar->add(static::string('deletingx',
                                                $activity->activity),
                                   url_generator::delete_activity($activity->id));
                break;

            case static::ACTION_ACTIVITY_EDIT:
                $PAGE->navbar->add(static::string('editingx',
                                   $activity->activity));
                break;

            case static::ACTION_ACTIVITY_LOG:
                $PAGE->navbar->add(static::string('logging'));
                break;
        }
    }

    /**
     * Get a language string.
     *
     * @param string          $string The name of the string to retrieve.
     * @param stdClass|string $a      An object or string containing
     *                                substitions to be made to the string's
     *                                value.
     * @param string          $module If retrieving a string from another Moodle
     *                                module, the name of the module.
     *
     * @return \lang_string The language string object.
     */
    public static function string($string, $a=null, $module=null) {
        $module = $module ?: static::MOODLE_MODULE;
        return new lang_string($string, $module, $a);
    }

    /**
     * Get a language string immediately.
     *
     * Returns the actual string instead of the intermediary lang_string
     * object, for inconsistent APIs that don't trigger __toString().
     *
     * @param string          $string The name of the string to retrieve.
     * @param stdClass|string $a      An object or string containing
     *                                substitions to be made to the string's
     *                                value.
     * @param string          $module If retrieving a string from another Moodle
     *                                module, the name of the module.
     *
     * @return string The language string.
     */
    public static function real_string($string, $a=null, $module=null) {
        $module = $module ?: static::MOODLE_MODULE;
        return get_string($string, static::MOODLE_MODULE, $a);
    }

    /**
     * Reduce a collection of model objects down to a single field.
     *
     * In the absence of a collection object to wrap sets of models, this is the
     * best we can do for now.
     *
     * @param stdClass[] $collection An array of objects.
     * @param string     $field      An individual field to retain.
     *
     * @return mixed[] An array containing the value of the specified field on
     *                 each object.
     */
    public static function reduce($collection, $field) {
        $result = array();

        foreach ($collection as $item) {
            $result[] = $item->{$field};
        }

        return $result;
    }

    /**
     * Does the string start with the substring?
     *
     * @param string $string    The larger of the two strings.
     * @param string $substring The substring to match at the beginning of the
     *                          string.
     *
     * @return boolean|string The remaining text after the substring if matched,
     *                        else false.
     */
    public static function starts_with($string, $substring) {
        if (substr_compare($string, $substring, 0) > 0) {
            return substr($string, strlen($substring));
        }

        return false;
    }
}
