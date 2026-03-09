<?php
declare(strict_types=1);

namespace DomainProviders\DTO;

final class OperationResult
{
    /** @param array<string, mixed>|null $metadata */
    public function __construct(
        public readonly bool $success,
        public readonly ?string $message = null,
        public readonly ?string $code = null,
        public readonly ?bool $retryable = null,
        public readonly ?string $providerReference = null,
        public readonly ?array $metadata = null,
    ) {
    }
}
