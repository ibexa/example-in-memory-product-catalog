@remotePIM
Feature: Set Ibexa DXP to use Remote PIM

    Scenario: Set up configuration
        Given I append configuration to "ibexa_product_catalog.engines" in "config/packages/ibexa_product_catalog.yaml"
        """
        in_memory:
            type: in_memory
            options:
                root_location_remote_id: ibexa_product_catalog_root
        """
        And I set configuration to "ibexa.repositories.default.product_catalog"
        """
            engine: in_memory
        """
        And I execute a migration
        """
        -
            type: currency
            mode: update
            criteria:
                type: field_value
                field: code
                value: EUR
                operator: '='
            enabled: true
        """
