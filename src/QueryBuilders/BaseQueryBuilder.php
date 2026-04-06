<?php

/**
 * @author Kwadwo Kyeremeh <kyerematics@gmail.com>
 * @link https://github.com/serenity-technologies
 * @license MIT
 */

namespace Gumroad\QueryBuilders;

abstract class BaseQueryBuilder
{
    protected array $parameters = [];
    
    public function getParameters(): array
    {
        return $this->parameters;
    }
    
    public function toArray(): array
    {
        return $this->parameters;
    }
    
    protected function addParameter(string $key, $value): self
    {
        if ($value !== null) {
            $this->parameters[$key] = $value;
        }
        return $this;
    }
}