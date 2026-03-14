<?php
declare(strict_types=1);

namespace DomainProviders\Provider\Spaceship;

use CommunitySDKs\Spaceship\Client;
use CommunitySDKs\Spaceship\DTO\DNSRecords\Request\DeleteRecordsRequest;
use CommunitySDKs\Spaceship\DTO\DNSRecords\Request\GetResourceRecordsListRequest;
use CommunitySDKs\Spaceship\DTO\DNSRecords\Request\SaveRecordsRequest;
use CommunitySDKs\Spaceship\DTO\DNSRecords\Schema\RecordsRecordsUpdateModel;
use CommunitySDKs\Spaceship\DTO\DNSRecords\Schema\ResourceRecordsListDeleteItem;
use CommunitySDKs\Spaceship\DTO\Domains\Request\CheckSingleDomainAvailabilityRequest;
use CommunitySDKs\Spaceship\DTO\Domains\Request\DomainCreateRequest;
use CommunitySDKs\Spaceship\DTO\Domains\Request\DomainRenewRequest;
use CommunitySDKs\Spaceship\DTO\Domains\Request\GetDomainInfoRequest;
use CommunitySDKs\Spaceship\DTO\Domains\Request\GetDomainListRequest;
use CommunitySDKs\Spaceship\DTO\Domains\Request\GetTransferInfoRequest;
use CommunitySDKs\Spaceship\DTO\Domains\Request\SetDomainNameserversRequest;
use CommunitySDKs\Spaceship\DTO\Domains\Request\TransferRequestRequest;
use CommunitySDKs\Spaceship\DTO\Domains\Schema\DomainCreateRequest as DomainCreateSchema;
use CommunitySDKs\Spaceship\DTO\Domains\Schema\DomainNameServersConfigurationRequest;
use CommunitySDKs\Spaceship\DTO\Domains\Schema\DomainTransferRequest;
use CommunitySDKs\Spaceship\DTO\Domains\Schema\DomainsDomainRenewalRequestInfo;
use CommunitySDKs\Spaceship\Exception\Common\ApiException;
use DomainProviders\Capabilities;
use DomainProviders\Contract\DomainProviderInterface;
use DomainProviders\Contract\TldDiscoveryInterface;
use DomainProviders\DTO\AvailabilityResult;
use DomainProviders\DTO\DnsRecord;
use DomainProviders\DTO\DomainContact;
use DomainProviders\DTO\DomainInfo;
use DomainProviders\DTO\DomainName;
use DomainProviders\DTO\DomainPrice;
use DomainProviders\DTO\DomainRegistrationPeriod;
use DomainProviders\DTO\DomainRegistrationResult;
use DomainProviders\DTO\DomainRenewalResult;
use DomainProviders\DTO\DomainTransferResult;
use DomainProviders\DTO\DnsRecordCreateResult;
use DomainProviders\DTO\DnsRecordDeleteResult;
use DomainProviders\DTO\DnsRecordUpdateResult;
use DomainProviders\DTO\Money;
use DomainProviders\DTO\NameserverSet;
use DomainProviders\DTO\NameserverUpdateResult;
use DomainProviders\DTO\ProviderCapability;
use DomainProviders\DTO\ProviderMetadata;
use DomainProviders\DTO\ProviderRequestContext;
use DomainProviders\DTO\TransferAvailabilityResult;
use DomainProviders\ErrorCategory;
use DomainProviders\Exception\DomainProviderException;
use DomainProviders\Exception\UnsupportedCapabilityException;
use DomainProviders\Statuses;

