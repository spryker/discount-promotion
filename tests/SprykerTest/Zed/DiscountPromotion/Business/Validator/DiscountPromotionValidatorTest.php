<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\DiscountPromotion\Business\Validator;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DiscountCalculatorTransfer;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Shared\DiscountPromotion\DiscountPromotionConfig;
use Spryker\Zed\DiscountPromotion\Business\DiscountPromotionBusinessFactory;
use Spryker\Zed\DiscountPromotion\Business\Validator\DiscountPromotionValidatorInterface;
use Spryker\Zed\DiscountPromotion\Business\Validator\Rule\DiscountPromotion\AbstractSkusDiscountPromotionValidatorRule;
use Spryker\Zed\DiscountPromotion\Business\Validator\Rule\DiscountPromotion\QuantityDiscountPromotionValidatorRule;
use SprykerTest\Zed\DiscountPromotion\DiscountPromotionBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DiscountPromotion
 * @group Business
 * @group Validator
 * @group DiscountPromotionValidatorTest
 * Add your own group annotations below this line
 */
class DiscountPromotionValidatorTest extends Unit
{
    protected const string KEY_ABSTRACT_SKU = 'abstractSku';

    protected const string KEY_ABSTRACT_SKUS = 'abstractSkus';

    protected const string KEY_QUANTITY = 'quantity';

    /**
     * @uses \Spryker\Zed\DiscountPromotion\Business\Validator\Rule\DiscountPromotion\AbstractSkusDiscountPromotionValidatorRule::TRANSLATION_PARAMETER_NON_EXISTING_SKUS
     */
    protected const string TRANSLATION_PARAMETER_NON_EXISTING_SKUS = '%non_existing_skus%';

    /**
     * Placeholder replaced by the SKU of a product created in Arrange, so the existence check sees a real row.
     */
    protected const string ABSTRACT_SKU = 'EXISTING-ABSTRACT-SKU';

    protected const string UNKNOWN_ABSTRACT_SKU = 'VALIDATE-DISCOUNT-PROMOTION-UNKNOWN-SKU';

    /**
     * @var array<int, string>
     */
    protected const array EXPECTED_ERROR_ABSTRACT_SKUS = [
        DiscountPromotionConfig::TRANSLATION_KEY_ABSTRACT_SKUS_REQUIRED,
        AbstractSkusDiscountPromotionValidatorRule::ENTITY_IDENTIFIER_ABSTRACT_SKUS,
    ];

    /**
     * @var array<int, string>
     */
    protected const array EXPECTED_ERROR_ABSTRACT_SKUS_UNKNOWN = [
        DiscountPromotionConfig::TRANSLATION_KEY_ABSTRACT_SKUS_UNKNOWN,
        AbstractSkusDiscountPromotionValidatorRule::ENTITY_IDENTIFIER_ABSTRACT_SKUS,
    ];

    protected const array EXPECTED_ERROR_QUANTITY = [
        DiscountPromotionConfig::TRANSLATION_KEY_QUANTITY_INVALID,
        QuantityDiscountPromotionValidatorRule::ENTITY_IDENTIFIER_QUANTITY,
    ];

    protected DiscountPromotionBusinessTester $tester;

    /**
     * @dataProvider provideDiscountConfigurators
     *
     * @param array<string, mixed>|null $promotion
     * @param array<int, array<int, string>> $expectedErrors
     */
    public function testReportsEveryBrokenPromotionRuleOfThePromotionStrategy(
        bool $hasDiscountCalculator,
        ?string $collectorStrategyType,
        ?array $promotion,
        array $expectedErrors
    ): void {
        // Arrange
        $existingAbstractSku = $this->tester->haveProductAbstract()->getSkuOrFail();
        $discountConfiguratorTransfer = $this->buildDiscountConfigurator(
            $hasDiscountCalculator,
            $collectorStrategyType,
            $this->replaceAbstractSkuPlaceholder($promotion, $existingAbstractSku),
        );

        // Act
        $errorCollectionTransfer = $this->createDiscountPromotionValidator()->validateDiscountPromotion($discountConfiguratorTransfer);

        // Assert
        $this->assertSame(
            $expectedErrors,
            array_map(
                static fn (ErrorTransfer $errorTransfer): array => [
                    (string)$errorTransfer->getMessage(),
                    (string)$errorTransfer->getEntityIdentifier(),
                ],
                array_values($errorCollectionTransfer->getErrors()->getArrayCopy()),
            ),
        );
    }

