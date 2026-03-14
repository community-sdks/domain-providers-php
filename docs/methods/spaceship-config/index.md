[Docs Home](../../README.md)

## `__construct`

### Signature

```php
public function __construct(
	string $apiKey,
	string $apiSecret,
	string $environment = 'production',
	?array $onlyTlds = null,
	array $exceptTlds = [],
	int $priority = 100,
	array $priorityTlds = [],
)
```

### Purpose

Create Spaceship provider config used by `SpaceshipProviderFactory::fromConfig()`.

### Parameters

- `apiKey`: Spaceship API key
- `apiSecret`: Spaceship API secret
- `environment`: `sandbox` or `production`
- `onlyTlds`: optional allow-list for this provider (`null` means all)
- `exceptTlds`: deny-list of TLDs for this provider
- `priority`: default provider priority (lower number wins)
- `priorityTlds`: TLDs where this provider should be preferred first

### Example

```php
$config = new SpaceshipConfig(
	apiKey: 'your-key',
	apiSecret: 'your-secret',
	environment: 'sandbox',
	onlyTlds: ['com', 'net'],
	exceptTlds: ['io'],
	priority: 10,
);
```