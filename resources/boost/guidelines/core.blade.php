<?php

/**
 * @author Kwadwo Kyeremeh <kyerematics@gmail.com>
 * @link https://github.com/serenity-technologies/gumroad-php
 * @license MIT
 */

?>
## Gumroad PHP

This package provides a comprehensive Laravel integration for the Gumroad API, covering all 43 API endpoints with type-safe DTOs, query builders, and exception handling.

### Package Structure

The package follows a clean architecture:

- **Clients/**: HTTP client layer (`GumroadClient` extends `BaseClient`)
- **DTOs/**: Type-safe data transfer objects (31 DTOs covering all API entities)
- **QueryBuilders/**: Fluent query builders for Sales and Subscribers
- **Facades/**: Laravel facade for static-like access (`Gumroad::method()`)
- **Exceptions/**: Custom `GumroadException` for API errors

### Configuration

The package is configured via `config/gumroad.php`:

```php
return [
    'access_token' => env('GUMROAD_ACCESS_TOKEN', ''),
    'base_url' => env('GUMROAD_BASE_URL', 'https://api.gumroad.com/v2'),
    'http' => [
        'timeout' => 30,
        'connect_timeout' => 10,
    ],
];
```

### Usage Patterns

**Always use dependency injection or the facade:**

```php
// Via dependency injection
public function index(GumroadClient $gumroad)
{
    $products = $gumroad->getAllProducts();
}

// Via facade
use Gumroad\Facades\Gumroad;

$products = Gumroad::getAllProducts();
```

### API Endpoints

The package implements all 43 Gumroad API endpoints across 11 resource categories:

#### Products (5 endpoints)

- `getAllProducts(): ProductListDTO` - Retrieve all products
- `getProduct(string $productId): ProductDTO` - Get product details
- `deleteProduct(string $productId): array` - Delete a product
- `enableProduct(string $productId): array` - Enable a product
- `disableProduct(string $productId): array` - Disable a product

Example usage:

```php
$products = Gumroad::getAllProducts();
$product = Gumroad::getProduct('product-id-here');
Gumroad::enableProduct('product-id-here');
```

#### Variant Categories & Variants (10 endpoints)

- `getVariantCategories(string $productId): array` - List variant categories
- `getVariantCategory(string $productId, string $categoryId): VariantCategoryDTO` - Get category details
- `createVariantCategory(string $productId, array $data): VariantCategoryDTO` - Create category
- `updateVariantCategory(string $productId, string $categoryId, array $data): VariantCategoryDTO` - Update category
- `deleteVariantCategory(string $productId, string $categoryId): array` - Delete category
- `getVariants(string $productId, string $categoryId): array` - List variants
- `getVariant(string $productId, string $categoryId, string $variantId): VariantDTO` - Get variant details
- `createVariant(string $productId, string $categoryId, array $data): VariantDTO` - Create variant
- `updateVariant(string $productId, string $categoryId, string $variantId, array $data): VariantDTO` - Update variant
- `deleteVariant(string $productId, string $categoryId, string $variantId): array` - Delete variant

Example usage:

```php
$categories = Gumroad::getVariantCategories('product-id');
$category = Gumroad::createVariantCategory('product-id', ['title' => 'Size']);
```

#### Offer Codes (5 endpoints)

- `getOfferCodes(string $productId): OfferCodeListDTO` - List offer codes
- `getOfferCode(string $productId, string $offerCodeId): OfferCodeDTO` - Get offer code
- `createOfferCode(string $productId, CreateOfferCodeDTO $data): OfferCodeDTO` - Create offer code
- `updateOfferCode(string $productId, string $offerCodeId, UpdateOfferCodeDTO $data): OfferCodeDTO` - Update offer code
- `deleteOfferCode(string $productId, string $offerCodeId): array` - Delete offer code

Example usage:

```php
use Gumroad\DTOs\CreateOfferCodeDTO;

$offerCode = Gumroad::createOfferCode('product-id', new CreateOfferCodeDTO(
    name: 'SAVE20',
    amount_off: 2000,
    offer_type: 'cents',
    max_purchase_count: 100,
    universal: false
));
```

#### Custom Fields (4 endpoints)

- `getCustomFields(string $productId): array` - List custom fields
- `addCustomField(string $productId, array $data): array` - Create custom field
- `updateCustomField(string $productId, string $fieldName, array $data): array` - Update custom field
- `deleteCustomField(string $productId, string $fieldName): array` - Delete custom field

#### User (1 endpoint)

- `getUser(): UserResponseDTO` - Get authenticated user details

#### Sales (5 endpoints)

- `getSales(array $queryParams = []): SaleListDTO` - List all sales
- `getSale(string $saleId): SaleResponseDTO` - Get sale details
- `markSaleAsShipped(string $saleId, ?MarkShippedDTO $data = null): SaleResponseDTO` - Mark as shipped
- `refundSale(string $saleId): RefundSaleDTO` - Refund a sale
- `resendReceipt(string $saleId): array` - Resend purchase receipt

Example usage with QueryBuilder:

```php
use Gumroad\QueryBuilders\SalesQueryBuilder;

$query = (new SalesQueryBuilder())
    ->after('2024-01-01')
    ->before('2024-12-31')
    ->productId('product-id')
    ->build();

$sales = Gumroad::getSales($query);
```

#### Subscribers (2 endpoints)

- `getActiveSubscribers(string $productId, array $queryParams = []): array` - List subscribers
- `getSubscriberDetails(string $subscriberId): array` - Get subscriber details

Example usage with QueryBuilder:

```php
use Gumroad\QueryBuilders\SubscribersQueryBuilder;

$query = (new SubscribersQueryBuilder())
    ->email('customer@example.com')
    ->paginated(true)
    ->build();

$subscribers = Gumroad::getActiveSubscribers('product-id', $query);
```

#### Licenses (5 endpoints)

- `verifyLicense(VerifyLicenseDTO $data): LicenseVerificationDTO` - Verify a license (no OAuth required)
- `enableLicense(EnableLicenseDTO $data): LicenseResponseDTO` - Enable a license
- `disableLicense(DisableLicenseDTO $data): LicenseResponseDTO` - Disable a license
- `decrementLicenseUses(DecrementUsesDTO $data): LicenseResponseDTO` - Decrement uses count
- `rotateLicense(DecrementUsesDTO $data): LicenseResponseDTO` - Rotate license key

Example usage:

```php
use Gumroad\DTOs\VerifyLicenseDTO;

$verification = Gumroad::verifyLicense(new VerifyLicenseDTO(
    product_id: 'product-id',
    license_key: 'ABCD-1234-EFGH-5678',
    increment_uses_count: 'true'
));
```

#### Resource Subscriptions (3 endpoints)

- `createResourceSubscription(CreateResourceSubscriptionDTO $data): ResourceSubscriptionResponseDTO` - Subscribe to webhook
- `getResourceSubscriptions(string $resourceName): ResourceSubscriptionListDTO` - List active subscriptions
- `unsubscribeFromResource(string $subscriptionId): array` - Unsubscribe from webhook

Supported resource names: `sale`, `refund`, `dispute`, `dispute_won`, `cancellation`, `subscription_updated`, `subscription_ended`, `subscription_restarted`

Example usage:

```php
use Gumroad\DTOs\CreateResourceSubscriptionDTO;

Gumroad::createResourceSubscription(new CreateResourceSubscriptionDTO(
    resource_name: 'sale',
    post_url: 'https://your-app.com/webhooks/gumroad/sales'
));
```

#### Payouts (3 endpoints)

- `getAllPayouts(array $queryParams = []): PayoutListDTO` - List all payouts
- `getPayout(string $payoutId, array $queryParams = []): PayoutResponseDTO` - Get payout details
- `getUpcomingPayouts(array $queryParams = []): UpcomingPayoutsDTO` - Get upcoming payouts

Example usage:

```php
$payouts = Gumroad::getAllPayouts([
    'after' => '2024-01-01',
    'before' => '2024-12-31'
]);

$payout = Gumroad::getPayout('payout-id', ['include_transactions' => 'true']);
```

### DTOs

All API responses are returned as strongly-typed DTOs extending `BaseDTO`. DTOs provide:

- Type safety with PHP 8 constructor property promotion
- `from(array $data)` static method for creation
- `toArray()` method for conversion
- Nullable types marked with `?` prefix

**Common DTO patterns:**

```php
// List responses
ProductListDTO { success: bool, products: array }

// Single entity responses  
ProductDTO { id: string, name: string, price: int, ... }

// Response wrappers
SaleResponseDTO { success: bool, sale: SaleDTO }
```

### Error Handling

All API errors throw `GumroadException`. Always wrap calls in try-catch:

```php
use Gumroad\Exceptions\GumroadException;

try {
    $product = Gumroad::getProduct('invalid-id');
} catch (GumroadException $e) {
    Log::error('Gumroad API error: ' . $e->getMessage());
    return response()->json(['error' => 'Failed to fetch product'], 500);
}
```

### Query Builders

Use query builders for complex queries with optional parameters:

- **SalesQueryBuilder**: Filter sales by date range, product, email, order ID, etc.
- **SubscribersQueryBuilder**: Filter subscribers by email, pagination, page key

Query builders use fluent interface pattern - all methods return `$this` for chaining, call `build()` to get final array.

### Best Practices

1. **Always use DTOs** - Never work with raw arrays; DTOs provide type safety
2. **Use QueryBuilders** - For Sales and Subscribers queries, use builders instead of manual arrays
3. **Handle exceptions** - Wrap all API calls in try-catch blocks
4. **Use facades or DI consistently** - Don't mix patterns in the same codebase
5. **Respect rate limits** - Gumroad API has rate limiting; implement caching where appropriate
6. **Secure webhooks** - Verify webhook signatures when handling resource subscription POSTs
7. **Scope awareness** - Some endpoints require specific OAuth scopes (`view_sales`, `view_payouts`, etc.)
