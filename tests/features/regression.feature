@api @regression
Feature: Regression
  As a developer
  When make style changes
  They shouldn't impact things they aren't supposed to

  @javascript
  Scenario: Article regression test
    Given I am an anonymous user
    And I am viewing an "article":
      | title       | Test article 1               |
      | status      | 1                            |
      | body        | Test article body content    |
      | field_image | tests/files/black_rabbit.jpg |
    Then I should see "Test article 1"
    Then the screenshot should be equal to "test.png"

  @javascript @regression_baseline
  Scenario: Article regression baseline screenshot
    Given I am an anonymous user
    And I am viewing an "article":
      | title       | Test article 1               |
      | status      | 1                            |
      | body        | Test article body content    |
    Then I take a regression screenshot named "article"
