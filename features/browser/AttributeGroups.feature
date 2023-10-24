@remotePIM @javascript
Feature: Remote PIM

    Background:
        Given I am logged as admin

    Scenario: Attribute groups can be listed
        Given I open "Attribute groups" page in admin SiteAccess
        Then there is an Attribute group "DEFAULT"

    Scenario: Attribute group can be viewed
        Given I open "DEFAULT" Attribute group page in admin SiteAccess
        Then I should see an Attribute group with values
            | Name     | Identifier | Position  |
            | DEFAULT  | default    | 0         |
