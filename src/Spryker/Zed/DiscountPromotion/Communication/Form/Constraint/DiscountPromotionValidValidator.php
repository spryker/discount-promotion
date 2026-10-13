<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\DiscountPromotion\Communication\Form\Constraint;

use Generated\Shared\Transfer\DiscountCalculatorTransfer;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Shared\DiscountPromotion\DiscountPromotionConfig;
use Spryker\Zed\DiscountPromotion\Business\Validator\Rule\DiscountPromotion\AbstractSkusDiscountPromotionValidatorRule;
use Spryker\Zed\DiscountPromotion\Business\Validator\Rule\DiscountPromotion\QuantityDiscountPromotionValidatorRule;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class DiscountPromotionValidValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof DiscountPromotionValid) {
            throw new UnexpectedTypeException($constraint, DiscountPromotionValid::class);
        }

        $discountPromotionValidator = $constraint->getDiscountPromotionValidator();

        if (!$value instanceof DiscountPromotionTransfer || $discountPromotionValidator === null) {
            return;
        }

        $errorCollectionTransfer = $discountPromotionValidator->validateDiscountPromotion(
            $this->createDiscountConfiguratorTransfer($value),
        );

        foreach ($errorCollectionTransfer->getErrors() as $errorTransfer) {
            $this->context
                ->buildViolation($this->translate($errorTransfer, $constraint))
                ->atPath($this->getFormPath((string)$errorTransfer->getEntityIdentifier()))
                ->addViolation();
        }
    }

    protected function createDiscountConfiguratorTransfer(
        DiscountPromotionTransfer $discountPromotionTransfer
    ): DiscountConfiguratorTransfer {
        return (new DiscountConfiguratorTransfer())->setDiscountCalculator(
            (new DiscountCalculatorTransfer())
                ->setCollectorStrategyType(DiscountPromotionConfig::DISCOUNT_COLLECTOR_STRATEGY)
                ->setDiscountPromotion($discountPromotionTransfer),
        );
    }

    protected function translate(ErrorTransfer $errorTransfer, DiscountPromotionValid $constraint): string
    {
        $translatorFacade = $constraint->getTranslatorFacade();
        $glossaryKey = (string)$errorTransfer->getMessage();

        if ($translatorFacade === null) {
            return $glossaryKey;
        }

        return $translatorFacade->trans($glossaryKey, $errorTransfer->getParameters());
    }

    protected function getFormPath(string $entityIdentifier): string
    {
        return $this->getFormPathByEntityIdentifier()[$entityIdentifier] ?? '';
    }

    /**
     * @return array<string, string>
     */
    protected function getFormPathByEntityIdentifier(): array
    {
        return [
            AbstractSkusDiscountPromotionValidatorRule::ENTITY_IDENTIFIER_ABSTRACT_SKUS => DiscountPromotionTransfer::ABSTRACT_SKUS,
            QuantityDiscountPromotionValidatorRule::ENTITY_IDENTIFIER_QUANTITY => DiscountPromotionTransfer::QUANTITY,
        ];
    }
}
