<?php
declare(strict_types=1);

namespace DomainProviders\Provider\GoDaddy;

use DomainProviders\Config\ProviderConfig;

class GoDaddyConfig extends ProviderConfig
{
    public const ACCOUNT_MODE_RESELLER = 'reseller';
    public const ACCOUNT_MODE_DIRECT = 'direct';

    /**
     * @param list<string>|null $onlyTlds
     * @param list<string> $exceptTlds
     * @param list<string> $priorityTlds
     */
    public function __construct(
        public readonly string $apiKey,
        public readonly string $apiSecret,
        public readonly ?string $customerId = null,
        public readonly string $environment = 'production',
        public readonly string $accountMode = self::ACCOUNT_MODE_RESELLER,
        ?array $onlyTlds = null,
        array $exceptTlds = [],
        int $priority = 100,
        array $priorityTlds = [],
    ) {
        parent::__construct($onlyTlds, $exceptTlds, $priority, $priorityTlds);
    }

    public function usesResellerAccount(): bool
    {
        return strtolower($this->accountMode) !== self::ACCOUNT_MODE_DIRECT;
    }
}