    /**
     * @return array<string, array{bool, string|null, array<string, mixed>|null, array<int, array<int, string>>}>
     */
    public function provideDiscountConfigurators(): array
    {
        return [
            'given the query string strategy, when the configurator is validated, then the promotion rules stay silent' => [
                true,
                DiscountConstants::DISCOUNT_COLLECTOR_STRATEGY_QUERY_STRING,
                null,
                [],
            ],
            'given no discount calculator, when the configurator is validated, then the promotion rules stay silent' => [
                false,
                null,
                null,
                [],
            ],
            'given the promotion strategy with skus and a quantity, when the configurator is validated, then it is accepted' => [
                true,
                DiscountPromotionConfig::DISCOUNT_COLLECTOR_STRATEGY,
                [static::KEY_ABSTRACT_SKUS => [static::ABSTRACT_SKU], static::KEY_QUANTITY => 1],
                [],
            ],
            'given the promotion strategy with the legacy single sku, when the configurator is validated, then it is accepted' => [
                true,
                DiscountPromotionConfig::DISCOUNT_COLLECTOR_STRATEGY,
                [static::KEY_ABSTRACT_SKU => static::ABSTRACT_SKU, static::KEY_QUANTITY => 1],
                [],
            ],
            'given an empty sku list, when the configurator is validated, then the abstract skus rule answers' => [
                true,
                DiscountPromotionConfig::DISCOUNT_COLLECTOR_STRATEGY,
                [static::KEY_ABSTRACT_SKUS => [], static::KEY_QUANTITY => 1],
                [static::EXPECTED_ERROR_ABSTRACT_SKUS],
            ],
            'given a sku list of blank strings, when the configurator is validated, then the abstract skus rule answers' => [
                true,
                DiscountPromotionConfig::DISCOUNT_COLLECTOR_STRATEGY,
                [static::KEY_ABSTRACT_SKUS => ['  '], static::KEY_QUANTITY => 1],
                [static::EXPECTED_ERROR_ABSTRACT_SKUS],
            ],
            'given a sku no product carries, when the configurator is validated, then the abstract skus rule names it' => [
                true,
                DiscountPromotionConfig::DISCOUNT_COLLECTOR_STRATEGY,
                [static::KEY_ABSTRACT_SKUS => [static::UNKNOWN_ABSTRACT_SKU], static::KEY_QUANTITY => 1],
                [static::EXPECTED_ERROR_ABSTRACT_SKUS_UNKNOWN],
            ],
            'given an unknown sku next to an existing one, when the configurator is validated, then the abstract skus rule still rejects it' => [
                true,
                DiscountPromotionConfig::DISCOUNT_COLLECTOR_STRATEGY,
                [static::KEY_ABSTRACT_SKUS => [static::ABSTRACT_SKU, static::UNKNOWN_ABSTRACT_SKU], static::KEY_QUANTITY => 1],
                [static::EXPECTED_ERROR_ABSTRACT_SKUS_UNKNOWN],
            ],
            'given an unknown legacy single sku, when the configurator is validated, then the abstract skus rule rejects it' => [
                true,
                DiscountPromotionConfig::DISCOUNT_COLLECTOR_STRATEGY,
                [static::KEY_ABSTRACT_SKU => static::UNKNOWN_ABSTRACT_SKU, static::KEY_QUANTITY => 1],
                [static::EXPECTED_ERROR_ABSTRACT_SKUS_UNKNOWN],
            ],
            'given no quantity, when the configurator is validated, then the quantity rule answers' => [
                true,
                DiscountPromotionConfig::DISCOUNT_COLLECTOR_STRATEGY,
                [static::KEY_ABSTRACT_SKUS => [static::ABSTRACT_SKU], static::KEY_QUANTITY => null],
                [static::EXPECTED_ERROR_QUANTITY],
            ],
            'given a quantity of zero, when the configurator is validated, then the quantity rule answers' => [
                true,
                DiscountPromotionConfig::DISCOUNT_COLLECTOR_STRATEGY,
                [static::KEY_ABSTRACT_SKUS => [static::ABSTRACT_SKU], static::KEY_QUANTITY => 0],
                [static::EXPECTED_ERROR_QUANTITY],
            ],
            'given a negative quantity, when the configurator is validated, then the quantity rule answers' => [
                true,
                DiscountPromotionConfig::DISCOUNT_COLLECTOR_STRATEGY,
                [static::KEY_ABSTRACT_SKUS => [static::ABSTRACT_SKU], static::KEY_QUANTITY => -1],
                [static::EXPECTED_ERROR_QUANTITY],
            ],
            'given no promotion at all, when the configurator is validated, then both rules answer in order' => [
                true,
                DiscountPromotionConfig::DISCOUNT_COLLECTOR_STRATEGY,
                null,
                [static::EXPECTED_ERROR_ABSTRACT_SKUS, static::EXPECTED_ERROR_QUANTITY],
            ],
        ];
    }

