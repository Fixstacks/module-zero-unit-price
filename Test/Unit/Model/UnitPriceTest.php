<?php
/**
 * Copyright © 2026 Roman Pasichnyk
 * SPDX-License-Identifier: OSL-3.0 OR AFL-3.0
 */
declare(strict_types=1);

namespace Zero\UnitPrice\Test\Unit\Model;

use PHPUnit\Framework\TestCase;
use Zero\UnitPrice\Model\UnitPrice;

class UnitPriceTest extends TestCase
{
    private UnitPrice $unitPrice;

    protected function setUp(): void
    {
        $this->unitPrice = new UnitPrice();
    }

    public function testLargeBottleIsPerLitre(): void
    {
        $result = $this->unitPrice->calculate(7.00, 750, 'ml');
        self::assertSame('l', $result['reference']);
        self::assertEqualsWithDelta(9.333, $result['value'], 0.001);
    }

    public function testSmallPackIsPer100gUnlessTheStoreSaysNo(): void
    {
        self::assertSame(['value' => 1.6, 'reference' => '100 g'], $this->unitPrice->calculate(4.00, 250, 'g'));
        self::assertEqualsWithDelta(16.0, $this->unitPrice->calculate(4.00, 250, 'g', false)['value'], 0.0001);
        self::assertSame('kg', $this->unitPrice->calculate(4.00, 251, 'g')['reference']);
    }

    public function testUnitsWithinAFamilyConvert(): void
    {
        self::assertEqualsWithDelta(5.0, $this->unitPrice->calculate(10.00, 2, 'kg')['value'], 0.0001);
        self::assertEqualsWithDelta(4.0, $this->unitPrice->calculate(2.00, 50, 'cl')['value'], 0.0001);
        self::assertEqualsWithDelta(12.5, $this->unitPrice->calculate(25.00, 200, 'cm')['value'], 0.0001);
        self::assertSame(['value' => 3.0, 'reference' => 'pcs'], $this->unitPrice->calculate(9.00, 3, 'pcs'));
    }

    public function testNothingWithoutAUsableContent(): void
    {
        self::assertNull($this->unitPrice->calculate(9.00, 0, 'g'));
        self::assertNull($this->unitPrice->calculate(9.00, 100, 'stones'));
        self::assertNull($this->unitPrice->calculate(0.0, 100, 'g'));
    }
}
