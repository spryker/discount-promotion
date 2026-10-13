<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\DiscountPromotion\Business\Expander;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DiscountCalculatorTransfer;
use Generated\Shared\Transfer\DiscountConfiguratorCollectionTransfer;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountGeneralTransfer;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Spryker\Shared\DiscountPromotion\DiscountPromotionConfig;
use Spryker\Zed\DiscountPromotion\Business\Expander\DiscountConfiguratorCollectionExpander;
use Spryker\Zed\DiscountPromotion\Business\Expander\DiscountConfiguratorCollectionExpanderInterface;
use Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionRepository;
use SprykerTest\Zed\DiscountPromotion\DiscountPromotionBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DiscountPromotion
 * @group Business
 * @group Expander
 * @group DiscountConfiguratorCollectionExpanderTest
 * Add your own group annotations below this line
 */
class DiscountConfiguratorCollectionExpanderTest extends Unit
{
    protected DiscountPromotionBusinessTester $tester;

    public function testSetsThePromotionAndStrategyOfEveryPromotionalDiscountAndLeavesTheOthersUntouched(): void
    {
        // Arrange
        $promotionalDiscountGeneralTransfer = $this->tester->haveDiscount();
        $secondPromotionalDiscountGeneralTransfer = $this->tester->haveDiscount();
        $plainDiscountGeneralTransfer = $this->tester->haveDiscount();
        $this->tester->haveDiscountPromotion([DiscountPromotionTransfer::FK_DISCOUNT => $promotionalDiscountGeneralTransfer->getIdDiscount()]);
        $this->tester->haveDiscountPromotion([DiscountPromotionTransfer::FK_DISCOUNT => $secondPromotionalDiscountGeneralTransfer->getIdDiscount()]);

        $discountConfiguratorCollectionTransfer = (new DiscountConfiguratorCollectionTransfer())
            ->addDiscountConfigurator($this->createDiscountConfigurator($promotionalDiscountGeneralTransfer))
            ->addDiscountConfigurator($this->createDiscountConfigurator($plainDiscountGeneralTransfer))
            ->addDiscountConfigurator($this->createDiscountConfigurator($secondPromotionalDiscountGeneralTransfer));

        // Act
        $discountConfiguratorCollectionTransfer = $this->createDiscountConfiguratorCollectionExpander()
            ->expandDiscountConfiguratorCollectionWithPromotions($discountConfiguratorCollectionTransfer);

        // Assert
        $discountConfiguratorTransfers = $discountConfiguratorCollectionTransfer->getDiscountConfigurators();
        $this->assertSame(
            $promotionalDiscountGeneralTransfer->getIdDiscount(),
            $discountConfiguratorTransfers->offsetGet(0)->getDiscountCalculatorOrFail()->getDiscountPromotionOrFail()->getFkDiscount(),
        );
        $this->assertSame(
            DiscountPromotionConfig::DISCOUNT_COLLECTOR_STRATEGY,
            $discountConfiguratorTransfers->offsetGet(0)->getDiscountCalculatorOrFail()->getCollectorStrategyType(),
        );
        $this->assertNull($discountConfiguratorTransfers->offsetGet(1)->getDiscountCalculatorOrFail()->getDiscountPromotion());
        $this->assertNull($discountConfiguratorTransfers->offsetGet(1)->getDiscountCalculatorOrFail()->getCollectorStrategyType());
        $this->assertSame(
            $secondPromotionalDiscountGeneralTransfer->getIdDiscount(),
            $discountConfiguratorTransfers->offsetGet(2)->getDiscountCalculatorOrFail()->getDiscountPromotionOrFail()->getFkDiscount(),
        );
    }

    public function testLeavesAnEmptyCollectionUntouched(): void
    {
        // Arrange
        $discountConfiguratorCollectionTransfer = new DiscountConfiguratorCollectionTransfer();

        // Act
        $expandedDiscountConfiguratorCollectionTransfer = $this->createDiscountConfiguratorCollectionExpander()
            ->expandDiscountConfiguratorCollectionWithPromotions($discountConfiguratorCollectionTransfer);

        // Assert
        $this->assertCount(0, $expandedDiscountConfiguratorCollectionTransfer->getDiscountConfigurators());
    }

    protected function createDiscountConfiguratorCollectionExpander(): DiscountConfiguratorCollectionExpanderInterface
    {
        return new DiscountConfiguratorCollectionExpander(new DiscountPromotionRepository());
    }

    protected function createDiscountConfigurator(DiscountGeneralTransfer $discountGeneralTransfer): DiscountConfiguratorTransfer
    {
        return (new DiscountConfiguratorTransfer())
            ->setDiscountGeneral($discountGeneralTransfer)
            ->setDiscountCalculator(new DiscountCalculatorTransfer());
    }
}
