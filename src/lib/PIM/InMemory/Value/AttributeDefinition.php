<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\ExampleInMemoryProductCatalog\PIM\InMemory\Value;

use Ibexa\Contracts\Core\Options\OptionsBag;
use Ibexa\Contracts\ProductCatalog\Values\AttributeDefinitionInterface;
use Ibexa\Contracts\ProductCatalog\Values\AttributeGroupInterface;
use Ibexa\Contracts\ProductCatalog\Values\AttributeTypeInterface;

final class AttributeDefinition implements AttributeDefinitionInterface
{
    public function __construct(
        private readonly int $id,
        private readonly string $identifier,
        private readonly string $name,
        private readonly ?string $description,
        private readonly AttributeTypeInterface $type,
        private readonly AttributeGroupInterface $group
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getType(): AttributeTypeInterface
    {
        return $this->type;
    }

    public function getGroup(): AttributeGroupInterface
    {
        return $this->group;
    }

    public function getPosition(): int
    {
        return 0;
    }

    public function getOptions(): OptionsBag
    {
        return new AttributeOptions();
    }
}
