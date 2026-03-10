<?php
declare(strict_types=1);

namespace DomainProviders\Config;

abstract class ProviderConfig
{
    /**
     * @param list<string>|null $onlyTlds
     * @param list<string> $exceptTlds
     * @param list<string> $priorityTlds
     */
    public function __construct(
        public readonly ?array $onlyTlds = null,
        public readonly array $exceptTlds = [],
        public readonly int $priority = 100,
        public readonly array $priorityTlds = [],
    ) {
    }

    /** @return list<string>|null */
    public function normalizedOnlyTlds(): ?array
    {
        return $this->onlyTlds === null ? null : $this->normalizeTlds($this->onlyTlds);
    }

    /** @return list<string> */
    public function normalizedExceptTlds(): array
    {
        return $this->normalizeTlds($this->exceptTlds);
    }

    /** @return list<string> */
    public function normalizedPriorityTlds(): array
    {
        return $this->normalizeTlds($this->priorityTlds);
    }

    public function matchesTld(string $tld): bool
    {
        $normalized = self::normalizeTld($tld);

        if (in_array($normalized, $this->normalizedExceptTlds(), true)) {
            return false;
        }

        $only = $this->normalizedOnlyTlds();
        if ($only === null) {
            return true;
        }

        return in_array($normalized, $only, true);
    }

    public function isPriorityTld(string $tld): bool
    {
        return in_array(self::normalizeTld($tld), $this->normalizedPriorityTlds(), true);
    }

    public static function normalizeTld(string $tld): string
    {
        return ltrim(strtolower(trim($tld)), '.');
    }

    /**
     * @param list<string> $tlds
     * @return list<string>
     */
    private function normalizeTlds(array $tlds): array
    {
        $normalized = [];
        foreach ($tlds as $tld) {
            $item = self::normalizeTld($tld);
            if ($item === '') {
                continue;
            }

            $normalized[$item] = true;
        }

        return array_values(array_keys($normalized));
    }
}
