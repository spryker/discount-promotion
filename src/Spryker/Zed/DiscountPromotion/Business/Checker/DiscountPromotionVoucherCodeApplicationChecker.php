<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\Checker;

use Generated\Shared\Transfer\DiscountVoucherCheckResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class DiscountPromotionVoucherCodeApplicationChecker implements DiscountPromotionVoucherCodeApplicationCheckerInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VOUCHER_NON_APPLICABLE = 'cart.voucher.apply.non_applicable';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VOUCHER_APPLY_SUCCESSFUL = 'cart.voucher.apply.successful';

    /**
     * @uses \Spryker\Shared\CartCode\CartCodesConfig::MESSAGE_TYPE_SUCCESS
     *
     * @var string
     */
    protected const MESSAGE_TYPE_SUCCESS = 'success';

    /**
     * @uses \Spryker\Shared\CartCode\CartCodesConfig::MESSAGE_TYPE_ERROR
     *
     * @var string
     */
    protected const MESSAGE_TYPE_ERROR = 'error';

    public function check(QuoteTransfer $quoteTransfer, string $voucherCode): DiscountVoucherCheckResponseTransfer
    {
        if (in_array($voucherCode, $quoteTransfer->getUsedNotAppliedVoucherCodes(), true)) {
            return $this->createSuccessResponse();
        }

        foreach ($quoteTransfer->getVoucherDiscounts() as $discountTransfer) {
            if ($discountTransfer->getVoucherCode() === $voucherCode) {
                return $this->createSuccessResponse();
            }
        }

        return $this->createErrorResponse();
    }

    protected function createSuccessResponse(): DiscountVoucherCheckResponseTransfer
    {
        $messageTransfer = (new MessageTransfer())
            ->setValue(static::GLOSSARY_KEY_VOUCHER_APPLY_SUCCESSFUL)
            ->setType(static::MESSAGE_TYPE_SUCCESS);

        return (new DiscountVoucherCheckResponseTransfer())->setIsSuccessful(true)
            ->setMessage($messageTransfer);
    }

    protected function createErrorResponse(): DiscountVoucherCheckResponseTransfer
    {
        $messageTransfer = (new MessageTransfer())
            ->setValue(static::GLOSSARY_KEY_VOUCHER_NON_APPLICABLE)
            ->setType(static::MESSAGE_TYPE_ERROR);

        return (new DiscountVoucherCheckResponseTransfer())->setIsSuccessful(false)
            ->setMessage($messageTransfer);
    }
}
