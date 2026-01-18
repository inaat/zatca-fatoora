<?php

namespace SaudiEv\Fatoora\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \SaudiEv\Fatoora\Invoice\InvoiceGenerator invoice()
 * @method static \SaudiEv\Fatoora\OnBoarding onboarding()
 * @method static \SaudiEv\Fatoora\Models\ZatcaDocument|null getDocumentByUuid(string $uuid)
 * @method static \SaudiEv\Fatoora\Models\ZatcaDocument|null getDocumentByInvoiceId(int $invoiceId)
 * @method static \Illuminate\Database\Eloquent\Collection getPendingDocuments()
 * @method static \SaudiEv\Fatoora\ZatcaConfig config()
 *
 * @see \SaudiEv\Fatoora\Fatoora
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
