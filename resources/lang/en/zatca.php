<?php

return [
    'name' => 'ZATCA Integration',
    'description' => 'Saudi Arabia Tax Authority E-Invoice Integration',

    // Onboarding
    'onboarding' => [
        'success' => 'ZATCA onboarding completed successfully',
        'failed' => 'ZATCA onboarding failed',
        'invalid_otp' => 'Invalid OTP code',
        'missing_compliance' => 'Compliance steps not completed',
    ],

    // Invoice
    'invoice' => [
        'created' => 'Invoice created successfully',
        'submitted' => 'Invoice submitted to ZATCA',
        'failed' => 'Invoice submission failed',
        'invalid_signature' => 'Invalid digital signature',
    ],

    // Validation
    'validation' => [
        'vat_number_required' => 'VAT number is required',
        'certificate_required' => 'Certificate is required',
        'environment_required' => 'Environment is required',
    ],

    // Messages
    'messages' => [
        'certificate_saved' => 'Certificate saved successfully',
        'configuration_updated' => 'Configuration updated',
    ],
];
