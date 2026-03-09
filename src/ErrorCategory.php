<?php
declare(strict_types=1);

namespace DomainProviders;

final class ErrorCategory
{
    public const VALIDATION = 'validation';
    public const AUTHENTICATION = 'authentication';
    public const AUTHORIZATION = 'authorization';
    public const UNSUPPORTED_CAPABILITY = 'unsupported_capability';
    public const PROVIDER_COMMUNICATION = 'provider_communication';
    public const PROVIDER_TIMEOUT = 'provider_timeout';
    public const PROVIDER_RESPONSE_PARSING = 'provider_response_parsing';
    public const RATE_LIMIT = 'rate_limit';
    public const DOMAIN_NOT_FOUND = 'domain_not_found';
    public const DOMAIN_ALREADY_REGISTERED = 'domain_already_registered';
    public const DOMAIN_UNAVAILABLE = 'domain_unavailable';
    public const DNS_RECORD_NOT_FOUND = 'dns_record_not_found';
    public const UNKNOWN = 'unknown';
}