    public function testNamesEveryUnknownSkuInTheErrorParameters(): void
    {
        // Arrange
        $existingAbstractSku = $this->tester->haveProductAbstract()->getSkuOrFail();
        $discountConfiguratorTransfer = $this->buildDiscountConfigurator(
            true,
            DiscountPromotionConfig::DISCOUNT_COLLECTOR_STRATEGY,
            [static::KEY_ABSTRACT_SKUS => [$existingAbstractSku, static::UNKNOWN_ABSTRACT_SKU, static::UNKNOWN_ABSTRACT_SKU . '-2'], static::KEY_QUANTITY => 1],
        );

        // Act
        $errorCollectionTransfer = $this->createDiscountPromotionValidator()->validateDiscountPromotion($discountConfiguratorTransfer);

        // Assert
        $this->assertCount(1, $errorCollectionTransfer->getErrors());
        $this->assertSame(
            [static::TRANSLATION_PARAMETER_NON_EXISTING_SKUS => static::UNKNOWN_ABSTRACT_SKU . ', ' . static::UNKNOWN_ABSTRACT_SKU . '-2'],
            $errorCollectionTransfer->getErrors()->offsetGet(0)->getParameters(),
        );
    }

    /**
     * @param array<string, mixed>|null $promotion
     *
     * @return array<string, mixed>|null
     */
    protected function replaceAbstractSkuPlaceholder(?array $promotion, string $existingAbstractSku): ?array
    {
        if ($promotion === null) {
            return null;
        }

        if (($promotion[static::KEY_ABSTRACT_SKU] ?? null) === static::ABSTRACT_SKU) {
            $promotion[static::KEY_ABSTRACT_SKU] = $existingAbstractSku;
        }

        $promotion[static::KEY_ABSTRACT_SKUS] = array_map(
            static fn (string $abstractSku): string => $abstractSku === self::ABSTRACT_SKU ? $existingAbstractSku : $abstractSku,
            $promotion[static::KEY_ABSTRACT_SKUS] ?? [],
        );

        return $promotion;
    }

    /**
     * @param array<string, mixed>|null $promotion
     */
    protected function buildDiscountConfigurator(
        bool $hasDiscountCalculator,
        ?string $collectorStrategyType,
        ?array $promotion
    ): DiscountConfiguratorTransfer {
        $discountConfiguratorTransfer = new DiscountConfiguratorTransfer();

        if (!$hasDiscountCalculator) {
            return $discountConfiguratorTransfer;
        }

        $discountCalculatorTransfer = (new DiscountCalculatorTransfer())->setCollectorStrategyType($collectorStrategyType);

        if ($promotion !== null) {
            $discountCalculatorTransfer->setDiscountPromotion(
                (new DiscountPromotionTransfer())
                    ->setAbstractSku($promotion[static::KEY_ABSTRACT_SKU] ?? null)
                    ->setAbstractSkus($promotion[static::KEY_ABSTRACT_SKUS] ?? [])
                    ->setQuantity($promotion[static::KEY_QUANTITY] ?? null),
            );
        }

        return $discountConfiguratorTransfer->setDiscountCalculator($discountCalculatorTransfer);
    }

    protected function createDiscountPromotionValidator(): DiscountPromotionValidatorInterface
    {
        return (new DiscountPromotionBusinessFactory())->createDiscountPromotionValidator();
    }
}
