<?php

/**
 * @author Kwadwo Kyeremeh <kyerematics@gmail.com>
 * @link https://github.com/serenity-technologies/gumroad-php
 * @license MIT
 */

namespace Gumroad\Clients;

use Gumroad\DTOs\CreateProductRequestDTO;
use Gumroad\DTOs\CreateResourceSubscriptionDTO;
use Gumroad\DTOs\ProductDTO;
use Gumroad\DTOs\ProductListDTO;
use Gumroad\DTOs\VariantCategoryDTO;
use Gumroad\DTOs\VariantDTO;
use Gumroad\DTOs\OfferCodeDTO;
use Gumroad\DTOs\OfferCodeListDTO;
use Gumroad\DTOs\CreateOfferCodeDTO;
use Gumroad\DTOs\UpdateOfferCodeDTO;
use Gumroad\DTOs\UserResponseDTO;
use Gumroad\DTOs\SaleDTO;
use Gumroad\DTOs\SaleListDTO;
use Gumroad\DTOs\SaleResponseDTO;
use Gumroad\DTOs\MarkShippedDTO;
use Gumroad\DTOs\RefundSaleDTO;
use Gumroad\DTOs\LicenseVerificationDTO;
use Gumroad\DTOs\LicenseResponseDTO;
use Gumroad\DTOs\VerifyLicenseDTO;
use Gumroad\DTOs\EnableLicenseDTO;
use Gumroad\DTOs\DisableLicenseDTO;
use Gumroad\DTOs\DecrementUsesDTO;
use Gumroad\DTOs\PayoutDTO;
use Gumroad\DTOs\PayoutListDTO;
use Gumroad\DTOs\PayoutResponseDTO;
use Gumroad\DTOs\UpcomingPayoutsDTO;
use Gumroad\DTOs\ResourceSubscriptionDTO;
use Gumroad\DTOs\ResourceSubscriptionListDTO;
use Gumroad\DTOs\ResourceSubscriptionResponseDTO;
use Gumroad\Exceptions\GumroadException;

class GumroadClient extends BaseClient
{
    // Products API

    /**
     * @throws GumroadException
     * @throws \ReflectionException
     */
    public function getAllProducts(): ProductListDTO
    {
        $response = $this->get('/products');
        return ProductListDTO::fromArray($response);
    }
    /**
     * name, price, description, tags
     * @throws \ReflectionException
     * @throws GumroadException
     */
    public function createProduct(CreateProductRequestDTO $productData): ProductDTO
    {
        $response = $this->post("/products", data: $productData->toArray());
        return ProductDTO::fromArray($response['product']);
    }
    /**
     * @throws GumroadException
     * @throws \ReflectionException
     */
    public function getProduct(string $productId): ProductDTO
    {
        $response = $this->get("/products/{$productId}");
        return ProductDTO::fromArray($response['product']);
    }

    /**
     * @throws GumroadException
     */
    public function enableProduct(string $productId): array
    {
        return $this->put("/products/{$productId}/enable");
    }

    /**
     * @throws GumroadException
     */
    public function disableProduct(string $productId): array
    {
        return $this->put("/products/{$productId}/disable");
    }

    /**
     * @throws GumroadException
     */
    public function deleteProduct(string $productId): array
    {
        return $this->delete("/products/{$productId}");
    }
    
    // Variant Categories API

    /**
     * @throws GumroadException
     */
    public function getVariantCategories(string $productId): array
    {
        return $this->get("/products/{$productId}/variant_categories");
    }

    /**
     * @throws GumroadException
     * @throws \ReflectionException
     */
    public function getVariantCategory(string $productId, string $categoryId): VariantCategoryDTO
    {
        $response = $this->get("/products/{$productId}/variant_categories/{$categoryId}");
        return VariantCategoryDTO::fromArray($response['variant_category']);
    }

    /**
     * @throws GumroadException
     * @throws \ReflectionException
     */
    public function createVariantCategory(string $productId, array $data): VariantCategoryDTO
    {
        $response = $this->post("/products/{$productId}/variant_categories", $data);
        return VariantCategoryDTO::fromArray($response['variant_category']);
    }

    /**
     * @throws GumroadException
     * @throws \ReflectionException
     */
    public function updateVariantCategory(string $productId, string $categoryId, array $data): VariantCategoryDTO
    {
        $response = $this->put("/products/{$productId}/variant_categories/{$categoryId}", $data);
        return VariantCategoryDTO::fromArray($response['variant_category']);
    }

    /**
     * @throws GumroadException
     */
    public function deleteVariantCategory(string $productId, string $categoryId): array
    {
        return $this->delete("/products/{$productId}/variant_categories/{$categoryId}");
    }
    
