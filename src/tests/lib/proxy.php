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

// MOODLE_INTERNAL check omitted for Behat

/**
 * Proxy to an object.
 *
 * Because the Moodle Behat implementation won't have included config.php (and
 * defined MOODLE_INTERNAL) at the time the steps definitions classes are
 * instantiated, we need an intermediary library which can instantiate the data
 * data generator classes when necessary. This class will likely come in handy
 * for other purposes as we expand the test suite.
 */
class local_cpd_proxy {
    /**
     * Name of the proxied class or a callable to instantiate it.
     *
     * @var callable|string
     */
    protected $classnameorcallable;

    /**
     * Generator filename.
     *
     * @var string
     */
    protected $filename;

    /**
     * The instance of the proxied class.
     *
     * @var \stdClass
     */
    protected $instance;

    /**
     * Initialiser.
     */
    public function __construct($classnameorcallable, $filename) {
        $this->classnameorcallable = $classnameorcallable;
        $this->filename            = $filename;
    }

    /**
     * Proxy a method call.
     *
     * @param string  $method    The name of the method called on the proxy
     *                           object.
     * @param mixed[] $arguments Arguments passed to the method.
     */
    public function __call($method, $arguments) {
        $callable = array($this->get_proxied_instance(), $method);

        return call_user_func_array($callable, $arguments);
    }

    /** 
     * Attempt to instantiate the proxied class.
     *
     * @return \stdClass An instance of the proxied class.
     */
    protected function get_proxied_instance() {
        if ($this->instance === null) {
            require_once $this->filename;

            if (is_callable($this->classnameorcallable)) {
                $this->instance = call_user_func($this->classnameorcallable);
            } else {
                $this->instance = new $this->classnameorcallable();
            }
        }

        return $this->instance;
    }
}
