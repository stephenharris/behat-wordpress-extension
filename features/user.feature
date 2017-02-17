Feature: Managing users

  Background:
    Given I am logged in as an admin
		And I am on the Dashboard

  @javascript
  Scenario: I can add a new user
    Given I go to menu item "Users"
    When I click on the "Add New" link in the header
    Then I should be on the "Add New User" page

    When I fill in "user_login" with "neweditor"
    And I fill in "email" with "neweditor@example.org"
    And I fill in "first_name" with "Ed"
    And I fill in "last_name" with "New"
    And I select "editor" from "role"
    And I press "Add New User"
    Then I should see a status message that says "New user created"

  @javascript
  Scenario: I can go straight to the Add New User screen
    When I go to menu item "Users > Add New"
    Then I should be on the "Add New User" page
