<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\DiscountPromotion\Business\Validator\Rule\DiscountPromotion;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Shared\DiscountPromotion\DiscountPromotionConfig;
use Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToProductInterface;

class AbstractSkusDiscountPromotionValidatorRule implements DiscountPromotionValidatorRuleInterface
{
    public const string ENTITY_IDENTIFIER_ABSTRACT_SKUS = 'discountCalculator.discountPromotion.abstractSkus';

    protected const string TRANSLATION_PARAMETER_NON_EXISTING_SKUS = '%non_existing_skus%';

    protected const string SKU_SEPARATOR = ', ';

    public function __construct(protected DiscountPromotionToProductInterface $productFacade)
    {
    }

    public function validate(DiscountConfiguratorTransfer $discountConfiguratorTransfer): ErrorCollectionTransfer
    {
        $errorCollectionTransfer = new ErrorCollectionTransfer();
        $abstractSkus = $this->extractAbstractSkus($discountConfiguratorTransfer->getDiscountCalculator()?->getDiscountPromotion());

        if ($abstractSkus === []) {
            return $errorCollectionTransfer->addError(
                (new ErrorTransfer())
                    ->setMessage(DiscountPromotionConfig::TRANSLATION_KEY_ABSTRACT_SKUS_REQUIRED)
                    ->setEntityIdentifier(static::ENTITY_IDENTIFIER_ABSTRACT_SKUS),
            );
        }

        $nonExistingAbstractSkus = $this->findNonExistingAbstractSkus($abstractSkus);

        if ($nonExistingAbstractSkus === []) {
            return $errorCollectionTransfer;
        }

        return $errorCollectionTransfer->addError(
            (new ErrorTransfer())
                ->setMessage(DiscountPromotionConfig::TRANSLATION_KEY_ABSTRACT_SKUS_UNKNOWN)
                ->setEntityIdentifier(static::ENTITY_IDENTIFIER_ABSTRACT_SKUS)
                ->setParameters([
                    static::TRANSLATION_PARAMETER_NON_EXISTING_SKUS => implode(static::SKU_SEPARATOR, $nonExistingAbstractSkus),
                ]),
        );
    }

    /**
     * @return array<int, string>
     */
    protected function extractAbstractSkus(?DiscountPromotionTransfer $discountPromotionTransfer): array
    {
        $abstractSkus = $discountPromotionTransfer?->getAbstractSkus() ?? [];
        $abstractSkus[] = $discountPromotionTransfer?->getAbstractSku();

        $trimmedAbstractSkus = [];

        foreach ($abstractSkus as $abstractSku) {
            $trimmedAbstractSku = trim((string)$abstractSku);

            if ($trimmedAbstractSku !== '') {
                $trimmedAbstractSkus[] = $trimmedAbstractSku;
            }
        }

        return array_values(array_unique($trimmedAbstractSkus));
    }

    /**
     * @param array<int, string> $abstractSkus
     *
     * @return array<int, string>
     */
    protected function findNonExistingAbstractSkus(array $abstractSkus): array
    {
        $existingAbstractSkus = [];

        foreach ($this->productFacade->getRawProductAbstractTransfersByAbstractSkus($abstractSkus) as $productAbstractTransfer) {
            $existingAbstractSkus[] = (string)$productAbstractTransfer->getSku();
        }

        return array_values(array_diff($abstractSkus, $existingAbstractSkus));
    }
}
