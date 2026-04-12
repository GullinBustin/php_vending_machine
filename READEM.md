# Vending Machine

## Build Docker image

In the project root, run:

```
docker build -t php-vending-machine .
```

This will create the image with all required dependencies.

## Running Tests

This project uses PHPUnit for testing. To set up the test dependencies and run the tests, follow these steps:

### Run the tests in Local

#### 1. Install Composer dependencies

If you haven't already, install Composer from https://getcomposer.org/.

Then, in the project root directory, run:

```
composer install
```

This will create the `vendor` directory and install PHPUnit and other dependencies.

#### 2. Run the tests

To run all tests, use:

```
./vendor/bin/phpunit --testdox tests
```

This will execute all test cases in the `tests` directory and show a readable output.

### Run the tests in Docker

Once the image is built, run the tests with:

```
docker run --rm php-vending-machine ./vendor/bin/phpunit --testdox tests
```

This will execute all tests in an isolated environment inside the Docker container.
