# community-sdks/domain-providers-php

PHP implementation of the [`contracts`](https://github.com/community-sdks/contracts/tree/main/domain-providers) specification.

This package provides:

- contract-aligned DTOs and operation interfaces
- capability-aware provider abstraction
- provider adapters for Spaceship and GoDaddy

## Install

```bash
composer require community-sdks/domain-providers-php
```

Spaceship and GoDaddy support are included out of the box.

## Quick start (Spaceship)

```php
<?php

declare(strict_types=1);

use DomainProviders\DTO\DnsRecord;
use DomainProviders\DTO\DomainContact;
use DomainProviders\DTO\DomainName;
use DomainProviders\DTO\DomainRegistrationPeriod;
use DomainProviders\DTO\NameserverSet;
use DomainProviders\Provider\Spaceship\SpaceshipConfig;
use DomainProviders\Provider\Spaceship\SpaceshipProviderFactory;

$config = new SpaceshipConfig(
    apiKey: 'your-key',
    apiSecret: 'your-secret',
    environment: 'sandbox', // or 'production'
    onlyTlds: ['com', 'net'],
    exceptTlds: ['io'],
    priority: 10,
);

$provider = SpaceshipProviderFactory::fromConfig($config);

$availability = $provider->checkAvailability(new DomainName('example.com'));

if ($availability->available) {
    $registrant = new DomainContact(
        fullName: 'Jane Doe',
        email: 'jane@example.com',
        phone: '+12025550123',
        addressLine1: '123 Main Street',
        addressLine2: null,
        city: 'Phoenix',
        stateOrRegion: 'AZ',
        postalCode: '85001',
        countryCode: 'US',
        organization: null,
    );

    $provider->registerDomain(
        domain: new DomainName('example.com'),
        period: new DomainRegistrationPeriod(1),
        registrantContact: $registrant,
        nameservers: new NameserverSet(['ns1.example.com', 'ns2.example.com']),
        privacyEnabled: true,
    );
}

$records = $provider->listDnsRecords(new DomainName('example.com'));

$provider->createDnsRecord(
    new DomainName('example.com'),
    new DnsRecord(
        id: null,
        type: 'A',
        name: '@',
        value: '203.0.113.10',
        ttl: 3600,
    ),
);
```

## Quick start (GoDaddy)

```php
<?php

declare(strict_types=1);

use DomainProviders\DTO\DomainName;
use DomainProviders\DTO\DomainRegistrationPeriod;
use DomainProviders\DTO\ProviderRequestContext;
use DomainProviders\Provider\GoDaddy\GoDaddyConfig;
use DomainProviders\Provider\GoDaddy\GoDaddyProviderFactory;

$config = new GoDaddyConfig(
    apiKey: 'your-key',
    apiSecret: 'your-secret',
    // Use reseller mode with customerId for /v2/customers/* endpoints,
    // or direct mode without customerId for /v1/domains/* endpoints.
    accountMode: GoDaddyConfig::ACCOUNT_MODE_DIRECT,
    customerId: null,
    // Routing fields shared by all ProviderConfig implementations:
    onlyTlds: null,
    exceptTlds: ['rs', 'co.rs', 'in.rs'],
    priority: 20,
    priorityTlds: ['net'],
);

$provider = GoDaddyProviderFactory::fromConfig($config);

$availability = $provider->checkAvailability(new DomainName('example.com'));

if ($availability->available) {
    $result = $provider->renewDomain(
        new DomainName('example.com'),
        new DomainRegistrationPeriod(1)
    );
}

// Request-scoped values like shopper and market are passed where needed:
$domains = $provider->listDomains(status: 'active', shopperId: 'optional-shopper-id');

// Or pass provider-agnostic context scopes (recommended for multi-provider use cases):
$context = new ProviderRequestContext(scopes: ['shopper_id' => 'optional-shopper-id']);
$domainsFromContext = $provider->listDomains(status: 'active', context: $context);
```

## Provider-agnostic routing handler

Use `DomainProviderHandler` to register multiple providers and route by TLD rules.

```php
<?php

declare(strict_types=1);

use DomainProviders\DTO\DomainName;
use DomainProviders\Handler\DomainProviderHandler;
use DomainProviders\Provider\GoDaddy\GoDaddyConfig;
use DomainProviders\Provider\GoDaddy\GoDaddyProviderFactory;
use DomainProviders\Provider\Spaceship\SpaceshipConfig;
use DomainProviders\Provider\Spaceship\SpaceshipProviderFactory;

$spaceshipConfig = new SpaceshipConfig(
    apiKey: 'sp-key',
    apiSecret: 'sp-secret',
    environment: 'sandbox',
    onlyTlds: ['rs', 'co.rs', 'in.rs'],
    priority: 10,
    priorityTlds: ['com'],
);

$godaddyConfig = new GoDaddyConfig(
    apiKey: 'gd-key',
    apiSecret: 'gd-secret',
    customerId: 'gd-customer',
    accountMode: GoDaddyConfig::ACCOUNT_MODE_RESELLER,
    exceptTlds: ['rs', 'co.rs', 'in.rs'],
    priority: 20,
);

$handler = (new DomainProviderHandler())
    ->registerProvider('spaceship', SpaceshipProviderFactory::fromConfig($spaceshipConfig), $spaceshipConfig)
    ->registerProvider('godaddy', GoDaddyProviderFactory::fromConfig($godaddyConfig), $godaddyConfig)
    ->preferProviderForTld('com', 'spaceship');

$availability = $handler->checkAvailability(new DomainName('example.co.rs')); // routed to spaceship
$comAvailability = $handler->checkAvailability(new DomainName('example.com')); // prefers spaceship

// If provider supports TLD discovery, you can inspect its live TLD list.
$spaceshipTlds = $handler->listProviderTlds('spaceship');
```

Routing decision order for domain-based operations:

- explicit `preferProviderForTld()` match
- `onlyTlds` and `exceptTlds` filtering
- `priorityTlds` match
- numeric `priority` (lower number = higher priority)

## Contract coverage

This package includes contract methods for:

- check availability
- register domain
- renew domain
- transfer domain
- get domain info
- list domains
- get/set nameservers
- list/create/update/delete DNS records
- get pricing
- check transfer availability
- provider metadata and capabilities

Unsupported provider operations are reported through `UnsupportedCapabilityException`.

## Method docs

Detailed method-by-method documentation is available in:

- [`docs/README.md`](docs/README.md)
- [`docs/methods/domain-provider/index.md`](docs/methods/domain-provider/index.md)
- [`docs/methods/provider-registry/index.md`](docs/methods/provider-registry/index.md)
- [`docs/methods/godaddy-provider-factory/index.md`](docs/methods/godaddy-provider-factory/index.md)
- [`docs/methods/godaddy-config/index.md`](docs/methods/godaddy-config/index.md)
- [`docs/methods/godaddy-domains-api-interface/index.md`](docs/methods/godaddy-domains-api-interface/index.md)

## Custom providers outside this package

You can register custom providers (class, instance, or factory) using `ProviderRegistry`.

```php
<?php

use DomainProviders\Registry\ProviderRegistry;
use Vendor\Custom\MyProvider;

$registry = new ProviderRegistry();

$registry->registerClass('my-provider', MyProvider::class);
// or registerInstance('my-provider', new MyProvider())
// or registerFactory('my-provider', fn() => new MyProvider($deps))

$provider = $registry->get('my-provider');
```

## Provider notes (GoDaddy)

- Supports both GoDaddy reseller account endpoints (`/v2/customers/*`) and direct account endpoints (`/v1/domains/*`) via `GoDaddyConfig::accountMode`.
- In reseller mode, `customerId` is required. In direct mode, `customerId` can be `null`.
- The `community-sdks/godaddy-php` client currently exposes DNS retrieval by `{type}/{name}` path, not a full zone list endpoint in this adapter.
- Because of that, `dns_record_list` is declared unsupported for now, while create/update/delete DNS operations are supported.

## Provider notes (Spaceship)

- Uses `SpaceshipConfig::environment` with `sandbox` or `production` to select the API base environment.
- Supports domain availability, registration, renewal, transfer, domain info, nameserver read/update, and DNS record list/create/update/delete.
- `pricing_lookup` is currently declared unsupported in this adapter.
