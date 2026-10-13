<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\DiscountPromotion\Communication\Form\Constraint;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Spryker\Shared\DiscountPromotion\DiscountPromotionConfig;
use Spryker\Zed\DiscountPromotion\Business\DiscountPromotionBusinessFactory;
use Spryker\Zed\DiscountPromotion\Communication\Form\Constraint\DiscountPromotionValid;
use Spryker\Zed\DiscountPromotion\Communication\Form\Constraint\DiscountPromotionValidValidator;
use SprykerTest\Zed\DiscountPromotion\DiscountPromotionCommunicationTester;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Validation;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DiscountPromotion
 * @group Communication
 * @group Form
 * @group Constraint
 * @group DiscountPromotionValidValidatorTest
 * Add your own group annotations below this line
 */
class DiscountPromotionValidValidatorTest extends Unit
{
    protected const string UNKNOWN_ABSTRACT_SKU = 'discount-promotion-valid-validator-test-unknown-sku';

    protected const string GROUP_DEFAULT = 'Default';

    /**
     * @var array<int, string>
     */
    protected const array EXPECTED_VIOLATION_QUANTITY = [
        DiscountPromotionTransfer::QUANTITY,
        DiscountPromotionConfig::TRANSLATION_KEY_QUANTITY_INVALID,
    ];

    /**
     * @var array<int, string>
     */
    protected const array EXPECTED_VIOLATION_ABSTRACT_SKUS_REQUIRED = [
        DiscountPromotionTransfer::ABSTRACT_SKUS,
        DiscountPromotionConfig::TRANSLATION_KEY_ABSTRACT_SKUS_REQUIRED,
    ];

    /**
     * @var array<int, string>
     */
    protected const array EXPECTED_VIOLATION_ABSTRACT_SKUS_UNKNOWN = [
        DiscountPromotionTransfer::ABSTRACT_SKUS,
        DiscountPromotionConfig::TRANSLATION_KEY_ABSTRACT_SKUS_UNKNOWN,
    ];

    protected DiscountPromotionCommunicationTester $tester;

    /**
     * @dataProvider provideSubmittedPromotions
     *
     * @param array<int, string>|null $abstractSkus
     * @param array<int, string> $groups
     * @param array<int, array<int, string>> $expectedViolations
     */
    public function testPutsEveryReportedPromotionErrorOnTheFormFieldItAddresses(
        ?array $abstractSkus,
        ?int $quantity,
        array $groups,
        array $expectedViolations
    ): void {
        // Arrange
        $discountPromotionTransfer = (new DiscountPromotionTransfer())
            ->setAbstractSkus($abstractSkus ?? [$this->tester->haveProductAbstract()->getSkuOrFail()])
            ->setQuantity($quantity);

        // Act
        $constraintViolationList = Validation::createValidator()->validate(
            $discountPromotionTransfer,
            $this->createConstraint(),
            $groups,
        );

        // Assert
        $this->assertSame($expectedViolations, $this->mapViolations($constraintViolationList));
    }

    /**
     * A null sku list stands for an existing product, which the test creates.
     *
     * @return array<string, array{array<int, string>|null, int|null, array<int, string>, array<int, array<int, string>>}>
     */
    public function provideSubmittedPromotions(): array
    {
        return [
            'given the promotion group and a valid promotion, when it is validated, then nothing is reported' => [
                null, 1, [DiscountPromotionConfig::DISCOUNT_COLLECTOR_STRATEGY], [],
            ],
            'given the promotion group and a quantity of zero, when it is validated, then the quantity field is flagged' => [
                null, 0, [DiscountPromotionConfig::DISCOUNT_COLLECTOR_STRATEGY], [static::EXPECTED_VIOLATION_QUANTITY],
            ],
            'given the promotion group and no quantity, when it is validated, then the quantity field is flagged' => [
                null, null, [DiscountPromotionConfig::DISCOUNT_COLLECTOR_STRATEGY], [static::EXPECTED_VIOLATION_QUANTITY],
            ],
            'given the promotion group and no skus, when it is validated, then the sku field is flagged' => [
                [], 1, [DiscountPromotionConfig::DISCOUNT_COLLECTOR_STRATEGY], [static::EXPECTED_VIOLATION_ABSTRACT_SKUS_REQUIRED],
            ],
            'given the promotion group and an unknown sku, when it is validated, then the sku field is flagged' => [
                [static::UNKNOWN_ABSTRACT_SKU], 1, [DiscountPromotionConfig::DISCOUNT_COLLECTOR_STRATEGY], [static::EXPECTED_VIOLATION_ABSTRACT_SKUS_UNKNOWN],
            ],
            'given only the default group, when a broken promotion is validated, then the constraint stays out of the group' => [
                [], 0, [static::GROUP_DEFAULT], [],
            ],
        ];
    }

    public function testIgnoresAValueThatIsNotADiscountPromotionTransfer(): void
    {
        // Act
        $constraintViolationList = Validation::createValidator()->validate(
            static::UNKNOWN_ABSTRACT_SKU,
            $this->createConstraint(),
            [DiscountPromotionConfig::DISCOUNT_COLLECTOR_STRATEGY],
        );

        // Assert
        $this->assertCount(0, $constraintViolationList);
    }

    public function testRejectsAConstraintItDoesNotOwn(): void
    {
        // Arrange
        $discountPromotionValidValidator = new DiscountPromotionValidValidator();

        // Assert
        $this->expectException(UnexpectedTypeException::class);

        // Act
        $discountPromotionValidValidator->validate(new DiscountPromotionTransfer(), new NotBlank());
    }

    public function testBuildsTheConstraintWithTheValidatorTheTranslatorAndThePromotionGroupOnly(): void
    {
        // Act
        $discountPromotionValid = $this->tester->getFactory()->createDiscountPromotionValidConstraint();

        // Assert
        $this->assertInstanceOf(DiscountPromotionValid::class, $discountPromotionValid);
        $this->assertNotNull($discountPromotionValid->getDiscountPromotionValidator());
        $this->assertNotNull($discountPromotionValid->getTranslatorFacade());
        $this->assertSame([DiscountPromotionConfig::DISCOUNT_COLLECTOR_STRATEGY], $discountPromotionValid->groups);
    }

    protected function createConstraint(): DiscountPromotionValid
    {
        return new DiscountPromotionValid(
            [DiscountPromotionValid::OPTION_DISCOUNT_PROMOTION_VALIDATOR => (new DiscountPromotionBusinessFactory())->createDiscountPromotionValidator()],
            [DiscountPromotionConfig::DISCOUNT_COLLECTOR_STRATEGY],
        );
    }

    /**
     * @return array<int, array<int, string>>
     */
    protected function mapViolations(ConstraintViolationListInterface $constraintViolationList): array
    {
        return array_map(
            static fn (ConstraintViolationInterface $constraintViolation): array => [
                $constraintViolation->getPropertyPath(),
                (string)$constraintViolation->getMessage(),
            ],
            iterator_to_array($constraintViolationList),
        );
    }
}
