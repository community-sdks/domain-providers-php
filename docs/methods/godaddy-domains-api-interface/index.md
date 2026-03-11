
[Docs Home](../../README.md)

## `tlds`

### Signature

```php
public function tlds(): mixed;
```

### Purpose

Return TLDs available from the GoDaddy domains API.

## `available`

### Signature

```php
public function available(string $domain, ?string $checkType = null, ?bool $forTransfer = null): mixed;
```

### Purpose

Bridge call for domain availability in the underlying GoDaddy domains client.

## `list`

### Signature

```php
public function list(
	?string $xShopperId = null,
	?array $statuses = null,
	?array $statusGroups = null,
	?int $limit = null,
	?string $marker = null,
	?array $includes = null,
	?string $modifiedDate = null,
): mixed;
```

### Purpose

Bridge call for domain listing.

## `getAgreement`

### Signature

```php
public function getAgreement(array $tlds, bool $privacy, ?string $xMarketId = null, ?bool $forTransfer = null): mixed;
```

### Purpose

Bridge call for retrieving required agreement keys before registration/transfer.

## `registerDomainForCustomer`

### Signature

```php
public function registerDomainForCustomer(string $customerId, array $body, ?string $xRequestId = null): mixed;
```

### Purpose

Bridge call for v2 customer domain registration.

## `renewDomainForCustomer`

### Signature

```php
public function renewDomainForCustomer(string $customerId, string $domain, array $body, ?string $xRequestId = null): mixed;
```

### Purpose

Bridge call for v2 customer domain renewal.

## `transferDomainForCustomer`

### Signature

```php
public function transferDomainForCustomer(string $customerId, string $domain, array $body, ?string $xRequestId = null): mixed;
```

### Purpose

Bridge call for v2 customer transfer initiation.

## `getDomainForCustomer`

### Signature

```php
public function getDomainForCustomer(string $customerId, string $domain, ?string $xRequestId = null, ?array $includes = null): mixed;
```

### Purpose

Bridge call for retrieving v2 customer domain details.

## `setDomainNameserversForCustomer`

### Signature

```php
public function setDomainNameserversForCustomer(string $customerId, string $domain, array $body, ?string $xRequestId = null): mixed;
```

### Purpose

Bridge call for updating v2 customer domain nameservers.

## `recordAdd`

### Signature

```php
public function recordAdd(string $domain, array $records, ?string $xShopperId = null): mixed;
```

### Purpose

Bridge call for adding DNS records.

## `recordReplaceTypeName`

### Signature

```php
public function recordReplaceTypeName(string $domain, string $type, string $name, array $records, ?string $xShopperId = null): mixed;
```

### Purpose

Bridge call for replacing DNS records by type and name.

## `recordDeleteTypeName`

### Signature

```php
public function recordDeleteTypeName(string $domain, string $type, string $name, ?string $xShopperId = null): mixed;
```

### Purpose

Bridge call for deleting DNS records by type and name.

## `getDomainTransferForCustomer`

### Signature

```php
public function getDomainTransferForCustomer(string $customerId, string $domain, ?string $xRequestId = null): mixed;
```

### Purpose

Bridge call for retrieving transfer status for a v2 customer domain.
