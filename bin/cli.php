#!/usr/bin/env php
<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/cli_functions.php';

$vm = new Vending\CustomVendingMachine();

printHelp();

$stop = false;
while (!$stop) {
    echo "\n> ";
    $input = trim(fgets(STDIN));
    if (strtolower($input) === 'exit') {
        $stop = true;
    }
    if ($input === '') {
        continue;
    }
    $parsed = parseInput($input);
    processCommand($vm, $parsed['coins'], $parsed['command']);
}
