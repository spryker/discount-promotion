<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\DiscountPromotionCreator;

use Generated\Shared\Transfer\DiscountPromotionTransfer;

interface DiscountPromotionCreatorInterface
{
    public function create(DiscountPromotionTransfer $discountPromotionTransfer): DiscountPromotionTransfer;
}
