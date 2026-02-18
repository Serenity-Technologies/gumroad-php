<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gumroad\Clients\GumroadClient;
use Gumroad\DTOs\CreateOfferCodeDTO;
use Gumroad\DTOs\UpdateOfferCodeDTO;
use Gumroad\DTOs\VerifyLicenseDTO;
use Gumroad\DTOs\MarkShippedDTO;
use Gumroad\QueryBuilders\SalesQueryBuilder;
use Gumroad\QueryBuilders\SubscribersQueryBuilder;
use Gumroad\Exceptions\GumroadException;
use Illuminate\Routing\Controller;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class GumroadController extends Controller
{
    public function __construct(private readonly GumroadClient $gumroad) {}

    /**
     * Display a listing of products
     */
    public function indexProducts(): \Illuminate\Http\JsonResponse
    {
        try {
            $products = $this->gumroad->getAllProducts();
            return response()->json($products);
        } catch (GumroadException|UnknownProperties $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show a specific product
     */
    public function showProduct(string $productId): \Illuminate\Http\JsonResponse
    {
        try {
            $product = $this->gumroad->getProduct($productId);
            return response()->json($product);
        } catch (GumroadException|UnknownProperties $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Create a new offer code
     */
    public function createOfferCode(Request $request, string $productId): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'percent_off' => 'nullable|integer|min:1|max:100',
            'amount_off' => 'nullable|integer|min:1',
            'offer_type' => 'required|in:percent,cents',
            'max_purchase_count' => 'nullable|integer|min:1',
        ]);

        try {
            $offerCodeData = new CreateOfferCodeDTO($validated);
            $offerCode = $this->gumroad->createOfferCode($productId, $offerCodeData);
            
            return response()->json($offerCode, 201);
        } catch (GumroadException|UnknownProperties $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    /**
     * Get sales with optional filtering
     */
    public function indexSales(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $queryBuilder = new SalesQueryBuilder();
            
            if ($request->has('after')) {
                $queryBuilder->after($request->input('after'));
            }
            
            if ($request->has('before')) {
                $queryBuilder->before($request->input('before'));
            }
            
            if ($request->has('product_id')) {
                $queryBuilder->productId($request->input('product_id'));
            }
            
            if ($request->has('email')) {
                $queryBuilder->email($request->input('email'));
            }
            
            $sales = $this->gumroad->getSales($queryBuilder->build());
            
            return response()->json($sales);
        } catch (GumroadException|UnknownProperties $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Mark a sale as shipped
     */
    public function markAsShipped(Request $request, string $saleId): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'tracking_url' => 'nullable|url',
        ]);

        try {
            $shippingData = new MarkShippedDTO($validated);
            $result = $this->gumroad->markSaleAsShipped($saleId, $shippingData);
            
            return response()->json($result);
        } catch (GumroadException|UnknownProperties $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    /**
     * Verify a license key
     */
    public function verifyLicense(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|string',
            'license_key' => 'required|string',
            'increment_uses_count' => 'boolean',
        ]);

        try {
            $licenseData = new VerifyLicenseDTO($validated);
            $verification = $this->gumroad->verifyLicense($licenseData);
            
            return response()->json($verification);
        } catch (GumroadException|UnknownProperties $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    /**
     * Get active subscribers for a product
     */
    public function indexSubscribers(string $productId, Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $queryBuilder = new SubscribersQueryBuilder();
            
            if ($request->has('email')) {
                $queryBuilder->email($request->input('email'));
            }
            
            if ($request->has('paginated')) {
                $queryBuilder->paginated($request->boolean('paginated'));
            }
            
            if ($request->has('page_key')) {
                $queryBuilder->pageKey($request->input('page_key'));
            }
            
            $subscribers = $this->gumroad->getActiveSubscribers($productId, $queryBuilder->build());
            
            return response()->json($subscribers);
        } catch (GumroadException|UnknownProperties $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Toggle product status (enable/disable)
     */
    public function toggleProductStatus(string $productId, Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'action' => 'required|in:enable,disable',
        ]);

        try {
            if ($validated['action'] === 'enable') {
                $result = $this->gumroad->enableProduct($productId);
            } else {
                $result = $this->gumroad->disableProduct($productId);
            }
            
            return response()->json($result);
        } catch (GumroadException|UnknownProperties $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    /**
     * Get current user information
     */
    public function currentUser(): \Illuminate\Http\JsonResponse
    {
        try {
            $user = $this->gumroad->getUser();
            return response()->json($user);
        } catch (GumroadException|UnknownProperties $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}