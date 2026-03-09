<?php
declare(strict_types=1);

namespace DomainProviders\Exception;

use RuntimeException;

class DomainProviderException extends RuntimeException
{
    public function __construct(
        public readonly string $category,
        string $message,
        public readonly ?string $codeName = null,
        public readonly ?bool $retryable = null,
        public readonly ?string $providerReference = null,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }
}
