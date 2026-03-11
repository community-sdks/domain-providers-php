[Docs Home](../../README.md)

## `Get`

### Signature

```php
public function get(string $key): DomainProviderInterface;
```

### Purpose

Resolve a provider instance by key.

### Parameters

- `key`: provider key

### Return

- `DomainProviderInterface`

### Errors

- `ProviderNotFoundException` when key is missing.
- `InvalidArgumentException` when factory returns invalid type.

## `Has`

### Signature

```php
public function has(string $key): bool;
```

### Purpose

Check whether a provider key is registered.

### Parameters

- `key`: provider key

### Return

- `bool`

## `Keys`

### Signature

```php
public function keys(): array;
```

### Purpose

List all registered provider keys.

### Return

- `list<string>`

## `Register Class`

### Signature

```php
public function registerClass(string $key, string $providerClass): self;
```

### Purpose

Register a provider class name that can be instantiated with no constructor arguments.

### Parameters

- `key`: provider key
- `providerClass`: class-string implementing `DomainProviderInterface`

### Return

- `ProviderRegistry`

### Errors

- `InvalidArgumentException` if class does not implement `DomainProviderInterface`.

## `Register Factory`

### Signature

```php
public function registerFactory(string $key, callable $factory): self;
```

### Purpose

Register a provider factory callback. Use this when provider creation needs config/dependencies.

### Parameters

- `key`: provider key
- `factory`: callable returning `DomainProviderInterface`

### Return

- `ProviderRegistry`

## `Register Instance`

### Signature

```php
public function registerInstance(string $key, DomainProviderInterface $provider): self;
```

### Purpose

Register a concrete provider instance by key.

### Parameters

- `key`: provider key, for example `godaddy` or `my-provider`
- `provider`: provider instance

### Return

- `ProviderRegistry` for fluent chaining
