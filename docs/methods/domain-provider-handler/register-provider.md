
[Docs Home](../../README.md)

## Signature

```php
public function registerProvider(
    string $key,
    DomainProviderInterface $provider,
    ?ProviderConfig $config = null
): self;
```

## Purpose

Register a provider instance and optional routing config in the global handler.
