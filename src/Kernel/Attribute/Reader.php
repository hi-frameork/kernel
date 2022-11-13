<?php

declare(strict_types=1);

namespace Hi\Kernel\Attribute;

use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

class Reader
{
    public static function hasClassAttribute(ReflectionClass $reflection, string $attributeName): bool
    {
        return static::getAttribute($reflection, $attributeName) ? true : false;
    }

    public static function getClassAttribute(ReflectionClass $reflection, string $attributeName): ?object
    {
        return static::getAttribute($reflection, $attributeName);
    }

    public static function getMethodAttribute(ReflectionMethod $reflection, string $attributeName): ?object
    {
        return static::getAttribute($reflection, $attributeName);
    }

    public static function getPropertyAttribute(ReflectionProperty $reflection, string $attributeName): ?object
    {
        return static::getAttribute($reflection, $attributeName);
    }

    /**
     * @param ReflectionClass|ReflectionMethod|ReflectionProperty $reflection
     */
    private static function getAttribute($reflection, string $attributeName): ?object
    {
        $attributes = $reflection->getAttributes($attributeName);
        if (isset($attributes[0])) {
            return $attributes[0]->newInstance();
        }

        return null;
    }
}
