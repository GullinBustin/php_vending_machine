<?php

declare(strict_types=1);

namespace Vending\Tests;

use PHPUnit\Framework\TestCase;
use Vending\CoinInventory;

class CoinInventoryTest extends TestCase
{
    private CoinInventory $inventory;
    private array $allowedCoins = [0.1, 0.25, 0.5, 1.0];

    protected function setUp(): void
    {
        // Use initialStock=0 for precise test control
        $this->inventory = new CoinInventory($this->allowedCoins, 0);
    }

    public function testIsAllowed(): void
    {
        $this->assertTrue($this->inventory->isAllowed(0.25));
        $this->assertFalse($this->inventory->isAllowed(2.0));
    }

    public function testAddAndHasCoin(): void
    {
        $this->assertFalse($this->inventory->hasCoin(0.5));
        $this->inventory->addCoin(0.5);
        $this->assertTrue($this->inventory->hasCoin(0.5));
    }

    public function testRemoveCoin(): void
    {
        $this->inventory->addCoin(1.0);
        $this->assertTrue($this->inventory->hasCoin(1.0));
        $this->inventory->removeCoin(1.0);
        $this->assertFalse($this->inventory->hasCoin(1.0));
    }

    public function testRemoveCoinThrowsIfNone(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->inventory->removeCoin(0.25);
    }

    public function testAddInsertedCoins(): void
    {
        $inserted = [0.1, 0.25, 1.0, 0.25];
        $this->inventory->addInsertedCoins($inserted);
        $this->assertTrue($this->inventory->hasCoin(0.1));
        $this->assertTrue($this->inventory->hasCoin(0.25));
        $this->assertTrue($this->inventory->hasCoin(1.0));
        $this->assertFalse($this->inventory->hasCoin(0.5));
    }

    public function testAddCoinNotAllowedThrows(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->inventory->addCoin(2.0);
    }
}
