<?php

namespace Saudiza\Fatoora\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Saudiza\Fatoora\Invoice\InvoiceGenerator invoice()
 * @method static \Saudiza\Fatoora\OnBoarding onboarding()
 * @method static \Saudiza\Fatoora\Models\ZatcaDocument|null getDocumentByUuid(string $uuid)
 * @method static \Saudiza\Fatoora\Models\ZatcaDocument|null getDocumentByInvoiceId(int $invoiceId)
 * @method static \Illuminate\Database\Eloquent\Collection getPendingDocuments()
 * @method static \Saudiza\Fatoora\ZatcaConfig config()
 *
 * @see \Saudiza\Fatoora\Fatoora
 */
class Fatoora extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'fatoora';
    }
}
