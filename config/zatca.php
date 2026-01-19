<?php

return [
    /*
    |--------------------------------------------------------------------------
    | ZATCA Environment
    |--------------------------------------------------------------------------
    |
    | Supported: "production", "sandbox", "simulation", "developer-portal"
    |
    */
    'environment' => env('ZATCA_ENVIRONMENT', 'sandbox'),

    /*
    |--------------------------------------------------------------------------
    | ZATCA API Language
    |--------------------------------------------------------------------------
    |
    | The language for API response messages
    | Supported: "en", "ar"
    |
    */
    'language' => env('ZATCA_LANGUAGE', 'en'),

    /*
    |--------------------------------------------------------------------------
    | Certificate Configuration
    |--------------------------------------------------------------------------
    |
    | Your ZATCA certificate and authentication details
    |
    */
    'certificate' => [
        'compliance_certificate' => env('ZATCA_COMPLIANCE_CERTIFICATE'),
        'compliance_secret' => env('ZATCA_COMPLIANCE_SECRET'),
        'production_certificate' => env('ZATCA_PRODUCTION_CERTIFICATE'),
        'production_secret' => env('ZATCA_PRODUCTION_SECRET'),
        'private_key' => env('ZATCA_PRIVATE_KEY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Organization Details
    |--------------------------------------------------------------------------
    |
    | Your organization information for ZATCA onboarding
    |
    */
    'organization' => [
        'vat_number' => env('ZATCA_VAT_NUMBER'),
        'vat_name' => env('ZATCA_VAT_NAME'),
        'common_name' => env('ZATCA_COMMON_NAME'),
        'country_code' => env('ZATCA_COUNTRY_CODE', 'SA'),
        'organization_name' => env('ZATCA_ORGANIZATION_NAME'),
        'organization_unit_name' => env('ZATCA_ORGANIZATION_UNIT_NAME'),
        'registered_address' => env('ZATCA_REGISTERED_ADDRESS'),
        'business_category' => env('ZATCA_BUSINESS_CATEGORY'),
        'email_address' => env('ZATCA_EMAIL_ADDRESS'),
    ],

    /*
    |--------------------------------------------------------------------------
    | EGS (E-Invoice Generation System) Configuration
    |--------------------------------------------------------------------------
    */
    'egs' => [
        'serial_number' => env('ZATCA_EGS_SERIAL_NUMBER'),
        'invoice_type' => env('ZATCA_INVOICE_TYPE', '1000'), // 1000: Standard, 0100: Simplified
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Configuration
    |--------------------------------------------------------------------------
    |
    | Table name for storing ZATCA documents
    |
    */
    'database' => [
        'table_name' => 'zatca_documents',
    ],

    /*
    |--------------------------------------------------------------------------
    | API Endpoints
    |--------------------------------------------------------------------------
    |
    | ZATCA API base URLs for different environments
    |
    */
    'api' => [
        'production' => 'https://gw-fatoora.zatca.gov.sa/e-invoicing/core',
        'sandbox' => 'https://gw-fatoora.zatca.gov.sa/e-invoicing/simulation',
        'simulation' => 'https://gw-fatoora.zatca.gov.sa/e-invoicing/simulation',
        'developer-portal' => 'https://gw-fatoora.zatca.gov.sa/e-invoicing/developer-portal',
    ],

    /*
    |--------------------------------------------------------------------------
    | Certificate Templates
    |--------------------------------------------------------------------------
    |
    | Certificate template names for different environments
    |
    */
    'certificate_templates' => [
        'production' => 'ZATCA-Code-Signing',
        'sandbox' => 'PREZATCA-Code-Signing',
        'simulation' => 'PREZATCA-Code-Signing',
        'developer-portal' => 'PREZATCA-Code-Signing',
    ],

    /*
    |--------------------------------------------------------------------------
    | Invoice Types
    |--------------------------------------------------------------------------
    |
    | 0200000: Simplified Invoice (B2C)
    | 0100000: Standard Invoice (B2B)
    |
    */
    'invoice_types' => [
        'simplified' => '0200000',
        'standard' => '0100000',
    ],
];
