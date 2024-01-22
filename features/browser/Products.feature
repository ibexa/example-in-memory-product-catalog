@remotePIM @javascript
Feature: Remote PIM

    Background:
        Given I am logged as admin

    Scenario: Products can be listed
        Given I open "Products" page in admin SiteAccess
        Then there's a "Demo Product 1" on Products list

    Scenario: Products can viewed
        Given I open "Demo Product 1" Product page in admin SiteAccess
        And product attributes equal
            | label  | value | type    |
            | Height | 1     | integer |
            | Width  | 1     | integer |

    Scenario: Base price in EUR can be added to Product
        Given I open "Demo Product 1" Product page in admin SiteAccess
        When I change tab to "Prices"
        And I set a Base price to "10.00" in "EUR"
        And I click on the edit action bar button "Save and close"
        Then I should see a Base price with "10.00 €" value

   Scenario: Availability can be added to Product
        Given I open "Demo Product 1" Product page in admin SiteAccess
        When I change tab to "Availability"
        And I start adding availability to product
        And I set an Availability to "true"
        And I set a Stock to "5"
        And I click on the edit action bar button "Save and close"
        Then I should see an Availability with "true" value
        And I should see a Stock with "5" value
