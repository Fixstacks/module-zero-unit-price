<?php
/**
 * Copyright © 2026 Roman Pasichnyk
 * SPDX-License-Identifier: OSL-3.0 OR AFL-3.0
 */
declare(strict_types=1);

namespace Zero\UnitPrice\Model\Attribute\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

/**
 * The "Unit" product attribute's choices: what the Content is measured in.
 */
class Unit extends AbstractSource
{
    /**
     * @return array<int, array{value: string, label: \Magento\Framework\Phrase|string}>
     */
    public function getAllOptions()
    {
        if ($this->_options === null) {
            $this->_options = [
                ['value' => '', 'label' => __('-- No unit price --')],
                ['value' => 'g', 'label' => __('Grams (g)')],
                ['value' => 'kg', 'label' => __('Kilograms (kg)')],
                ['value' => 'mg', 'label' => __('Milligrams (mg)')],
                ['value' => 'ml', 'label' => __('Millilitres (ml)')],
                ['value' => 'cl', 'label' => __('Centilitres (cl)')],
                ['value' => 'l', 'label' => __('Litres (l)')],
                ['value' => 'mm', 'label' => __('Millimetres (mm)')],
                ['value' => 'cm', 'label' => __('Centimetres (cm)')],
                ['value' => 'm', 'label' => __('Metres (m)')],
                ['value' => 'm2', 'label' => __('Square metres (m²)')],
                ['value' => 'm3', 'label' => __('Cubic metres (m³)')],
                ['value' => 'pcs', 'label' => __('Pieces')],
            ];
        }
        return $this->_options;
    }
}
