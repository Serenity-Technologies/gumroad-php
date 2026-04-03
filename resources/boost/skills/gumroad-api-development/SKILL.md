---
name: gumroad-api-development
description: Build and work with the Gumroad API integration, including all 43 endpoints, DTOs, query builders, and webhook handling.
---

# Gumroad API Development

## When to use this skill

Use this skill when:
- Making API calls to Gumroad (products, sales, licenses, payouts, etc.)
- Implementing webhook handlers for Gumroad events
- Working with Gumroad DTOs or query builders
- Setting up OAuth integration with Gumroad
- Building e-commerce features with Gumroad products

## Core Concepts

This package provides complete Laravel integration with the Gumroad API (all 43 endpoints). It uses:
- **GumroadClient**: Main API client with methods for all endpoints
- **DTOs**: 31 type-safe data transfer objects for API responses
- **QueryBuilders**: Fluent builders for Sales and Subscribers queries
- **Facade**: Static-like access via `Gumroad::methodName()`

## Package Structure

```
src/
├── Clients/
│   ├── BaseClient.php          # HTTP client foundation
│   └── GumroadClient.php       # All 43 API endpoint methods
├── DTOs/                        # 31 DTOs for type safety
│   ├── BaseDTO.php             # Base class with from()/toArray()
│   ├── ProductDTO.php          # Product entity
│   ├── SaleDTO.php             # Sale entity
│   ├── LicenseDTO.php          # License entity
│   ├── PayoutDTO.php           # Payout entity
│   └── ...                     # All other entities
├── QueryBuilders/
│   ├── BaseQueryBuilder.php    # Foundation for query builders
│   ├── SalesQueryBuilder.php   # Fluent sales filtering
│   └── SubscribersQueryBuilder.php # Fluent subscriber filtering
├── Facades/
│   └── Gumroad.php             # Laravel facade
└── Exceptions/
    └── GumroadException.php    # API error handling
```

## Accessing the API

### Via Facade (Quick Access)

```php
use Gumroad\Facades\Gumroad;

$products = Gumroad::getAllProducts();
$product = Gumroad::getProduct('product-id');
```

### Via Dependency Injection (Recommended)

```php
use Gumroad\Clients\GumroadClient;

public function index(GumroadClient $gumroad)
{
    $products = $gumroad->getAllProducts();
}
```

## API Endpoint Categories

### 1. Products (5 endpoints)

Manage Gumroad products including creation, retrieval, enabling/disabling, and deletion.

```php
// Get all products
$products = Gumroad::getAllProducts();

// Get single product
$product = Gumroad::getProduct('product-id');

// Enable/disable product
Gumroad::enableProduct('product-id');
Gumroad::disableProduct('product-id');

// Delete product
Gumroad::deleteProduct('product-id');
```

**Returns:** `ProductListDTO`, `ProductDTO`, or `array`

### 2. Variant Categories & Variants (10 endpoints)

Manage product variants (e.g., sizes, tiers, editions).

```php
// Get variant categories
$categories = Gumroad::getVariantCategories('product-id');

// Create variant category
$category = Gumroad::createVariantCategory('product-id', [
    'title' => 'Edition Type'
]);

// Get variants in category
$variants = Gumroad::getVariants('product-id', 'category-id');

// Create variant
$variant = Gumroad::createVariant('product-id', 'category-id', [
    'name' => 'Premium',
    'price_difference_cents' => 5000
]);
```

**Returns:** `VariantCategoryDTO`, `VariantDTO`, or `array`

### 3. Offer Codes (5 endpoints)

Create and manage discount codes/coupons.

```php
use Gumroad\DTOs\CreateOfferCodeDTO;

// Create offer code
$offerCode = Gumroad::createOfferCode('product-id', new CreateOfferCodeDTO(
    name: 'SAVE20',
    amount_off: 2000,  // In cents
    offer_type: 'cents',  // or 'percent'
    max_purchase_count: 100,
    universal: false
));

// Get all offer codes
$offerCodes = Gumroad::getOfferCodes('product-id');

// Get specific offer code
$offerCode = Gumroad::getOfferCode('product-id', 'offer-code-id');
```

**Returns:** `OfferCodeDTO`, `OfferCodeListDTO`, or `array`

### 4. Custom Fields (4 endpoints)

Add custom fields to product checkout forms.