final class SpaceshipProvider implements DomainProviderInterface, TldDiscoveryInterface
{
    /** @var array<string, bool> */
    private array $capabilities = [
        Capabilities::AVAILABILITY_CHECK    => true,
        Capabilities::DOMAIN_REGISTRATION   => true,
        Capabilities::DOMAIN_RENEWAL        => true,
        Capabilities::DOMAIN_TRANSFER       => true,
        Capabilities::DOMAIN_INFO           => true,
        Capabilities::DOMAIN_LISTING        => true,
        Capabilities::NAMESERVER_READ       => true,
        Capabilities::NAMESERVER_UPDATE     => true,
        Capabilities::DNS_RECORD_LIST       => true,
        Capabilities::DNS_RECORD_CREATE     => true,
        Capabilities::DNS_RECORD_UPDATE     => true,
        Capabilities::DNS_RECORD_DELETE     => true,
        Capabilities::PRICING_LOOKUP        => false,
    ];

    public function __construct(
        private readonly Client $client,
        private readonly SpaceshipConfig $config,
    ) {
    }

    public function metadata(): ProviderMetadata
    {
        $capabilitySummary = [];
        foreach ($this->capabilities as $name => $supported) {
            $capabilitySummary[] = new ProviderCapability($name, $supported);
        }

        return new ProviderMetadata(
            providerName: 'Spaceship',
            providerKey: 'spaceship',
            environment: $this->config->environment,
            accountReference: null,
            supportedTlds: null,
            capabilitySummary: $capabilitySummary,
        );
    }

    public function supports(string $capability): bool
    {
        return $this->capabilities[$capability] ?? false;
    }

    public function listSupportedTlds(): array
    {
        return [];
    }

    public function checkAvailability(DomainName $domain): AvailabilityResult
    {
        $this->assertCapability(Capabilities::AVAILABILITY_CHECK);

        try {
            $response = $this->client->domains()->checkSingleDomainAvailability(
                new CheckSingleDomainAvailabilityRequest($domain->full),
            );

            $data = $response->data->toArray();

            $status = strtolower((string) ($data['status'] ?? ''));
            $available = $status === 'available' || (bool) ($data['available'] ?? false);
            $premium = (bool) ($data['premium'] ?? false);

            $price = null;
            if (isset($data['price']) && is_array($data['price'])) {
                $price = new Money(
                    amount: (string) ($data['price']['amount'] ?? '0'),
                    currency: (string) ($data['price']['currency'] ?? 'USD'),
                );
            }

            return new AvailabilityResult(
                available: $available,
                premium: $premium,
                price: $price,
                reason: $data['reason'] ?? null,
                providerReference: null,
            );
        } catch (\Throwable $e) {
            throw $this->mapException('check_availability', $e);
        }
    }

    public function registerDomain(
        DomainName $domain,
        DomainRegistrationPeriod $period,
        DomainContact $registrantContact,
        ?NameserverSet $nameservers = null,
        ?bool $privacyEnabled = null,
        ?string $marketId = null,
        ?ProviderRequestContext $context = null,
    ): DomainRegistrationResult {
        $this->assertCapability(Capabilities::DOMAIN_REGISTRATION);

        $contact = $this->toSpaceshipContact($registrantContact);

        $body = [
            'years' => $period->years,
            'contacts' => [
                'owner'   => $contact,
                'admin'   => $contact,
                'tech'    => $contact,
                'billing' => $contact,
            ],
        ];

        if ($nameservers !== null && count($nameservers->nameservers) > 0) {
            $body['nameservers'] = [
                'type'  => 'custom',
                'hosts' => $nameservers->nameservers,
            ];
        }

        if ($privacyEnabled !== null) {
            $body['privacy'] = $privacyEnabled;
        }

        try {
            $this->client->domains()->domainCreate(
                new DomainCreateRequest(
                    $domain->full,
                    DomainCreateSchema::fromArray($body),
                ),
            );

            return new DomainRegistrationResult(
                success: true,
                message: 'Domain registration request accepted.',
                code: 'register_domain.success',
                retryable: false,
                providerReference: null,
            );
        } catch (\Throwable $e) {
            throw $this->mapException('register_domain', $e);
        }
    }

