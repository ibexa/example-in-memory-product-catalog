<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\ExampleInMemoryProductCatalog\PIM\InMemory\Data;

use DateTimeImmutable;
use Ibexa\Contracts\Core\Collection\ArrayMap;
use Ibexa\Contracts\Core\Collection\MapInterface;
use Ibexa\Contracts\Core\Collection\MutableArrayMap;
use Ibexa\Contracts\ProductCatalog\PriceResolverInterface;
use Ibexa\ExampleInMemoryProductCatalog\PIM\InMemory\Value\AssetCollection;
use Ibexa\ExampleInMemoryProductCatalog\PIM\InMemory\Value\Attribute;
use Ibexa\ExampleInMemoryProductCatalog\PIM\InMemory\Value\AttributeDefinition;
use Ibexa\ExampleInMemoryProductCatalog\PIM\InMemory\Value\AttributeDefinitionAssignment;
use Ibexa\ExampleInMemoryProductCatalog\PIM\InMemory\Value\AttributeGroup;
use Ibexa\ExampleInMemoryProductCatalog\PIM\InMemory\Value\AttributeType;
use Ibexa\ExampleInMemoryProductCatalog\PIM\InMemory\Value\Product;
use Ibexa\ExampleInMemoryProductCatalog\PIM\InMemory\Value\ProductType;
use Ibexa\ExampleInMemoryProductCatalog\PIM\InMemory\Value\VatCategory;
use Ibexa\ProductCatalog\Local\Repository\DomainMapper\ProductAvailabilityDelegate;

final class DataProvider
{
    private PriceResolverInterface $priceResolver;

    private ProductAvailabilityDelegate $availabilityDelegate;

    /** @var \Ibexa\Contracts\Core\Collection\MapInterface<string, AttributeDefinition>|null */
    private ?MapInterface $attributeDefinitions = null;

    /** @var \Ibexa\Contracts\Core\Collection\MapInterface<string, ProductType>|null */
    private ?MapInterface $productTypes = null;

    /** @var \Ibexa\Contracts\Core\Collection\MapInterface<string, Product>|null */
    private ?MapInterface $products = null;

    public function __construct(PriceResolverInterface $priceResolver, ProductAvailabilityDelegate $availabilityDelegate)
    {
        $this->priceResolver = $priceResolver;
        $this->availabilityDelegate = $availabilityDelegate;
    }

    /**
     * @return \Ibexa\Contracts\Core\Collection\MapInterface<string, Product>
     */
    public function getProducts(): MapInterface
    {
        if ($this->products === null) {
            $this->products = new MutableArrayMap();

            for ($i = 1; $i < 1000; ++$i) {
                $code = str_pad((string) $i, 4, '0', STR_PAD_LEFT);

                $product = new Product(
                    $this->priceResolver,
                    $this->availabilityDelegate,
                    $code,
                    'Demo Product ' . $i,
                    $this->getProductTypes()->get('example'),
                    new AssetCollection(),
                    [
                        'height' => new Attribute(
                            $this->getAttributeDefinitions()->get('height'),
                            100
                        ),
                        'width' => new Attribute(
                            $this->getAttributeDefinitions()->get('width'),
                            200
                        ),
                    ],
                    new DateTimeImmutable('2023-06-15 00:00:00'),
                    new DateTimeImmutable('2023-06-15 00:00:00'),
                );

                $this->products->set($code, $product);
            }
        }

        return $this->products;
    }

    /**
     * @return \Ibexa\Contracts\Core\Collection\MapInterface<string, ProductType>
     */
    public function getProductTypes(): MapInterface
    {
        if ($this->productTypes === null) {
            $this->productTypes = new ArrayMap([
                'example' => new ProductType(
                    'example',
                    'Example',
                    [
                        'height' => new AttributeDefinitionAssignment($this->getAttributeDefinitions()->get('height')),
                        'width' => new AttributeDefinitionAssignment($this->getAttributeDefinitions()->get('width')),
                    ],
                    new VatCategory('default', 'standard', 25.0)
                ),
                'demo' => new ProductType(
                    'demo',
                    'Demo',
                    [
                    ],
                    new VatCategory('default', 'standard', 25.0)
                ),
            ]);
        }

        return $this->productTypes;
    }

    /**
     * @return \Ibexa\Contracts\Core\Collection\MapInterface<string, AttributeDefinition>
     */
    public function getAttributeDefinitions(): MapInterface
    {
        if ($this->attributeDefinitions === null) {
            $this->attributeDefinitions = new ArrayMap([
                'height' => new AttributeDefinition(
                    1,
                    'height',
                    'Height',
                    null,
                    AttributeType::INTEGER,
                    AttributeGroup::DEFAULT
                ),
                'width' => new AttributeDefinition(
                    2,
                    'width',
                    'Width',
                    null,
                    AttributeType::INTEGER,
                    AttributeGroup::DEFAULT
                ),
            ]);
        }

        return $this->attributeDefinitions;
    }
}