```php
// Get custom fields
$fields = Gumroad::getCustomFields('product-id');

// Add custom field
Gumroad::addCustomField('product-id', [
    'name' => 'Company Name',
    'required' => 'true'
]);

// Update custom field
Gumroad::updateCustomField('product-id', 'Company Name', [
    'required' => 'false'
]);

// Delete custom field
Gumroad::deleteCustomField('product-id', 'Company Name');
```

### 5. User (1 endpoint)

Get authenticated user profile information.

```php
$user = Gumroad::getUser();
// Returns UserResponseDTO with user details
```

### 6. Sales (5 endpoints)

Retrieve and manage sales data. Requires `view_sales` scope.

```php
// Get all sales (with optional filters)
$sales = Gumroad::getSales([
    'after' => '2024-01-01',
    'before' => '2024-12-31',
    'product_id' => 'product-id'
]);

// Get single sale
$sale = Gumroad::getSale('sale-id');

// Mark as shipped
Gumroad::markSaleAsShipped('sale-id', new MarkShippedDTO(
    tracking_url: 'https://tracking.example.com/123'
));

// Refund sale
Gumroad::refundSale('sale-id');

// Resend receipt
Gumroad::resendReceipt('sale-id');
```

**Returns:** `SaleListDTO`, `SaleResponseDTO`, `RefundSaleDTO`, or `array`

### 7. Subscribers (2 endpoints)

Manage subscription customers. Requires `view_sales` scope.

```php
// Get subscribers for a product
$subscribers = Gumroad::getActiveSubscribers('product-id', [
    'email' => 'customer@example.com',
    'paginated' => 'true'
]);

// Get specific subscriber
$subscriber = Gumroad::getSubscriberDetails('subscriber-id');
```

### 8. Licenses (5 endpoints)

Verify and manage software license keys. Verify endpoint doesn't require OAuth.

```php
use Gumroad\DTOs\VerifyLicenseDTO;

// Verify license (no OAuth needed)
$verification = Gumroad::verifyLicense(new VerifyLicenseDTO(
    product_id: 'product-id',
    license_key: 'ABCD-1234-EFGH-5678',
    increment_uses_count: 'true'
));

// Enable/disable license
Gumroad::enableLicense(new EnableLicenseDTO(
    product_id: 'product-id',
    license_key: 'ABCD-1234-EFGH-5678'
));

Gumroad::disableLicense(new DisableLicenseDTO(
    product_id: 'product-id',
    license_key: 'ABCD-1234-EFGH-5678'
));

// Decrement uses count
Gumroad::decrementLicenseUses(new DecrementUsesDTO(
    product_id: 'product-id',
    license_key: 'ABCD-1234-EFGH-5678'
));

// Rotate license key
Gumroad::rotateLicense(new DecrementUsesDTO(
    product_id: 'product-id',
    license_key: 'ABCD-1234-EFGH-5678'
));
```

**Returns:** `LicenseVerificationDTO`, `LicenseResponseDTO`

### 9. Resource Subscriptions (3 endpoints)

Set up webhooks for Gumroad events.

```php
use Gumroad\DTOs\CreateResourceSubscriptionDTO;

// Subscribe to webhook
Gumroad::createResourceSubscription(new CreateResourceSubscriptionDTO(
    resource_name: 'sale',  // sale, refund, dispute, cancellation, etc.
    post_url: 'https://your-app.com/webhooks/gumroad'
));

// Get active subscriptions
$subscriptions = Gumroad::getResourceSubscriptions('sale');

// Unsubscribe
Gumroad::unsubscribeFromResource('subscription-id');
```

**Returns:** `ResourceSubscriptionResponseDTO`, `ResourceSubscriptionListDTO`, or `array`

**Supported resource names:**
- `sale` - New sales
- `refund` - Refunds
- `dispute` - Disputes raised
- `dispute_won` - Disputes won
- `cancellation` - Subscription cancellations
- `subscription_updated` - Subscription upgrades/downgrades
- `subscription_ended` - Subscription terminations
- `subscription_restarted` - Subscription reactivations

### 10. Payouts (3 endpoints)

Retrieve payout information. Requires `view_payouts` scope.

