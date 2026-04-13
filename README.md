
# Vending Machine

## Using Docker

### Build the Docker image

In the project root, run:

```
docker build -t php-vending-machine .
```

This will create the image with all required dependencies.

### Run the CLI (Docker)

To start the interactive CLI inside the Docker container:

```
docker run --rm -it php-vending-machine
```

### Run the Tests (Docker)

To execute all tests in an isolated environment inside the Docker container:

```
docker run --rm php-vending-machine ./vendor/bin/phpunit --testdox tests
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
./vendor/bin/phpunit --testdox tests
```

This will execute all test cases in the `tests` directory and show a readable output.

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
