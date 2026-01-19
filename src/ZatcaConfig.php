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
     * @param string $env
     * @return string
     */
    public static function baseUrl(string $env): string
    {
        return "https://gw-fatoora.zatca.gov.sa/e-invoicing/$env";
    }

    /**
     * Get available ZATCA environments
     *
     * @return array
     */
    public static function getEnvironments(): array
    {
        return [
            'developer-portal',
            'simulation',
            'core'
        ];
    }

    /**
     * Get zatca certificate templates
     *
     * @param string $env
     * @return string
     */
    public static function getCertificateTemplates(string $env): string
    {
        $templates = [
            'developer-portal' => 'TSTZATCA-Code-Signing',
            'simulation' => 'PREZATCA-Code-Signing',
            'core' => 'ZATCA-Code-Signing',
        ];

        return $templates[$env];
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
