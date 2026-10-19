# Zero_UnitPrice

The price per kilogram, litre, metre, square or cubic metre, or piece under
every price of a product sold by quantity: "€ 7,00 (€ 9,33 / 1 l)". EU price
indication rules ask for it (Directive 98/6/EC; Germany's PAngV, Austria's
PrAG) wherever such goods are offered with a price.

## Install

Requires Magento Open Source 2.4.7–2.4.9 and PHP 8.2–8.4 with the GD extension built with WebP (`php -r 'var_dump(function_exists("imagewebp"));'` says `true`; Magento's own PHP requirements cover the rest). Without WebP in GD, Zero skips its WebP image copies and serves the originals.

```bash
composer require fixstacks/module-zero-unit-price
bin/magento setup:upgrade
bin/magento setup:di:compile           # production mode
bin/magento setup:static-content:deploy # production mode
bin/magento cache:flush
```

Composer installs `fixstacks/module-zero-core` with it.

Part of [`fixstacks/zero`](https://fixstacks.com/zero), which installs the Zero theme and every Zero module at once.

## Setting it up

1. Stores > Configuration > Zero > Unit Price > Enabled: Yes (off by
   default, per store view).
2. On a product (Catalog > Products > General): **Content**, how much the
   pack holds, and **Unit**, what it's measured in: 750 and Millilitres for
   a 750 ml bottle; 3 and Pieces for a three-pack.

| Unit | Shown per |
| --- | --- |
| mg, g, kg | 1 kg; 100 g for packs up to 250 g (setting) |
| ml, cl, l | 1 l; 100 ml for packs up to 250 ml (setting) |
| mm, cm, m | 1 m |
| m² | 1 m² |
| m³ | 1 m³ |
| Pieces | piece |

"Hide When It Equals the Price" (on by default) shows nothing for a 1 kg
pack, whose price per kilogram is its price.

## Where it shows

Under the final price box, so wherever a price is: the product page, product
cards in categories, search and widgets, related products. It is worked out
from the price the box shows (with or without tax as the store displays
prices; a configurable product's lowest), and formatted in the store's
currency and locale ("€ 9,33" in de_AT, "9,33 €" in de_DE). Added after the
price box's HTML, so the price box's own block cache is untouched.

Not yet: the cart and checkout lines.

## License

OSL-3.0 or AFL-3.0, your choice: see `NOTICE.md`.

---

<a href="https://vilmate.com"><picture>
  <source media="(prefers-color-scheme: dark)" srcset="https://raw.githubusercontent.com/Fixstacks/zero/main/docs/images/vilmate-logo-dark.svg">
  <img src="https://raw.githubusercontent.com/Fixstacks/zero/main/docs/images/vilmate-logo.svg" alt="Vilmate" height="34" align="right">
</picture></a>

Zero is made by [Fixstacks](https://fixstacks.com/zero) in partnership with [Vilmate](https://vilmate.com), a software development company.
Documentation: https://fixstacks.com/zero/docs. Demo store: https://zero.fixstacks.com.
