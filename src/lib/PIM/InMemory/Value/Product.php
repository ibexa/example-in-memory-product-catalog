<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\ExampleInMemoryProductCatalog\PIM\InMemory\Value;

use DateTimeImmutable;
use DateTimeInterface;
use Ibexa\Contracts\Core\Repository\Values\Content\Thumbnail;
use Ibexa\Contracts\ProductCatalog\PriceResolverInterface;
use Ibexa\Contracts\ProductCatalog\Values\Asset\AssetCollectionInterface;
use Ibexa\Contracts\ProductCatalog\Values\Availability\AvailabilityContextInterface;
use Ibexa\Contracts\ProductCatalog\Values\Availability\AvailabilityInterface;
use Ibexa\Contracts\ProductCatalog\Values\AvailabilityAwareInterface;
use Ibexa\Contracts\ProductCatalog\Values\Price\PriceContextInterface;
use Ibexa\Contracts\ProductCatalog\Values\PriceAwareInterface;
use Ibexa\Contracts\ProductCatalog\Values\PriceInterface;
use Ibexa\Contracts\ProductCatalog\Values\ProductInterface;
use Ibexa\Contracts\ProductCatalog\Values\ProductTypeInterface;
use Ibexa\ProductCatalog\Local\Repository\DomainMapper\ProductAvailabilityDelegate;

final class Product extends ValueObject implements ProductInterface, PriceAwareInterface, AvailabilityAwareInterface
{
    /**
     * @param \Ibexa\Contracts\ProductCatalog\Values\AttributeInterface[] $attributes
     */
    public function __construct(
        protected readonly PriceResolverInterface $priceResolver,
        protected readonly ProductAvailabilityDelegate $availabilityDelegate,
        protected readonly string $code,
        protected readonly string $name,
        protected readonly ProductTypeInterface $type,
        protected readonly AssetCollectionInterface $assets = new AssetCollection(),
        protected readonly iterable $attributes = [],
        protected readonly DateTimeInterface $createdAt = new DateTimeImmutable(),
        protected readonly DateTimeInterface $updatedAt = new DateTimeImmutable(),
        protected readonly ?Thumbnail $thumbnail = null
    ) {
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getProductType(): ProductTypeInterface
    {
        return $this->type;
    }

    public function getThumbnail(): Thumbnail
    {
        return $this->thumbnail ?? $this->getPlaceholderThumbnail();
    }

    private function getPlaceholderThumbnail(): Thumbnail
    {
        return new Thumbnail([
            'resource' => '/placeholder',
        ]);
    }

    public function getAssets(): AssetCollectionInterface
    {
        return $this->assets;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getAttributes(): iterable
    {
        return $this->attributes;
    }

    public function isBaseProduct(): bool
    {
        return false;
    }

    public function isVariant(): bool
    {
        return false;
    }

    public function getPrice(?PriceContextInterface $context = null): ?PriceInterface
    {
        return $this->priceResolver->resolvePrice($this, $context);
    }

    public function getAvailability(?AvailabilityContextInterface $context = null): AvailabilityInterface
    {
        return $this->availabilityDelegate->getAvailability($this, $context);
    }

    public function isAvailable(?AvailabilityContextInterface $context = null): bool
    {
        if ($this->hasAvailability()) {
            return $this->getAvailability($context)->isAvailable();
        }

        return false;
    }

    public function hasAvailability(): bool
    {
        return $this->availabilityDelegate->hasAvailability($this);
    }
}
