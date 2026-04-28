<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\ExampleInMemoryProductCatalog\PIM\InMemory;

use Ibexa\Contracts\ProductCatalog\AssetServiceInterface;
use Ibexa\Contracts\ProductCatalog\Values\Asset\AssetCollectionInterface;
use Ibexa\Contracts\ProductCatalog\Values\Asset\AssetInterface;
use Ibexa\Contracts\ProductCatalog\Values\ProductInterface;
use Ibexa\Core\Base\Exceptions\NotFoundException;
use Ibexa\ExampleInMemoryProductCatalog\PIM\InMemory\Value\AssetCollection;

final class AssetService implements AssetServiceInterface
{
    public function findAssets(ProductInterface $product, bool $onlyAvailable = false): AssetCollectionInterface
    {
        return new AssetCollection();
    }

    public function getAsset(ProductInterface $product, string $identifier): AssetInterface
    {
        throw new NotFoundException(AssetInterface::class, $identifier);
    }
}
