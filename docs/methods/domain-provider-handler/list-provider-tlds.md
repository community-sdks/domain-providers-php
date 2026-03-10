
[Docs Home](../../README.md)

## Signature

```php
public function listProviderTlds(string $providerKey): array;
```

## Purpose

Return supported TLDs for a provider when it implements `TldDiscoveryInterface`.

## Notes

- For GoDaddy, this delegates to `/v1/domains/tlds` through `GoDaddyProvider::listSupportedTlds()`.
- Returns an empty list when provider does not expose TLD discovery.
