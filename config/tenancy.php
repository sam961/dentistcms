<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Application Domain
    |--------------------------------------------------------------------------
    |
    | This value is the base domain used for multi-tenancy.
    | Each tenant will have a subdomain like: {tenant}.{domain}
    |
    | Examples:
    | - Local development: dentistcms.test
    | - Production: dentistcms.com
    |
    */

    'domain' => env('APP_DOMAIN', 'dentistcms.test'),

    /*
    |--------------------------------------------------------------------------
    | Reserved Subdomains
    |--------------------------------------------------------------------------
    |
    | These subdomains are reserved and cannot be used by tenants.
    |
    */

    'reserved_subdomains' => [
        'www',
        'admin',
        'api',
        'mail',
        'ftp',
        'localhost',
        'webmail',
        'smtp',
        'pop',
        'ns1',
        'ns2',
        'cpanel',
        'whm',
        'webdisk',
        'blog',
        'shop',
    ],

    /*
    |--------------------------------------------------------------------------
    | Tenant Identification
    |--------------------------------------------------------------------------
    |
    | Configuration for how tenants are identified from requests.
    |
    */

    'identification' => [
        'method' => 'subdomain', // subdomain, domain, or path
    ],
];
