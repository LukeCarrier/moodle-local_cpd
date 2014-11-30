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

use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;

// MOODLE_INTERNAL check omitted for Behat

require_once dirname(dirname(dirname(dirname(__DIR__)))) . '/lib/behat/behat_base.php';

/**
 * Behat steps for the CPD plugin.
 */
class behat_local_cpd extends behat_base {
    /**
     * Map of friendly names to internal names.
     *
     * @var string[]
     */
    protected static $elements = array(
        'activities'        => 'activity',
        'activity statuses' => 'activity_status',
        'activity types'    => 'activity_type',
        'years'             => 'year',
    );

    /**
     * Create the specified elements.
     *
     * @Given /^the following local_cpd "(?P<element_string>(?:[^"]|\\")*)" exist:$/
     *
     * @param string                       $element The name of the element type
     *                                              to create.
     * @param Behat\Gherkin\Node\TableNode $data    Rows containing element
     *                                              field values.
     */
    public function the_following_local_cpd_exist($element, TableNode $table) {
        $data           = $table->getHash();
        $machineelement = static::$elements[$element];

        $generator       = behat_util::get_data_generator();
        $plugingenerator = $generator->get_plugin_generator('local_cpd');

        $method = array($plugingenerator, "create_{$machineelement}");

        if (!is_callable($method)) {
            throw new PendingException();
        }

        foreach ($data as $options) {
            call_user_func($method, $options);
        }
    }
}
