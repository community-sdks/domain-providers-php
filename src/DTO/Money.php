<?php
declare(strict_types=1);

namespace DomainProviders\DTO;

final class Money
{
    public function __construct(
        public readonly string $amount,
        public readonly string $currency,
    ) {
    }
}
