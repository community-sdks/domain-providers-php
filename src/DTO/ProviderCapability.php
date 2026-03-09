<?php
declare(strict_types=1);

namespace DomainProviders\DTO;

final class ProviderCapability
{
    public function __construct(
        public readonly string $name,
        public readonly bool $supported,
        public readonly ?string $notes = null,
    ) {
    }
}
