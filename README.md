# 🇸🇦 Fatoora - Laravel ZATCA E-Invoice Integration

[![Latest Version](https://img.shields.io/packagist/v/saudiza/zatca-fatoora.svg?style=flat-square)](https://packagist.org/packages/saudiza/zatca-fatoora)
[![License](https://img.shields.io/packagist/l/saudiza/zatca-fatoora.svg?style=flat-square)](LICENSE)
[![Total Downloads](https://img.shields.io/packagist/dt/saudiza/zatca-fatoora.svg?style=flat-square)](https://packagist.org/packages/saudiza/zatca-fatoora)

> **Complete ZATCA Phase 2 e-invoicing integration for Laravel** - Saudi Arabia Tax Authority compliance made simple.

## Features

🚀 **Complete Phase 2 Support**
- ✅ Automated onboarding & certificate generation
- ✅ Standard invoices (B2B) & Simplified invoices (B2C)
- ✅ Credit notes & Debit notes
- ✅ Digital signatures & QR codes
- ✅ UBL 2.1 XML compliant

💼 **Laravel First**
- Laravel 9.x, 10.x & 11.x support
- Beautiful Facade API (`Fatoora::invoice()`)
- Artisan commands for setup
- Multiple environments (sandbox, production)

🔒 **Security**
- ECDSA secp256k1 signatures
- Certificate-based authentication
- Hash validation
- Cryptographic stamps

## Installation

```bash
composer require saudiza/zatca-fatoora
```

### Quick Setup

```bash
# 1. Install package
php artisan fatoora:install

# 2. Configure .env
ZATCA_ENVIRONMENT=sandbox
ZATCA_VAT_NUMBER="300000000000003"
# ... add other credentials

# 3. Run onboarding
php artisan zatca:onboarding --otp=YOUR_OTP
```

## Usage

### Generate Invoice

```php
use Saudiza\Fatoora\Facades\Fatoora;

// Create invoice
$invoice = Fatoora::invoice();

$invoice->setInvoiceNumber('INV-001')
    ->setInvoiceUuid(\Illuminate\Support\Str::uuid())
    ->setInvoiceIssueDate(now()->format('Y-m-d'))
    ->setInvoiceIssueTime(now()->format('H:i:s'))
    ->setInvoiceType('0100000', '388') // Standard invoice
    ->setInvoiceCurrencyCode('SAR')
    ->setInvoiceTaxCurrencyCode('SAR');

// Configure supplier
$supplier = new \Saudiza\Fatoora\Invoice\Supplier();
$supplier->setVatName('Your Company')
    ->setVatNumber('300000000000003')
    ->setCityName('Riyadh')
    ->setPostalCode('12345')
    ->setBuildingNumber('1234')
    ->setStreet('King Fahd Road')
    ->setDistrict('Al Olaya');

$invoice->setInvoiceSupplier($supplier);

// Add items, totals, etc... (see documentation)

// Submit to ZATCA
$result = $invoice->sendDocument(true);

if ($result['success']) {
    echo "✅ Invoice submitted! QR: " . $result['qr_value'];
}
```

### Using Facade Alias

```php
// You can use either:
Fatoora::invoice()  // New name
Zatca::invoice()    // Backward compatibility
```

## Configuration

All settings are in `config/zatca.php`:

```php
return [
    'environment' => env('ZATCA_ENVIRONMENT', 'sandbox'),
    'organization' => [
        'vat_number' => env('ZATCA_VAT_NUMBER'),
        // ... more settings
    ],
];
```

## Artisan Commands

```bash
# Install package
php artisan fatoora:install

# Run onboarding
php artisan zatca:onboarding --otp=YOUR_OTP

# Check configuration
php artisan tinker
>>> Fatoora::config()->get('environment')
```

## Invoice Types

| Type | Code | Description |
|------|------|-------------|
| Standard | `0100000` | Tax Invoice (B2B) |
| Simplified | `0200000` | Tax Invoice (B2C) |
| Credit Note | `381` | Credit Note |
| Debit Note | `383` | Debit Note |

## Environments

| Environment | Description |
|-------------|-------------|
| `sandbox` | Testing environment |
| `simulation` | Simulation environment |
| `production` | Live ZATCA system |

## Documentation

- 📖 [Installation Guide](INSTALLATION.md)
- 💡 [Usage Examples](USAGE_EXAMPLES.md)
- 📦 [Package Summary](PACKAGE_SUMMARY.md)
- 🚀 [Quick Start](QUICKSTART.md)

## Requirements

- PHP 8.0+
- Laravel 9.x, 10.x, or 11.x
- OpenSSL extension
- XMLReader extension

## Testing

```bash
php artisan test
```

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md)

## License

MIT License - see [LICENSE](LICENSE)

## Support

- 📧 Email: inayatullahkks@gmail.com
- 🐛 Issues: [GitHub Issues](https://github.com/inaat/zatca-fatoora/issues)
- 📚 Documentation: See docs folder
- 💼 LinkedIn: [Inayat Ullah](https://www.linkedin.com/in/inayat-ullah-927b09146/)

## Credits

Built with ❤️ for the Saudi developer community by [Inayat Ullah](https://github.com/inaat).

---

**Made in Saudi Arabia 🇸🇦**
