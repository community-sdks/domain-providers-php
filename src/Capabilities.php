<?php
declare(strict_types=1);

namespace DomainProviders;

final class Capabilities
{
    public const AVAILABILITY_CHECK = 'availability_check';
    public const DOMAIN_REGISTRATION = 'domain_registration';
    public const DOMAIN_RENEWAL = 'domain_renewal';
    public const DOMAIN_TRANSFER = 'domain_transfer';
    public const DOMAIN_INFO = 'domain_info';
    public const DOMAIN_LISTING = 'domain_listing';
    public const NAMESERVER_READ = 'nameserver_read';
    public const NAMESERVER_UPDATE = 'nameserver_update';
    public const DNS_RECORD_LIST = 'dns_record_list';
    public const DNS_RECORD_CREATE = 'dns_record_create';
    public const DNS_RECORD_UPDATE = 'dns_record_update';
    public const DNS_RECORD_DELETE = 'dns_record_delete';
    public const PRICING_LOOKUP = 'pricing_lookup';
}