```php
// Get all payouts
$payouts = Gumroad::getAllPayouts([
    'after' => '2024-01-01',
    'before' => '2024-12-31',
    'include_upcoming' => 'true'
]);

// Get specific payout
$payout = Gumroad::getPayout('payout-id', [
    'include_transactions' => 'true'
]);

// Get upcoming payouts
$upcoming = Gumroad::getUpcomingPayouts();
```

**Returns:** `PayoutListDTO`, `PayoutResponseDTO`, `UpcomingPayoutsDTO`

## Query Builders

Use query builders for complex filtering instead of manual arrays.

### SalesQueryBuilder

```php
use Gumroad\QueryBuilders\SalesQueryBuilder;

$query = (new SalesQueryBuilder())
    ->after('2024-01-01')
    ->before('2024-12-31')
    ->productId('product-id')
    ->email('customer@example.com')
    ->orderId(12345)
    ->pageKey('pagination-key')
    ->productName('Product Name')
    ->referrer('direct')
    ->country('US')
    ->build();

$sales = Gumroad::getSales($query);
```

### SubscribersQueryBuilder

```php
use Gumroad\QueryBuilders\SubscribersQueryBuilder;

$query = (new SubscribersQueryBuilder())
    ->email('customer@example.com')
    ->paginated(true)
    ->pageKey('pagination-key')
    ->build();

$subscribers = Gumroad::getActiveSubscribers('product-id', $query);
```

## Error Handling

All API calls throw `GumroadException` on failure. Always use try-catch:

```php
use Gumroad\Exceptions\GumroadException;

try {
    $product = Gumroad::getProduct('product-id');
} catch (GumroadException $e) {
    Log::error('Gumroad API error: ' . $e->getMessage());
    // Handle error appropriately
}
```

## DTOs

All API responses use strongly-typed DTOs. Key patterns:

### List DTOs
```php
ProductListDTO {
    success: bool,
    products: array  // Array of ProductDTO
}

SaleListDTO {
    success: bool,
    sales: array,  // Array of SaleDTO
    next_page_key: ?string,
    next_page_url: ?string
}
```

### Single Entity DTOs
```php
ProductDTO {
    id: string,
    name: string,
    description: string,
    price: int,  // In cents
    currency: string,
    url: string,
    published: bool,
    // ... many more fields
}
```

### Response Wrapper DTOs
```php
SaleResponseDTO {
    success: bool,
    sale: SaleDTO
}
```

### Create/Update DTOs (for requests)
```php
CreateOfferCodeDTO {
    name: string,
    amount_off: int,
    offer_type: string,  // 'cents' or 'percent'
    max_purchase_count: ?int,
    universal: bool
}
```

## Common Patterns

### Creating DTOs from API responses
```php
$response = Gumroad::getProduct('id');
// $response is ProductDTO with all properties typed
echo $response->name;
echo $response->price;  // int (cents)
```

### Creating DTOs for API requests
```php
$dto = new CreateOfferCodeDTO(
    name: 'SUMMER2024',
    amount_off: 5000,
    offer_type: 'cents'
);

Gumroad::createOfferCode('product-id', $dto);
```

### Converting DTOs to arrays
```php
$array = $dto->toArray();
```

## Best Practices

1. **Use DTOs, not arrays** - DTOs provide type safety and IDE autocomplete
2. **Use QueryBuilders** - For Sales/Subscribers, use builders instead of manual arrays
3. **Always handle exceptions** - Wrap API calls in try-catch
4. **Choose access pattern** - Use facade OR dependency injection consistently
5. **Cache responses** - Gumroad has rate limits; cache where appropriate
6. **Verify webhooks** - Validate webhook signatures in production
7. **Check scopes** - Some endpoints need specific OAuth scopes
8. **Prices in cents** - All prices are integers in cents, not floats

## Configuration

Set environment variables in `.env`:

```env
GUMROAD_ACCESS_TOKEN=your_token_here
GUMROAD_BASE_URL=https://api.gumroad.com/v2
```

## OAuth Scopes

Different endpoints require different scopes:

- `account` - Full access to all endpoints
- `view_profile` - Read-only user and products
- `edit_products` - Read/write products, variants, offer codes, custom fields
- `view_sales` - Read sales data and subscribers (required for Sales/Subscribers/Payouts)
- `view_payouts` - Read payout information
- `mark_sales_as_shipped` - Mark sales as shipped
- `edit_sales` - Refund sales and resend receipts
