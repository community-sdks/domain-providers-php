<?php
declare(strict_types=1);

namespace DomainProviders\DTO;

final class NameserverSet
{
    /** @var list<string> */
    public readonly array $nameservers;

    /** @param list<string> $nameservers */
    public function __construct(array $nameservers)
    {
        $this->nameservers = array_values(array_map(static fn (string $n): string => strtolower(trim($n)), $nameservers));
    }
}
