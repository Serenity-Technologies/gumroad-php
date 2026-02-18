<?php

namespace Gumroad\DTOs;

class PurchaseDTO extends BaseDTO
{
    public string $seller_id;
    public string $product_id;
    public string $product_name;
    public string $permalink;
    public string $product_permalink;
    public string $email;
    public int $price;
    public int $gumroad_fee;
    public string $currency;
    public int $quantity;
    public bool $discover_fee_charged;
    public bool $can_contact;
    public string $referrer;
    public array $card;
    public int $order_number;
    public string $sale_id;
    public string $sale_timestamp;
    public string $purchaser_id;
    public ?string $subscription_id;
    public string $variants;
    public string $license_key;
    public bool $is_multiseat_license;
    public string $ip_country;
    public ?string $recurrence;
    public bool $is_gift_receiver_purchase;
    public bool $refunded;
    public bool $disputed;
    public bool $dispute_won;
    public string $id;
    public string $created_at;
    public array $custom_fields;
    public bool $chargebacked;
    public ?string $subscription_ended_at;
    public ?string $subscription_cancelled_at;
    public ?string $subscription_failed_at;
}