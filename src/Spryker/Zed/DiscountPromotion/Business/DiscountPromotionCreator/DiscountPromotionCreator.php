<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\DiscountPromotionCreator;

use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionEntityManagerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class DiscountPromotionCreator implements DiscountPromotionCreatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionEntityManagerInterface
     */
    protected $discountPromotionEntityManager;

    /**
     * @param \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionEntityManagerInterface $discountPromotionEntityManager
     */
    public function __construct(DiscountPromotionEntityManagerInterface $discountPromotionEntityManager)
    {
        $this->discountPromotionEntityManager = $discountPromotionEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer
     */
    public function create(DiscountPromotionTransfer $discountPromotionTransfer): DiscountPromotionTransfer
    {
        $discountPromotionTransfer->requireFkDiscount();

        return $this->getTransactionHandler()
            ->handleTransaction(function () use ($discountPromotionTransfer) {
                return $this->discountPromotionEntityManager->createDiscountPromotion($discountPromotionTransfer);
            });
    }
}
