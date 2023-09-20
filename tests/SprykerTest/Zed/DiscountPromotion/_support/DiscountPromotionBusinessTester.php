<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DiscountPromotion;

use Codeception\Actor;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Spryker\Zed\Availability\Business\AvailabilityFacadeInterface;
use Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionRepository;
use Spryker\Zed\Stock\Business\StockFacadeInterface;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionFacadeInterface getFacade()
 *
 * @SuppressWarnings(\SprykerTest\Zed\DiscountPromotion\PHPMD)
 */
class DiscountPromotionBusinessTester extends Actor
{
    use _generated\DiscountPromotionBusinessTesterActions;

    /**
     * @var string
     */
    public const STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    public const TEST_ABSTRACT_SKU = 'sku-123';

    /**
     * @var string
     */
    public const TEST_ABSTRACT_SKU_2 = 'sku-130';

    /**
     * @return \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface
     */
    public function getAvailabilityFacade(): AvailabilityFacadeInterface
    {
        return $this->getLocator()
            ->availability()
            ->facade();
    }

    /**
     * @return \Spryker\Zed\Stock\Business\StockFacadeInterface
     */
    public function getStockFacade(): StockFacadeInterface
    {
        return $this->getLocator()
            ->stock()
            ->facade();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param int $quantity
     *
     * @return void
     */
    public function addStockForProduct(ProductConcreteTransfer $productConcreteTransfer, int $quantity): void
    {
        $availableStockTypes = $this->getStockFacade()
            ->getAvailableStockTypes();

        foreach ($availableStockTypes as $stockType) {
            $stockProductTransfer = (new StockProductTransfer())
                ->setSku($productConcreteTransfer->getSku())
                ->setQuantity($quantity)
                ->setStockType($stockType);

            $this->getStockFacade()
                ->createStockProduct($stockProductTransfer);
        }
    }

    /**
     * @param array $productAbstractOverride
     * @param array $productConcreteOverride
     * @param int $quantity
     *
     * @return void
     */
    public function haveProductWithStock(
        array $productAbstractOverride = [],
        array $productConcreteOverride = [],
        int $quantity = 1
    ): void {
        $productConcreteTransfer = $this->haveProduct($productConcreteOverride, $productAbstractOverride);

        $this->addStockForProduct($productConcreteTransfer, $quantity);
        $this->getAvailabilityFacade()
            ->updateAvailability($productConcreteTransfer->getSku());
    }

    /**
     * @param array $storeOverride
     * @param array $quoteOverride
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function prepareQuoteWithStore(
        array $storeOverride = [],
        array $quoteOverride = []
    ): QuoteTransfer {
        $storeTransfer = $this->haveStore($storeOverride);

        return (new QuoteBuilder($quoteOverride))
            ->build()
            ->setStore($storeTransfer);
    }

    /**
     * @return bool
     */
    public function isAbstractSkusFieldExists(): bool
    {
        return (new DiscountPromotionRepository())->isAbstractSkusFieldExists();
    }

    /**
     * @param array<string, mixed> $seed
     * @param int|null $stockQuantity
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer
     */
    public function createDiscountPromotionWithProductStock(array $seed = [], ?int $stockQuantity = null): DiscountPromotionTransfer
    {
        $seed += [
            DiscountPromotionTransfer::FK_DISCOUNT => $this->haveDiscount()->getIdDiscount(),
            DiscountPromotionTransfer::ABSTRACT_SKU => static::TEST_ABSTRACT_SKU,
            DiscountPromotionTransfer::ABSTRACT_SKUS => [static::TEST_ABSTRACT_SKU],
        ];

        $discountPromotionTransfer = $this->haveDiscountPromotion($seed);

        if ($stockQuantity === null) {
            $stockQuantity = $discountPromotionTransfer->getQuantity();
        }

        foreach ($seed[DiscountPromotionTransfer::ABSTRACT_SKUS] as $abstractSku) {
            $this->haveProductWithStock([ProductAbstractTransfer::SKU => $abstractSku], [], $stockQuantity);
        }

        return $discountPromotionTransfer;
    }
}
