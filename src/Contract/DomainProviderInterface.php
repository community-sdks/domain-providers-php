<?php
declare(strict_types=1);

namespace DomainProviders\Contract;

use DomainProviders\DTO\AvailabilityResult;
use DomainProviders\DTO\DnsRecord;
use DomainProviders\DTO\DomainContact;
use DomainProviders\DTO\DomainInfo;
use DomainProviders\DTO\DomainName;
use DomainProviders\DTO\DomainPrice;
use DomainProviders\DTO\DomainRegistrationResult;
use DomainProviders\DTO\DomainRegistrationPeriod;
use DomainProviders\DTO\DomainRenewalResult;
use DomainProviders\DTO\DomainTransferResult;
use DomainProviders\DTO\DnsRecordCreateResult;
use DomainProviders\DTO\DnsRecordDeleteResult;
use DomainProviders\DTO\DnsRecordUpdateResult;
use DomainProviders\DTO\NameserverSet;
use DomainProviders\DTO\NameserverUpdateResult;
use DomainProviders\DTO\ProviderMetadata;
use DomainProviders\DTO\ProviderRequestContext;
use DomainProviders\DTO\TransferAvailabilityResult;

interface DomainProviderInterface
{
    public function metadata(): ProviderMetadata;

    public function supports(string $capability): bool;

    public function checkAvailability(DomainName $domain): AvailabilityResult;

    public function registerDomain(
        DomainName $domain,
        DomainRegistrationPeriod $period,
        DomainContact $registrantContact,
        ?NameserverSet $nameservers = null,
        ?bool $privacyEnabled = null,
        ?string $marketId = null,
        ?ProviderRequestContext $context = null,
    ): DomainRegistrationResult;

    public function renewDomain(DomainName $domain, DomainRegistrationPeriod $period): DomainRenewalResult;

    public function transferDomain(
        DomainName $domain,
        string $authCode,
        ?DomainContact $registrantContact = null,
    ): DomainTransferResult;

    public function getDomainInfo(DomainName $domain): DomainInfo;

    /** @return list<DomainInfo> */
    public function listDomains(?int $page = null, ?int $pageSize = null, ?string $status = null, ?string $shopperId = null, ?ProviderRequestContext $context = null): array;

    public function getNameservers(DomainName $domain): NameserverSet;

    public function setNameservers(DomainName $domain, NameserverSet $nameservers): NameserverUpdateResult;

    /** @return list<DnsRecord> */
    public function listDnsRecords(DomainName $domain): array;

    public function createDnsRecord(DomainName $domain, DnsRecord $record, ?string $shopperId = null, ?ProviderRequestContext $context = null): DnsRecordCreateResult;

    public function updateDnsRecord(DomainName $domain, DnsRecord $record, ?string $shopperId = null, ?ProviderRequestContext $context = null): DnsRecordUpdateResult;

    public function deleteDnsRecord(DomainName $domain, ?string $recordId = null, ?DnsRecord $matchRecord = null, ?string $shopperId = null, ?ProviderRequestContext $context = null): DnsRecordDeleteResult;

    public function getDomainPricing(
        ?DomainName $domain = null,
        ?string $tld = null,
        ?DomainRegistrationPeriod $period = null,
    ): DomainPrice;

    public function checkTransferAvailability(DomainName $domain): TransferAvailabilityResult;
}
