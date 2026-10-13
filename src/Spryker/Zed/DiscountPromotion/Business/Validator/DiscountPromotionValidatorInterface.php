<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\DiscountPromotion\Business\Validator;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\ErrorCollectionTransfer;

interface DiscountPromotionValidatorInterface
{
    public function validateDiscountPromotion(DiscountConfiguratorTransfer $discountConfiguratorTransfer): ErrorCollectionTransfer;
}
