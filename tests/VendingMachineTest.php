<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Vending\CustomVendingMachine;

class VendingMachineTest extends TestCase
{
    public function testInsertAllowedCoin()
    {
        $vm = new CustomVendingMachine();
        $vm->insertCoin(0.1);
        $this->assertEquals(0.1, $vm->totalInsertedMoney());
    }

    public function testInsertNotAllowedCoinThrows()
    {
        $vm = new CustomVendingMachine();
        $this->expectException(InvalidArgumentException::class);
        $vm->insertCoin(0.2);
    }

     public function testReturnCoinsAfterInsert()
    {
        $vm = new CustomVendingMachine();
        $vm->insertCoin(0.1);
        $vm->insertCoin(0.25);
        $this->assertEquals([0.1, 0.25], $vm->returnCoins());
    }

    public function testReturnCoinsWhenEmpty()
    {
        $vm = new CustomVendingMachine();
        $this->assertEquals([], $vm->returnCoins());
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('returnChangeProvider')]
    public function testReturnChange(float $amount, array $expected): void
    {
        $vm = new CustomVendingMachine();
        $this->assertEquals($expected, $vm->returnChange($amount));
    }

    public static function returnChangeProvider(): array
    {
        return [
            'exact match' => [1.0, [1.0]],
            'multiple coins' => [1.35, [1.0, 0.25, 0.1]],
            'smallest coin' => [0.05, [0.05]],
            'no change' => [0.0, []],
            'complex' => [1.4, [1.0, 0.25, 0.1, 0.05]],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('buyProductSuccessProvider')]
    public function testBuyProductSuccess(array $coins, string $product): void
    {
        $vm = new CustomVendingMachine();
        foreach ($coins as $coin) {
            $vm->insertCoin($coin);
        }
        $vm->buyProduct($product);
        $this->assertTrue(true); // If no exception, test passes
    }

    public static function buyProductSuccessProvider(): array
    {
        return [
            'enough for water' => [[1.0], 'Water'],
            'exact for soda' => [[1.0, 1.0], 'Soda'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('buyProductInsufficientFundsProvider')]
    public function testBuyProductInsufficientFunds(array $coins, string $product): void
    {
        $vm = new CustomVendingMachine();
        foreach ($coins as $coin) {
            $vm->insertCoin($coin);
        }
        $this->expectException(InvalidArgumentException::class);
        $vm->buyProduct($product);
    }

    public static function buyProductInsufficientFundsProvider(): array
    {
        return [
            'not enough for juice' => [[0.25, 0.25], 'Juice'],
            'not enough for soda' => [[1.0], 'Soda'],
        ];
    }

    public function testBuyProductNonexistentProduct(): void
    {
        $vm = new CustomVendingMachine();
        $vm->insertCoin(1.0);
        $this->expectException(InvalidArgumentException::class);
        $vm->buyProduct('Tea');
    }

}
