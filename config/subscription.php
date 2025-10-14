<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Subscription Tiers
    |--------------------------------------------------------------------------
    |
    | Define the available subscription tiers - Paid only
    | Custom amount and duration set per subscription
    |
    */

    'tiers' => [
        'paid' => [
            'name' => 'Paid Plan',
            'price' => 0, // Custom amount set per subscription
            'description' => 'Full access - Custom pricing & duration',
            'features' => [
                'Unlimited users',
                'Unlimited patients',
                'Unlimited dentists',
                'Advanced reporting & analytics',
                'Priority support',
                'Dental chart management',
                'Invoice & billing',
                'Appointment reminders',
                'Custom branding',
            ],
            'limits' => [
                'max_users' => null, // unlimited
                'max_patients' => null,
                'max_dentists' => null,
                'max_appointments_per_month' => null,
            ],
        ],
    ],
];
