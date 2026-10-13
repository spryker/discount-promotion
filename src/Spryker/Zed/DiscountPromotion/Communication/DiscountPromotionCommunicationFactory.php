<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Communication;

use Spryker\Shared\DiscountPromotion\DiscountPromotionConfig;
use Spryker\Zed\DiscountPromotion\Communication\Expander\DiscountPromotionFormExpander;
use Spryker\Zed\DiscountPromotion\Communication\Expander\DiscountPromotionFormExpanderInterface;
use Spryker\Zed\DiscountPromotion\Communication\Form\Constraint\DiscountPromotionValid;
use Spryker\Zed\DiscountPromotion\Communication\Form\DiscountPromotionFormType;
use Spryker\Zed\DiscountPromotion\Communication\Form\Transformer\AbstractSkusTransformer;
use Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToLocaleFacadeInterface;
use Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToProductInterface;
use Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToTranslatorFacadeInterface;
use Spryker\Zed\DiscountPromotion\DiscountPromotionDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Kernel\Communication\BusinessFactoryResolverAwareTrait;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Validator\Constraint;

/**
 * @method \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\DiscountPromotion\DiscountPromotionConfig getConfig()
 * @method \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionFacadeInterface getFacade()
 * @method \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionRepositoryInterface getRepository()
 */
class DiscountPromotionCommunicationFactory extends AbstractCommunicationFactory
{
    use BusinessFactoryResolverAwareTrait;

    /**
     * @deprecated Use the FQCN directly.
     *
     * @return string
     */
    public function createDiscountFormPromotionType()
    {
        return DiscountPromotionFormType::class;
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface<array<string>|null, string>
     */
    public function createAbstractSkusTransformer(): DataTransformerInterface
    {
        return new AbstractSkusTransformer();
    }

    public function createDiscountPromotionFormExpander(): DiscountPromotionFormExpanderInterface
    {
        return new DiscountPromotionFormExpander($this->getLocaleFacade());
    }

    public function createDiscountPromotionValidConstraint(): Constraint
    {
        return new DiscountPromotionValid(
            [
                DiscountPromotionValid::OPTION_DISCOUNT_PROMOTION_VALIDATOR => $this->getBusinessFactory()->createDiscountPromotionValidator(),
                DiscountPromotionValid::OPTION_TRANSLATOR_FACADE => $this->getTranslatorFacade(),
            ],
            [DiscountPromotionConfig::DISCOUNT_COLLECTOR_STRATEGY],
        );
    }

    public function getProductFacade(): DiscountPromotionToProductInterface
    {
        return $this->getProvidedDependency(DiscountPromotionDependencyProvider::FACADE_PRODUCT);
    }

    public function getTranslatorFacade(): DiscountPromotionToTranslatorFacadeInterface
    {
        return $this->getProvidedDependency(DiscountPromotionDependencyProvider::FACADE_TRANSLATOR);
    }

    public function getLocaleFacade(): DiscountPromotionToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(DiscountPromotionDependencyProvider::FACADE_LOCALE);
    }
}
