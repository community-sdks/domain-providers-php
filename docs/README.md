# Documentation

## Purpose

This folder contains method-level documentation for the public API of `domain-providers/php`.

Provider-specific docs currently cover Spaceship and GoDaddy.

## Method reference

- [`methods/domain-provider/index.md`](methods/domain-provider/index.md): `DomainProviderInterface` contract methods
- [`methods/provider-registry/index.md`](methods/provider-registry/index.md): `ProviderRegistry` methods
- [`methods/domain-provider-handler/index.md`](methods/domain-provider-handler/index.md): `DomainProviderHandler` routing methods
- [`methods/spaceship-provider-factory/index.md`](methods/spaceship-provider-factory/index.md): `SpaceshipProviderFactory` methods
- [`methods/spaceship-config/index.md`](methods/spaceship-config/index.md): `SpaceshipConfig` constructor details
- [`methods/godaddy-provider-factory/index.md`](methods/godaddy-provider-factory/index.md): `GoDaddyProviderFactory` methods
- [`methods/godaddy-config/index.md`](methods/godaddy-config/index.md): `GoDaddyConfig` constructor details
- [`methods/godaddy-domains-api-interface/index.md`](methods/godaddy-domains-api-interface/index.md): GoDaddy domains API bridge methods

Each folder now uses a single `index.md` that documents all related methods with signatures, input parameters, return types, error behavior, and usage examples.
