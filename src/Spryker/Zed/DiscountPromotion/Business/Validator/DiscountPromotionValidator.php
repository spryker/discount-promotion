<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\DiscountPromotion\Business\Validator;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Shared\DiscountPromotion\DiscountPromotionConfig;

class DiscountPromotionValidator implements DiscountPromotionValidatorInterface
{
    /**
     * @param list<\Spryker\Zed\DiscountPromotion\Business\Validator\Rule\DiscountPromotion\DiscountPromotionValidatorRuleInterface> $discountPromotionValidatorRules
     */
    public function __construct(protected array $discountPromotionValidatorRules)
    {
    }

    public function validateDiscountPromotion(DiscountConfiguratorTransfer $discountConfiguratorTransfer): ErrorCollectionTransfer
    {
        $errorCollectionTransfer = new ErrorCollectionTransfer();
        $collectorStrategyType = $discountConfiguratorTransfer->getDiscountCalculator()?->getCollectorStrategyType();

        if ($collectorStrategyType !== DiscountPromotionConfig::DISCOUNT_COLLECTOR_STRATEGY) {
            return $errorCollectionTransfer;
        }

        foreach ($this->discountPromotionValidatorRules as $discountPromotionValidatorRule) {
            foreach ($discountPromotionValidatorRule->validate($discountConfiguratorTransfer)->getErrors() as $errorTransfer) {
                $errorCollectionTransfer->addError($errorTransfer);
            }
        }

        return $errorCollectionTransfer;
    }
}
