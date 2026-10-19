<?php
/**
 * Copyright © 2026 Roman Pasichnyk
 * SPDX-License-Identifier: OSL-3.0 OR AFL-3.0
 */
declare(strict_types=1);

namespace Zero\UnitPrice\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Stores > Configuration > Zero > Unit Price.
 */
class Config
{
    public const CONTENT = 'zero_unit_content';
    public const UNIT = 'zero_unit';

    public function __construct(private readonly ScopeConfigInterface $scopeConfig)
    {
    }

    public function isEnabled(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag('zero_unit_price/general/enabled', ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function smallPacks(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag('zero_unit_price/general/small_packs', ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function hideEqual(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag('zero_unit_price/general/hide_equal', ScopeInterface::SCOPE_STORE, $storeId);
    }
}
