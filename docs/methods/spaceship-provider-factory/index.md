[Docs Home](../../README.md)

## `fromConfig`

### Signature

```php
public static function fromConfig(SpaceshipConfig $config): SpaceshipProvider;
```

### Purpose

Build a ready-to-use `SpaceshipProvider` instance from config values.

### Parameters

- `config`: `SpaceshipConfig`

### Return

- `SpaceshipProvider`

### Usage

```php
$provider = SpaceshipProviderFactory::fromConfig(new SpaceshipConfig(
	apiKey: 'key',
	apiSecret: 'secret',
	environment: 'sandbox',
));
```