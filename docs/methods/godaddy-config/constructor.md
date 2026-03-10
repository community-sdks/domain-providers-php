
[Docs Home](../../README.md)

## Signature

```php
public function __construct(
    string $apiKey,
    string $apiSecret,
    string $customerId,
    ?string $shopperId = null,
    string $environment = 'production',
    ?string $marketId = null,
    ?array $onlyTlds = null,
    array $exceptTlds = [],
    int $priority = 100,
    array $priorityTlds = [],
)
```

## Purpose

Create GoDaddy provider config used by `GoDaddyProviderFactory::fromConfig()`.

## Parameters

- `apiKey`: GoDaddy API key
- `apiSecret`: GoDaddy API secret
- `customerId`: customer identifier used by v2 domain operations
- `shopperId`: optional shopper scope for supported endpoints
- `environment`: logical environment label used in metadata
- `marketId`: optional market ID for agreement lookups
- `onlyTlds`: optional allow-list for this provider (`null` means all)
- `exceptTlds`: deny-list of TLDs for this provider
- `priority`: default provider priority (lower number wins)
- `priorityTlds`: TLDs where this provider should be preferred first
