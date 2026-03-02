<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Persistence;

use Generated\Shared\Transfer\DiscountPromotionCollectionTransfer;
use Generated\Shared\Transfer\DiscountPromotionCriteriaTransfer;
use Generated\Shared\Transfer\DiscountPromotionTransfer;

interface DiscountPromotionRepositoryInterface
{
    /**
     * @deprecated Will be removed in next major release.
     *
     * @return bool
     */
    public function isAbstractSkusFieldExists(): bool;

    public function getDiscountPromotionCollection(DiscountPromotionCriteriaTransfer $discountPromotionCriteriaTransfer): DiscountPromotionCollectionTransfer;

    public function hasDiscountPromotion(DiscountPromotionCriteriaTransfer $discountPromotionCriteriaTransfer): bool;

    public function findDiscountPromotionByCriteria(
        DiscountPromotionCriteriaTransfer $discountPromotionCriteriaTransfer
    ): ?DiscountPromotionTransfer;
}
