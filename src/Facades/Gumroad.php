<?php

/**
 * @author Kwadwo Kyeremeh <kyerematics@gmail.com>
 * @link https://github.com/serenity-technologies/gumroad-php
 * @license MIT
 */

namespace Gumroad\Facades;

use Gumroad\Clients\GumroadClient;
use Gumroad\DTOs\CreateOfferCodeDTO;
use Gumroad\DTOs\CreateResourceSubscriptionDTO;
use Gumroad\DTOs\DecrementUsesDTO;
use Gumroad\DTOs\DisableLicenseDTO;
use Gumroad\DTOs\EnableLicenseDTO;
use Gumroad\DTOs\LicenseResponseDTO;
use Gumroad\DTOs\LicenseVerificationDTO;
use Gumroad\DTOs\MarkShippedDTO;
use Gumroad\DTOs\OfferCodeDTO;
use Gumroad\DTOs\OfferCodeListDTO;
use Gumroad\DTOs\PayoutListDTO;
use Gumroad\DTOs\PayoutResponseDTO;
use Gumroad\DTOs\ProductDTO;
use Gumroad\DTOs\ProductListDTO;
use Gumroad\DTOs\RefundSaleDTO;
use Gumroad\DTOs\ResourceSubscriptionListDTO;
use Gumroad\DTOs\ResourceSubscriptionResponseDTO;
use Gumroad\DTOs\SaleListDTO;
use Gumroad\DTOs\SaleResponseDTO;
use Gumroad\DTOs\UpcomingPayoutsDTO;
use Gumroad\DTOs\UpdateOfferCodeDTO;
use Gumroad\DTOs\UserResponseDTO;
use Gumroad\DTOs\VariantCategoryDTO;
use Gumroad\DTOs\VariantDTO;
use Gumroad\DTOs\VerifyLicenseDTO;
use Illuminate\Support\Facades\Facade;

/**
 * @method static ProductListDTO getAllProducts()
 * @method static ProductDTO getProduct(string $productId)
 * @method static array enableProduct(string $productId)
 * @method static array disableProduct(string $productId)
 * @method static array deleteProduct(string $productId)
 * @method static array getVariantCategories(string $productId)
 * @method static VariantCategoryDTO getVariantCategory(string $productId, string $categoryId)
 * @method static VariantCategoryDTO createVariantCategory(string $productId, array $data)
 * @method static VariantCategoryDTO updateVariantCategory(string $productId, string $categoryId, array $data)
 * @method static array deleteVariantCategory(string $productId, string $categoryId)
 * @method static array getVariants(string $productId, string $categoryId)
 * @method static VariantDTO getVariant(string $productId, string $categoryId, string $variantId)
 * @method static VariantDTO createVariant(string $productId, string $categoryId, array $data)
 * @method static VariantDTO updateVariant(string $productId, string $categoryId, string $variantId, array $data)
 * @method static array deleteVariant(string $productId, string $categoryId, string $variantId)
 * @method static OfferCodeListDTO getOfferCodes(string $productId)
 * @method static OfferCodeDTO getOfferCode(string $productId, string $offerCodeId)
 * @method static OfferCodeDTO createOfferCode(string $productId, CreateOfferCodeDTO $offerCodeData)
 * @method static OfferCodeDTO updateOfferCode(string $productId, string $offerCodeId, UpdateOfferCodeDTO $offerCodeData)
 * @method static array deleteOfferCode(string $productId, string $offerCodeId)
 * @method static UserResponseDTO getUser()
 * @method static SaleListDTO getSales(array $queryParams = [])
 * @method static SaleResponseDTO getSale(string $saleId)
 * @method static SaleResponseDTO markSaleAsShipped(string $saleId, ?MarkShippedDTO $data = null)
 * @method static RefundSaleDTO refundSale(string $saleId)
 * @method static array resendReceipt(string $saleId)
 * @method static LicenseVerificationDTO verifyLicense(VerifyLicenseDTO $licenseData)
 * @method static LicenseResponseDTO enableLicense(EnableLicenseDTO $licenseData)
 * @method static LicenseResponseDTO disableLicense(DisableLicenseDTO $licenseData)
 * @method static LicenseResponseDTO decrementLicenseUses(DecrementUsesDTO $licenseData)
 * @method static LicenseResponseDTO rotateLicense(DecrementUsesDTO $licenseData)
 * @method static PayoutListDTO getAllPayouts(array $queryParams = [])
 * @method static PayoutResponseDTO getPayout(string $payoutId, array $queryParams = [])
 * @method static UpcomingPayoutsDTO getUpcomingPayouts(array $queryParams = [])
 * @method static array getCustomFields(string $productId)
 * @method static array addCustomField(string $productId, array $data)
 * @method static array updateCustomField(string $productId, string $fieldName, array $data)
 * @method static array deleteCustomField(string $productId, string $fieldName)
 * @method static ResourceSubscriptionResponseDTO createResourceSubscription(CreateResourceSubscriptionDTO $data)
 * @method static ResourceSubscriptionListDTO getResourceSubscriptions(string $resourceName)
 * @method static array unsubscribeFromResource(string $subscriptionId)
 * @method static array getActiveSubscribers(string $productId, array $queryParams = [])
 * @method static array getSubscriberDetails(string $subscriberId)
 *
 * @see GumroadClient
 */
class Gumroad extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'gumroad';
    }
}