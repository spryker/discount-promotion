<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\Checker;

use Generated\Shared\Transfer\DiscountVoucherCheckResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface DiscountPromotionVoucherCodeApplicationCheckerInterface
{
    public function check(QuoteTransfer $quoteTransfer, string $voucherCode): DiscountVoucherCheckResponseTransfer;
}
