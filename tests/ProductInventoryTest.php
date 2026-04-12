<?php

declare(strict_types=1);

namespace Vending\Tests;

use PHPUnit\Framework\TestCase;
use Vending\ProductInventory;

class ProductInventoryTest extends TestCase
{
    private ProductInventory $inventory;
    private array $products = [
        'Coke' => 1.5,
        'Pepsi' => 1.4,
        'Water' => 1.0,
    ];

    protected function setUp(): void
    {
        $this->inventory = new ProductInventory($this->products, 10);
    }

    public function testHasProduct(): void
    {
        $this->assertTrue($this->inventory->hasProduct('Coke'));
        $this->assertFalse($this->inventory->hasProduct('Sprite'));
    }

    public function testHasStockInitiallyTrue(): void
    {
        $this->assertTrue($this->inventory->hasStock('Pepsi'));
    }

    public function testHasStockAfterDecrement(): void
    {
        $this->inventory->decrementStock('Coke');
        $this->assertTrue($this->inventory->hasStock('Coke'));
        // Decrement 9 more times to exhaust stock
        for ($i = 0; $i < 9; $i++) {
            $this->inventory->decrementStock('Coke');
        }
        $this->assertFalse($this->inventory->hasStock('Coke'));
    }

    public function testGetPrice(): void
    {
        $this->assertEquals(1.5, $this->inventory->getPrice('Coke'));
        $this->assertEquals(1.0, $this->inventory->getPrice('Water'));
    }

    public function testGetPriceThrowsOnInvalidProduct(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->inventory->getPrice('Sprite');
    }

    public function testDecrementStockThrowsOnInvalidProduct(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->inventory->decrementStock('Sprite');
    }

    public function testDecrementStockThrowsWhenNoStock(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $this->inventory->decrementStock('Pepsi');
        }
        $this->expectException(\RuntimeException::class);
        $this->inventory->decrementStock('Pepsi');
    }

    public function testSetStock(): void
    {
        $this->inventory->setStock('Water', 5);
        for ($i = 0; $i < 5; $i++) {
            $this->assertTrue($this->inventory->hasStock('Water'));
            $this->inventory->decrementStock('Water');
        }
        $this->assertFalse($this->inventory->hasStock('Water'));
    }

    public function testSetStockThrowsOnInvalidProduct(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->inventory->setStock('Sprite', 3);
    }
}
