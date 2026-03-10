
[Docs Home](../../README.md)

## Signature

```php
public function preferProviderForTld(string $tld, string $providerKey): self;
```

## Purpose

Define explicit provider preference for a specific TLD before default priority sorting.

## Example

```php
$handler->preferProviderForTld('com', 'namecheap');
```
