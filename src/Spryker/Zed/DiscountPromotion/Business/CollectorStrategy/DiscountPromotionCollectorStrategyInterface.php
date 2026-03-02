<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\CollectorStrategy;

use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface DiscountPromotionCollectorStrategyInterface
{
    public function isApplicable(
        DiscountPromotionTransfer $discountPromotionTransfer,
        DiscountTransfer $discountTransfer,
        QuoteTransfer $quoteTransfer
    ): bool;

    /**
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<\Generated\Shared\Transfer\DiscountableItemTransfer>
     */
    public function collect(
        DiscountPromotionTransfer $discountPromotionTransfer,
        DiscountTransfer $discountTransfer,
        QuoteTransfer $quoteTransfer
    ): array;
}
