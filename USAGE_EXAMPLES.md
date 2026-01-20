# ZATCA Laravel - Usage Examples

This document provides detailed examples for common use cases.

## Table of Contents

1. [Complete Standard Invoice Example](#complete-standard-invoice-example)
2. [Simplified Invoice Example](#simplified-invoice-example)
3. [Credit Note Example](#credit-note-example)
4. [Multiple Line Items](#multiple-line-items)
5. [Discount Application](#discount-application)
6. [Integration with Existing Invoice System](#integration-with-existing-invoice-system)
7. [Batch Processing](#batch-processing)
8. [Error Handling](#error-handling)

## Complete Standard Invoice Example

```php
<?php

use Saudiza\Fatoora\Facades\Fatoora;
use Saudiza\Fatoora\Invoice\Supplier;
use Saudiza\Fatoora\Invoice\Client;
use Saudiza\Fatoora\Invoice\InvoiceLine;
use Saudiza\Fatoora\Invoice\TaxesTotal;
use Saudiza\Fatoora\Invoice\TaxSubtotal;
use Saudiza\Fatoora\Invoice\LegalMonetaryTotal;
use Saudiza\Fatoora\Invoice\PIH;
use Saudiza\Fatoora\Invoice\AdditionalDocumentReference;
use Saudiza\Fatoora\Invoice\Delivery;
use Saudiza\Fatoora\Invoice\PaymentType;
use Illuminate\Support\Str;

function generateStandardInvoice($invoiceData)
{
    // Initialize invoice generator
    $invoice = Fatoora::invoice();

    // Set invoice header
    $invoice->setInvoiceNumber($invoiceData['invoice_number'])
        ->setInvoiceUuid(Str::uuid()->toString())
        ->setInvoiceIssueDate(now()->format('Y-m-d'))
        ->setInvoiceIssueTime(now()->format('H:i:s'))
        ->setInvoiceType('0100000', '388') // Standard invoice
        ->setInvoiceCurrencyCode('SAR')
        ->setInvoiceTaxCurrencyCode('SAR');

    // Configure supplier
    $supplier = (new Supplier())
        ->setVatName(config('zatca.organization.vat_name'))
        ->setVatNumber(config('zatca.organization.vat_number'))
        ->setCityName('Riyadh')
        ->setPostalCode('12345')
        ->setBuildingNumber('1234')
        ->setStreet('King Fahd Road')
        ->setDistrict('Al Olaya')
        ->setAdditionalStreet('Near Landmark')
        ->setCountryCode('SA');

    $invoice->setInvoiceSupplier($supplier);

    // Configure client
    $client = (new Client())
        ->setBuyerName($invoiceData['customer_name'])
        ->setBuyerVatNumber($invoiceData['customer_vat'])
        ->setCityName($invoiceData['city'])
        ->setPostalCode($invoiceData['postal_code'])
        ->setBuildingNumber($invoiceData['building_number'])
        ->setStreet($invoiceData['street'])
        ->setDistrict($invoiceData['district'])
        ->setCountryCode('SA');

    $invoice->setInvoiceClient($client);

    // Add line items
    $lineItems = [];
    $subtotal = 0;
    $totalTax = 0;

    foreach ($invoiceData['items'] as $index => $item) {
        $lineAmount = $item['quantity'] * $item['unit_price'];
        $taxAmount = $lineAmount * ($item['tax_rate'] / 100);

        $lineItem = (new InvoiceLine())
            ->setId((string)($index + 1))
            ->setQuantity($item['quantity'])
            ->setItemName($item['name'])
            ->setItemPrice($item['unit_price'])
            ->setLineExtensionAmount($lineAmount)
            ->setTaxCategory('S', $item['tax_rate'], 'VAT');

        $lineItems[] = $lineItem;
        $subtotal += $lineAmount;
        $totalTax += $taxAmount;
    }

    $invoice->setInvoiceLines(...$lineItems);

    // Set tax totals
    $taxTotal = (new TaxesTotal())
        ->setTaxTotal($totalTax);

    $taxSubtotal = (new TaxSubtotal())
        ->setTaxableAmount($subtotal)
        ->setTaxAmount($totalTax)
        ->setTaxCategory('S', 15, 'VAT');

    $invoice->setInvoiceTaxesTotal($taxTotal)
        ->setInvoiceTaxSubTotal($taxSubtotal);

    // Set monetary totals
    $total = $subtotal + $totalTax;

    $legalMonetary = (new LegalMonetaryTotal())
        ->setLineExtensionAmount($subtotal)
        ->setTaxExclusiveAmount($subtotal)
        ->setTaxInclusiveAmount($total)
        ->setPayableAmount($total);

    $invoice->setInvoiceLegalMonetaryTotal($legalMonetary);

    // Set delivery
    $delivery = (new Delivery())
        ->setActualDeliveryDate(now()->format('Y-m-d'));
    $invoice->setInvoiceDelivery($delivery);

    // Set payment method
    $paymentType = (new PaymentType())
        ->setPaymentMeansCode($invoiceData['payment_method'] ?? '10');
    $invoice->setInvoicePaymentType($paymentType);

    // Get previous invoice hash
    $lastDocument = \Saudiza\Fatoora\Models\ZatcaDocument::latest()->first();
    $previousHash = $lastDocument
        ? $lastDocument->hash
        : 'NWZlY2ViNjZmZmM4NmYzOGQ5NTI3ODZjNmQ2OTZjNzljMmRiYzIzOWRkNGU5MWI0NjcyOWQ3M2EyN2ZiNTdlOQ==';

    $pih = (new PIH())->setPIH($previousHash);
    $invoice->setInvoicePIH($pih);

    // Set ICV (Invoice Counter Value)
    $icv = $lastDocument ? ($lastDocument->icv + 1) : 1;
    $additionalDoc = (new AdditionalDocumentReference())->setIcv((string)$icv);
    $invoice->setInvoiceAdditionalDocumentReference($additionalDoc);

    // Submit to ZATCA
    $isProduction = config('zatca.environment') === 'production';
    $result = $invoice->sendDocument($isProduction);

    if ($result['success']) {
        // Save to database
        $zatcaDoc = \Saudiza\Fatoora\Models\ZatcaDocument::create([
            'icv' => $icv,
            'uuid' => $invoice->getInvoiceUuid(),
            'hash' => $result['hash'],
            'xml' => $result['xml'],
            'sent_to_zatca' => true,
            'sent_to_zatca_status' => 'REPORTED',
            'signing_time' => $result['signing_time'],
            'response' => json_encode($result['response']),
            'qr_value' => $result['qr_value'],
            'type' => 'standard',
            'portal_mode' => config('zatca.environment'),
            'invoice_id' => $invoiceData['id'],
        ]);

        return [
            'success' => true,
            'zatca_document' => $zatcaDoc,
            'qr_code' => $result['qr_value'],
        ];
    }

    return [
        'success' => false,
        'error' => $result['response']->message ?? 'Unknown error',
        'details' => $result['response'],
    ];
}

// Usage
$invoiceData = [
    'id' => 123,
    'invoice_number' => 'INV-2024-001',
    'customer_name' => 'ABC Corporation',
    'customer_vat' => '300000000000004',
    'city' => 'Jeddah',
    'postal_code' => '23456',
    'building_number' => '5678',
    'street' => 'Prince Sultan Road',
    'district' => 'Al Salamah',
    'payment_method' => '10', // Cash
    'items' => [
        [
            'name' => 'Product A',
            'quantity' => 2,
            'unit_price' => 100.00,
            'tax_rate' => 15,
        ],
        [
            'name' => 'Product B',
            'quantity' => 1,
            'unit_price' => 500.00,
            'tax_rate' => 15,
        ],
    ],
];

$result = generateStandardInvoice($invoiceData);
```

## Simplified Invoice Example

```php
<?php

function generateSimplifiedInvoice($saleData)
{
    $invoice = Fatoora::invoice();

    // Simplified invoice type
    $invoice->setInvoiceNumber($saleData['invoice_number'])
        ->setInvoiceUuid(Str::uuid()->toString())
        ->setInvoiceIssueDate(now()->format('Y-m-d'))
        ->setInvoiceIssueTime(now()->format('H:i:s'))
        ->setInvoiceType('0200000', '388') // Simplified invoice
        ->setInvoiceCurrencyCode('SAR')
        ->setInvoiceTaxCurrencyCode('SAR');

    // Supplier only (no client details needed for B2C)
    $supplier = (new Supplier())
        ->setVatName(config('zatca.organization.vat_name'))
        ->setVatNumber(config('zatca.organization.vat_number'))
        ->setCityName('Riyadh')
        ->setPostalCode('12345')
        ->setBuildingNumber('1234')
        ->setStreet('King Fahd Road')
        ->setDistrict('Al Olaya');

    $invoice->setInvoiceSupplier($supplier);

    // Add items
    $items = [];
    $subtotal = 0;
    $totalTax = 0;

    foreach ($saleData['items'] as $index => $item) {
        $lineAmount = $item['quantity'] * $item['price'];
        $taxAmount = $lineAmount * 0.15; // 15% VAT

        $lineItem = (new InvoiceLine())
            ->setId((string)($index + 1))
            ->setQuantity($item['quantity'])
            ->setItemName($item['name'])
            ->setItemPrice($item['price'])
            ->setLineExtensionAmount($lineAmount)
            ->setTaxCategory('S', 15, 'VAT');

        $items[] = $lineItem;
        $subtotal += $lineAmount;
        $totalTax += $taxAmount;
    }

    $invoice->setInvoiceLines(...$items);

    // Continue with totals, delivery, payment, etc...
    // (similar to standard invoice)

    return $invoice->sendDocument(true);
}
```

## Credit Note Example

```php
<?php

use Saudiza\Fatoora\Invoice\BillingReference;
use Saudiza\Fatoora\Invoice\ReturnReason;

function generateCreditNote($originalInvoiceNumber, $returnData)
{
    $invoice = Fatoora::invoice();

    // Credit note type
    $invoice->setInvoiceNumber($returnData['credit_note_number'])
        ->setInvoiceUuid(Str::uuid()->toString())
        ->setInvoiceIssueDate(now()->format('Y-m-d'))
        ->setInvoiceIssueTime(now()->format('H:i:s'))
        ->setInvoiceType('0100000', '381') // Credit note
        ->setInvoiceCurrencyCode('SAR')
        ->setInvoiceTaxCurrencyCode('SAR');

    // Reference to original invoice
    $billingRef = (new BillingReference())
        ->setInvoiceDocumentReference(
            $originalInvoiceNumber,
            $returnData['original_invoice_date']
        );
    $invoice->setInvoiceBillingReference($billingRef);

    // Return reason
    $returnReason = (new ReturnReason())
        ->setReason($returnData['return_reason'] ?? 'Product return');
    $invoice->setInvoiceReturnReason($returnReason);

    // Set supplier, client, items, totals...
    // (similar to standard invoice but with negative amounts)

    return $invoice->sendDocument(true);
}
```

## Multiple Line Items

```php
<?php

$items = [];

foreach ($orderItems as $index => $item) {
    $lineItem = (new InvoiceLine())
        ->setId((string)($index + 1))
        ->setQuantity($item->quantity)
        ->setItemName($item->product_name)
        ->setItemPrice($item->unit_price)
        ->setLineExtensionAmount($item->quantity * $item->unit_price)
        ->setTaxCategory('S', 15, 'VAT');

    $items[] = $lineItem;
}

$invoice->setInvoiceLines(...$items);
```

## Discount Application

```php
<?php

use Saudiza\Fatoora\Invoice\AllowanceCharge;

// Add discount
$discount = (new AllowanceCharge())
    ->setChargeIndicator(false) // false = allowance (discount)
    ->setAllowanceChargeReason('Special discount')
    ->setAmount(50.00) // Discount amount
    ->setTaxCategory('S', 15, 'VAT');

$invoice->setInvoiceAllowanceCharges($discount);

// Update legal monetary total to reflect discount
$legalMonetary = (new LegalMonetaryTotal())
    ->setLineExtensionAmount($subtotal)
    ->setTaxExclusiveAmount($subtotal - 50.00) // After discount
    ->setAllowanceTotalAmount(50.00)
    ->setTaxInclusiveAmount(($subtotal - 50.00) + $totalTax)
    ->setPayableAmount(($subtotal - 50.00) + $totalTax);
```

## Integration with Existing Invoice System

```php
<?php

namespace App\Services;

use App\Models\Invoice;
use Saudiza\Fatoora\Facades\Fatoora;

class ZatcaIntegrationService
{
    public function submitInvoiceToZatca(Invoice $invoice)
    {
        // Check if already submitted
        if ($invoice->zatcaDocument()->exists()) {
            return [
                'success' => false,
                'message' => 'Invoice already submitted to ZATCA'
            ];
        }

        try {
            $zatcaInvoice = $this->convertToZatcaFormat($invoice);
            $result = $zatcaInvoice->sendDocument(true);

            if ($result['success']) {
                // Create ZATCA document record
                $zatcaDoc = $invoice->zatcaDocument()->create([
                    'icv' => $this->getNextIcv(),
                    'uuid' => $zatcaInvoice->getInvoiceUuid(),
                    'hash' => $result['hash'],
                    'xml' => $result['xml'],
                    'sent_to_zatca' => true,
                    'sent_to_zatca_status' => 'REPORTED',
                    'signing_time' => $result['signing_time'],
                    'response' => json_encode($result['response']),
                    'qr_value' => $result['qr_value'],
                    'type' => $invoice->type,
                    'portal_mode' => config('zatca.environment'),
                ]);

                // Update invoice with QR code
                $invoice->update([
                    'zatca_qr_code' => $result['qr_value'],
                    'zatca_status' => 'reported',
                ]);

                return ['success' => true, 'document' => $zatcaDoc];
            }

            return $result;

        } catch (\Exception $e) {
            \Log::error('ZATCA submission failed', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    protected function convertToZatcaFormat(Invoice $invoice)
    {
        // Convert your invoice model to ZATCA format
        $zatcaInvoice = Fatoora::invoice();

        // Map your invoice fields to ZATCA invoice
        // ...

        return $zatcaInvoice;
    }

    protected function getNextIcv(): int
    {
        return \Saudiza\Fatoora\Models\ZatcaDocument::max('icv') + 1;
    }
}
```

## Batch Processing

```php
<?php

use Illuminate\Support\Facades\Queue;
use App\Jobs\SubmitInvoiceToZatca;

// Queue job for batch processing
class SubmitInvoiceToZatca implements ShouldQueue
{
    protected $invoiceId;

    public function __construct($invoiceId)
    {
        $this->invoiceId = $invoiceId;
    }

    public function handle()
    {
        $invoice = Invoice::find($this->invoiceId);

        if (!$invoice) {
            return;
        }

        $service = new ZatcaIntegrationService();
        $result = $service->submitInvoiceToZatca($invoice);

        if (!$result['success']) {
            \Log::error('ZATCA batch submission failed', [
                'invoice_id' => $this->invoiceId,
                'error' => $result['message'] ?? 'Unknown error',
            ]);
        }
    }
}

// Batch submit pending invoices
$pendingInvoices = Invoice::where('zatca_status', 'pending')
    ->whereNull('zatca_document_id')
    ->get();

foreach ($pendingInvoices as $invoice) {
    SubmitInvoiceToZatca::dispatch($invoice->id);
}
```

## Error Handling

```php
<?php

try {
    $result = $invoice->sendDocument(true);

    if (!$result['success']) {
        // Parse ZATCA error response
        $response = $result['response'];

        if (isset($response->errors)) {
            foreach ($response->errors as $error) {
                \Log::error('ZATCA Error', [
                    'code' => $error->code ?? null,
                    'message' => $error->message ?? null,
                    'category' => $error->category ?? null,
                ]);
            }
        }

        // Handle specific error codes
        if (isset($response->code)) {
            switch ($response->code) {
                case 'Invalid-OTP':
                    return 'Invalid OTP provided';
                case 'Missing-ComplianceSteps':
                    return 'Compliance steps not completed';
                case 'Invalid-Signature':
                    return 'Digital signature validation failed';
                default:
                    return $response->message ?? 'Unknown error';
            }
        }
    }

} catch (\GuzzleHttp\Exception\ClientException $e) {
    \Log::error('HTTP Client Error', [
        'status' => $e->getResponse()->getStatusCode(),
        'body' => $e->getResponse()->getBody()->getContents(),
    ]);
} catch (\Exception $e) {
    \Log::error('General Error', [
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
    ]);
}
```

## Testing in Sandbox

```php
<?php

// Force sandbox environment for testing
config(['zatca.environment' => 'sandbox']);

$invoice = Fatoora::invoice();
// ... configure invoice

// Send to sandbox
$result = $invoice->sendDocument(false); // false = compliance mode

if ($result['success']) {
    echo "Sandbox test successful!";
}
```
