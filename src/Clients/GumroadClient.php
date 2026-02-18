<?php

namespace Gumroad\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
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
use Gumroad\Exceptions\GumroadException;
use GuzzleHttp\Exception\RequestException;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class GumroadClient
{
    private Client $httpClient;
    private string $accessToken;
    private string $baseUrl = 'https://api.gumroad.com/v2';
    
    public function __construct(string $accessToken, ?Client $httpClient = null)
    {
        $this->accessToken = $accessToken;
        $this->httpClient = $httpClient ?? new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }
    
    // Products API

    /**
     * @throws UnknownProperties
     * @throws GumroadException
     */
    public function getAllProducts(): ProductListDTO
    {
        $response = $this->makeRequest('GET', '/products');
        return new ProductListDTO($response);
    }

    /**
     * @throws UnknownProperties
     * @throws GumroadException
     */
    public function getProduct(string $productId): ProductDTO
    {
        $response = $this->makeRequest('GET', "/products/{$productId}");
        return new ProductDTO($response['product']);
    }

    /**
     * @throws GumroadException
     */
    public function enableProduct(string $productId): array
    {
        return $this->makeRequest('PUT', "/products/{$productId}/enable");
    }

    /**
     * @throws GumroadException
     */
    public function disableProduct(string $productId): array
    {
        return $this->makeRequest('PUT', "/products/{$productId}/disable");
    }

    /**
     * @throws GumroadException
     */
    public function deleteProduct(string $productId): array
    {
        return $this->makeRequest('DELETE', "/products/{$productId}");
    }
    
    // Variant Categories API

    /**
     * @throws GumroadException
     */
    public function getVariantCategories(string $productId): array
    {
        return $this->makeRequest('GET', "/products/{$productId}/variant_categories");
    }

    /**
     * @throws UnknownProperties
     * @throws GumroadException
     */
    public function getVariantCategory(string $productId, string $categoryId): VariantCategoryDTO
    {
        $response = $this->makeRequest('GET', "/products/{$productId}/variant_categories/{$categoryId}");
        return new VariantCategoryDTO($response['variant_category']);
    }

    /**
     * @throws UnknownProperties
     * @throws GumroadException
     */
    public function createVariantCategory(string $productId, array $data): VariantCategoryDTO
    {
        $response = $this->makeRequest('POST', "/products/{$productId}/variant_categories", $data);
        return new VariantCategoryDTO($response['variant_category']);
    }

    /**
     * @throws UnknownProperties
     * @throws GumroadException
     */
    public function updateVariantCategory(string $productId, string $categoryId, array $data): VariantCategoryDTO
    {
        $response = $this->makeRequest('PUT', "/products/{$productId}/variant_categories/{$categoryId}", $data);
        return new VariantCategoryDTO($response['variant_category']);
    }

    /**
     * @throws GumroadException
     */
    public function deleteVariantCategory(string $productId, string $categoryId): array
    {
        return $this->makeRequest('DELETE', "/products/{$productId}/variant_categories/{$categoryId}");
    }
    
    // Variants API

    /**
     * @throws GumroadException
     */
    public function getVariants(string $productId, string $categoryId): array
    {
        return $this->makeRequest('GET', "/products/{$productId}/variant_categories/{$categoryId}/variants");
    }

    /**
     * @throws UnknownProperties
     * @throws GumroadException
     */
    public function getVariant(string $productId, string $categoryId, string $variantId): VariantDTO
    {
        $response = $this->makeRequest('GET', "/products/{$productId}/variant_categories/{$categoryId}/variants/{$variantId}");
        return new VariantDTO($response['variant']);
    }

    /**
     * @throws UnknownProperties
     * @throws GumroadException
     */
    public function createVariant(string $productId, string $categoryId, array $data): VariantDTO
    {
        $response = $this->makeRequest('POST', "/products/{$productId}/variant_categories/{$categoryId}/variants", $data);
        return new VariantDTO($response['variant']);
    }

    /**
     * @throws UnknownProperties
     * @throws GumroadException
     */
    public function updateVariant(string $productId, string $categoryId, string $variantId, array $data): VariantDTO
    {
        $response = $this->makeRequest('PUT', "/products/{$productId}/variant_categories/{$categoryId}/variants/{$variantId}", $data);
        return new VariantDTO($response['variant']);
    }

    /**
     * @throws GumroadException
     */
    public function deleteVariant(string $productId, string $categoryId, string $variantId): array
    {
        return $this->makeRequest('DELETE', "/products/{$productId}/variant_categories/{$categoryId}/variants/{$variantId}");
    }
    
    // Offer Codes API

    /**
     * @throws UnknownProperties
     * @throws GumroadException
     */
    public function getOfferCodes(string $productId): OfferCodeListDTO
    {
        $response = $this->makeRequest('GET', "/products/{$productId}/offer_codes");
        return new OfferCodeListDTO($response);
    }

    /**
     * @throws UnknownProperties
     * @throws GumroadException
     */
    public function getOfferCode(string $productId, string $offerCodeId): OfferCodeDTO
    {
        $response = $this->makeRequest('GET', "/products/{$productId}/offer_codes/{$offerCodeId}");
        return new OfferCodeDTO($response['offer_code']);
    }

    /**
     * @throws UnknownProperties
     * @throws GumroadException
     */
    public function createOfferCode(string $productId, CreateOfferCodeDTO $offerCodeData): OfferCodeDTO
    {
        $response = $this->makeRequest('POST', "/products/{$productId}/offer_codes", $offerCodeData->toArray());
        return new OfferCodeDTO($response['offer_code']);
    }

    /**
     * @throws UnknownProperties
     * @throws GumroadException
     */
    public function updateOfferCode(string $productId, string $offerCodeId, UpdateOfferCodeDTO $offerCodeData): OfferCodeDTO
    {
        $response = $this->makeRequest('PUT', "/products/{$productId}/offer_codes/{$offerCodeId}", $offerCodeData->toArray());
        return new OfferCodeDTO($response['offer_code']);
    }

    /**
     * @throws GumroadException
     */
    public function deleteOfferCode(string $productId, string $offerCodeId): array
    {
        return $this->makeRequest('DELETE', "/products/{$productId}/offer_codes/{$offerCodeId}");
    }
    
    // Users API

    /**
     * @throws UnknownProperties
     * @throws GumroadException
     */
    public function getUser(): UserResponseDTO
    {
        $response = $this->makeRequest('GET', '/user');
        return new UserResponseDTO($response);
    }
    
    // Sales API

    /**
     * @throws UnknownProperties
     * @throws GumroadException
     */
    public function getSales(array $queryParams = []): SaleListDTO
    {
        $response = $this->makeRequest('GET', '/sales', [], $queryParams);
        return new SaleListDTO($response);
    }

    /**
     * @throws UnknownProperties
     * @throws GumroadException
     */
    public function getSale(string $saleId): SaleResponseDTO
    {
        $response = $this->makeRequest('GET', "/sales/{$saleId}");
        return new SaleResponseDTO($response);
    }

    /**
     * @throws UnknownProperties
     * @throws GumroadException
     */
    public function markSaleAsShipped(string $saleId, ?MarkShippedDTO $data = null): SaleResponseDTO
    {
        $requestData = $data ? $data->toArray() : [];
        $response = $this->makeRequest('PUT', "/sales/{$saleId}/mark_as_shipped", $requestData);
        return new SaleResponseDTO($response);
    }

    /**
     * @throws UnknownProperties
     * @throws GumroadException
     */
    public function refundSale(string $saleId): RefundSaleDTO
    {
        $response = $this->makeRequest('PUT', "/sales/{$saleId}/refund");
        return new RefundSaleDTO($response);
    }
    
    // Licenses API

    /**
     * @throws UnknownProperties
     * @throws GumroadException
     */
    public function verifyLicense(VerifyLicenseDTO $licenseData): LicenseVerificationDTO
    {
        $response = $this->makeRequest('POST', '/licenses/verify', $licenseData->toArray());
        return new LicenseVerificationDTO($response);
    }

    /**
     * @throws UnknownProperties
     * @throws GumroadException
     */
    public function enableLicense(EnableLicenseDTO $licenseData): LicenseResponseDTO
    {
        $response = $this->makeRequest('PUT', '/licenses/enable', $licenseData->toArray());
        return new LicenseResponseDTO($response);
    }

    /**
     * @throws UnknownProperties
     * @throws GumroadException
     */
    public function disableLicense(DisableLicenseDTO $licenseData): LicenseResponseDTO
    {
        $response = $this->makeRequest('PUT', '/licenses/disable', $licenseData->toArray());
        return new LicenseResponseDTO($response);
    }

    /**
     * @throws UnknownProperties
     * @throws GumroadException
     */
    public function decrementLicenseUses(DecrementUsesDTO $licenseData): LicenseResponseDTO
    {
        $response = $this->makeRequest('PUT', '/licenses/decrement_uses_count', $licenseData->toArray());
        return new LicenseResponseDTO($response);
    }
    
    // Custom Fields API

    /**
     * @throws GumroadException
     */
    public function getCustomFields(string $productId): array
    {
        return $this->makeRequest('GET', "/products/{$productId}/custom_fields");
    }

    /**
     * @throws GumroadException
     */
    public function addCustomField(string $productId, array $data): array
    {
        return $this->makeRequest('POST', "/products/{$productId}/custom_fields", $data);
    }

    /**
     * @throws GumroadException
     */
    public function updateCustomField(string $productId, string $fieldName, array $data): array
    {
        return $this->makeRequest('PUT', "/products/{$productId}/custom_fields/{$fieldName}", $data);
    }

    /**
     * @throws GumroadException
     */
    public function deleteCustomField(string $productId, string $fieldName): array
    {
        return $this->makeRequest('DELETE', "/products/{$productId}/custom_fields/{$fieldName}");
    }
    
    // Resource Subscriptions API

    /**
     * @throws GumroadException
     */
    public function getResourceSubscription(string $subscriptionId): array
    {
        return $this->makeRequest('GET', "/resource_subscriptions/{$subscriptionId}");
    }

    /**
     * @throws GumroadException
     */
    public function updateResourceSubscription(array $data): array
    {
        return $this->makeRequest('PUT', '/resource_subscriptions', $data);
    }

    /**
     * @throws GumroadException
     */
    public function unsubscribeFromResource(string $subscriptionId): array
    {
        return $this->makeRequest('DELETE', "/resource_subscriptions/{$subscriptionId}");
    }
    
    // Subscribers API

    /**
     * @throws GumroadException
     */
    public function getActiveSubscribers(string $productId, array $queryParams = []): array
    {
        return $this->makeRequest('GET', "/products/{$productId}/subscribers", [], $queryParams);
    }

    /**
     * @throws GumroadException
     */
    public function getSubscriberDetails(string $subscriberId): array
    {
        return $this->makeRequest('GET', "/subscribers/{$subscriberId}");
    }

    /**
     * @throws GumroadException
     */
    private function makeRequest(string $method, string $uri, array $data = [], array $queryParams = []): array
    {
        try {
            $options = [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'Accept' => 'application/json',
                ],
            ];

            if (!empty($data)) {
                if ($method === 'POST' || $method === 'PUT') {
                    $options['headers']['Content-Type'] = 'application/json';
                    $options['json'] = $data;
                }
            }
            
            if (!empty($queryParams)) {
                $options['query'] = $queryParams;
            }
            
            $response = $this->httpClient->request($method, $uri, $options);
            $responseData = json_decode($response->getBody()->getContents(), true);
            
            if (!$responseData['success'] ?? false) {
                throw new GumroadException($responseData['message'] ?? 'API request failed');
            }
            
            return $responseData;
        } catch (ConnectException $e) {
            throw new GumroadException('Connection failed: ' . $e->getMessage(), $e->getCode(), $e);
        } catch (RequestException $e) {
            throw new GumroadException('Request failed: ' . $e->getMessage(), $e->getCode(), $e);
        } catch (GuzzleException $e) {
            throw new GumroadException('HTTP request failed: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }
}