<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\DiscountPromotion\Business\Validator\Rule\DiscountPromotion;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Shared\DiscountPromotion\DiscountPromotionConfig;

class QuantityDiscountPromotionValidatorRule implements DiscountPromotionValidatorRuleInterface
{
    public const string ENTITY_IDENTIFIER_QUANTITY = 'discountCalculator.discountPromotion.quantity';

    protected const int MINIMUM_QUANTITY = 1;

    protected const string TRANSLATION_PARAMETER_MIN = '%min%';

    public function validate(DiscountConfiguratorTransfer $discountConfiguratorTransfer): ErrorCollectionTransfer
    {
        $errorCollectionTransfer = new ErrorCollectionTransfer();
        $quantity = $discountConfiguratorTransfer->getDiscountCalculator()?->getDiscountPromotion()?->getQuantity();

        if ($quantity !== null && $quantity >= static::MINIMUM_QUANTITY) {
            return $errorCollectionTransfer;
        }

        return $errorCollectionTransfer->addError(
            (new ErrorTransfer())
                ->setMessage(DiscountPromotionConfig::TRANSLATION_KEY_QUANTITY_INVALID)
                ->setEntityIdentifier(static::ENTITY_IDENTIFIER_QUANTITY)
                ->setParameters([static::TRANSLATION_PARAMETER_MIN => static::MINIMUM_QUANTITY]),
        );
    }
}
