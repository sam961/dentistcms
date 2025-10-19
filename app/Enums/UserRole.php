<?php

namespace App\Enums;

enum UserRole: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
    case DENTIST = 'dentist';
    case RECEPTIONIST = 'receptionist';

    public function label(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Super Admin',
            self::ADMIN => 'Admin',
            self::DENTIST => 'Dentist',
            self::RECEPTIONIST => 'Receptionist',
        };
    }

    public function permissions(): array
    {
        return match ($this) {
            self::SUPER_ADMIN => ['*'],
            self::ADMIN => ['manage_users', 'manage_patients', 'manage_appointments', 'manage_invoices', 'view_reports'],
            self::DENTIST => ['manage_patients', 'manage_appointments', 'view_reports'],
            self::RECEPTIONIST => ['manage_appointments', 'view_patients'],
        };
    }
}
