<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\ExampleInMemoryProductCatalog\PIM\InMemory;

use Ibexa\Contracts\ProductCatalog\CurrencyServiceInterface;
use Ibexa\Contracts\ProductCatalog\Values\Price\PriceContext;
use Ibexa\Contracts\ProductCatalog\Values\Product\Query\Criterion;
use Ibexa\Contracts\ProductCatalog\Values\Product\Query\CriterionInterface;
use Ibexa\Contracts\ProductCatalog\Values\ProductInterface;

final class CriterionVisitor
{
    public function __construct(
        private readonly CurrencyServiceInterface $currencyService,
    ) {
    }

    public function evaluate(ProductInterface $product, ?CriterionInterface $criterion = null): bool
    {
        if ($criterion === null) {
            return true;
        }

        return match (get_class($criterion)) {
            Criterion\ProductCode::class => $this->evaluateCode($criterion, $product),
            Criterion\ProductName::class => $this->evaluateName($criterion, $product),
            Criterion\ProductType::class => $this->evaluateType($criterion, $product),
            Criterion\ProductAvailability::class => $this->evaluateAvailability($criterion, $product),
            Criterion\LogicalAnd::class => $this->evaluateLogicalAnd($criterion, $product),
            Criterion\LogicalOr::class => $this->evaluateLogicalOr($criterion, $product),
            Criterion\ProductCategorySubtree::class => true,
            Criterion\MatchAll::class => true,
            Criterion\BasePrice::class => $this->evaluatePrice($criterion, $product),
            Criterion\BasePriceRange::class => $this->evaluatePriceRange($criterion, $product),
            Criterion\IntegerAttribute::class => $this->evaluateIntegerAttribute($criterion, $product),
            Criterion\IntegerAttributeRange::class => $this->evaluateIntegerRangeAttribute($criterion, $product),
            // Ignore unsupported criteria
            default => false
        };
    }

    private function evaluateCode(Criterion\ProductCode $criterion, ProductInterface $product): bool
    {
        return in_array($product->getCode(), $criterion->getCodes());
    }

    private function evaluateName(Criterion\ProductName $criterion, ProductInterface $product): bool
    {
        return str_contains($criterion->getName(), $product->getName());
    }

    private function evaluateType(Criterion\ProductType $criteria, ProductInterface $product): bool
    {
        return in_array($product->getProductType()->getIdentifier(), $criteria->getTypes());
    }

    private function evaluateAvailability(Criterion\ProductAvailability $criterion, ProductInterface $product): bool
    {
        return $product->isAvailable() === $criterion->isAvailable();
    }

    private function evaluatePrice(Criterion\BasePrice $criterion, ProductInterface $product): bool
    {
        $currency = $this->currencyService->getCurrencyByCode(
            $criterion->getCurrency()->getCode()
        );

        /** @var \Ibexa\ExampleInMemoryProductCatalog\PIM\InMemory\Value\Product $product */
        $price = $product->getPrice(new PriceContext($currency));
        if ($price === null) {
            return false;
        }

        return $criterion->getValue()->equals($price->getMoney());
    }

    private function evaluatePriceRange(Criterion\BasePriceRange $criterion, ProductInterface $product): bool
    {
        $min = $criterion->getMin();
        $max = $criterion->getMax();

        if ($min === null && $max === null) {
            return false;
        }

        if ($min === null) {
            $currencyCode = $max->getCurrency()->getCode();
        } else {
            $currencyCode = $min->getCurrency()->getCode();
        }

        $currency = $this->currencyService->getCurrencyByCode($currencyCode);

        /** @var \Ibexa\ExampleInMemoryProductCatalog\PIM\InMemory\Value\Product $product */
        $price = $product->getPrice(new PriceContext($currency));
        if ($price === null) {
            return false;
        }

        $productPriceMoney = $price->getMoney();

        return $min?->lessThanOrEqual($productPriceMoney)
            && $max?->greaterThanOrEqual($productPriceMoney);
    }

    private function evaluateIntegerAttribute(
        Criterion\IntegerAttribute $criterion,
        ProductInterface $product
    ): bool {
        foreach ($product->getAttributes() as $attribute) {
            if ($criterion->getValue() === $attribute->getValue()) {
                return true;
            }
        }

        return false;
    }

    private function evaluateIntegerRangeAttribute(
        Criterion\IntegerAttributeRange $criterion,
        ProductInterface $product
    ): bool {
        $criterionMin = $criterion->getMin();
        $criterionMax = $criterion->getMax();

        foreach ($product->getAttributes() as $attribute) {
            $attributeVal = $attribute->getValue();
            if ($attributeVal >= $criterionMin && $attributeVal <= $criterionMax) {
                return true;
            }
        }

        return false;
    }

    private function evaluateLogicalAnd(Criterion\LogicalAnd $criteria, ProductInterface $product): bool
    {
        foreach ($criteria->getCriteria() as $innerCriterion) {
            if (!$this->evaluate($product, $innerCriterion)) {
                return false;
            }
        }

        return true;
    }

    private function evaluateLogicalOr(Criterion\LogicalOr $criterion, ProductInterface $product): bool
    {
        foreach ($criterion->getCriteria() as $innerCriterion) {
            if ($this->evaluate($product, $innerCriterion)) {
                return true;
            }
        }

        return false;
    }
}
