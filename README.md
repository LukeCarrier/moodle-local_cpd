Log CPD activities within Moodle
================================

Allows Moodle users to log their CPD via their user profiles.

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/LukeCarrier/moodle-local_cpd/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/LukeCarrier/moodle-local_cpd/?branch=master)

License
-------

    Copyright (c) Luke Carrier, The Development Manager Ltd

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

Requirements
------------

* Moodle 2.5+
* PHP 5.3+

Upgrading from report_cpd
-------------------------

1. Back up your Moodle database!
2. Download an archive of the plugin and extract its contents into the ```local``` directory
3. Execute ```php local/cpd/cli/upgrade_from_report_cpd.php```
4. Remove the ```report/cpd``` directory
5. Execute ```php admin/cli/upgrade.php```

Building
--------

1. Clone this repository, and ````cd```` into it
2. Execute ````make```` to generate a zip file containing the plugin
3. Upload to the ````moodle.org```` plugins site
