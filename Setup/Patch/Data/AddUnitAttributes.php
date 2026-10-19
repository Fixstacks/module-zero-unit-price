<?php
/**
 * Copyright © 2026 Roman Pasichnyk
 * SPDX-License-Identifier: OSL-3.0 OR AFL-3.0
 */
declare(strict_types=1);

namespace Zero\UnitPrice\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Zero\UnitPrice\Model\Attribute\Source\Unit;
use Zero\UnitPrice\Model\Config;

/**
 * "Content" (how much the pack holds) and "Unit" (what it's measured in),
 * next to the price in every attribute set's General / Product Details
 * group, and loaded with product lists (the cards show the unit price).
 */
class AddUnitAttributes implements DataPatchInterface
{
    public function __construct(
        private readonly ModuleDataSetupInterface $moduleDataSetup,
        private readonly EavSetupFactory $eavSetupFactory
    ) {
    }

    public function apply(): void
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $attributes = [
            Config::CONTENT => [
                'type' => 'decimal',
                'label' => 'Content',
                'input' => 'text',
                'frontend_class' => 'validate-zero-or-greater',
                'note' => 'How much the pack holds, in the Unit below: 750 for a 750 ml bottle. With both, the unit price shows next to the price.',
            ],
            Config::UNIT => [
                'type' => 'varchar',
                'label' => 'Unit',
                'input' => 'select',
                'source' => Unit::class,
                'note' => 'Per kg/l (per 100 g/ml for small packs), per m, m², m³ or piece.',
            ],
        ];
        $order = 41;
        foreach ($attributes as $code => $definition) {
            if (!$eavSetup->getAttributeId(Product::ENTITY, $code)) {
                $eavSetup->addAttribute(Product::ENTITY, $code, $definition + [
                    'global' => Attribute::SCOPE_GLOBAL,
                    'required' => false,
                    'user_defined' => true,
                    'visible' => true,
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'visible_on_front' => false,
                    'used_in_product_listing' => true,
                    'is_used_in_grid' => false,
                    'is_visible_in_grid' => false,
                    'is_filterable_in_grid' => false,
                ]);
            }
            foreach ($eavSetup->getAllAttributeSetIds(Product::ENTITY) as $setId) {
                $group = $eavSetup->getAttributeGroup(Product::ENTITY, $setId, 'General')
                    ?: $eavSetup->getAttributeGroup(Product::ENTITY, $setId, 'Product Details');
                if ($group) {
                    $eavSetup->addAttributeToGroup(Product::ENTITY, $setId, (int) $group['attribute_group_id'], $code, $order);
                }
            }
            $order++;
        }
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }
}
