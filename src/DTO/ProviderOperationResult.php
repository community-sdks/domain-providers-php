<?php
declare(strict_types=1);

namespace DomainProviders\DTO;

abstract class ProviderOperationResult
{
    public function __construct(
        public readonly bool $success,
        public readonly ?string $message = null,
        public readonly ?string $code = null,
        public readonly ?bool $retryable = null,
        public readonly ?string $providerReference = null,
    ) {
    }
}
