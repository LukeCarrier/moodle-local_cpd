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
Feature: Manage own CPD records
  In order to record continuing professional development activities
  As a user
  I need to create and manage my CPD records

  @javascript
  Scenario: View existing CPD records
    Given the following "users" exist:
      | username | firstname | lastname | email             |
      | user1    | User      | 1        | user1@example.com |
    And I log in as "user1"
    And I am on homepage
    And I navigate to "My CPD" node in "My profile"
    Then I should see "Please ensure your CPD activity log is up to date by logging your CPD activities"

  @javascript
  Scenario: Create own CPD record
    Given the following "users" exist:
      | username | firstname | lastname | email         |
      | user1    | User      | 1        | user1@asd.com |
    And I log in as "user1"
    And I am on homepage
    And I navigate to "My CPD" node in "My profile"
    And I click on "Log CPD activity" "button"
    And I set the following fields to these values:
      | Objective            | Develop Behat testing skill                                              |
      | Development Need     | Understand Moodle's Behat extension sufficiently to write detailed tests |
      | Activity Type        | On-the-job training                                                      |
      | Activity Description | Trial, error and lots of debugging                                       |
      | Status               | Started                                                                  |
    And I set the following fields to these values:
      | id_duedate_day      | 1        |
      | id_duedate_month    | October  |
      | id_duedate_year     | 2015     |
      | id_startdate_day    | 30       |
      | id_startdate_month  | October  |
      | id_startdate_year   | 2014     |
      | id_enddate_day      | 1        |
      | id_enddate_month    | December |
      | id_enddate_year     | 2014     |
      | id_timetaken_number | 6        |
    And I click on "Save changes" "button"
    Then I should see "Develop Behat testing skill"

  @javascript
  Scenario: Delete own CPD record
    Given the following "users" exist:
      | username | firstname | lastname | email         |
      | user1    | User      | 1        | user1@asd.com |
    And the following local_cpd "activities" exist:
      | User  | Objective                   | Development Need           | Activity Type       | Activity Description               | Status  | Due Date   | Start Date | End Date   | Time Taken |
      | user1 | Develop Behat testing skill | Understand data generators | On-the-job training | Trial, error and lots of debugging | Started | 01/10/2015 | 30/10/2014 | 01/12/2014 | 1800       |
    And I log in as "user1"
    And I am on homepage
    And I navigate to "My CPD" node in "My profile"
    And I click on "a[title=Delete]" "css_element" in the "Develop Behat testing skill" "table_row"
    And I click on "Continue" "button"
    Then I should not see "Develop Behat testing skill"
