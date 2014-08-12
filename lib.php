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

/**
 * Extend the site navigation
 *
 * @return void
 */
function local_cpd_extends_navigation(global_navigation $navroot) {
    $navprof = $navroot->find('myprofile', navigation_node::TYPE_ROOTNODE);

    $navnode = $navprof->add(get_string('mycpd', 'local_cpd'),
                             new moodle_url('/local/cpd/index.php'),
                             navigation_node::NODETYPE_LEAF,
                             get_string('cpd', 'report_cpd'),
                             'local_cpd-mycpd');
}
