# Gumroad PHP SDK for Laravel

A comprehensive Laravel package for interacting with the Gumroad API. This package provides full support for all Gumroad API endpoints with proper DTOs, QueryBuilders, and Laravel integration.

## Features

- ✅ Full API coverage for all Gumroad endpoints
- ✅ Type-safe DTOs for requests and responses
- ✅ Fluent QueryBuilder classes for complex queries
- ✅ Laravel Service Provider and Facade integration
- ✅ Proper exception handling
- ✅ PSR-4 autoloading
- ✅ Comprehensive documentation

## Installation

```bash
composer require gumroad/gumroad-php
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --provider="Gumroad\GumroadServiceProvider" --tag=config
```

Add your Gumroad access token to your `.env` file:

```env
GUMROAD_ACCESS_TOKEN=your_gumroad_access_token_here
```

## Usage

### Using the Facade

```php
use Gumroad\Facades\Gumroad;

// Get all products
$products = Gumroad::getAllProducts();

// Get a specific product
$product = Gumroad::getProduct('product-id-here');

// Create a new offer code
$offerCodeData = new CreateOfferCodeDTO([
    'name' => 'SPRING20',
    'percent_off' => 20,
    'offer_type' => 'percent'
]);

$offerCode = Gumroad::createOfferCode('product-id', $offerCodeData);
```

### Using Dependency Injection

```php
use Gumroad\Clients\GumroadClient;

class ProductService 
{
    public function __construct(private GumroadClient $gumroad) {}
    
    public function getAllProducts()
    {
        return $this->gumroad->getAllProducts();
    }
}
```

## API Coverage

### Products
- `getAllProducts()` - Get all products
- `getProduct($productId)` - Get specific product details
- `enableProduct($productId)` - Enable a product
- `disableProduct($productId)` - Disable a product
- `deleteProduct($productId)` - Delete a product

### Variant Categories
- `getVariantCategories($productId)` - Get all variant categories
- `getVariantCategory($productId, $categoryId)` - Get specific variant category
- `createVariantCategory($productId, $data)` - Create new variant category
- `updateVariantCategory($productId, $categoryId, $data)` - Update variant category
- `deleteVariantCategory($productId, $categoryId)` - Delete variant category

### Variants
- `getVariants($productId, $categoryId)` - Get all variants in a category
- `getVariant($productId, $categoryId, $variantId)` - Get specific variant
- `createVariant($productId, $categoryId, $data)` - Create new variant
- `updateVariant($productId, $categoryId, $variantId, $data)` - Update variant
- `deleteVariant($productId, $categoryId, $variantId)` - Delete variant

### Offer Codes
- `getOfferCodes($productId)` - Get all offer codes
- `getOfferCode($productId, $offerCodeId)` - Get specific offer code
- `createOfferCode($productId, $offerCodeData)` - Create new offer code
- `updateOfferCode($productId, $offerCodeId, $offerCodeData)` - Update offer code
- `deleteOfferCode($productId, $offerCodeId)` - Delete offer code

### Sales
- `getSales($queryParams)` - Get sales with filtering
- `getSale($saleId)` - Get specific sale details
- `markSaleAsShipped($saleId, $trackingData)` - Mark sale as shipped
- `refundSale($saleId)` - Refund a sale

### Licenses
- `verifyLicense($licenseData)` - Verify a license key
- `enableLicense($licenseData)` - Enable a license
- `disableLicense($licenseData)` - Disable a license
- `decrementLicenseUses($licenseData)` - Decrement license usage count

### Users
- `getUser()` - Get current user information

### Custom Fields
- `getCustomFields($productId)` - Get product custom fields
- `addCustomField($productId, $data)` - Add custom field to product
- `updateCustomField($productId, $fieldName, $data)` - Update custom field
- `deleteCustomField($productId, $fieldName)` - Delete custom field

### Subscribers
- `getActiveSubscribers($productId, $queryParams)` - Get active subscribers
- `getSubscriberDetails($subscriberId)` - Get subscriber details

## Query Builders

### Sales Query Builder

```php
use Gumroad\QueryBuilders\SalesQueryBuilder;

$query = (new SalesQueryBuilder())
    ->after('2023-01-01')
    ->before('2023-12-31')
    ->productId('product-id')
    ->email('customer@example.com')
    ->build();

$sales = Gumroad::getSales($query);
```

### Subscribers Query Builder

```php
use Gumroad\QueryBuilders\SubscribersQueryBuilder;

$query = (new SubscribersQueryBuilder())
    ->email('subscriber@example.com')
    ->paginated(true)
    ->pageKey('next-page-key')
    ->build();

$subscribers = Gumroad::getActiveSubscribers('product-id', $query);
```

## DTO Examples

### Creating a Product Variant

```php
use Gumroad\DTOs\VariantDTO;

$variantData = [
    'name' => 'Large',
    'price_difference_cents' => 500,
    'purchasing_power_parity_prices' => [
        'US' => 500,
        'IN' => 250,
        'EC' => 125
    ],
    'is_pay_what_you_want' => false
];

$variant = new VariantDTO($variantData);
```

### Creating an Offer Code

```php
use Gumroad\DTOs\CreateOfferCodeDTO;

$offerCodeData = new CreateOfferCodeDTO([
    'name' => 'WELCOME10',
    'percent_off' => 10,
    'offer_type' => 'percent',
    'max_purchase_count' => 100
]);
```

### Verifying a License

```php
use Gumroad\DTOs\VerifyLicenseDTO;

$licenseData = new VerifyLicenseDTO([
    'product_id' => 'product-id',
    'license_key' => 'ABC123-DEF456-GHI789-JKL012',
    'increment_uses_count' => true
]);

$verification = Gumroad::verifyLicense($licenseData);
```

## Error Handling

All API errors are wrapped in `GumroadException`:

```php
use Gumroad\Exceptions\GumroadException;

try {
    $product = Gumroad::getProduct('invalid-id');
} catch (GumroadException $e) {
    echo "API Error: " . $e->getMessage();
}
```

## Testing

```bash
composer test
```

## Requirements

- PHP ^8.1
- Laravel ^9.0|^10.0|^11.0
- GuzzleHttp ^7.0
- Spatie Data Transfer Object ^3.0

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Contributing

Contributions are welcome! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email kyerematics@gmail.com instead of using the issue tracker.