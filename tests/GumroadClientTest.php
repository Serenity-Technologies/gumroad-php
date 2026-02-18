<?php

namespace Gumroad\Tests;

use PHPUnit\Framework\TestCase;
use Gumroad\Clients\GumroadClient;
use Gumroad\DTOs\ProductDTO;
use Gumroad\DTOs\ProductListDTO;
use Gumroad\DTOs\CreateOfferCodeDTO;
use Gumroad\DTOs\VerifyLicenseDTO;
use Gumroad\QueryBuilders\SalesQueryBuilder;
use Gumroad\QueryBuilders\SubscribersQueryBuilder;

class GumroadClientTest extends TestCase
{
    private GumroadClient $client;
    
    protected function setUp(): void
    {
        $this->client = new GumroadClient('test-token');
    }
    
    public function test_can_create_client()
    {
        $this->assertInstanceOf(GumroadClient::class, $this->client);
    }
    
    public function test_products_dto_structure()
    {
        $productData = [
            'id' => 'test-product-id',
            'name' => 'Test Product',
            'description' => 'A test product',
            'price' => 1000,
            'currency' => 'usd',
            'url' => 'https://example.com/product',
            'thumbnail_url' => 'https://example.com/thumb.jpg',
            'tags' => ['test', 'demo'],
            'is_tiered_membership' => false,
            'recurrences' => ['monthly'],
            'variants' => [],
            'published' => true,
            'short_url' => 'https://gumroad.com/l/test',
            'formatted_price' => '$10.00',
            'file_info' => [],
            'custom_fields' => []
        ];
        
        $product = new ProductDTO($productData);
        
        $this->assertEquals('test-product-id', $product->id);
        $this->assertEquals('Test Product', $product->name);
        $this->assertEquals(1000, $product->price);
    }
    
    public function test_product_list_dto()
    {
        $productListData = [
            'success' => true,
            'products' => [
                [
                    'id' => 'prod-1',
                    'name' => 'Product 1',
                    'price' => 1000,
                    'currency' => 'usd',
                    'url' => 'https://example.com/prod1',
                    'tags' => [],
                    'is_tiered_membership' => false,
                    'recurrences' => [],
                    'variants' => [],
                    'published' => true,
                    'short_url' => 'https://gumroad.com/l/prod1',
                    'formatted_price' => '$10.00',
                    'file_info' => [],
                    'custom_fields' => []
                ]
            ]
        ];
        
        $productList = new ProductListDTO($productListData);
        
        $this->assertTrue($productList->success);
        $this->assertCount(1, $productList->products);
    }
    
    public function test_create_offer_code_dto()
    {
        $offerCodeData = new CreateOfferCodeDTO([
            'name' => 'TEST20',
            'percent_off' => 20,
            'offer_type' => 'percent',
            'max_purchase_count' => 50
        ]);
        
        $this->assertEquals('TEST20', $offerCodeData->name);
        $this->assertEquals(20, $offerCodeData->percent_off);
        $this->assertEquals('percent', $offerCodeData->offer_type);
        $this->assertEquals(50, $offerCodeData->max_purchase_count);
    }
    
    public function test_verify_license_dto()
    {
        $licenseData = new VerifyLicenseDTO([
            'product_id' => 'prod-123',
            'license_key' => 'ABC123-DEF456',
            'increment_uses_count' => true
        ]);
        
        $this->assertEquals('prod-123', $licenseData->product_id);
        $this->assertEquals('ABC123-DEF456', $licenseData->license_key);
        $this->assertTrue($licenseData->increment_uses_count);
    }
    
    public function test_sales_query_builder()
    {
        $query = (new SalesQueryBuilder())
            ->after('2023-01-01')
            ->before('2023-12-31')
            ->productId('prod-123')
            ->email('test@example.com')
            ->build();
        
        $expected = [
            'after' => '2023-01-01',
            'before' => '2023-12-31',
            'product_id' => 'prod-123',
            'email' => 'test@example.com'
        ];
        
        $this->assertEquals($expected, $query);
    }
    
    public function test_subscribers_query_builder()
    {
        $query = (new SubscribersQueryBuilder())
            ->email('subscriber@example.com')
            ->paginated(true)
            ->pageKey('page-123')
            ->build();
        
        $expected = [
            'email' => 'subscriber@example.com',
            'paginated' => 'true',
            'page_key' => 'page-123'
        ];
        
        $this->assertEquals($expected, $query);
    }
}