<?php
declare(strict_types=1);

namespace DomainProviders\Provider\GoDaddy;

use CommunitySDKs\GoDaddy\Client;

final class GoDaddyDomainsSdkAdapter implements GoDaddyDomainsApiInterface
{
    public function __construct(private readonly Client $client)
    {
    }

    public function available(string $domain, ?string $checkType = null, ?bool $forTransfer = null): mixed
    {
        return $this->callFirstAvailable(['available'], [$domain, $checkType, $forTransfer]);
    }

    public function list(
        ?string $xShopperId = null,
        ?array $statuses = null,
        ?array $statusGroups = null,
        ?int $limit = null,
        ?string $marker = null,
        ?array $includes = null,
        ?string $modifiedDate = null,
    ): mixed {
        return $this->callFirstAvailable(['list', 'listDomains'], [$xShopperId, $statuses, $statusGroups, $limit, $marker, $includes, $modifiedDate]);
    }

    public function getAgreement(array $tlds, bool $privacy, ?string $xMarketId = null, ?bool $forTransfer = null): mixed
    {
        return $this->callFirstAvailable(['getAgreement'], [$tlds, $privacy, $xMarketId, $forTransfer]);
    }

    public function registerDomainForCustomer(string $customerId, array $body, ?string $xRequestId = null): mixed
    {
        return $this->callFirstAvailable(['registerDomainForCustomer'], [$customerId, $body, $xRequestId]);
    }

    public function renewDomainForCustomer(string $customerId, string $domain, array $body, ?string $xRequestId = null): mixed
    {
        return $this->callFirstAvailable(['renewDomainForCustomer'], [$customerId, $domain, $body, $xRequestId]);
    }

    public function transferDomainForCustomer(string $customerId, string $domain, array $body, ?string $xRequestId = null): mixed
    {
        return $this->callFirstAvailable(['transferDomainForCustomer'], [$customerId, $domain, $body, $xRequestId]);
    }

    public function getDomainForCustomer(string $customerId, string $domain, ?string $xRequestId = null, ?array $includes = null): mixed
    {
        return $this->callFirstAvailable(['getDomainForCustomer'], [$customerId, $domain, $xRequestId, $includes]);
    }

    public function setDomainNameserversForCustomer(string $customerId, string $domain, array $body, ?string $xRequestId = null): mixed
    {
        return $this->callFirstAvailable(['setDomainNameserversForCustomer'], [$customerId, $domain, $body, $xRequestId]);
    }

    public function recordAdd(string $domain, array $records, ?string $xShopperId = null): mixed
    {
        return $this->callFirstAvailable(['recordAdd'], [$domain, $records, $xShopperId]);
    }

    public function recordReplaceTypeName(string $domain, string $type, string $name, array $records, ?string $xShopperId = null): mixed
    {
        return $this->callFirstAvailable(['recordReplaceTypeName'], [$domain, $type, $name, $records, $xShopperId]);
    }

    public function recordDeleteTypeName(string $domain, string $type, string $name, ?string $xShopperId = null): mixed
    {
        return $this->callFirstAvailable(['recordDeleteTypeName'], [$domain, $type, $name, $xShopperId]);
    }

    public function getDomainTransferForCustomer(string $customerId, string $domain, ?string $xRequestId = null): mixed
    {
        return $this->callFirstAvailable(['getDomainTransferForCustomer'], [$customerId, $domain, $xRequestId]);
    }

    private function callFirstAvailable(array $methodCandidates, array $args): mixed
    {
        $domainsService = $this->client->domains();

        foreach ($methodCandidates as $method) {
            if (method_exists($domainsService, $method)) {
                return $domainsService->{$method}(...$args);
            }
        }

        throw new \BadMethodCallException(
            sprintf('None of the expected GoDaddy domains methods were found: %s', implode(', ', $methodCandidates))
        );
    }
}
