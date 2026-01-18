<?php

namespace Saudiza\Fatoora;

use Exception;

/**
 * ZATCA configuration utility class
 */
class ZatcaConfig
{
    /**
     * Get base URL based on ZATCA environment
     *
     * @param string|null $env
     * @return string
     */
    public static function baseUrl(?string $env = null): string
    {
        $env = $env ?? config('zatca.environment', 'sandbox');

        $urls = config('zatca.api', [
            'production' => 'https://gw-fatoora.zatca.gov.sa/e-invoicing/core',
            'sandbox' => 'https://gw-apic-gov.gazt.gov.sa/e-invoicing/core',
            'simulation' => 'https://gw-apic-gov.gazt.gov.sa/e-invoicing/simulation',
            'developer-portal' => 'https://gw-apic-gov.gazt.gov.sa/e-invoicing/developer-portal',
        ]);

        return $urls[$env] ?? $urls['sandbox'];
    }

    /**
     * Get available ZATCA environments
     *
     * @return array
     */
    public static function getEnvironments(): array
    {
        return [
            'production',
            'sandbox',
            'simulation',
            'developer-portal',
        ];
    }

    /**
     * Get certificate template name for environment
     *
     * @param string|null $env
     * @return string
     */
    public static function getCertificateTemplate(?string $env = null): string
    {
        $env = $env ?? config('zatca.environment', 'sandbox');

        $templates = config('zatca.certificate_templates', [
            'production' => 'ZATCA-Code-Signing',
            'sandbox' => 'PREZATCA-Code-Signing',
            'simulation' => 'PREZATCA-Code-Signing',
            'developer-portal' => 'TSTZATCA-Code-Signing',
        ]);

        return $templates[$env] ?? $templates['sandbox'];
    }

    /**
     * Get valid invoice types
     *
     * @return array
     */
    public static function getInvoiceTypes(): array
    {
        return [
            '1100', // Both simplified and standard
            '0100', // Simplified only
            '1000', // Standard only
        ];
    }

    /**
     * Get invoice type code
     *
     * @param string $type 'simplified' or 'standard'
     * @return string
     */
    public static function getInvoiceTypeCode(string $type): string
    {
        $types = config('zatca.invoice_types', [
            'simplified' => '0200000',
            'standard' => '0100000',
        ]);

        return $types[$type] ?? $types['standard'];
    }

    /**
     * Check if environment is production
     *
     * @param string|null $env
     * @return bool
     */
    public static function isProduction(?string $env = null): bool
    {
        $env = $env ?? config('zatca.environment', 'sandbox');
        return $env === 'production';
    }

    /**
     * Get configuration value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        return config("zatca.{$key}", $default);
    }
}
