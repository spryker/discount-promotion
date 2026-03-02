<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Persistence;

use Generated\Shared\Transfer\DiscountPromotionTransfer;

interface DiscountPromotionEntityManagerInterface
{
    public function removePromotionByIdDiscount(int $idDiscount): void;

    public function createDiscountPromotion(DiscountPromotionTransfer $discountPromotionTransfer): DiscountPromotionTransfer;

    public function updateDiscountPromotion(DiscountPromotionTransfer $discountPromotionTransfer): DiscountPromotionTransfer;
}
