<?php

declare(strict_types=1);

namespace Vending;

class CoinInventory
{
    /**
     * @var float[] Sorted list of coin values (descending)
     */
    private array $coinValues;

    /**
     * @var int[] List of coin counts, index-matched to $coinValues
     */
    private array $coinCounts;

    /**
     * @param float[] $coinValues
     * @param int $initialStock Initial stock count for each coin
     */
    public function __construct(array $coinValues, int $initialStock = 10)
    {
        rsort($coinValues, SORT_NUMERIC);
        $this->coinValues = $coinValues;
        $this->coinCounts = array_fill(0, count($coinValues), $initialStock);
    }

    /**
     * Check if a coin value is allowed (exists in the inventory)
     *
     * @param float $coin
     * @return bool
     */
    public function isAllowed(float $coin): bool
    {
        return in_array($coin, $this->coinValues, true);
    }

    /**
     * Add a coin to the inventory by value
     *
     * @param float $coin
     * @return void
     */
    public function addCoin(float $coin): void
    {
        $index = array_search($coin, $this->coinValues, true);
        if ($index === false) {
            throw new \InvalidArgumentException("Coin value $coin is not supported.");
        }
        $this->coinCounts[$index]++;
    }

    /**
     * Add each coin from an array of floats to the inventory
     *
     * @param float[] $coins
     * @return void
     */
    public function addInsertedCoins(array $coins): void
    {
        foreach ($coins as $coin) {
            $this->addCoin($coin);
        }
    }

    /**
     * Remove a coin from the inventory by value
     *
     * @param float $coin
     * @return void
     */
    public function removeCoin(float $coin): void
    {
        $index = array_search($coin, $this->coinValues, true);
        if ($index === false) {
            throw new \InvalidArgumentException("Coin value $coin is not supported.");
        }
        if ($this->coinCounts[$index] <= 0) {
            throw new \RuntimeException("No coins of value $coin left to remove.");
        }
        $this->coinCounts[$index]--;
    }

    /**
     * Check if there is at least one coin of the given value available
     *
     * @param float $coin
     * @return bool
     */
    public function hasCoin(float $coin): bool
    {
        $index = array_search($coin, $this->coinValues, true);
        if ($index === false) {
            return false;
        }
        return $this->coinCounts[$index] > 0;
    }

    /**
     * Set the stock of a coin to n
     *
     * @param float $coin
     * @param int $n
     * @throws \InvalidArgumentException if coin does not exist
     */
    public function setCoinsCount(float $coin, int $n): void
    {
        $index = array_search($coin, $this->coinValues, true);
        if ($index === false) {
            throw new \InvalidArgumentException("Coin value $coin is not supported.");
        }
        $this->coinCounts[$index] = $n;
    }

    /**
     * Get the list of coin values in the inventory
     *
     * @return float[]
     */
    public function getCoinValues(): array
    {
        return $this->coinValues;
    }
}
