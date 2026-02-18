<?php

namespace Gumroad\QueryBuilders;

class SalesQueryBuilder extends BaseQueryBuilder
{
    public function after(?string $date): self
    {
        return $this->addParameter('after', $date);
    }
    
    public function before(?string $date): self
    {
        return $this->addParameter('before', $date);
    }
    
    public function productId(?string $productId): self
    {
        return $this->addParameter('product_id', $productId);
    }
    
    public function email(?string $email): self
    {
        return $this->addParameter('email', $email);
    }
    
    public function orderId(?int $orderId): self
    {
        return $this->addParameter('order_id', $orderId);
    }
    
    public function pageKey(?string $pageKey): self
    {
        return $this->addParameter('page_key', $pageKey);
    }
    
    public function build(): array
    {
        return $this->parameters;
    }

    public function productName(?string $productName): self
    {
        return $this->addParameter('product_name', $productName);
    }

    public function referrer(?string $referrer): self
    {
        return $this->addParameter('referrer', $referrer);
    }

    public function country(?string $country): self
    {
        return $this->addParameter('country', $country);
    }
}