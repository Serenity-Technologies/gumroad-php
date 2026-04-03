<?php

namespace Gumroad\DTOs;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Nullable;

abstract class BaseDTO extends Data
{
    /**
     * Create DTO from array data
     *
     * @param array $data
     * @return static
     * @throws \ReflectionException
     */
    public static function fromArray(array $data): static
    {
        // Always use manual constructor approach to avoid Laravel container dependencies
        $reflection = new \ReflectionClass(static::class);
        $constructor = $reflection->getConstructor();
        $parameters = $constructor->getParameters();
        
        $args = [];
        foreach ($parameters as $param) {
            $name = $param->getName();
            $args[] = $data[$name] ?? ($param->isDefaultValueAvailable() ? $param->getDefaultValue() : null);
        }
        
        return $reflection->newInstanceArgs($args);
    }
    
    /**
     * Convert DTO to array
     * 
     * @return array
     */
    public function toArray(): array
    {
        try {
            return $this->all();
        } catch (\Exception $e) {
            // Fallback to manual array conversion when Laravel container is not available
            $data = [];
            $reflection = new \ReflectionClass($this);
            foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
                if (!$property->isStatic()) {
                    $data[$property->getName()] = $property->getValue($this);
                }
            }
            return $data;
        }
    }
}
