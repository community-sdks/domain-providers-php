<?php
declare(strict_types=1);

namespace DomainProviders\DTO;

final class DomainName
{
    public readonly string $full;
    public readonly string $label;
    public readonly string $tld;

    public function __construct(string $full)
    {
        $normalized = strtolower(trim($full));
        $parts = explode('.', $normalized);
        if (count($parts) < 2) {
            throw new \InvalidArgumentException('Invalid domain format.');
        }

        $this->full = $normalized;
        $this->tld = (string) array_pop($parts);
        $this->label = (string) array_pop($parts);
    }
}
