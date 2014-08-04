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

defined('MOODLE_INTERNAL') || die;

// Release information
$plugin->component = 'local_cpd';
$plugin->release   = '0.1.0';
$plugin->maturity  = MATURITY_ALPHA;

// Version format:  YYYYMMDDXX
$plugin->version  = 2014073100;
$plugin->requires = null;
