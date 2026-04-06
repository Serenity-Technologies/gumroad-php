<?php

/**
 * @author Kwadwo Kyeremeh <kyerematics@gmail.com>
 * @link https://github.com/serenity-technologies/gumroad-php
 * @license MIT
 */

namespace Gumroad\QueryBuilders;

class SubscribersQueryBuilder extends BaseQueryBuilder
{
    public function email(?string $email): self
    {
        return $this->addParameter('email', $email);
    }

    
    public function pageKey(?string $pageKey): self
    {
        return $this->addParameter('page_key', $pageKey);
    }
    
    public function build(): array
    {
        return $this->parameters;
    }
    public function paginated(?bool $paginated): self
    {
        return $this->addParameter('paginated', $paginated);
    }
}