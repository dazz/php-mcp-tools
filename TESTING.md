# Testing Guidelines

## PHPUnit Tests

### Test Method Naming Convention

All test methods must follow the `snake_case` convention and be descriptive about what they are testing:

```php
public function test_some_functionality_works(): void
{
    // Test code here
}
```

Or when using PHPUnit attributes:

```php
#[Test]
public function some_functionality_works(): void
{
    // Test code here
}
```

### Test Organization

1. Group tests by the class they are testing
2. Organize test methods in a logical order:
   - Happy path tests first
   - Edge cases second
   - Error cases last

### Test Coverage

- All new code should have corresponding tests
- Run `make coverage` to generate a coverage report

## Running Tests

```bash
# Run all tests
make test

# Run tests with coverage report
make coverage
```

## Code Style for Tests

Tests follow the same code style as the main codebase, with the exception of method naming. 
The code style is automatically enforced using PHP-CS-Fixer with custom rules for test methods.

To check and fix code style:

```bash
# Check code style
make cs

# Fix code style automatically
make csfix
```