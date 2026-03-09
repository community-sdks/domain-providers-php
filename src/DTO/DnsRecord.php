<?php
declare(strict_types=1);

namespace DomainProviders\DTO;

final class DnsRecord
{
    /** @param array<string, mixed>|null $metadata */
    public function __construct(
        public readonly ?string $id,
        public readonly string $type,
        public readonly string $name,
        public readonly string $value,
        public readonly int $ttl,
        public readonly ?int $priority = null,
        public readonly ?bool $proxied = null,
        public readonly ?array $metadata = null,
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $payload = [
            'type' => strtoupper($this->type),
            'name' => $this->name,
            'data' => $this->value,
            'ttl' => $this->ttl,
        ];

        if ($this->priority !== null) {
            $payload['priority'] = $this->priority;
        }

        return $payload;
    }
}
