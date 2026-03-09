<?php
declare(strict_types=1);

namespace DomainProviders\DTO;

final class DomainRegistrationPeriod
{
    public function __construct(public readonly int $years)
    {
        if ($years < 1) {
            throw new \InvalidArgumentException('Registration period years must be >= 1.');
        }
    }
}
