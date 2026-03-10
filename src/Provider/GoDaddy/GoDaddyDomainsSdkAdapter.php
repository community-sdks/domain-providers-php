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
        return $this->client->domains()->available($domain, $checkType, $forTransfer);
    }

    public function tlds(): mixed
    {
        return $this->client->domains()->tlds();
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
        return $this->client->domains()->list($xShopperId, $statuses, $statusGroups, $limit, $marker, $includes, $modifiedDate);
    }

    public function getAgreement(array $tlds, bool $privacy, ?string $xMarketId = null, ?bool $forTransfer = null): mixed
    {
        return $this->client->domains()->getAgreement($tlds, $privacy, $xMarketId, $forTransfer);
    }

    public function registerDomainForCustomer(string $customerId, array $body, ?string $xRequestId = null): mixed
    {
        return $this->client->domains()->register($customerId, $body, $xRequestId);
    }

    public function renewDomainForCustomer(string $customerId, string $domain, array $body, ?string $xRequestId = null): mixed
    {
        return $this->client->domains()->renew($domain, null, $body, $customerId, $xRequestId);
    }

    public function transferDomainForCustomer(string $customerId, string $domain, array $body, ?string $xRequestId = null): mixed
    {
        return $this->client->domains()->transfer($customerId, $domain, $body, $xRequestId);
    }

    public function getDomainForCustomer(string $customerId, string $domain, ?string $xRequestId = null, ?array $includes = null): mixed
    {
        return $this->client->domains()->get($domain, null, $customerId, $xRequestId, $includes);
    }

    public function setDomainNameserversForCustomer(string $customerId, string $domain, array $body, ?string $xRequestId = null): mixed
    {
        return $this->client->domains()->replaceNameServers($customerId, $domain, $body, $xRequestId);
    }

    public function recordAdd(string $domain, array $records, ?string $xShopperId = null): mixed
    {
        return $this->client->domains()->recordAdd($domain, $records, $xShopperId);
    }

    public function recordReplaceTypeName(string $domain, string $type, string $name, array $records, ?string $xShopperId = null): mixed
    {
        return $this->client->domains()->recordReplaceTypeName($domain, $type, $name, $records, $xShopperId);
    }

    public function recordDeleteTypeName(string $domain, string $type, string $name, ?string $xShopperId = null): mixed
    {
        return $this->client->domains()->recordDeleteTypeName($domain, $type, $name, $xShopperId);
    }

    public function getDomainTransferForCustomer(string $customerId, string $domain, ?string $xRequestId = null): mixed
    {
        return $this->client->domains()->getTransfer($customerId, $domain, $xRequestId);
    }
}
