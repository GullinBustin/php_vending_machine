
# Vending Machine

## Using Docker

### Run the CLI (Docker)

To start the interactive CLI inside the Docker container:

```
docker compose run --rm app
```

### Run the Tests (Docker)

To execute all tests in an isolated environment inside the Docker container:

```
docker compose run --rm test
```

### Run static analysis (Docker)

To execute static analysis in an isolated environment inside the Docker container:

```
docker compose run --rm test composer lint
docker compose run --rm test composer analyse
```

---

## Using Locally (without Docker)

### Install Composer dependencies

If you haven't already, install Composer from https://getcomposer.org/.

Then, in the project root directory, run:

```
composer install
```

This will create the `vendor` directory and install PHPUnit and other dependencies.

### Run the CLI (Local)

You can run the vending machine CLI directly with PHP:

```
php bin/cli.php
```

### Run the Tests (Local)

To run all tests locally:

```
composer test
```

This will execute all test cases in the `tests` directory and show a readable output.

### Run static analysis (Local)

To run static analysis:

```
composer lint
composer analyse
```

## Console CLI Usage

The vending machine provides an interactive console (CLI) for simulating coin insertion and product selection.

### How to Use

At the prompt (`>`), enter a comma-separated list of coins and/or a command. Each line can include any number of coins and a single command.

**Supported commands:**
- `GET-<PRODUCT>`: Buy a product (e.g., `GET-SODA`, `GET-WATER`)
- `RETURN-COIN`: Return all inserted coins
- `SERVICE`: Enter service mode to set coin and product stock interactively
- `HELP`: Show help message
- `exit`: Quit the console

**Examples:**
```
1,0.25,0.25,GET-SODA    # Insert coins and buy SODA
0.1,RETURN-COIN         # Insert 0.1 and return it
1,GET-WATER             # Insert 1 and buy WATER
HELP                    # Show help
```

**Service Mode:**
When you enter `SERVICE`, you will be prompted to set the count for each allowed coin and the stock for each product interactively.

#### Accepted Coins and Initial Stock

The vending machine accepts the following coins (default initial stock: 10 units each):

| Coin Value | Initial Stock |
|------------|--------------|
| 1.00       | 10           |
| 0.25       | 10           |
| 0.10       | 10           |
| 0.05       | 10           |

### Available Products, Prices, and Initial Stock

The following products are available by default:

| Product | Price | Initial Stock |
|---------|-------|--------------|
| Water   | 0.65  | 10           |
| Juice   | 1.00  | 10           |
| Soda    | 1.50  | 10           |
