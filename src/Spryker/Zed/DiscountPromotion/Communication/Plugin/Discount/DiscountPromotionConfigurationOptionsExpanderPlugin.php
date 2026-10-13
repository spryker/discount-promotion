<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Communication\Plugin\Discount;

use Generated\Shared\Transfer\DiscountCollectorStrategyOptionTransfer;
use Generated\Shared\Transfer\DiscountConfigurationOptionsTransfer;
use Spryker\Shared\DiscountPromotion\DiscountPromotionConfig;
use Spryker\Zed\DiscountExtension\Dependency\Plugin\DiscountConfigurationOptionsExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionFacadeInterface getFacade()
 * @method \Spryker\Zed\DiscountPromotion\Communication\DiscountPromotionCommunicationFactory getFactory()
 * @method \Spryker\Zed\DiscountPromotion\DiscountPromotionConfig getConfig()
 * @method \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionQueryContainerInterface getQueryContainer()
 */
class DiscountPromotionConfigurationOptionsExpanderPlugin extends AbstractPlugin implements DiscountConfigurationOptionsExpanderPluginInterface
{
    /**
     * @uses \Spryker\Zed\DiscountPromotion\Communication\Plugin\Discount\DiscountPromotionCalculationFormDataExpanderPlugin::expandDataProviderOptions()
     */
    protected const string COLLECTOR_STRATEGY_LABEL = 'Discount promotion to product';

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function expand(
        DiscountConfigurationOptionsTransfer $discountConfigurationOptionsTransfer
    ): DiscountConfigurationOptionsTransfer {
        return $discountConfigurationOptionsTransfer->addCollectorStrategyType(
            (new DiscountCollectorStrategyOptionTransfer())
                ->setKey(DiscountPromotionConfig::DISCOUNT_COLLECTOR_STRATEGY)
                ->setLabel(static::COLLECTOR_STRATEGY_LABEL),
        );
    }
}
