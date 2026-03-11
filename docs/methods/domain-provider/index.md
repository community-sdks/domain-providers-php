[Docs Home](../../README.md)

## `Check Availability`

### Signature

```php
public function checkAvailability(DomainName $domain): AvailabilityResult;
```

### Purpose

Check domain registration availability and optional pricing hints.

### Parameters

- `domain`: `DomainName`

### Return

- `AvailabilityResult`

### Usage

```php
$result = $provider->checkAvailability(new DomainName('example.com'));
if ($result->available) {
    // proceed
}
```

## `Check Transfer Availability`

### Signature

```php
public function checkTransferAvailability(DomainName $domain): TransferAvailabilityResult;
```

### Purpose

Check whether a domain appears ready for transfer initiation.

### Parameters

- `domain`: target `DomainName`

### Return

- `TransferAvailabilityResult`

## `Create Dns Record`

### Signature

```php
public function createDnsRecord(DomainName $domain, DnsRecord $record, ?string $shopperId = null, ?ProviderRequestContext $context = null): DnsRecordCreateResult;
```

### Purpose

Create a DNS record in a domain zone.

### Parameters

- `domain`: target `DomainName`
- `record`: `DnsRecord`
- `shopperId`: optional shopper scope header value
- `context`: optional provider-agnostic request context (scopes/meta)

### Return

- `DnsRecordCreateResult`

## `Delete Dns Record`

### Signature

```php
public function deleteDnsRecord(DomainName $domain, ?string $recordId = null, ?DnsRecord $matchRecord = null, ?string $shopperId = null, ?ProviderRequestContext $context = null): DnsRecordDeleteResult;
```

### Purpose

Delete a DNS record.

### Parameters

- `domain`: target `DomainName`
- `recordId`: optional provider record ID
- `matchRecord`: optional typed match context
- `shopperId`: optional shopper scope header value
- `context`: optional provider-agnostic request context (scopes/meta)

### Return

- `DnsRecordDeleteResult`

### Notes

Some providers require `matchRecord` if ID-based deletion is not supported.

## `Get Domain Info`

### Signature

```php
public function getDomainInfo(DomainName $domain): DomainInfo;
```

### Purpose

Retrieve normalized information for a domain.

### Parameters

- `domain`: target `DomainName`

### Return

- `DomainInfo`

## `Get Domain Pricing`

### Signature

```php
public function getDomainPricing(
    ?DomainName $domain = null,
    ?string $tld = null,
    ?DomainRegistrationPeriod $period = null,
): DomainPrice;
```

### Purpose

Get normalized domain pricing for a domain or TLD.

### Parameters

- `domain`: optional concrete `DomainName`
- `tld`: optional TLD key when domain is not supplied
- `period`: optional period in years

### Return

- `DomainPrice`

### Errors

- Validation error when both `domain` and `tld` are `null`.

## `Get Nameservers`

### Signature

```php
public function getNameservers(DomainName $domain): NameserverSet;
```

### Purpose

Read current nameserver delegation for a domain.

### Parameters

- `domain`: target `DomainName`

### Return

- `NameserverSet`

## `List Dns Records`

### Signature

```php
public function listDnsRecords(DomainName $domain): array;
```

### Purpose

List DNS records for a domain when provider supports this capability.

### Parameters

- `domain`: target `DomainName`

### Return

- `list<DnsRecord>`

### Errors

- `UnsupportedCapabilityException` when provider does not support DNS listing.

## `List Domains`

### Signature

```php
public function listDomains(?int $page = null, ?int $pageSize = null, ?string $status = null, ?string $shopperId = null, ?ProviderRequestContext $context = null): array;
```

### Purpose

List domains for provider account context when supported.

### Parameters

- `page`: optional page marker/index
- `pageSize`: optional page size
- `status`: optional status filter
- `shopperId`: optional shopper scope header value
- `context`: optional provider-agnostic request context (scopes/meta)

### Return

- `list<DomainInfo>`

## `Metadata`

### Signature

```php
public function metadata(): ProviderMetadata;
```

### Purpose

Return provider identity and capability summary metadata.

### Return

- `ProviderMetadata`

### Usage

```php
$metadata = $provider->metadata();
$providerName = $metadata->providerName;
```

## `Register Domain`

### Signature

```php
public function registerDomain(
    DomainName $domain,
    DomainRegistrationPeriod $period,
    DomainContact $registrantContact,
    ?NameserverSet $nameservers = null,
    ?bool $privacyEnabled = null,
    ?string $marketId = null,
    ?ProviderRequestContext $context = null,
): DomainRegistrationResult;
```

### Purpose

Register a domain for the configured provider account context.

### Parameters

- `domain`: target `DomainName`
- `period`: `DomainRegistrationPeriod` in years
- `registrantContact`: `DomainContact`
- `nameservers`: optional `NameserverSet`
- `privacyEnabled`: optional privacy flag
- `marketId`: optional market scope used by providers that require it
- `context`: optional provider-agnostic request context (scopes/meta)

### Return

- `DomainRegistrationResult`

### Usage

```php
$result = $provider->registerDomain($domain, new DomainRegistrationPeriod(1), $contact);
```

## `Renew Domain`

### Signature

```php
public function renewDomain(DomainName $domain, DomainRegistrationPeriod $period): DomainRenewalResult;
```

### Purpose

Renew an existing domain.

### Parameters

- `domain`: target `DomainName`
- `period`: renewal period

### Return

- `DomainRenewalResult`

### Usage

```php
$result = $provider->renewDomain(new DomainName('example.com'), new DomainRegistrationPeriod(1));
```

## `Set Nameservers`

### Signature

```php
public function setNameservers(DomainName $domain, NameserverSet $nameservers): NameserverUpdateResult;
```

### Purpose

Update nameserver delegation for a domain.

### Parameters

- `domain`: target `DomainName`
- `nameservers`: `NameserverSet`

### Return

- `NameserverUpdateResult`

## `Supports`

### Signature

```php
public function supports(string $capability): bool;
```

### Purpose

Check if a provider supports a specific capability.

### Parameters

- `capability`: capability key from `DomainProviders\Capabilities`

### Return

- `bool`

### Usage

```php
if ($provider->supports(\DomainProviders\Capabilities::DOMAIN_RENEWAL)) {
    // safe to call renewDomain
}
```

## `Transfer Domain`

### Signature

```php
public function transferDomain(
    DomainName $domain,
    string $authCode,
    ?DomainContact $registrantContact = null,
): DomainTransferResult;
```

### Purpose

Initiate domain transfer into current provider account.

### Parameters

- `domain`: target `DomainName`
- `authCode`: transfer/EPP auth code
- `registrantContact`: optional transfer contact payload

### Return

- `DomainTransferResult`

## `Update Dns Record`

### Signature

```php
public function updateDnsRecord(DomainName $domain, DnsRecord $record, ?string $shopperId = null, ?ProviderRequestContext $context = null): DnsRecordUpdateResult;
```

### Purpose

Update an existing DNS record.

### Parameters

- `domain`: target `DomainName`
- `record`: updated `DnsRecord`
- `shopperId`: optional shopper scope header value
- `context`: optional provider-agnostic request context (scopes/meta)

### Return

- `DnsRecordUpdateResult`
