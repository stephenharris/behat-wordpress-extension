Feature: Widgets

  Scenario: Viewing a widget
    Given I have the "RSS" widget in "Blog Sidebar"
      | Title   | Url                              | Items   |
      | My feed | https://wordpress.org/news/feed/ | 3       |
    And I am on "/"
    Then I should see "My feed"
