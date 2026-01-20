# Quick Start Guide

## Package Overview

This is the **Fatoora** ZATCA Laravel package for complete Phase 2 e-invoicing integration.

**Package**: `saudiza/zatca-fatoora`

---

## 🚀 Quick Installation

### For a New Laravel Project

1. **Install via Composer:**

```bash
composer require saudiza/zatca-fatoora
```

2. **Run package installation:**

```bash
php artisan fatoora:install
```

3. **Configure `.env`:**

```env
ZATCA_ENVIRONMENT=sandbox
ZATCA_VAT_NUMBER="300000000000003"
ZATCA_VAT_NAME="Your Company"
ZATCA_ORGANIZATION_NAME="Your Company Ltd"
ZATCA_COMMON_NAME="Common Name"
ZATCA_ORGANIZATION_UNIT_NAME="IT Department"
ZATCA_COUNTRY_CODE=SA
ZATCA_REGISTERED_ADDRESS="123 Main St, Riyadh"
ZATCA_BUSINESS_CATEGORY="Technology"
ZATCA_EMAIL_ADDRESS="admin@company.com"
ZATCA_EGS_SERIAL_NUMBER="1-TST|2-TST|3-UUID"
ZATCA_INVOICE_TYPE="1000"
```

4. **Run onboarding:**

```bash
php artisan zatca:onboarding --otp=YOUR_OTP_FROM_ZATCA
```

---

## 📦 Package Information

This package is published on Packagist as `saudiza/zatca-fatoora` and is ready for production use.

**Installation**: Simply run `composer require saudiza/zatca-fatoora` in your Laravel project.

**GitHub**: https://github.com/inaat/zatca-fatoora

---

## 🧪 Testing

```bash
# Run package tests
php artisan test
```

---

## 📝 Usage Example

```php
use Saudiza\Fatoora\Facades\Fatoora;
use Illuminate\Support\Str;

// Create invoice
$invoice = Fatoora::invoice();

$invoice->setInvoiceNumber('INV-001')
    ->setInvoiceUuid(Str::uuid())
    ->setInvoiceIssueDate(now()->format('Y-m-d'))
    ->setInvoiceIssueTime(now()->format('H:i:s'))
    ->setInvoiceType('0100000', '388')
    ->setInvoiceCurrencyCode('SAR')
    ->setInvoiceTaxCurrencyCode('SAR');

// Configure supplier, client, items, etc.
// See USAGE_EXAMPLES.md for complete examples

// Submit to ZATCA
$result = $invoice->sendDocument(true);

if ($result['success']) {
    echo "Invoice submitted! QR: " . $result['qr_value'];
}
```

---

## 🔧 Development

### Local Development

For local development, you can use a local path repository:

```bash
# In your Laravel project's composer.json
composer config repositories.fatoora-local path ../zatca-fatoora
composer require saudiza/zatca-fatoora:@dev
```

Changes to the package will be reflected immediately (symlinked).

---

## 📚 Documentation

- **README.md** - Full documentation
- **INSTALLATION.md** - Detailed installation guide
- **USAGE_EXAMPLES.md** - Code examples
- **PACKAGE_SUMMARY.md** - Complete package overview
- **CONTRIBUTING.md** - Contribution guidelines

---

## 🎯 Next Steps

1. ✅ Install the package via Composer
2. ✅ Run `php artisan fatoora:install`
3. ✅ Configure your `.env` file
4. ✅ Run onboarding with ZATCA OTP
5. ✅ Start generating invoices!

---

## 📞 Support

For issues or questions:
- Check documentation files
- Run verification script
- Open GitHub issue (after publishing)

---

**Package Version**: 1.0.0
**Status**: ✅ Production Ready
**Last Updated**: 2024-01-18