    // Variants API

    /**
     * @throws GumroadException
     */
    public function getVariants(string $productId, string $categoryId): array
    {
        return $this->get("/products/{$productId}/variant_categories/{$categoryId}/variants");
    }

    /**
     * @throws GumroadException
     * @throws \ReflectionException
     */
    public function getVariant(string $productId, string $categoryId, string $variantId): VariantDTO
    {
        $response = $this->get("/products/{$productId}/variant_categories/{$categoryId}/variants/{$variantId}");
        return VariantDTO::fromArray($response['variant']);
    }

    /**
     * @throws GumroadException
     * @throws \ReflectionException
     */
    public function createVariant(string $productId, string $categoryId, array $data): VariantDTO
    {
        $response = $this->post("/products/{$productId}/variant_categories/{$categoryId}/variants", $data);
        return VariantDTO::fromArray($response['variant']);
    }

    /**
     * @throws GumroadException
     * @throws \ReflectionException
     */
    public function updateVariant(string $productId, string $categoryId, string $variantId, array $data): VariantDTO
    {
        $response = $this->put("/products/{$productId}/variant_categories/{$categoryId}/variants/{$variantId}", $data);
        return VariantDTO::fromArray($response['variant']);
    }

    /**
     * @throws GumroadException
     */
    public function deleteVariant(string $productId, string $categoryId, string $variantId): array
    {
        return $this->delete("/products/{$productId}/variant_categories/{$categoryId}/variants/{$variantId}");
    }
    
    // Offer Codes API

    /**
     * @throws GumroadException
     * @throws \ReflectionException
     */
    public function getOfferCodes(string $productId): OfferCodeListDTO
    {
        $response = $this->get("/products/{$productId}/offer_codes");
        return OfferCodeListDTO::fromArray($response);
    }

    /**
     * @throws GumroadException
     * @throws \ReflectionException
     */
    public function getOfferCode(string $productId, string $offerCodeId): OfferCodeDTO
    {
        $response = $this->get("/products/{$productId}/offer_codes/{$offerCodeId}");
        return OfferCodeDTO::fromArray($response['offer_code']);
    }

    /**
     * @throws GumroadException
     * @throws \ReflectionException
     */
    public function createOfferCode(string $productId, CreateOfferCodeDTO $offerCodeData): OfferCodeDTO
    {
        $response = $this->post("/products/{$productId}/offer_codes", $offerCodeData->toArray());
        return OfferCodeDTO::fromArray($response['offer_code']);
    }

    /**
     * @throws GumroadException
     * @throws \ReflectionException
     */
    public function updateOfferCode(string $productId, string $offerCodeId, UpdateOfferCodeDTO $offerCodeData): OfferCodeDTO
    {
        $response = $this->put("/products/{$productId}/offer_codes/{$offerCodeId}", $offerCodeData->toArray());
        return OfferCodeDTO::fromArray($response['offer_code']);
    }

    /**
     * @throws GumroadException
     */
    public function deleteOfferCode(string $productId, string $offerCodeId): array
    {
        return $this->delete("/products/{$productId}/offer_codes/{$offerCodeId}");
    }
    
    // Users API

    /**
     * @throws GumroadException
     * @throws \ReflectionException
     */
    public function getUser(): UserResponseDTO
    {
        $response = $this->get('/user');
        return UserResponseDTO::fromArray($response);
    }

    // Sales API

    /**
     * @throws GumroadException
     * @throws \ReflectionException
     */
    public function getSales(array $queryParams = []): SaleListDTO
    {
        $response = $this->get('/sales', $queryParams);
        return SaleListDTO::fromArray($response);
    }

    /**
     * @throws GumroadException
     * @throws \ReflectionException
     */
    public function getSale(string $saleId): SaleResponseDTO
    {
        $response = $this->get("/sales/{$saleId}");
        return SaleResponseDTO::fromArray($response);
    }

    /**
     * @throws GumroadException
     * @throws \ReflectionException
     */
    public function markSaleAsShipped(string $saleId, ?MarkShippedDTO $data = null): SaleResponseDTO
    {
        $requestData = $data ? $data->toArray() : [];
        $response = $this->put("/sales/{$saleId}/mark_as_shipped", $requestData);
        return SaleResponseDTO::fromArray($response);
    }

    /**
     * @throws GumroadException
     * @throws \ReflectionException
     */
    public function refundSale(string $saleId): RefundSaleDTO
    {
        $response = $this->put("/sales/{$saleId}/refund");
        return RefundSaleDTO::fromArray($response);
    }

    /**
     * @throws GumroadException
     */
    public function resendReceipt(string $saleId): array
    {
        return $this->post("/sales/{$saleId}/resend_receipt");
    }
    