    public function renewDomain(DomainName $domain, DomainRegistrationPeriod $period): DomainRenewalResult
    {
        $this->assertCapability(Capabilities::DOMAIN_RENEWAL);

        try {
            $this->client->domains()->domainRenew(
                new DomainRenewRequest(
                    $domain->full,
                    DomainsDomainRenewalRequestInfo::fromArray(['years' => $period->years]),
                ),
            );

            return new DomainRenewalResult(
                success: true,
                message: 'Domain renewal request accepted.',
                code: 'renew_domain.success',
                retryable: false,
                providerReference: null,
            );
        } catch (\Throwable $e) {
            throw $this->mapException('renew_domain', $e);
        }
    }

    public function transferDomain(
        DomainName $domain,
        string $authCode,
        ?DomainContact $registrantContact = null,
    ): DomainTransferResult {
        $this->assertCapability(Capabilities::DOMAIN_TRANSFER);

        $body = ['authCode' => $authCode];

        if ($registrantContact !== null) {
            $body['contacts'] = [
                'owner' => $this->toSpaceshipContact($registrantContact),
            ];
        }

        try {
            $this->client->domains()->transferRequest(
                new TransferRequestRequest(
                    $domain->full,
                    DomainTransferRequest::fromArray($body),
                ),
            );

            return new DomainTransferResult(
                success: true,
                message: 'Domain transfer request initiated.',
                code: 'transfer_domain.initiated',
                retryable: false,
                providerReference: null,
            );
        } catch (\Throwable $e) {
            throw $this->mapException('transfer_domain', $e);
        }
    }

    public function getDomainInfo(DomainName $domain): DomainInfo
    {
        $this->assertCapability(Capabilities::DOMAIN_INFO);

        try {
            $response = $this->client->domains()->getDomainInfo(
                new GetDomainInfoRequest($domain->full),
            );

            $data = $response->data->toArray();

            $rawStatuses = isset($data['status']) ? [(string) $data['status']] : null;

            $nameservers = null;
            if (isset($data['nameservers']) && is_array($data['nameservers'])) {
                $nameservers = $data['nameservers']['hosts'] ?? null;
            }

            return new DomainInfo(
                domain: (string) ($data['name'] ?? $data['domain'] ?? $domain->full),
                status: $this->mapStatus($data['status'] ?? null),
                expirationDate: $this->normalizeDate($data['expiresAt'] ?? $data['expiredAt'] ?? null),
                registrationDate: $this->normalizeDate($data['createdAt'] ?? null),
                nameservers: $nameservers,
                authCodeSupported: true,
                locked: isset($data['transferLock']) ? (bool) $data['transferLock'] : null,
                privacyEnabled: isset($data['privacyProtection']) ? (bool) $data['privacyProtection'] : null,
                providerReference: null,
                rawStatuses: $rawStatuses,
            );
        } catch (\Throwable $e) {
            throw $this->mapException('get_domain_info', $e);
        }
    }

    public function listDomains(
        ?int $page = null,
        ?int $pageSize = null,
        ?string $status = null,
        ?string $shopperId = null,
        ?ProviderRequestContext $context = null,
    ): array {
        $this->assertCapability(Capabilities::DOMAIN_LISTING);

        $take = $pageSize ?? 100;
        $skip = $page !== null && $page > 1 ? ($page - 1) * $take : 0;

        try {
            $response = $this->client->domains()->getDomainList(
                new GetDomainListRequest($take, $skip, []),
            );

            $data = $response->data;
            if (!is_array($data)) {
                return [];
            }

            $rows = $data['items'] ?? $data;
            if (!is_array($rows)) {
                return [];
            }

            $items = [];
            foreach ($rows as $row) {
                if (!is_array($row)) {
                    continue;
                }

                $nameservers = null;
                if (isset($row['nameservers']) && is_array($row['nameservers'])) {
                    $nameservers = $row['nameservers']['hosts'] ?? null;
                }

                $items[] = new DomainInfo(
                    domain: (string) ($row['name'] ?? $row['domain'] ?? ''),
                    status: $this->mapStatus($row['status'] ?? null),
                    expirationDate: $this->normalizeDate($row['expiresAt'] ?? null),
                    registrationDate: $this->normalizeDate($row['createdAt'] ?? null),
                    nameservers: $nameservers,
                    providerReference: null,
                    rawStatuses: isset($row['status']) ? [(string) $row['status']] : null,
                );
            }

            return $items;
        } catch (\Throwable $e) {
            throw $this->mapException('list_domains', $e);
        }
    }

