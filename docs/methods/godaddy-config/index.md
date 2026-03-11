
[Docs Home](../../README.md)

## `__construct`

### Signature

```php
public function __construct(
	string $apiKey,
	string $apiSecret,
	string $customerId,
	string $environment = 'production',
	?array $onlyTlds = null,
	array $exceptTlds = [],
	int $priority = 100,
	array $priorityTlds = [],
)
```

### Purpose

Create GoDaddy provider config used by `GoDaddyProviderFactory::fromConfig()`.

### Parameters

- `apiKey`: GoDaddy API key
- `apiSecret`: GoDaddy API secret
- `customerId`: customer identifier used by v2 domain operations
- `environment`: logical environment label used in metadata
- `onlyTlds`: optional allow-list for this provider (`null` means all)
- `exceptTlds`: deny-list of TLDs for this provider
- `priority`: default provider priority (lower number wins)
- `priorityTlds`: TLDs where this provider should be preferred first

## Shopper Setup (GoDaddy)

`domain-providers-php` does not create GoDaddy shoppers directly. Create shopper/subaccount via `community-sdks/godaddy-php`, then pass shopper scope through provider method params or `ProviderRequestContext`.

### Create Shopper Example

```php
<?php

use CommunitySDKs\GoDaddy\Client as GoDaddyClient;
use CommunitySDKs\GoDaddy\Config as GoDaddySdkConfig;
use CommunitySDKs\GoDaddy\Dto\Shoppers\Request\CreateSubaccountRequest;

$gd = new GoDaddyClient(new GoDaddySdkConfig(
	apiKey: 'your-key',
	apiSecret: 'your-secret',
));

$shopper = $gd->shoppers()->createSubaccount(new CreateSubaccountRequest(
	email: 'admin@example.com',
	password: 'StrongPassword123!',
	nameFirst: 'John',
	nameLast: 'Doe',
));

$shopperId = $shopper->shopperId;
```

### Use Shopper With ProviderRequestContext

```php
<?php

use DomainProviders\DTO\ProviderRequestContext;

$context = new ProviderRequestContext(scopes: [
	'shopper_id' => $shopperId,
	'market_id' => 'en-US',
]);

$domains = $provider->listDomains(status: 'active', context: $context);
```
