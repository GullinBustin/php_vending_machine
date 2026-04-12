<?php

namespace Vending;

class VendingMachine
{
    /**
     * @var float[] List of allowed coin values
     */
    protected array $allowed_coins;

    /**
     * @var CoinInventory Inventory of coins available for change
     */
    protected CoinInventory $coin_inventory;

    /**
     * @var array<string, float> Dictionary of product names to Product objects
     */
    protected array $products;

    /**
     * @var ProductInventory Inventory of products available for purchase
     */
    protected ProductInventory $product_inventory;

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
        $this->coin_inventory = new CoinInventory($this->allowed_coins);
        $this->product_inventory = new ProductInventory($this->products);
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
        if (!$this->coin_inventory->isAllowed($coin)) {
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
        $remaining = round($amount, 2);
        $coins = $this->coin_inventory->getCoinValues();
        foreach ($coins as $coin) {
            while ($remaining >= $coin && $this->coin_inventory->hasCoin($coin)) {
                $change[] = $coin;
                $remaining -= $coin;
                $remaining = round($remaining, 2); // Avoid floating point issues
                $this->coin_inventory->removeCoin($coin); // Decrease coin count
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
        if (!$this->product_inventory->hasProduct($product)) {
            throw new \InvalidArgumentException("Product '$product' does not exist.");
        }
        if ($this->totalInsertedMoney() < $this->product_inventory->getPrice($product)) {
            throw new \InvalidArgumentException("Insufficient funds to buy '$product'.");
        }
        if (!$this->product_inventory->hasStock($product)) {
            throw new \InvalidArgumentException("Product '$product' is out of stock.");
        }
        $change_amount = $this->totalInsertedMoney() - $this->product_inventory->getPrice($product);
        $this->coin_inventory->addInsertedCoins($this->inserted_coins); // Add inserted coins to inventory
        $this->inserted_coins = []; // Clear inserted coins after purchase
        $this->product_inventory->decrementStock($product); // Decrease product count
        return $this->returnChange($change_amount);
    }
}
