<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\DiscountPromotion\Communication\Plugin\Discount;

use Generated\Shared\Transfer\DiscountConfiguratorCollectionTransfer;
use Spryker\Zed\DiscountExtension\Dependency\Plugin\DiscountConfiguratorCollectionExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionFacadeInterface getFacade()
 * @method \Spryker\Zed\DiscountPromotion\DiscountPromotionConfig getConfig()
 * @method \Spryker\Zed\DiscountPromotion\Communication\DiscountPromotionCommunicationFactory getFactory()
 */
class DiscountPromotionDiscountConfiguratorCollectionExpanderPlugin extends AbstractPlugin implements DiscountConfiguratorCollectionExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Loads the promotional discounts of the whole collection with one query and sets them to the matching calculators.
     *
     * @api
     */
    public function expand(
        DiscountConfiguratorCollectionTransfer $discountConfiguratorCollectionTransfer
    ): DiscountConfiguratorCollectionTransfer {
        return $this->getBusinessFactory()
            ->createDiscountConfiguratorCollectionExpander()
            ->expandDiscountConfiguratorCollectionWithPromotions($discountConfiguratorCollectionTransfer);
    }
}
