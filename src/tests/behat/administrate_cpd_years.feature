#
# Moodle/Totara LMS CPD plugin.
#
# A modern replacement for the legacy report_cpd module, which has numerous
# security issues. Supports the existing tables without any fuss.
#
# @author Luke Carrier <luke@carrier.im> <luke@tdm.co>
# @copyright 2014 Luke Carrier, The Development Manager Ltd
# @license GPL v3
#

@local_cpd
Feature: Administrate CPD years
  In order to manage reporting of CPD records
  As an admin
  I need to create and manage CPD years

  @javascript
  Scenario: View existing CPD years
    Given I log in as "admin"
    And I am on homepage
    And I navigate to "Manage CPD years" node in "Site administration > Plugins > Local plugins > CPD"
    Then I should see "CPD years are used as a tool to filter recorded CPD activities"

  @javascript
  Scenario: Create new CPD year
    Given I log in as "admin"
    And I am on homepage
    And I navigate to "Manage CPD years" node in "Site administration > Plugins > Local plugins > CPD"
    And I click on "Add CPD year" "button"
    And I set the following fields to these values:
      | id_startdate_day    | 1        |
      | id_startdate_month  | January  |
      | id_startdate_year   | 2014     |
      | id_enddate_day      | 31       |
      | id_enddate_month    | December |
      | id_enddate_year     | 2014     |
    And I click on "Save changes" "button"
    Then I should see "1 January 2014"
    And I should see "31 December 2014"

  @javascript
  Scenario: Delete existing CPD year
    Given the following local_cpd "years" exist:
      | Start Date | End Date   |
      | 01/01/2014 | 31/12/2014 |
    And I log in as "admin"
    And I am on homepage
    And I navigate to "Manage CPD years" node in "Site administration > Plugins > Local plugins > CPD"
    And I click on "a[title=Delete]" "css_element" in the "1 January 2014" "table_row"
    Then I should not see "1 January 2014"
    And I should not see "31 December 2014"
