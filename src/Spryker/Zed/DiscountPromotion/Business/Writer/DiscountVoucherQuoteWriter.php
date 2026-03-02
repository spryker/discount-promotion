<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\Writer;

use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class DiscountVoucherQuoteWriter implements DiscountVoucherQuoteWriterInterface
{
    public function addDiscountVoucherCode(
        DiscountTransfer $discountTransfer,
        QuoteTransfer $quoteTransfer
    ): void {
        if (!$discountTransfer->getVoucherCode()) {
            return;
        }

        $storedUnusedCodes = $quoteTransfer->getUsedNotAppliedVoucherCodes();
        if (!in_array($discountTransfer->getVoucherCode(), $storedUnusedCodes)) {
            $quoteTransfer->addUsedNotAppliedVoucherCode($discountTransfer->getVoucherCode());
        }
    }

    public function removeDiscountVoucherCode(DiscountTransfer $discountTransfer, QuoteTransfer $quoteTransfer): void
    {
        if (!$discountTransfer->getVoucherCode()) {
            return;
        }

        $usedNotAppliedCodes = [];
        foreach ($quoteTransfer->getUsedNotAppliedVoucherCodes() as $unusedVoucherCode) {
            if ($unusedVoucherCode === $discountTransfer->getVoucherCode()) {
                continue;
            }

            $usedNotAppliedCodes[] = $unusedVoucherCode;
        }

        $quoteTransfer->setUsedNotAppliedVoucherCodes($usedNotAppliedCodes);
    }
}
