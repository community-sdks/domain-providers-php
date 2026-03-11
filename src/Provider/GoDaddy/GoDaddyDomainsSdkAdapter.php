<?php
declare(strict_types=1);

namespace DomainProviders\Provider\GoDaddy;

use CommunitySDKs\GoDaddy\Client;
use CommunitySDKs\GoDaddy\Dto\Domains\Request\CustomerDomainBodyRequest;
use CommunitySDKs\GoDaddy\Dto\Domains\Request\CustomerDomainIncludesRequest;
use CommunitySDKs\GoDaddy\Dto\Domains\Request\CustomerDomainRequest;
use CommunitySDKs\GoDaddy\Dto\Domains\Request\CustomerRegisterRequest;
use CommunitySDKs\GoDaddy\Dto\Domains\Request\DomainAvailabilityRequest;
use CommunitySDKs\GoDaddy\Dto\Domains\Request\DomainBodyRequest;
use CommunitySDKs\GoDaddy\Dto\Domains\Request\DomainsAgreementRequest;
use CommunitySDKs\GoDaddy\Dto\Domains\Request\DomainsListRequest;
use CommunitySDKs\GoDaddy\Dto\Domains\Request\DomainsTldsRequest;
use CommunitySDKs\GoDaddy\Dto\Domains\Request\DomainTypeNameBodyRequest;
use CommunitySDKs\GoDaddy\Dto\Domains\Request\DomainTypeNameLookupRequest;
use CommunitySDKs\GoDaddy\Dto\Domains\Response\DomainAgreementResponse;
use CommunitySDKs\GoDaddy\Dto\Domains\Response\DomainCollectionResponse;
use CommunitySDKs\GoDaddy\Dto\Domains\Response\DomainOperationResponse;
use CommunitySDKs\GoDaddy\Dto\Domains\Response\DomainOrderResponse;
use CommunitySDKs\GoDaddy\Dto\Domains\Response\DomainRecordListResponse;
use CommunitySDKs\GoDaddy\Dto\Domains\Response\DomainTldListResponse;
use CommunitySDKs\GoDaddy\Dto\Domains\Response\DomainTransferResponse;

final class GoDaddyDomainsSdkAdapter implements GoDaddyDomainsApiInterface
{
    public function __construct(private readonly Client $client)
    {
    }

    public function available(string $domain, ?string $checkType = null, ?bool $forTransfer = null): mixed
    {
        $response = $this->client->domains()->available(new DomainAvailabilityRequest(
            domain: $domain,
            checkType: $checkType,
            forTransfer: $forTransfer,
        ));

        return $this->normalizeResponse($response);
    }

    public function tlds(): mixed
    {
        $response = $this->client->domains()->tlds(new DomainsTldsRequest());

        return $this->normalizeResponse($response);
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
        $response = $this->client->domains()->list(new DomainsListRequest(
            xShopperId: $xShopperId,
            statuses: $statuses,
            statusGroups: $statusGroups,
            limit: $limit,
            marker: $marker,
            includes: $includes,
            modifiedDate: $modifiedDate,
        ));

        return $this->normalizeResponse($response);
    }

    public function getAgreement(array $tlds, bool $privacy, ?string $xMarketId = null, ?bool $forTransfer = null): mixed
    {
        $response = $this->client->domains()->getAgreement(new DomainsAgreementRequest(
            tlds: $tlds,
            privacy: $privacy,
            xMarketId: $xMarketId,
            forTransfer: $forTransfer,
        ));

        return $this->normalizeResponse($response);
    }

    public function registerDomainForCustomer(string $customerId, array $body, ?string $xRequestId = null): mixed
    {
        $response = $this->client->domains()->registerCustomerDomain(new CustomerRegisterRequest(
            customerId: $customerId,
            body: $body,
            xRequestId: $xRequestId,
        ));

        return $this->normalizeResponse($response, forceOk: true);
    }

    public function renewDomainForCustomer(string $customerId, string $domain, array $body, ?string $xRequestId = null): mixed
    {
        $response = $this->client->domains()->renewCustomerDomain(new CustomerDomainBodyRequest(
            customerId: $customerId,
            domain: $domain,
            body: $body,
            xRequestId: $xRequestId,
        ));

        return $this->normalizeResponse($response, forceOk: true);
    }

