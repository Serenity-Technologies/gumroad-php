<?php

/**
 * @author Kwadwo Kyeremeh <kyerematics@gmail.com>
 * @link https://github.com/serenity-technologies/gumroad-php
 * @license MIT
 */

namespace Gumroad\DTOs;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\DataCollectionOf;

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
            $value = $data[$name] ?? ($param->isDefaultValueAvailable() ? $param->getDefaultValue() : null);
            
            // Check if the corresponding property has DataCollectionOf attribute
            if (is_array($value) && $reflection->hasProperty($name)) {
                $property = $reflection->getProperty($name);
                $attributes = $property->getAttributes(DataCollectionOf::class);
                
                if (!empty($attributes)) {
                    $dtoClass = $attributes[0]->newInstance()->class;
                    // Transform array of arrays into array of DTOs
                    $value = array_map(function($item) use ($dtoClass) {
                        if (is_array($item)) {
                            return $dtoClass::fromArray($item);
                        }
                        return $item;
                    }, $value);
                }
            }
            
            $args[] = $value;
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
