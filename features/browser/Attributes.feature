@remotePIM @javascript
Feature: Remote PIM

    Background:
        Given I am logged as admin

    Scenario: Attributes can be listed
        Given I open "Attributes" page in admin SiteAccess
        Then there is an Attribute "Height"
        And there is an Attribute "Width"

    Scenario: Attribute can be viewed
        Given I open "Height" Attribute page in admin SiteAccess
        And I should see an Attribute with values
            | Name    | Identifier | Type    | Group   | Position |
            | Height  | height     | integer | DEFAULT | 0        |