    public function transferDomainForCustomer(string $customerId, string $domain, array $body, ?string $xRequestId = null): mixed
    {
        $response = $this->client->domains()->transferCustomerDomain(new CustomerDomainBodyRequest(
            customerId: $customerId,
            domain: $domain,
            body: $body,
            xRequestId: $xRequestId,
        ));

        return $this->normalizeResponse($response, forceOk: true);
    }

    public function getDomainForCustomer(string $customerId, string $domain, ?string $xRequestId = null, ?array $includes = null): mixed
    {
        $response = $this->client->domains()->getCustomerDomain(new CustomerDomainIncludesRequest(
            customerId: $customerId,
            domain: $domain,
            xRequestId: $xRequestId,
            includes: $includes,
        ));

        return $this->normalizeResponse($response);
    }

    public function setDomainNameserversForCustomer(string $customerId, string $domain, array $body, ?string $xRequestId = null): mixed
    {
        $response = $this->client->domains()->replaceCustomerDomainNameServers(new CustomerDomainBodyRequest(
            customerId: $customerId,
            domain: $domain,
            body: $body,
            xRequestId: $xRequestId,
        ));

        return $this->normalizeResponse($response, forceOk: true);
    }

    public function recordAdd(string $domain, array $records, ?string $xShopperId = null): mixed
    {
        $response = $this->client->domains()->recordAdd(new DomainBodyRequest(
            domain: $domain,
            body: $records,
            xShopperId: $xShopperId,
        ));

        return $this->normalizeResponse($response, forceOk: true);
    }

    public function recordReplaceTypeName(string $domain, string $type, string $name, array $records, ?string $xShopperId = null): mixed
    {
        $response = $this->client->domains()->recordReplaceTypeName(new DomainTypeNameBodyRequest(
            domain: $domain,
            type: $type,
            name: $name,
            body: $records,
            xShopperId: $xShopperId,
        ));

        return $this->normalizeResponse($response, forceOk: true);
    }

    public function recordDeleteTypeName(string $domain, string $type, string $name, ?string $xShopperId = null): mixed
    {
        $response = $this->client->domains()->recordDeleteTypeName(new DomainTypeNameLookupRequest(
            domain: $domain,
            type: $type,
            name: $name,
            xShopperId: $xShopperId,
        ));

        return $this->normalizeResponse($response, forceOk: true);
    }

    public function getDomainTransferForCustomer(string $customerId, string $domain, ?string $xRequestId = null): mixed
    {
        $response = $this->client->domains()->getCustomerDomainTransferStatus(new CustomerDomainRequest(
            customerId: $customerId,
            domain: $domain,
            xRequestId: $xRequestId,
        ));

        return $this->normalizeResponse($response);
    }

    /**
     * @return array<string, mixed>
     */
    private function normalizeResponse(mixed $response, bool $forceOk = false): array
    {
        $data = match (true) {
            $response instanceof DomainCollectionResponse => array_map(
                static fn (object $item): array => get_object_vars($item),
                $response->items
            ),
            $response instanceof DomainTldListResponse => array_map(
                static fn (object $item): array => get_object_vars($item),
                $response->tlds
            ),
            $response instanceof DomainAgreementResponse => [
                'agreementKeys' => array_values(array_filter(array_map(
                    static fn (object $item): string => (string) ($item->agreementKey ?? ''),
                    $response->agreements
                ))),
                'agreements' => array_map(static fn (object $item): array => get_object_vars($item), $response->agreements),
            ],
            $response instanceof DomainRecordListResponse => [
                'records' => array_map(static fn (object $item): array => get_object_vars($item), $response->records),
            ],
            default => is_object($response) ? get_object_vars($response) : (is_array($response) ? $response : []),
        };

        if ($response instanceof DomainTransferResponse && isset($data['transferStatus']) && !isset($data['status'])) {
            $data['status'] = $data['transferStatus'];
        }

        $payload = ['data' => $data];

        if ($forceOk || $response instanceof DomainOrderResponse || $response instanceof DomainOperationResponse) {
            $payload['ok'] = true;
        }

        return $payload;
    }
}
