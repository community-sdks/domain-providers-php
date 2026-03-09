<?php
declare(strict_types=1);

namespace DomainProviders\Exception;

use DomainProviders\ErrorCategory;

final class UnsupportedCapabilityException extends DomainProviderException
{
    public function __construct(string $capability)
    {
        parent::__construct(
            category: ErrorCategory::UNSUPPORTED_CAPABILITY,
            message: sprintf('Capability "%s" is not supported by this provider.', $capability),
            codeName: 'unsupported_capability',
            retryable: false,
        );
    }
}
