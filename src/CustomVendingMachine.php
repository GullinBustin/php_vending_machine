<?php

declare(strict_types=1);

namespace Vending;

class CustomVendingMachine extends VendingMachine
{
    protected array $allowed_coins = [0.05, 0.1, 0.25, 1.0];
    protected array $products = [
        'Water' => 0.65,
        'Juice' => 1.0,
        'Soda' => 1.5
    ];
}
