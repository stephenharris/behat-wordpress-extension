Feature: Widgets

  Scenario: Adding a widget
    Given I have the "RSS" widget in "Sidebar"
      | Title   | Url                              | Items   |
      | My feed | https://wordpress.org/news/feed/ | 3       |
    And I am on the homepage
    Then I should see "My feed"
