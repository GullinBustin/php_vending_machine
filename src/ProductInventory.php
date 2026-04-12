<?php

declare(strict_types=1);

namespace Vending;

class ProductInventory
{
    /**
     * @var string[] List of product names (index-matched to prices and counts)
     */
    private array $productNames;

    /**
     * @var float[] List of product prices
     */
    private array $productPrices;

    /**
     * @var int[] List of product stock counts
     */
    private array $productCounts;

    /**
     * @param array<string, float> $products Associative array of product name => price
     * @param int $initialStock Initial stock count for each product
     */
    public function __construct(array $products, int $initialStock = 10)
    {
        $this->productNames = array_keys($products);
        $this->productPrices = array_values($products);
        $this->productCounts = array_fill(0, count($products), $initialStock);
    }

    /**
     * Check if a product exists in the inventory
     */
    public function hasProduct(string $productName): bool
    {
        return in_array($productName, $this->productNames, true);
    }

    /**
     * Check if a product has stock available
     *
     * @param string $productName
     * @return bool
     */
    public function hasStock(string $productName): bool
    {
        $index = array_search($productName, $this->productNames, true);
        if ($index === false) {
            return false;
        }
        return $this->productCounts[$index] > 0;
    }

    /**
     * Get the price of a product
     *
     * @param string $productName
     * @return float
     * @throws \InvalidArgumentException if product does not exist
     */
    public function getPrice(string $productName): float
    {
        $index = array_search($productName, $this->productNames, true);
        if ($index === false) {
            throw new \InvalidArgumentException("Product '$productName' does not exist.");
        }
        return $this->productPrices[$index];
    }

    /**
     * Decrement the stock of a product by 1
     *
     * @param string $productName
     * @throws \InvalidArgumentException if product does not exist
     * @throws \RuntimeException if no stock left for the product
     */
    public function decrementStock(string $productName): void
    {
        $index = array_search($productName, $this->productNames, true);
        if ($index === false) {
            throw new \InvalidArgumentException("Product '$productName' does not exist.");
        }
        if ($this->productCounts[$index] <= 0) {
            throw new \RuntimeException("No stock left for product '$productName'.");
        }
        $this->productCounts[$index]--;
    }

    /**
     * Set the stock of a product to n
     *
     * @param string $productName
     * @param int $n
     * @throws \InvalidArgumentException if product does not exist
     */
    public function setStock(string $productName, int $n): void
    {
        $index = array_search($productName, $this->productNames, true);
        if ($index === false) {
            throw new \InvalidArgumentException("Product '$productName' does not exist.");
        }
        $this->productCounts[$index] = $n;
    }
}