    // Licenses API

    /**
     * @throws GumroadException
     * @throws \ReflectionException
     */
    public function verifyLicense(VerifyLicenseDTO $licenseData): LicenseVerificationDTO
    {
        $response = $this->post('/licenses/verify', $licenseData->toArray());
        return LicenseVerificationDTO::fromArray($response);
    }

    /**
     * @throws GumroadException
     * @throws \ReflectionException
     */
    public function enableLicense(EnableLicenseDTO $licenseData): LicenseResponseDTO
    {
        $response = $this->put('/licenses/enable', $licenseData->toArray());
        return LicenseResponseDTO::fromArray($response);
    }

    /**
     * @throws GumroadException
     * @throws \ReflectionException
     */
    public function disableLicense(DisableLicenseDTO $licenseData): LicenseResponseDTO
    {
        $response = $this->put('/licenses/disable', $licenseData->toArray());
        return LicenseResponseDTO::fromArray($response);
    }

    /**
     * @throws GumroadException
     * @throws \ReflectionException
     */
    public function decrementLicenseUses(DecrementUsesDTO $licenseData): LicenseResponseDTO
    {
        $response = $this->put('/licenses/decrement_uses_count', $licenseData->toArray());
        return LicenseResponseDTO::fromArray($response);
    }

    /**
     * @throws GumroadException
     * @throws \ReflectionException
     */
    public function rotateLicense(DecrementUsesDTO $licenseData): LicenseResponseDTO
    {
        $response = $this->put('/licenses/rotate', $licenseData->toArray());
        return LicenseResponseDTO::fromArray($response);
    }

    // Payouts API

    /**
     * @throws GumroadException
     * @throws \ReflectionException
     */
    public function getAllPayouts(array $queryParams = []): PayoutListDTO
    {
        $response = $this->get('/payouts', $queryParams);
        return PayoutListDTO::fromArray($response);
    }

    /**
     * @throws GumroadException
     * @throws \ReflectionException
     */
    public function getPayout(string $payoutId, array $queryParams = []): PayoutResponseDTO
    {
        $response = $this->get("/payouts/{$payoutId}", $queryParams);
        return PayoutResponseDTO::fromArray($response);
    }

    /**
     * @throws GumroadException
     * @throws \ReflectionException
     */
    public function getUpcomingPayouts(array $queryParams = []): UpcomingPayoutsDTO
    {
        $response = $this->get('/payouts/upcoming', $queryParams);
        return UpcomingPayoutsDTO::fromArray($response);
    }
    
    // Custom Fields API

    /**
     * @throws GumroadException
     */
    public function getCustomFields(string $productId): array
    {
        return $this->get("/products/{$productId}/custom_fields");
    }

    /**
     * @throws GumroadException
     */
    public function addCustomField(string $productId, array $data): array
    {
        return $this->post("/products/{$productId}/custom_fields", $data);
    }

    /**
     * @throws GumroadException
     */
    public function updateCustomField(string $productId, string $fieldName, array $data): array
    {
        return $this->put("/products/{$productId}/custom_fields/{$fieldName}", $data);
    }

    /**
     * @throws GumroadException
     */
    public function deleteCustomField(string $productId, string $fieldName): array
    {
        return $this->delete("/products/{$productId}/custom_fields/{$fieldName}");
    }
    
    // Resource Subscriptions API

    /**
     * @throws GumroadException
     * @throws \ReflectionException
     */
    public function createResourceSubscription(CreateResourceSubscriptionDTO $data): ResourceSubscriptionResponseDTO
    {
        $response = $this->put('/resource_subscriptions', $data->toArray());
        return ResourceSubscriptionResponseDTO::fromArray($response);
    }

    /**
     * @throws GumroadException
     * @throws \ReflectionException
     */
    public function getResourceSubscriptions(string $resourceName): ResourceSubscriptionListDTO
    {
        $response = $this->get('/resource_subscriptions', ['resource_name' => $resourceName]);
        return ResourceSubscriptionListDTO::fromArray($response);
    }

    /**
     * @throws GumroadException
     */
    public function unsubscribeFromResource(string $subscriptionId): array
    {
        return $this->delete("/resource_subscriptions/{$subscriptionId}");
    }
    
    // Subscribers API

    /**
     * @throws GumroadException
     */
    public function getActiveSubscribers(string $productId, array $queryParams = []): array
    {
        return $this->get("/products/{$productId}/subscribers", $queryParams);
    }

    /**
     * @throws GumroadException
     */
    public function getSubscriberDetails(string $subscriberId): array
    {
        return $this->get("/subscribers/{$subscriberId}");
    }
}
