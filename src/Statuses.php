<?php
declare(strict_types=1);

namespace DomainProviders;

final class Statuses
{
    public const DRAFT = 'draft';
    public const PENDING = 'pending';
    public const ACTIVE = 'active';
    public const EXPIRED = 'expired';
    public const TRANSFER_PENDING = 'transfer_pending';
    public const TRANSFERRED = 'transferred';
    public const SUSPENDED = 'suspended';
    public const DELETED = 'deleted';
    public const UNKNOWN = 'unknown';
}
