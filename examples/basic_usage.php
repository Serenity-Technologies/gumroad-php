<?php

/**
 * Gumroad PHP SDK Usage Examples
 * 
 * This file demonstrates various ways to use the Gumroad PHP SDK
 */

require_once __DIR__ . '/vendor/autoload.php';

use Gumroad\Clients\GumroadClient;
use Gumroad\DTOs\CreateOfferCodeDTO;
use Gumroad\DTOs\UpdateOfferCodeDTO;
use Gumroad\DTOs\VerifyLicenseDTO;
use Gumroad\DTOs\EnableLicenseDTO;
use Gumroad\DTOs\DisableLicenseDTO;
use Gumroad\DTOs\DecrementUsesDTO;
use Gumroad\DTOs\MarkShippedDTO;
use Gumroad\QueryBuilders\SalesQueryBuilder;
use Gumroad\QueryBuilders\SubscribersQueryBuilder;

// Initialize the client
$client = new GumroadClient($_ENV['GUMROAD_ACCESS_TOKEN']);

echo "=== GUMROAD PHP SDK USAGE EXAMPLES ===\n\n";

// 1. Products API Examples
echo "1. PRODUCTS API\n";
echo str_repeat("-", 30) . "\n";

try {
    // Get all products
    echo "Getting all products...\n";
    $products = $client->getAllProducts();
    echo "Found {$products->products} products\n\n";
    
    if (!empty($products->products)) {
        $firstProduct = $products->products[0];
        $percent = $firstProduct->price/100;
        echo "First product: {$firstProduct->name} {$percent}\n\n";
        
        // Get specific product details
        echo "Getting product details...\n";
        $product = $client->getProduct($firstProduct->id);
        echo "Product details retrieved\n\n";
        
        // Enable/disable product
        echo "Enabling product...\n";
        $result = $client->enableProduct($firstProduct->id);
        echo "Product enabled: " . ($result['success'] ? 'Yes' : 'No') . "\n\n";
    }
} catch (Exception $e) {
    echo "Error with products API: " . $e->getMessage() . "\n\n";
}

// 2. Offer Codes API Examples
echo "2. OFFER CODES API\n";
echo str_repeat("-", 30) . "\n";

try {
    // Assuming we have a product ID
    $productId = 'test-product-id';
    
    // Create offer code
    echo "Creating offer code...\n";
    $offerCodeData = new CreateOfferCodeDTO(
        name : 'WELCOME10',
        amount_off : 10,
        percent_off: 10,
        offer_type: 'percent',
        max_purchase_count : 100,
        universal : false,
    );
    
    $offerCode = $client->createOfferCode($productId, $offerCodeData);
    echo "Created offer code: {$offerCode->name}\n\n";
    
    // Get all offer codes
    echo "Getting all offer codes...\n";
    $offerCodes = $client->getOfferCodes($productId);
    echo "Found {$offerCodes->offer_codes} offer codes\n\n";
    
    // Update offer code
    if (!empty($offerCodes->offer_codes)) {
        $firstOfferCode = $offerCodes->offer_codes[0];
        echo "Updating offer code...\n";
        $updateData = new UpdateOfferCodeDTO([
            'max_purchase_count' => 150
        ]);
        
        $updatedOfferCode = $client->updateOfferCode($productId, $firstOfferCode->id, $updateData);
        echo "Updated offer code max purchases to: {$updatedOfferCode->max_purchase_count}\n\n";
    }
} catch (Exception $e) {
    echo "Error with offer codes API: " . $e->getMessage() . "\n\n";
}

// 3. Sales API Examples
echo "3. SALES API\n";
echo str_repeat("-", 30) . "\n";

try {
    // Build query with filters
    echo "Building sales query...\n";
    $salesQuery = (new SalesQueryBuilder())
        ->after('2023-01-01')
        ->before('2023-12-31')
        ->email('customer@example.com')
        ->build();
    
    echo "Query parameters: " . json_encode($salesQuery) . "\n";
    
    // Get sales with filters
    echo "Getting sales...\n";
    $sales = $client->getSales($salesQuery);
    echo "Found {$sales->sales} sales\n\n";
    
    if (!empty($sales->sales)) {
        $firstSale = $sales->sales[0];
        echo "First sale: {$firstSale->product_name} - {$firstSale->formatted_total_price}\n\n";
        
        // Mark sale as shipped
        echo "Marking sale as shipped...\n";
        $shippingData = new MarkShippedDTO([
            'tracking_url' => 'https://tracking.example.com/12345'
        ]);
        
        $result = $client->markSaleAsShipped($firstSale->id, $shippingData);
        echo "Sale marked as shipped\n\n";
    }
} catch (Exception $e) {
    echo "Error with sales API: " . $e->getMessage() . "\n\n";
}

// 4. Licenses API Examples
echo "4. LICENSES API\n";
echo str_repeat("-", 30) . "\n";

try {
    // Verify a license
    echo "Verifying license...\n";
    $licenseData = new VerifyLicenseDTO([
        'product_id' => 'test-product-id',
        'license_key' => 'ABC123-DEF456-GHI789-JKL012',
        'increment_uses_count' => true
    ]);
    
    $verification = $client->verifyLicense($licenseData);
    echo "License verification: " . ($verification->success ? 'Valid' : 'Invalid') . "\n";
    echo "License uses: {$verification->uses}\n\n";
    
    // Enable a license
    echo "Enabling license...\n";
    $enableData = new EnableLicenseDTO([
        'access_token' => $_ENV['GUMROAD_ACCESS_TOKEN'],
        'product_id' => 'test-product-id',
        'license_key' => 'ABC123-DEF456-GHI789-JKL012'
    ]);
    
    $result = $client->enableLicense($enableData);
    echo "License enabled\n\n";
    
    // Decrement license uses
    echo "Decrementing license uses...\n";
    $decrementData = new DecrementUsesDTO([
        'product_id' => 'test-product-id',
        'license_key' => 'ABC123-DEF456-GHI789-JKL012'
    ]);
    
    $result = $client->decrementLicenseUses($decrementData);
    echo "License uses decremented\n\n";
} catch (Exception $e) {
    echo "Error with licenses API: " . $e->getMessage() . "\n\n";
}

// 5. Subscribers API Examples
echo "5. SUBSCRIBERS API\n";
echo str_repeat("-", 30) . "\n";

try {
    $productId = 'test-product-id';
    
    // Get active subscribers with filters
    echo "Getting active subscribers...\n";
    $subscribersQuery = (new SubscribersQueryBuilder())
        ->email('subscriber@example.com')
        ->paginated(true)
        ->build();
    
    $subscribers = $client->getActiveSubscribers($productId, $subscribersQuery);
    echo "Retrieved subscribers data\n\n";
    
    // Get specific subscriber details
    if (isset($subscribers['subscribers']) && !empty($subscribers['subscribers'])) {
        $firstSubscriber = $subscribers['subscribers'][0];
        echo "Getting subscriber details...\n";
        $subscriberDetails = $client->getSubscriberDetails($firstSubscriber['id']);
        echo "Subscriber details retrieved\n\n";
    }
} catch (Exception $e) {
    echo "Error with subscribers API: " . $e->getMessage() . "\n\n";
}

// 6. User API Examples
echo "6. USER API\n";
echo str_repeat("-", 30) . "\n";

try {
    echo "Getting user information...\n";
    $user = $client->getUser();
    echo "User: {$user->user->name} ({$user->user->email})\n\n";
} catch (Exception $e) {
    echo "Error with user API: " . $e->getMessage() . "\n\n";
}

echo "=== END OF EXAMPLES ===\n";