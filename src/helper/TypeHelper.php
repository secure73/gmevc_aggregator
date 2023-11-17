<?php
namespace Aggregator\Helper;

class TypeHelper
{
    public static function justInt(mixed $var): false|int
    {
        if (\is_int($var)) {
            return $var;
        }

        return false;
    }

    public static function justIntPositive(mixed $var): false|int
    {
        if (\is_int($var) && $var > 0) {
            return $var;
        }

        return false;
    }



    /**
     * @return string GUID
     *                create GUID
     */
    public static function guid(): string
    {
        $data = openssl_random_pseudo_bytes(16);
        $data[6] = \chr(\ord($data[6]) & 0x0F | 0x40); // set version to 0100
        $data[8] = \chr(\ord($data[8]) & 0x3F | 0x80); // set bits 6-7 to 10

        return vsprintf('%s%s%s%s%s%s%s%s', str_split(bin2hex($data), 4));
    }



    /**
     * create string Unix Y-m-d H:i:s time now.
     */
    public static function timeStamp(): string
    {
        return date('Y-m-d H:i:s');
    }



    /**
     * @param object|stdClass $object
     * @return array<string>
     * id is not in the non null!
     */
    public static function getNonNullableProperties(object $object):array {
        $reflection = new \ReflectionClass($object);
        $properties = $reflection->getProperties();
        $nonNullableProperties = [];
    
        foreach ($properties as $property) {
            $propertyName = $property->getName();
    
            // Get the property type
            $propertyType = $property->getType();
    
            // Check if the property has a type declaration and is not nullable but not id!
            if ($propertyName !=='id' && $propertyType !== null && !$propertyType->allowsNull()) {
                $nonNullableProperties[] = $propertyName;
            }
        }
    
        return $nonNullableProperties;
    }
}

    
    
    
    
    
    
    
