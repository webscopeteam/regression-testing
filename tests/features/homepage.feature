@api @homepage
Feature: Homepage

When users come to the homepage I want them to view a complete page with content

  Scenario: View homepage
    Given I am an anonymous user
    And "article" content:
      | title                | status | body                       |
      | Test article 1 | 1      | Test article content |
      | Test article 2 | 1      | Test article content |
      | Test article 3 | 1      | Test article content |
    When I am at "/"
    Then I should see "Test article 1"
