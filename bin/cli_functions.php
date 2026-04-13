<?php

declare(strict_types=1);

use Vending\CustomVendingMachine;

/**
 * Print the help message for the CLI.
 *
 * @return void
 */
function printHelp(): void
{
    echo "Vending Machine CLI (interactive mode)\n";
    echo "Each line: <coin1,coin2,...,COMMAND>\n";
    echo "  Insert coins and/or a command, separated by commas.\n";
    echo "  COMMAND can be: GET-X, RETURN-COIN, SERVICE\n";
    echo "Examples:\n";
    echo "  1,0.25,0.25,GET-SODA\n";
    echo "  0.1,RETURN-COIN\n";
    echo "  1,GET-WATER\n";
    echo "Type 'exit' to quit.\n";
}

/**
 * Parse a line of input into coins and a command.
 *
 * @param string $input
 * @return array{coins: float[], command: string|null}
 */
function parseInput(string $input): array
{
    $parts = array_map('trim', explode(',', $input));
    $command = null;
    $coins = [];
    foreach ($parts as $part) {
        if (preg_match('/^(GET-.+|RETURN-COIN|SERVICE|HELP)$/i', $part)) {
            $command = strtoupper($part);
        } elseif (is_numeric($part)) {
            $coins[] = (float)$part;
        } elseif ($part !== '') {
            echo "Unknown input: $part\n";
        }
    }
    return ['coins' => $coins, 'command' => $command];
}

/**
 * Format an array of coins into a string for output.
 *
 * @param float[] $coins
 * @return string
 */
function parseCoints(array $coins): string
{
    $formatted = array_map(fn($c) => number_format($c, 2), $coins);
    return implode(', ', $formatted);
}

/**
 * Handle the RETURN-COIN command.
 *
 * @param CustomVendingMachine $vm
 * @return void
 */
function handleReturnCoin(CustomVendingMachine $vm): void
{
    $returned = $vm->returnCoins();
    echo parseCoints($returned) . "\n";
}

/**
 * Handle the GET-X command.
 *
 * @param CustomVendingMachine $vm
 * @param string $command
 * @return void
 */
function handleGetProduct(CustomVendingMachine $vm, string $command): void
{
    $product = substr($command, 4);
    $change = $vm->buyProduct(ucfirst(strtolower($product)));
    echo "$product";
    if (count($change) > 0) {
        echo ", " . parseCoints($change) . "\n";
    }
}

/**
 * Prompt the user to set the count for each allowed coin.
 *
 * @param CustomVendingMachine $vm
 * @param object $coinInventory
 * @return void
 */
function handleServiceSetCoins(CustomVendingMachine $vm, $coinInventory): void
{
    $allowedCoins = $coinInventory->getCoinValues();
    foreach ($allowedCoins as $coin) {
        while (true) {
            echo "Set count for coin $coin: ";
            $input = trim(fgets(STDIN));
            if (is_numeric($input) && (int)$input >= 0) {
                $vm->setCoinCount((float)$coin, (int)$input);
                break;
            } else {
                echo "Please enter a non-negative integer.\n";
            }
        }
    }
}

/**
 * Prompt the user to set the stock for each product.
 *
 * @param CustomVendingMachine $vm
 * @param array<string, float> $productNames
 * @return void
 */
function handleServiceSetProducts(CustomVendingMachine $vm, array $productNames): void
{
    foreach (array_keys($productNames) as $product) {
        while (true) {
            echo "Set stock for product $product: ";
            $input = trim(fgets(STDIN));
            if (is_numeric($input) && (int)$input >= 0) {
                $vm->setProductStock($product, (int)$input);
                break;
            } else {
                echo "Please enter a non-negative integer.\n";
            }
        }
    }
}

/**
 * Handle the SERVICE command: ask user to set coin counts and product stocks interactively.
 *
 * @param CustomVendingMachine $vm
 * @return void
 */
function handleService(CustomVendingMachine $vm): void
{
    echo "Entering service mode. Set coin counts and product stocks.\n";
    // Access protected properties via reflection
    $reflection = new ReflectionObject($vm);
    $coinInventoryProp = $reflection->getProperty('coin_inventory');
    $coinInventoryProp->setAccessible(true);
    $coinInventory = $coinInventoryProp->getValue($vm);
    handleServiceSetCoins($vm, $coinInventory);
    $productsProp = $reflection->getProperty('products');
    $productsProp->setAccessible(true);
    $productNames = $productsProp->getValue($vm);
    handleServiceSetProducts($vm, $productNames);
    echo "Service mode complete.\n";
}

/**
 * Process the coins and command for the vending machine.
 *
 * @param CustomVendingMachine $vm
 * @param float[] $coins
 * @param string|null $command
 * @return void
 */
function processCommand(CustomVendingMachine $vm, array $coins, ?string $command): void
{
    try {
        foreach ($coins as $coin) {
            $vm->insertCoin($coin);
        }
        if ($command) {
            switch (true) {
                case $command === 'RETURN-COIN':
                    handleReturnCoin($vm);
                    break;
                case str_starts_with($command, 'GET-'):
                    handleGetProduct($vm, $command);
                    break;
                case $command === 'SERVICE':
                    handleService($vm);
                    break;
                case $command === 'HELP':
                    printHelp();
                    break;
                default:
                    echo "Unknown command. Type 'help' for options.\n";
            }
        } elseif (count($coins) > 0) {
            echo parseCoints($coins) . "\n";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
