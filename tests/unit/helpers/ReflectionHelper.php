<?php
namespace app\tests\unit\helpers;

class ReflectionHelper
{

    public static function invokeMethod(&$object, $methodName, $params = [])    
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);        
        $method->setAccessible(true);
        return $method->invokeArgs($object, $params);
    }

    public static function getProperty(&$object, $propName)
    {
        $reflection = new \ReflectionClass(get_class($object));
        $prop = $reflection->getProperty($propName);
        $prop->setAccessible(true);
        return $prop->getValue($object);
    }


}