<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\DiscountPromotion;

class DiscountPromotionConfig
{
    /**
     * @api
     *
     * This constant used to uniquely identify promotion discount collector strategy type
     *
     * @var string
     */
    public const DISCOUNT_COLLECTOR_STRATEGY = 'promotion';

    /**
     * @api
     */
    public const string TRANSLATION_KEY_ABSTRACT_SKUS_REQUIRED = 'At least one abstract product SKU is required for a promotional discount.';

    /**
     * @api
     */
    public const string TRANSLATION_KEY_QUANTITY_INVALID = 'Maximum quantity must be at least %min%.';

    /**
     * Specification:
     * - Glossary key of the error reported when a promotional abstract SKU does not exist; `%non_existing_skus%` lists them.
     * - Shared by the Backend API and the Back Office form, so both channels show the same text.
     *
     * @api
     */
    public const string TRANSLATION_KEY_ABSTRACT_SKUS_UNKNOWN = 'Unknown SKU(s): %non_existing_skus%';
}
