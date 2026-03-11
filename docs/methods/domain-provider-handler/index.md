[Docs Home](../../README.md)

## `List Provider Tlds`

### Signature

```php
public function listProviderTlds(string $providerKey): array;
```

### Purpose

Return supported TLDs for a provider when it implements `TldDiscoveryInterface`.

### Notes

- For GoDaddy, this delegates to `/v1/domains/tlds` through `GoDaddyProvider::listSupportedTlds()`.
- Returns an empty list when provider does not expose TLD discovery.

## `Prefer Provider For Tld`

### Signature

```php
public function preferProviderForTld(string $tld, string $providerKey): self;
```

### Purpose

Define explicit provider preference for a specific TLD before default priority sorting.

### Example

```php
$handler->preferProviderForTld('com', 'namecheap');
```

## `Register Provider`

### Signature

```php
public function registerProvider(
    string $key,
    DomainProviderInterface $provider,
    ?ProviderConfig $config = null
): self;
```

### Purpose

Register a provider instance and optional routing config in the global handler.
