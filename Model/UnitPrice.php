<?php
/**
 * Copyright © 2026 Roman Pasichnyk
 * SPDX-License-Identifier: OSL-3.0 OR AFL-3.0
 */
declare(strict_types=1);

namespace Zero\UnitPrice\Model;

/**
 * A price per kilogram, litre, metre, square or cubic metre, or piece, from
 * a pack's content: 750 ml at 7.00 is 9.33 per litre.
 *
 * As EU price indication rules have it (Directive 98/6/EC; in Germany the
 * PAngV): weight per 1 kg and volume per 1 l, or per 100 g / 100 ml for packs
 * of up to 250 g / 250 ml when the store chooses; length per 1 m, area per
 * 1 m², volume of goods sold by the cubic metre per 1 m³, pieces per piece.
 */
class UnitPrice
{
    /** unit => [family, size in the family's smallest unit] */
    public const UNITS = [
        'mg' => ['weight', 0.001],
        'g' => ['weight', 1.0],
        'kg' => ['weight', 1000.0],
        'ml' => ['volume', 1.0],
        'cl' => ['volume', 10.0],
        'l' => ['volume', 1000.0],
        'mm' => ['length', 1.0],
        'cm' => ['length', 10.0],
        'm' => ['length', 1000.0],
        'm2' => ['area', 1.0],
        'm3' => ['cubic', 1.0],
        'pcs' => ['piece', 1.0],
    ];

    /** family => [reference unit, reference size; small packs: [limit, unit, size]] */
    private const REFERENCES = [
        'weight' => ['kg', 1000.0, [250.0, '100 g', 100.0]],
        'volume' => ['l', 1000.0, [250.0, '100 ml', 100.0]],
        'length' => ['m', 1000.0, null],
        'area' => ['m2', 1.0, null],
        'cubic' => ['m3', 1.0, null],
        'piece' => ['pcs', 1.0, null],
    ];

    /**
     * @return array{value: float, reference: string}|null reference: "kg", "100 g", "l", "m", "m2", "m3", "pcs"
     */
    public function calculate(float $price, float $content, string $unit, bool $smallPacks = true): ?array
    {
        if ($price <= 0 || $content <= 0 || !isset(self::UNITS[$unit])) {
            return null;
        }
        [$family, $size] = self::UNITS[$unit];
        $amount = $content * $size;
        [$reference, $referenceSize, $small] = self::REFERENCES[$family];
        if ($smallPacks && $small !== null && $amount <= $small[0]) {
            [, $reference, $referenceSize] = $small;
        }
        return ['value' => $price / $amount * $referenceSize, 'reference' => $reference];
    }
}
