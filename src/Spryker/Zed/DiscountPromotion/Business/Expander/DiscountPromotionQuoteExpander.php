<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\Expander;

use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\PromotionItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToProductInterface;

class DiscountPromotionQuoteExpander implements DiscountPromotionQuoteExpanderInterface
{
    /**
     * @var \Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToProductInterface
     */
    protected $productFacade;

    public function __construct(DiscountPromotionToProductInterface $productFacade)
    {
        $this->productFacade = $productFacade;
    }

    public function expandWithPromotionItem(
        DiscountTransfer $discountTransfer,
        QuoteTransfer $quoteTransfer,
        DiscountPromotionTransfer $discountPromotionTransfer,
        int $promotionMaximumQuantity
    ): void {
        $promotionItemTransfer = $this->createPromotionItemTransfer(
            $discountPromotionTransfer,
            $discountTransfer,
            $promotionMaximumQuantity,
        );

        $quoteTransfer->addPromotionItem($promotionItemTransfer);
    }

    protected function createPromotionItemTransfer(
        DiscountPromotionTransfer $discountPromotionTransfer,
        DiscountTransfer $discountTransfer,
        int $promotionProductMaximumQuantity
    ): PromotionItemTransfer {
        $idProductAbstract = $this->productFacade->findProductAbstractIdBySku($discountPromotionTransfer->getAbstractSku());

        $promotionItemDiscountTransfer = (new DiscountTransfer())->fromArray($discountTransfer->toArray());
        $promotionItemTransfer = (new PromotionItemTransfer())
            ->fromArray($discountPromotionTransfer->toArray(), true)
            ->setIdProductAbstract($idProductAbstract)
            ->setMaxQuantity($promotionProductMaximumQuantity)
            ->setDiscount($promotionItemDiscountTransfer);

        $abstractSkus = $discountPromotionTransfer->getAbstractSkus();
        if ($abstractSkus) {
            $discountTransfer->getDiscountPromotionOrFail()->setAbstractSku($abstractSkus[0]);
        }

        return $promotionItemTransfer;
    }
}
