<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\DiscountPromotion\Business\Expander;

use Generated\Shared\Transfer\DiscountConfiguratorCollectionTransfer;
use Generated\Shared\Transfer\DiscountPromotionConditionsTransfer;
use Generated\Shared\Transfer\DiscountPromotionCriteriaTransfer;
use Spryker\Shared\DiscountPromotion\DiscountPromotionConfig;
use Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionRepositoryInterface;

class DiscountConfiguratorCollectionExpander implements DiscountConfiguratorCollectionExpanderInterface
{
    public function __construct(protected DiscountPromotionRepositoryInterface $discountPromotionRepository)
    {
    }

    public function expandDiscountConfiguratorCollectionWithPromotions(
        DiscountConfiguratorCollectionTransfer $discountConfiguratorCollectionTransfer
    ): DiscountConfiguratorCollectionTransfer {
        $discountIds = [];

        foreach ($discountConfiguratorCollectionTransfer->getDiscountConfigurators() as $discountConfiguratorTransfer) {
            $idDiscount = $discountConfiguratorTransfer->getDiscountGeneral()?->getIdDiscount();

            if ($idDiscount !== null) {
                $discountIds[] = $idDiscount;
            }
        }

        if ($discountIds === []) {
            return $discountConfiguratorCollectionTransfer;
        }

        $discountPromotionTransfersByIdDiscount = $this->getDiscountPromotionTransfersIndexedByIdDiscount($discountIds);

        foreach ($discountConfiguratorCollectionTransfer->getDiscountConfigurators() as $discountConfiguratorTransfer) {
            $idDiscount = $discountConfiguratorTransfer->getDiscountGeneral()?->getIdDiscount();
            $discountCalculatorTransfer = $discountConfiguratorTransfer->getDiscountCalculator();

            if ($idDiscount === null || $discountCalculatorTransfer === null || !isset($discountPromotionTransfersByIdDiscount[$idDiscount])) {
                continue;
            }

            $discountCalculatorTransfer
                ->setCollectorStrategyType(DiscountPromotionConfig::DISCOUNT_COLLECTOR_STRATEGY)
                ->setDiscountPromotion($discountPromotionTransfersByIdDiscount[$idDiscount]);
        }

        return $discountConfiguratorCollectionTransfer;
    }

    /**
     * @param array<int, int> $discountIds
     *
     * @return array<int, \Generated\Shared\Transfer\DiscountPromotionTransfer>
     */
    protected function getDiscountPromotionTransfersIndexedByIdDiscount(array $discountIds): array
    {
        $discountPromotionCollectionTransfer = $this->discountPromotionRepository->getDiscountPromotionCollection(
            (new DiscountPromotionCriteriaTransfer())->setDiscountPromotionConditions(
                (new DiscountPromotionConditionsTransfer())->setDiscountIds($discountIds),
            ),
        );

        $indexed = [];

        /** @var \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer */
        foreach ($discountPromotionCollectionTransfer->getDiscountPromotions() as $discountPromotionTransfer) {
            $indexed[$discountPromotionTransfer->getFkDiscountOrFail()] = $discountPromotionTransfer;
        }

        return $indexed;
    }
}
