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

namespace local_cpd\report_builder;

use local_cpd\util;
use rb_base_source;

defined('MOODLE_INTERNAL') || die;

/**
 * Abstract report source.
 *
 * Provides some additional utility functions over the stock report builder
 * base class.
 */
abstract class abstract_source extends rb_base_source {
    public $base, $joinlist, $columnoptions, $filteroptions;
    public $contentoptions, $paramoptions, $defaultcolumns;
    public $defaultfilters, $sourcetitle, $requiredcolumns;

    /**
     * Initialiser.
     *
     * @override \rb_base_source
     */
    public function __construct() {
        $this->sourcetitle = static::string('sourcetitle');
        
        $this->joinlist        = $this->define_joinlist();
        $this->columnoptions   = $this->define_columnoptions();
        $this->filteroptions   = $this->define_filteroptions();
        $this->contentoptions  = $this->define_contentoptions();
        $this->paramoptions    = $this->define_paramoptions();
        $this->defaultcolumns  = $this->define_defaultcolumns();
        $this->requiredcolumns = $this->define_requiredcolumns();
        $this->defaultfilters  = $this->define_defaultfilters();

        parent::__construct();
    }

    /**
     * Get a language string.
     *
     * @param string          $string The name of the string to retrieve.
     * @param stdClass|string $a      An object or string containing
     *                                substitions to be made to the string's
     *                                value.
     *
     * @return \lang_string The language string object.
     */
    protected static function string($string, $a=null) { 
       return util::string($string, $a, get_called_class());
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
     *
     * @return string The language string.
     */
    protected static function real_string($string, $a=null) {
        return util::real_string($string, $a, get_called_class());
    }
}