    public function getNameservers(DomainName $domain): NameserverSet
    {
        $this->assertCapability(Capabilities::NAMESERVER_READ);

        try {
            $info = $this->getDomainInfo($domain);
            return new NameserverSet($info->nameservers ?? []);
        } catch (\Throwable $e) {
            throw $this->mapException('get_nameservers', $e);
        }
    }

    public function setNameservers(DomainName $domain, NameserverSet $nameservers): NameserverUpdateResult
    {
        $this->assertCapability(Capabilities::NAMESERVER_UPDATE);

        try {
            $this->client->domains()->setDomainNameservers(
                new SetDomainNameserversRequest(
                    $domain->full,
                    DomainNameServersConfigurationRequest::fromArray([
                        'type'  => 'custom',
                        'hosts' => $nameservers->nameservers,
                    ]),
                ),
            );

            return new NameserverUpdateResult(
                success: true,
                message: 'Nameservers updated.',
                code: 'set_nameservers.success',
                retryable: false,
                providerReference: null,
            );
        } catch (\Throwable $e) {
            throw $this->mapException('set_nameservers', $e);
        }
    }

    public function listDnsRecords(DomainName $domain): array
    {
        $this->assertCapability(Capabilities::DNS_RECORD_LIST);

        try {
            $response = $this->client->dnsRecords()->getResourceRecordsList(
                new GetResourceRecordsListRequest($domain->full, 500, 0, []),
            );

            $data = $response->data;
            if (!is_array($data)) {
                return [];
            }

            $rows = $data['items'] ?? $data;
            if (!is_array($rows)) {
                return [];
            }

            $records = [];
            foreach ($rows as $row) {
                if (!is_array($row)) {
                    continue;
                }

                $type = strtoupper((string) ($row['type'] ?? ''));
                $name = (string) ($row['name'] ?? '@');
                $value = (string) ($row['address'] ?? $row['value'] ?? $row['host'] ?? '');
                $ttl = isset($row['ttl']) ? (int) $row['ttl'] : null;

                if ($type === '' || $value === '') {
                    continue;
                }

                $records[] = new DnsRecord(
                    id: null,
                    type: $type,
                    name: $name,
                    value: $value,
                    ttl: $ttl ?? 3600,
                );
            }

            return $records;
        } catch (\Throwable $e) {
            throw $this->mapException('list_dns_records', $e);
        }
    }

    public function createDnsRecord(
        DomainName $domain,
        DnsRecord $record,
        ?string $shopperId = null,
        ?ProviderRequestContext $context = null,
    ): DnsRecordCreateResult {
        $this->assertCapability(Capabilities::DNS_RECORD_CREATE);

        try {
            $this->client->dnsRecords()->saveRecords(
                new SaveRecordsRequest(
                    $domain->full,
                    RecordsRecordsUpdateModel::fromArray([
                        'add' => [$record->toArray()],
                    ]),
                ),
            );

            return new DnsRecordCreateResult(
                success: true,
                message: 'DNS record created.',
                code: 'create_dns_record.success',
                retryable: false,
                providerReference: null,
            );
        } catch (\Throwable $e) {
            throw $this->mapException('create_dns_record', $e);
        }
    }

    public function updateDnsRecord(
        DomainName $domain,
        DnsRecord $record,
        ?string $shopperId = null,
        ?ProviderRequestContext $context = null,
    ): DnsRecordUpdateResult {
        $this->assertCapability(Capabilities::DNS_RECORD_UPDATE);

        try {
            $this->client->dnsRecords()->saveRecords(
                new SaveRecordsRequest(
                    $domain->full,
                    RecordsRecordsUpdateModel::fromArray([
                        'add' => [$record->toArray()],
                    ]),
                ),
            );

            return new DnsRecordUpdateResult(
                success: true,
                message: 'DNS record updated.',
                code: 'update_dns_record.success',
                retryable: false,
                providerReference: null,
            );
        } catch (\Throwable $e) {
            throw $this->mapException('update_dns_record', $e);
        }
    }

