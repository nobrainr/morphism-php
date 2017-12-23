<?php

namespace Morphism;

use Morphism\Helpers\MorphismHelper;

abstract class Morphism {

    use MorphismHelper;

    protected static $registries = array();

    public static function register($type, $schema) {
            
    }

    /** 
     * @static
     * @param string $type
     * @return bool
     */
    public static function exists($type){
        return array_key_exists($type, self::$registries);
    }

    /**
     * @static
     * @param string $type
     * @return array
     */
    public static function getMapper($type){
        return self::$registries[$type];
    }

    /**
     * @static
     * @param string $type
     * @param array $schema
     */
    public static function setMapper($type, $schema){
        if (!$type && !$schema) {
            throw new \Exception('type paramater is required when register a mapping');
        } else if (Morphism::exists($type)) {
            throw new \Exception(sprintf("A mapper for %s has already been registered", $type));
        }

        self::$registries[$type] = $schema;
    }

    /**
     * @static
     * @param string $type
     */
    public static function deleteMapper($type){
        unset(self::$registries[$type]);
    }

    /**
     * @static
     * @param string $type
     * @param array $data
     */
    public static function map($type, $data){
        if(!Morphism::exists($type)){
            throw new \Exception(sprintf("Mapper for %s not exist", $type));
        }

        $reflectedClass = new \ReflectionClass($type);

        if(!$reflectedClass->isInstantiable()){
            throw new \Exception($type . " is not an instantiable class.");
        }

        $instance = $reflectedClass->newInstance();
        if(isset($data[0])){
            return array_map(function($arr) use($instance, $type){
                return self::transformValuesFromObject($instance, Morphism::getMapper($type), $arr);
            }, $data);
        }
        else{
            return self::transformValuesFromObject($instance, Morphism::getMapper($type), $data);
        }
    }
}