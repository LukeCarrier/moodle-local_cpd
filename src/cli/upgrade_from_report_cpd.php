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

// Clear PHP's opcode cache if enabled
if (function_exists('opcache_reset')
        && !array_key_exists('REMOTE_ADDR', $_SERVER)) {
    opcache_reset();
}

// Make Moodle behave as if it's performing an ordinary upgrade
define('CACHE_DISABLE_ALL', true);
define('CLI_SCRIPT',        true);

require_once dirname(dirname(dirname(__DIR__))) . '/config.php';
require_once "{$CFG->libdir}/clilib.php";

list($options, $unrecognized) = cli_get_params(
    array(
        'force' => false,
    )
);

// Query plugin information
$pluginmgr = core_plugin_manager::instance();
$reportplugin = $pluginmgr->get_plugin_info('report_cpd');

// Bail if the schema isn't at the expected version
if ((!$reportplugin->is_installed_and_upgraded()
        || $reportplugin->version !== 2012042601) && !$options['force']) {
    cli_problem('Requires report_cpd version 2012042601!');
    cli_error("You can --force the upgrade if you're feeling confident.");
}

// Remove report_cpd
unset_config('version', 'report_cpd');
$reportplugin->uninstall_cleanup();

// Trick Moodle into not installing tables from install.xml
set_config('version', 1, 'local_cpd');

// Purge the caches
set_config('allversionshash', '');
cache_helper::purge_all(true);

// Let the user know it's not quite in the bag
mtrace('Upgrade complete!');
mtrace('You should now remove the report_cpd plugin directory (below) from');
mtrace('your server to prevent future reinstallations of the plugin:');
mtrace('');
mtrace("    {$CFG->dirroot}/report/cpd");
mtrace('');
mtrace('Then execute the upgrades to ensure any schema changes are applied:');
mtrace('');
mtrace('    php admin/cli/upgrade.php');
