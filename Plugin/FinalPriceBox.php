<?php
/**
 * Copyright © 2026 Roman Pasichnyk
 * SPDX-License-Identifier: OSL-3.0 OR AFL-3.0
 */
declare(strict_types=1);

namespace Zero\UnitPrice\Plugin;

use Magento\Catalog\Pricing\Render\FinalPriceBox as Subject;
use Magento\Framework\Escaper;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Zero\UnitPrice\Model\Config;
use Zero\UnitPrice\Model\UnitPrice;

/**
 * "(9,33 € / 1 l)" under a product's price, wherever the price is shown,
 * from the price the box shows (with or without tax, as the store displays
 * it; a configurable product's lowest). Added after the box's own HTML, so
 * the price box's block cache stays as it is.
 */
class FinalPriceBox
{
    private const REFERENCES = [
        'kg' => '1 kg',
        '100 g' => '100 g',
        'l' => '1 l',
        '100 ml' => '100 ml',
        'm' => '1 m',
        'm2' => '1 m²',
        'm3' => '1 m³',
    ];

    public function __construct(
        private readonly Config $config,
        private readonly UnitPrice $unitPrice,
        private readonly PriceCurrencyInterface $priceCurrency,
        private readonly Escaper $escaper
    ) {
    }

    /**
     * @param string $result
     * @return string
     */
    public function afterToHtml(Subject $subject, $result)
    {
        if (!is_string($result) || trim($result) === '' || !$this->config->isEnabled()) {
            return $result;
        }
        $product = $subject->getSaleableItem();
        $unit = (string) $product->getData(Config::UNIT);
        $content = (float) $product->getData(Config::CONTENT);
        if ($unit === '' || $content <= 0) {
            return $result;
        }
        $price = (float) $subject->getPrice()->getAmount()->getValue();
        $calculated = $this->unitPrice->calculate($price, $content, $unit, $this->config->smallPacks());
        if ($calculated === null
            || ($this->config->hideEqual() && abs($calculated['value'] - $price) < 0.005)
        ) {
            return $result;
        }
        $reference = $calculated['reference'] === 'pcs'
            ? (string) __('piece')
            : (self::REFERENCES[$calculated['reference']] ?? $calculated['reference']);
        $text = __('%1 / %2', $this->priceCurrency->format($calculated['value'], false), $reference);
        return $result . '<p class="zero-unit-price" data-zero-unit-price>(' . $this->escaper->escapeHtml((string) $text) . ')</p>';
    }
}
