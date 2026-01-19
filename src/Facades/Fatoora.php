<?php

namespace Saudiza\Fatoora\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Saudiza\Fatoora\Invoice\InvoiceGenerator invoice()
 * @method static \Saudiza\Fatoora\OnBoarding onboarding()
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
