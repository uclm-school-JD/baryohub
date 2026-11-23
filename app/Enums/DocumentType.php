<?php

namespace App\Enums;

enum DocumentType: string
{
    case BarangayClearance = 'barangay_clearance';
    case CertificateOfResidency = 'certificate_of_residency';
    case CertificateOfIndigency = 'certificate_of_indigency';

    public function label(): string
    {
        return match ($this) {
            self::BarangayClearance => 'Barangay clearance',
            self::CertificateOfResidency => 'Certificate of residency',
            self::CertificateOfIndigency => 'Certificate of indigency',
        };
    }
}