    public function deleteDnsRecord(
        DomainName $domain,
        ?string $recordId = null,
        ?DnsRecord $matchRecord = null,
        ?string $shopperId = null,
        ?ProviderRequestContext $context = null,
    ): DnsRecordDeleteResult {
        $this->assertCapability(Capabilities::DNS_RECORD_DELETE);

        if ($matchRecord === null) {
            throw new DomainProviderException(
                category: ErrorCategory::VALIDATION,
                message: 'Spaceship delete requires record type and name match details.',
                codeName: 'delete_dns_record.validation',
                retryable: false,
            );
        }

        try {
            $this->client->dnsRecords()->deleteRecords(
                new DeleteRecordsRequest(
                    $domain->full,
                    ResourceRecordsListDeleteItem::fromArray([
                        'delete' => [$matchRecord->toArray()],
                    ]),
                ),
            );

            return new DnsRecordDeleteResult(
                success: true,
                message: 'DNS record deleted.',
                code: 'delete_dns_record.success',
                retryable: false,
                providerReference: null,
            );
        } catch (\Throwable $e) {
            throw $this->mapException('delete_dns_record', $e);
        }
    }

    public function getDomainPricing(
        ?DomainName $domain = null,
        ?string $tld = null,
        ?DomainRegistrationPeriod $period = null,
    ): DomainPrice {
        throw new UnsupportedCapabilityException(Capabilities::PRICING_LOOKUP);
    }

    public function checkTransferAvailability(DomainName $domain): TransferAvailabilityResult
    {
        $this->assertCapability(Capabilities::DOMAIN_TRANSFER);

        try {
            $response = $this->client->domains()->getTransferInfo(
                new GetTransferInfoRequest($domain->full),
            );

            $data = $response->data ?? [];

            $lock = $data['lock'] ?? $data['locked'] ?? null;
            $status = strtolower((string) ($data['status'] ?? 'unknown'));
            $blocked = in_array($status, ['locked', 'denied', 'blocked', 'failed'], true);

            return new TransferAvailabilityResult(
                transferStatus: $blocked ? 'blocked' : 'ready',
                locked: is_bool($lock) ? $lock : null,
                authCodeRequired: true,
                reasons: isset($data['reason']) ? [(string) $data['reason']] : null,
                providerReference: null,
            );
        } catch (\Throwable $e) {
            throw $this->mapException('check_transfer_availability', $e);
        }
    }

    private function assertCapability(string $capability): void
    {
        if (!$this->supports($capability)) {
            throw new UnsupportedCapabilityException($capability);
        }
    }

    private function normalizeDate(mixed $value): ?string
    {
        if (!is_string($value) || $value === '') {
            return null;
        }

        return substr($value, 0, 10);
    }

    private function mapStatus(mixed $providerStatus): string
    {
        $raw = strtolower((string) $providerStatus);

        return match (true) {
            str_contains($raw, 'active') => Statuses::ACTIVE,
            str_contains($raw, 'expire') => Statuses::EXPIRED,
            str_contains($raw, 'suspend') => Statuses::SUSPENDED,
            str_contains($raw, 'delete') => Statuses::DELETED,
            str_contains($raw, 'transfer') && str_contains($raw, 'pending') => Statuses::TRANSFER_PENDING,
            str_contains($raw, 'transfer') => Statuses::TRANSFERRED,
            str_contains($raw, 'pending') => Statuses::PENDING,
            $raw === '' => Statuses::UNKNOWN,
            default => Statuses::UNKNOWN,
        };
    }

