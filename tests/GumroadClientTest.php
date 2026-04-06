<?php

/**
 * @author Kwadwo Kyeremeh <kyerematics@gmail.com>
 * @link https://github.com/serenity-technologies/gumroad-php
 * @license MIT
 */

namespace Gumroad\Tests;

use Orchestra\Testbench\TestCase;
use Gumroad\Clients\GumroadClient;
use Gumroad\DTOs\ProductDTO;
use Gumroad\DTOs\ProductListDTO;
use Gumroad\DTOs\CreateOfferCodeDTO;
use Gumroad\DTOs\VerifyLicenseDTO;
use Gumroad\QueryBuilders\SalesQueryBuilder;
use Gumroad\QueryBuilders\SubscribersQueryBuilder;
use Spatie\LaravelData\LaravelDataServiceProvider;
use Gumroad\GumroadServiceProvider;

class GumroadClientTest extends TestCase
{
    private GumroadClient $client;
    
    protected function getPackageProviders($app): array
    {
        return [
            GumroadServiceProvider::class,
            LaravelDataServiceProvider::class,
        ];
    }
    
    protected function setUp(): void
    {
        parent::setUp();
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
        
        $product = new ProductDTO(
            id: $productData['id'],
            name: $productData['name'],
            description: $productData['description'],
            price: $productData['price'],
            currency: $productData['currency'],
            url: $productData['url'],
            thumbnail_url: $productData['thumbnail_url'],
            tags: $productData['tags'],
            is_tiered_membership: $productData['is_tiered_membership'],
            recurrences: $productData['recurrences'],
            variants: [],
            custom_permalink: null,
            custom_receipt: null,
            custom_summary: null,
            custom_fields: $productData['custom_fields'],
            customizable_price: null,
            deleted: null,
            max_purchase_count: null,
            preview_url: null,
            require_shipping: null,
            subscription_duration: null,
            published: $productData['published'],
            purchasing_power_parity_prices: null,
            short_url: $productData['short_url'],
            formatted_price: $productData['formatted_price'],
            file_info: $productData['file_info'],
            sales_count: null,
            sales_usd_cents: null
        );
        
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
        
        $products = array_map(function($productData) {
            return new ProductDTO(
                id: $productData['id'],
                name: $productData['name'],
                description: null,
                price: $productData['price'],
                currency: $productData['currency'],
                url: $productData['url'],
                thumbnail_url: null,
                tags: $productData['tags'],
                is_tiered_membership: $productData['is_tiered_membership'],
                recurrences: $productData['recurrences'],
                variants: [],
                custom_permalink: null,
                custom_receipt: null,
                custom_summary: null,
                custom_fields: $productData['custom_fields'],
                customizable_price: null,
                deleted: null,
                max_purchase_count: null,
                preview_url: null,
                require_shipping: null,
                subscription_duration: null,
                published: $productData['published'],
                purchasing_power_parity_prices: null,
                short_url: $productData['short_url'],
                formatted_price: $productData['formatted_price'],
                file_info: $productData['file_info'],
                sales_count: null,
                sales_usd_cents: null
            );
        }, $productListData['products']);
        
        $productList = new ProductListDTO(
            success: $productListData['success'],
            products: $products
        );
        
        $this->assertTrue($productList->success);
        $this->assertCount(1, $productList->products);
    }
    
    public function test_create_offer_code_dto()
    {
        $offerCodeData = new CreateOfferCodeDTO(
            name: 'TEST20',
            amount_off: null,
            percent_off: 20,
            offer_type: 'percent',
            max_purchase_count: 50,
            universal: null
        );
        
        $this->assertEquals('TEST20', $offerCodeData->name);
        $this->assertEquals(20, $offerCodeData->percent_off);
        $this->assertEquals('percent', $offerCodeData->offer_type);
        $this->assertEquals(50, $offerCodeData->max_purchase_count);
    }
    
    public function test_verify_license_dto()
    {
        $licenseData = new VerifyLicenseDTO(
            product_id: 'prod-123',
            license_key: 'ABC123-DEF456',
            increment_uses_count: true
        );
        
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
    
    public function test_dto_constructors()
    {
        // Test constructor-based DTO creation
        $offerCode = new CreateOfferCodeDTO(
            name: 'CONSTRUCTOR20',
            amount_off: null,
            percent_off: 20,
            offer_type: 'percent',
            max_purchase_count: 100,
            universal: null
        );
        
        $this->assertEquals('CONSTRUCTOR20', $offerCode->name);
        $this->assertEquals(20, $offerCode->percent_off);
        
        // Test BaseDTO helper methods
        $arrayData = $offerCode->toArray();
        $this->assertIsArray($arrayData);
        $this->assertArrayHasKey('name', $arrayData);
        
        // Test static factory method
        $fromArray = CreateOfferCodeDTO::fromArray([
            'name' => 'FACTORY20',
            'amount_off' => null,
            'percent_off' => 25,
            'offer_type' => 'percent',
            'max_purchase_count' => 50,
            'universal' => null
        ]);
        
        $this->assertEquals('FACTORY20', $fromArray->name);
        $this->assertEquals(25, $fromArray->percent_off);
    }
}