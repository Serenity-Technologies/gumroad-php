<?php

namespace Gumroad\DTOs;

use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Casters\ArrayCaster;

class ProductDTO extends BaseDTO
{
    public string $id;
    public string $name;
    public ?string $description;
    public int $price;
    public string $currency;
    public string $url;
    public ?string $thumbnail_url;
    public array $tags;
    public bool $is_tiered_membership;
    public array $recurrences;
    
    #[CastWith(ArrayCaster::class, itemType: VariantDTO::class)]
    public array $variants;
    
    public ?string $custom_permalink;
    public ?string $custom_receipt;
    public ?string $custom_summary;
    public array $custom_fields;
    public ?int $customizable_price;
    public ?bool $deleted;
    public ?int $max_purchase_count;
    public ?string $preview_url;
    public ?bool $require_shipping;
    public ?string $subscription_duration;
    public ?bool $published;
    public ?array $purchasing_power_parity_prices;
    public string $short_url;
    public string $formatted_price;
    public array $file_info;
    public ?string $sales_count;
    public ?string $sales_usd_cents;
}
