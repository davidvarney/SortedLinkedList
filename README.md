# SortedLinkedList

A tiny PHP library that provides a sorted singly-linked list which accepts either ints or strings (but not both). The list keeps values in sorted order as values are added.

## Installation

Install with Composer:

```bash
composer require davidvarney/sortedlinkedlist
```

For development (to run tests):

```bash
composer install
```

## Basic usage

```php
use DavidVarney\SortedLinkedList\SortedLinkedList;

$list = new SortedLinkedList();
$list->add(5);
$list->add(1);
$list->add(3);

// returns [1, 3, 5]
print_r($list->toArray());

// string join with default glue ", "
echo $list->toString(); // "1, 3, 5"

// type information
echo $list->getType(); // "int"

$list->clear();

$list->add('banana');
$list->add('apple');
echo $list->toString('|'); // "apple|banana"
```

Notes:
- The list enforces type consistency: once you add an int the list only accepts ints; same for strings.
- Attempting to add the other type throws an InvalidArgumentException.

## API

- `add(int|string $value): void` — Insert a value in sorted order.
- `remove(int|string $value): bool` — Remove a value; returns true if removed.
- `toArray(): array` — Return the list as an array of values.
- `toString(string $glue = ', '): string` — Return a joined string of values.
- `contains(int|string $value): bool` — Check presence of a value.
- `isEmpty(): bool` — Check if the list is empty.
- `clear(): void` — Clear the list and reset the stored type.
- `getType(): ?string` — Returns `'int'`, `'string'`, or `null` for empty list.

## Running tests

This project uses PHPUnit (configured in `phpunit.xml`). Run the tests with the vendored PHPUnit binary:

```bash
./vendor/bin/phpunit
```

For a more readable output use testdox:

```bash
./vendor/bin/phpunit --testdox
```

## Test coverage

This repository doesn't include a coverage tool by default, but you can generate coverage with Xdebug or PCOV installed and PHPUnit's `--coverage-html` option. Example (requires Xdebug enabled):

```bash
./vendor/bin/phpunit --coverage-html coverage

# open coverage/index.html in your browser
```

## CI / PHPStan

This repository runs PHPStan via GitHub Actions on `push` and `pull_request` to `main`.

To run PHPStan locally after installing dev dependencies:

```bash
./vendor/bin/phpstan analyse -c phpstan.neon
```

## License

MIT
