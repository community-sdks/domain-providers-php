<?php
declare(strict_types=1);

namespace DomainProviders\DTO;

final readonly class ProviderRequestContext
{
    /**
     * @param array<string, scalar|null> $scopes
     * @param array<string, mixed> $meta
     */
    public function __construct(
        public array $scopes = [],
        public array $meta = [],
    ) {
    }

    public function scopeAsString(string ...$keys): ?string
    {
        foreach ($keys as $key) {
            if (!array_key_exists($key, $this->scopes)) {
                continue;
            }

            $value = $this->scopes[$key];
            if ($value === null) {
                return null;
            }

            if (!is_scalar($value)) {
                continue;
            }

            $normalized = trim((string) $value);
            if ($normalized !== '') {
                return $normalized;
            }
        }

        return null;
    }
}
