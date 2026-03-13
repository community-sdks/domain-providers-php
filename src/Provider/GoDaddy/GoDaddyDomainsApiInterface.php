<?php
declare(strict_types=1);

namespace DomainProviders\Provider\GoDaddy;

interface GoDaddyDomainsApiInterface
{
    public function tlds(): mixed;

    public function available(string $domain, ?string $checkType = null, ?bool $forTransfer = null): mixed;

    public function list(
        ?string $xShopperId = null,
        ?array $statuses = null,
        ?array $statusGroups = null,
        ?int $limit = null,
        ?string $marker = null,
        ?array $includes = null,
        ?string $modifiedDate = null,
    ): mixed;

    public function getAgreement(array $tlds, bool $privacy, ?string $xMarketId = null, ?bool $forTransfer = null): mixed;

    public function registerDomain(array $body, ?string $xShopperId = null): mixed;

    public function registerDomainForCustomer(string $customerId, array $body, ?string $xRequestId = null): mixed;

    public function renewDomain(string $domain, array $body, ?string $xShopperId = null): mixed;

    public function renewDomainForCustomer(string $customerId, string $domain, array $body, ?string $xRequestId = null): mixed;

    public function transferDomain(string $domain, array $body, ?string $xShopperId = null): mixed;

    public function transferDomainForCustomer(string $customerId, string $domain, array $body, ?string $xRequestId = null): mixed;

    public function getDomain(string $domain, ?string $xShopperId = null, ?array $includes = null): mixed;

    public function getDomainForCustomer(string $customerId, string $domain, ?string $xRequestId = null, ?array $includes = null): mixed;

    public function setDomainNameservers(string $domain, array $body, ?string $xShopperId = null): mixed;

    public function setDomainNameserversForCustomer(string $customerId, string $domain, array $body, ?string $xRequestId = null): mixed;

    public function recordAdd(string $domain, array $records, ?string $xShopperId = null): mixed;

    public function recordReplaceTypeName(string $domain, string $type, string $name, array $records, ?string $xShopperId = null): mixed;

    public function recordDeleteTypeName(string $domain, string $type, string $name, ?string $xShopperId = null): mixed;

    public function getDomainTransfer(string $domain, ?string $xShopperId = null): mixed;

    public function getDomainTransferForCustomer(string $customerId, string $domain, ?string $xRequestId = null): mixed;
}
