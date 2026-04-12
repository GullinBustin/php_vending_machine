<?php

namespace Vending;

class VendingMachine
{
    /**
     * @var float[] List of allowed coin values
     */
    protected array $allowed_coins;

    /**
     * @var array<string, float> Dictionary of product names to prices
     */
    protected array $products;

    /**
     * @var float[] List of inserted coin amounts
     */
    private array $inserted_coins;

    /**
     * Get the total money inserted (read-only)
     */
    public function totalInsertedMoney(): float
    {
        return array_sum($this->inserted_coins);
    }

    public function __construct()
    {
        $this->inserted_coins = [];
    }

    /**
     * Insert a coin if it is allowed, else throw exception
     *
     * @param float $coin
     * @throws \InvalidArgumentException
     */
    public function insertCoin(float $coin): void
    {
        if (!in_array($coin, $this->allowed_coins, true)) {
            throw new \InvalidArgumentException("Coin value $coin is not allowed.");
        }
        $this->inserted_coins[] = $coin;
    }

    /**
     * Return all inserted coins
     *
     * @return float[]
     */
    public function returnCoins(): array
    {
        return $this->inserted_coins;
    }

    /**
     * Return the inserted value as change using allowed coins, largest first.
     * @param float $amount
     * @return float[]
     */
    public function returnChange(float $amount): array
    {
        $change = [];
        $remaining = $amount;
        $coins = $this->allowed_coins;
        rsort($coins, SORT_NUMERIC); // Sort coins descending
        foreach ($coins as $coin) {
            while ($remaining >= $coin) { // Floating point tolerance
                $change[] = $coin;
                $remaining -= $coin;
                $remaining = round($remaining, 2); // Avoid floating point issues
            }
        }
        return $change;
    }

    /**
     * Attempt to buy a product by name.
     *
     * @param string $product
     * @return float[] List of coins returned as change
     * @throws \InvalidArgumentException if product does not exist or insufficient funds
     */
    public function buyProduct(string $product): array
    {
        if (!array_key_exists($product, $this->products)) {
            throw new \InvalidArgumentException("Product '$product' does not exist.");
        }
        $price = $this->products[$product];
        if ($this->totalInsertedMoney() < $price) {
            throw new \InvalidArgumentException("Insufficient funds to buy '$product'.");
        }
        $change_amount = $this->totalInsertedMoney() - $price;
        $this->inserted_coins = []; // Clear inserted coins after purchase
        return $this->returnChange($change_amount);
    }
}
