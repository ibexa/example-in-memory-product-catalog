@remotePIM @javascript
Feature: Remote PIM

    Background:
        Given I am logged as admin

    Scenario: Product Types can be listef
        Given I open "Product Types" page in admin SiteAccess
        Then there's a "Example" on Product Types list
        And there's a "Demo" on Product Types list
