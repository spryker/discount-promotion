<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\DiscountPromotion\Communication\Form\Constraint;

use Spryker\Zed\DiscountPromotion\Business\Validator\DiscountPromotionValidatorInterface;
use Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToTranslatorFacadeInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class DiscountPromotionValid extends SymfonyConstraint
{
    public const string OPTION_DISCOUNT_PROMOTION_VALIDATOR = 'discountPromotionValidator';

    public const string OPTION_TRANSLATOR_FACADE = 'translatorFacade';

    protected ?DiscountPromotionValidatorInterface $discountPromotionValidator = null;

    protected ?DiscountPromotionToTranslatorFacadeInterface $translatorFacade = null;

    public function getDiscountPromotionValidator(): ?DiscountPromotionValidatorInterface
    {
        return $this->discountPromotionValidator;
    }

    public function getTranslatorFacade(): ?DiscountPromotionToTranslatorFacadeInterface
    {
        return $this->translatorFacade;
    }

    public function getTargets(): string
    {
        return static::CLASS_CONSTRAINT;
    }
}
