<?php
declare(strict_types=1);

namespace DomainProviders\DTO;

/**
 * Backward-compatible base operation response.
 *
 * @deprecated Use operation-specific result DTOs (e.g. DomainRenewalResult).
 */
class OperationResult extends ProviderOperationResult
{
}
