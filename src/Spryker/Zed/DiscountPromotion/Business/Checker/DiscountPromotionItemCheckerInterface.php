<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\Checker;

use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Generated\Shared\Transfer\ItemTransfer;

interface DiscountPromotionItemCheckerInterface
{
    public function isItemRelatedToDiscountPromotion(
        ItemTransfer $itemTransfer,
        DiscountPromotionTransfer $discountPromotionTransfer,
        string $abstractSku
    ): bool;

    public function isItemPromotional(
        DiscountPromotionTransfer $discountPromotionTransfer,
        ItemTransfer $itemTransfer
    ): bool;
}
