
# Vending Machine

## 1. Using Docker

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

## 2. Using Locally (without Docker)

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
