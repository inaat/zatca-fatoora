<?php

namespace Saudiza\Fatoora;

use Saudiza\Fatoora\Invoice\InvoiceGenerator;
use Saudiza\Fatoora\OnBoarding;
use Saudiza\Fatoora\Models\ZatcaDocument;

/**
 * Main ZATCA class - Facade for package functionality
 */
class Zatca
{
    /**
     * Create a new invoice generator instance
     *
     * @return InvoiceGenerator
     */
    public function invoice(): InvoiceGenerator
    {
        $generator = new InvoiceGenerator();

        // Set defaults from config
        $generator->setZatcaEnv(config('zatca.environment', 'sandbox'));
        $generator->setZatcaLang(config('zatca.language', 'en'));

        // Set certificates if available
        $env = config('zatca.environment');
        if ($env === 'production') {
            $certificate = config('zatca.certificate.production_certificate');
            $secret = config('zatca.certificate.production_secret');
        } else {
            $certificate = config('zatca.certificate.compliance_certificate');
            $secret = config('zatca.certificate.compliance_secret');
        }

        $privateKey = config('zatca.certificate.private_key');

        if ($certificate && $secret && $privateKey) {
            $generator->setCertificateEncoded($certificate);
            $generator->setCertificateSecret($secret);
            $generator->setPrivateKeyEncoded($privateKey);
        }

        return $generator;
    }

    /**
     * Create a new onboarding instance
     *
     * @return OnBoarding
     */
    public function onboarding(): OnBoarding
    {
        $onboarding = new OnBoarding();

        // Set defaults from config
        $onboarding->setZatcaEnv(config('zatca.environment', 'sandbox'));
        $onboarding->setZatcaLang(config('zatca.language', 'en'));

        // Set organization details if available
        $org = config('zatca.organization');
        $egs = config('zatca.egs');

        if (!empty($org['email_address'])) {
            $onboarding->setEmailAddress($org['email_address']);
        }
        if (!empty($org['common_name'])) {
            $onboarding->setCommonName($org['common_name']);
        }
        if (!empty($org['country_code'])) {
            $onboarding->setCountryCode($org['country_code']);
        }
        if (!empty($org['organization_unit_name'])) {
            $onboarding->setOrganizationUnitName($org['organization_unit_name']);
        }
        if (!empty($org['organization_name'])) {
            $onboarding->setOrganizationName($org['organization_name']);
        }
        if (!empty($egs['serial_number'])) {
            $onboarding->setEgsSerialNumber($egs['serial_number']);
        }
        if (!empty($org['vat_number'])) {
            $onboarding->setVatNumber($org['vat_number']);
        }
        if (!empty($egs['invoice_type'])) {
            $onboarding->setInvoiceType($egs['invoice_type']);
        }
        if (!empty($org['registered_address'])) {
            $onboarding->setRegisteredAddress($org['registered_address']);
        }
        if (!empty($org['business_category'])) {
            $onboarding->setBusinessCategory($org['business_category']);
        }

        return $onboarding;
    }

    /**
     * Get ZATCA document by UUID
     *
     * @param string $uuid
     * @return ZatcaDocument|null
     */
    public function getDocumentByUuid(string $uuid): ?ZatcaDocument
    {
        return ZatcaDocument::where('uuid', $uuid)->first();
    }

    /**
     * Get ZATCA document by invoice ID
     *
     * @param int $invoiceId
     * @return ZatcaDocument|null
     */
    public function getDocumentByInvoiceId(int $invoiceId): ?ZatcaDocument
    {
        return ZatcaDocument::where('invoice_id', $invoiceId)->first();
    }

    /**
     * Get all pending ZATCA documents
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPendingDocuments()
    {
        return ZatcaDocument::where('sent_to_zatca', false)
            ->orWhereNull('sent_to_zatca')
            ->get();
    }

    /**
     * Get configuration helper
     *
     * @return ZatcaConfig
     */
    public function config(): ZatcaConfig
    {
        return new ZatcaConfig();
    }
}
