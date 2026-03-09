<?php
declare(strict_types=1);

namespace DomainProviders\DTO;

final class DomainContact
{
    public function __construct(
        public readonly string $fullName,
        public readonly ?string $organization,
        public readonly string $email,
        public readonly string $phone,
        public readonly string $addressLine1,
        public readonly ?string $addressLine2,
        public readonly string $city,
        public readonly ?string $stateOrRegion,
        public readonly string $postalCode,
        public readonly string $countryCode,
        public readonly ?string $taxId = null,
        public readonly ?string $contactType = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'full_name' => $this->fullName,
            'organization' => $this->organization,
            'email' => $this->email,
            'phone' => $this->phone,
            'address_line_1' => $this->addressLine1,
            'address_line_2' => $this->addressLine2,
            'city' => $this->city,
            'state_or_region' => $this->stateOrRegion,
            'postal_code' => $this->postalCode,
            'country_code' => strtoupper($this->countryCode),
            'tax_id' => $this->taxId,
            'contact_type' => $this->contactType,
        ];
    }
}
