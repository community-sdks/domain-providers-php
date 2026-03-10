
[Docs Home](../../README.md)

## Signature

```php
public function tlds(): mixed;
```

## Purpose

Return TLDs available from the GoDaddy domains API.

## Notes

- In this package, this is used by `GoDaddyProvider::listSupportedTlds()`.
- Returned payload shape depends on the underlying SDK/API response.
