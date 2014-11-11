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