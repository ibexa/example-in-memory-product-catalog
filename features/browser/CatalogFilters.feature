@remotePIM @javascript
Feature: Remote PIM

    Background:
        Given I am logged as admin

    Scenario: Catalog with filter can be added
        Given I open "Catalogs" page in admin SiteAccess
        And I click on the edit action bar button "Create"
        And I set fields
            | label       | value                |
            | Name        | Catalog with filter  |
            | Identifier  | CatalogWithFilter    |
        And I switch to "Filters" field group
        When I add filter "Height" to catalog
        And I set filter range parameters to "0" and "3"
        Then there is "Height" filter with value "(0 - 3)" on filters list
        And I should see products on catalog form
            | Name           | Code  |
            | Demo Product 1 | 0001  |
            | Demo Product 2 | 0002  |
            | Demo Product 3 | 0003  |
        And I should not see products on catalog form
            | Name            | Code  |
            | Demo Product 4  | 0004  |
        And I click on the edit action bar button "Create"
        And success notification that "Catalog 'Catalog with filter' created." appears
        And I should see a Catalog with values
            | Name                 | Identifier         |
            | Catalog with filter  | CatalogWithFilter  |
        And catalog status is "Draft"