    /** @return array<string, mixed> */
    private function toSpaceshipContact(DomainContact $contact): array
    {
        return [
            'firstName'   => $this->firstName($contact->fullName),
            'lastName'    => $this->lastName($contact->fullName),
            'email'       => $contact->email,
            'phoneNumber' => $contact->phone,
            'address'     => [
                'address1'   => $contact->addressLine1,
                'address2'   => $contact->addressLine2,
                'city'       => $contact->city,
                'state'      => $contact->stateOrRegion,
                'postalCode' => $contact->postalCode,
                'country'    => strtoupper($contact->countryCode),
            ],
            'organization' => $contact->organization,
        ];
    }

    private function firstName(string $fullName): string
    {
        $parts = preg_split('/\s+/', trim($fullName)) ?: [];
        return $parts[0] ?? $fullName;
    }

    private function lastName(string $fullName): string
    {
        $parts = preg_split('/\s+/', trim($fullName)) ?: [];
        if (count($parts) < 2) {
            return $parts[0] ?? 'Unknown';
        }

        return (string) end($parts);
    }

    private function mapException(string $operation, \Throwable $e): DomainProviderException
    {
        $providerMessage = $this->extractProviderErrorMessage($e);
        $message = strtolower($providerMessage);
        $statusCode = $e instanceof ApiException ? $e->statusCode : null;

        $category = match (true) {
            $statusCode === 401,
            str_contains($message, '401'),
            str_contains($message, 'unauthorized') => ErrorCategory::AUTHENTICATION,
            $statusCode === 403,
            str_contains($message, '403'),
            str_contains($message, 'forbidden') => ErrorCategory::AUTHORIZATION,
            $statusCode === 429,
            str_contains($message, '429'),
            str_contains($message, 'rate') => ErrorCategory::RATE_LIMIT,
            $statusCode === 408,
            $statusCode === 504,
            str_contains($message, 'timeout') => ErrorCategory::PROVIDER_TIMEOUT,
            $statusCode === 404,
            str_contains($message, 'not found') => ErrorCategory::DOMAIN_NOT_FOUND,
            str_contains($message, 'unavailable') => ErrorCategory::DOMAIN_UNAVAILABLE,
            str_contains($message, 'already') && str_contains($message, 'register') => ErrorCategory::DOMAIN_ALREADY_REGISTERED,
            default => ErrorCategory::PROVIDER_COMMUNICATION,
        };

        return new DomainProviderException(
            category: $category,
            message: sprintf('%s failed: %s', $operation, $providerMessage),
            codeName: $operation . '.' . $category,
            retryable: in_array($category, [ErrorCategory::PROVIDER_COMMUNICATION, ErrorCategory::PROVIDER_TIMEOUT, ErrorCategory::RATE_LIMIT], true),
            previous: $e,
        );
    }

    private function extractProviderErrorMessage(\Throwable $e): string
    {
        if (!$e instanceof ApiException) {
            return $e->getMessage();
        }

        $body = trim((string) $e->responseBody);
        if ($body === '') {
            return $e->getMessage();
        }

        try {
            $decoded = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            return $body;
        }

        if (!is_array($decoded)) {
            return $body;
        }

        $message = $this->firstStringFrom($decoded, ['message', 'detail', 'description', 'error', 'title']);
        if ($message !== null) {
            return $message;
        }

        if (isset($decoded['errors']) && is_array($decoded['errors'])) {
            foreach ($decoded['errors'] as $error) {
                if (!is_array($error)) {
                    continue;
                }

                $nested = $this->firstStringFrom($error, ['message', 'detail', 'description', 'error', 'title']);
                if ($nested !== null) {
                    return $nested;
                }
            }
        }

        return $e->getMessage();
    }

    /**
     * @param array<string, mixed> $data
     * @param list<string> $keys
     */
    private function firstStringFrom(array $data, array $keys): ?string
    {
        foreach ($keys as $key) {
            if (!isset($data[$key]) || !is_string($data[$key])) {
                continue;
            }

            $value = trim($data[$key]);
            if ($value !== '') {
                return $value;
            }
        }

        return null;
    }
}
