# Vending Machine

## Running Tests

This project uses PHPUnit for testing. To set up the test dependencies and run the tests, follow these steps:

### 1. Install Composer dependencies

If you haven't already, install Composer from https://getcomposer.org/.

Then, in the project root directory, run:

```
composer install
```

This will create the `vendor` directory and install PHPUnit and other dependencies.

### 2. Run the tests

To run all tests, use:

```
./vendor/bin/phpunit --testdox tests
```

This will execute all test cases in the `tests` directory and show a readable output.
