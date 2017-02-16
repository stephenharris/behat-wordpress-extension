Feature: REST API

  Scenario: Making a GET request
	  Given I am logged in as an admin
	  And I authenticate via oauth 1
    When I send a GET request to "http://behat.dev/wp-json/wp/v2/users/me"
