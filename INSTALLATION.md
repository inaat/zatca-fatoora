# Installation Guide - ZATCA Laravel Package

## Prerequisites

- PHP 8.1 or higher
- Laravel 10.x or 11.x
- Composer
- OpenSSL extension
- XMLReader extension

## Installation Methods

### Method 1: Local Path (for development/testing)

1. **Add to your Laravel project's `composer.json`:**

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "./packages/zatca-laravel",
            "options": {
                "symlink": true
            }
        }
    ],
    "require": {
        "yourvendor/zatca-laravel": "@dev"
    }
}
```

2. **Run composer update:**

```bash
composer update yourvendor/zatca-laravel
```

### Method 2: From Packagist (after publishing)

```bash
composer require yourvendor/zatca-laravel
```

## Post-Installation Setup

### Step 1: Run Installation Command

```bash
php artisan zatca:install
```

This command will:
- Publish configuration file to `config/zatca.php`
- Publish migrations
- Optionally run migrations

### Step 2: Configure Environment Variables

Add the following to your `.env` file:

```env
# ZATCA Environment (sandbox, simulation, developer-portal, production)
ZATCA_ENVIRONMENT=sandbox
ZATCA_LANGUAGE=en

# Organization Details
ZATCA_VAT_NUMBER="300000000000003"
ZATCA_VAT_NAME="Your Company Name"
ZATCA_COMMON_NAME="Your Common Name"
ZATCA_COUNTRY_CODE=SA
ZATCA_ORGANIZATION_NAME="Your Organization"
ZATCA_ORGANIZATION_UNIT_NAME="Your Department"
ZATCA_REGISTERED_ADDRESS="Your Full Address"
ZATCA_BUSINESS_CATEGORY="Business"
ZATCA_EMAIL_ADDRESS="your.email@example.com"

# EGS Configuration
ZATCA_EGS_SERIAL_NUMBER="1-TST|2-TST|3-ed22f1d8-e6a2-1118-9b58-d9a8f11e445f"
ZATCA_INVOICE_TYPE="1000"

# Certificates (filled automatically after onboarding)
ZATCA_COMPLIANCE_CERTIFICATE=
ZATCA_COMPLIANCE_SECRET=
ZATCA_PRODUCTION_CERTIFICATE=
ZATCA_PRODUCTION_SECRET=
ZATCA_PRIVATE_KEY=
```

### Step 3: Run ZATCA Onboarding

Get your OTP from ZATCA Fatoora portal and run:

```bash
php artisan zatca:onboarding --otp=YOUR_OTP_CODE
```

This will:
- Generate CSR (Certificate Signing Request)
- Request compliance certificate from ZATCA
- Run all compliance checks
- Request production certificate
- Save all credentials to your `.env` file

### Step 4: Run Migrations (if not done in Step 1)

```bash
php artisan migrate
```

This creates the `zatca_documents` table to store invoice submissions.

## Verification

### Verify Installation

Run the package verification script:

```bash
php packages/zatca-laravel/verify-package.php
```

### Test Basic Functionality

```php
use YourVendor\ZatcaLaravel\Facades\Zatca;

// Check configuration
$config = Zatca::config();
echo $config::get('environment'); // Should output: sandbox

// Test onboarding instance
$onboarding = Zatca::onboarding();
// Ready to use!

// Test invoice instance
$invoice = Zatca::invoice();
// Ready to use!
```

## Configuration

### Publish Config (if needed separately)

```bash
php artisan vendor:publish --tag=zatca-config
```

### Publish Migrations (if needed separately)

```bash
php artisan vendor:publish --tag=zatca-migrations
```

### Publish Language Files

```bash
php artisan vendor:publish --tag=zatca-lang
```

## Customization

### Custom Table Name

In `config/zatca.php`:

```php
'database' => [
    'table_name' => 'your_custom_table_name',
],
```

### Custom API Endpoints

In `config/zatca.php`:

```php
'api' => [
    'production' => 'https://your-custom-endpoint.com',
    // ...
],
```

## Troubleshooting

### Issue: Class not found

**Solution:**
```bash
composer dump-autoload
php artisan clear-compiled
php artisan config:clear
```

### Issue: Migration table already exists

**Solution:**
```bash
# Drop the table and re-run migration
php artisan migrate:rollback --step=1
php artisan migrate
```

### Issue: Certificate errors

**Solution:**
- Verify OpenSSL extension is installed: `php -m | grep openssl`
- Check certificate permissions
- Ensure `.env` variables are properly set
- Re-run onboarding: `php artisan zatca:onboarding --otp=NEW_OTP`

### Issue: Namespace errors

**Solution:**
Update the vendor name in all files:
- `composer.json`
- All PHP files in `src/` directory
- Documentation files

Search and replace:
```bash
# In the package directory
find . -type f -name "*.php" -exec sed -i '' 's/YourVendor\\ZatcaLaravel/ActualVendor\\ZatcaLaravel/g' {} \;
```

## Development Setup

### For Package Development

1. Clone/copy the package to your project's `packages/` directory
2. Add to `composer.json` as shown in Method 1
3. Run `composer update`
4. Make changes to the package
5. Test changes immediately (symlinked)

### Running Tests

```bash
cd packages/zatca-laravel
vendor/bin/phpunit
```

## Uninstallation

```bash
# Remove the package
composer remove yourvendor/zatca-laravel

# Optional: Drop the database table
php artisan migrate:rollback
```

## Support

For issues or questions:
- Check documentation: `README.md` and `USAGE_EXAMPLES.md`
- Run verification: `php packages/zatca-laravel/verify-package.php`
- Check logs: `storage/logs/laravel.log`

## Next Steps

After successful installation:
1. Read `USAGE_EXAMPLES.md` for code examples
2. Configure your organization details
3. Run onboarding process
4. Test in sandbox environment
5. Generate your first invoice!
