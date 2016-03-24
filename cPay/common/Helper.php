<?php

namespace cPay\Common;

class Helper
{

    public static function camelCase($str)
    {
        $str = self::convertToLowercase($str);
        return preg_replace_callback(
            '/_([a-z])/',
            function ($match) {
                return strtoupper($match[1]);
            },
            $str
        );
    }

    protected static function convertToLowercase($str)
    {
        $explodedStr = explode('_', $str);

        if (count($explodedStr) > 1) {
            foreach ($explodedStr as $value) {
                $lowercasedStr[] = strtolower($value);
            }
            $str = implode('_', $lowercasedStr);
        }

        return $str;
    }

    public static function validateLuhn($number)
    {
        $str = '';
        foreach (array_reverse(str_split($number)) as $i => $c) {
            $str .= $i % 2 ? $c * 2 : $c;
        }

        return array_sum(str_split($str)) % 10 === 0;
    }

    public static function install($target, $parameters)
    {
        if (is_array($parameters)) {
            foreach ($parameters as $key => $value) {
                $method = 'set'.ucfirst(static::camelCase($key));
                if (method_exists($target, $method)) {
                    $target->$method($value);
                }
            }
        }
    }

    public static function getShortName($className)
    {
        if (0 === strpos($className, '\\')) {
            $className = substr($className, 1);
        }
        if (0 === strpos($className, 'cPay\\')) {
            return trim(str_replace('\\', '_', substr($className, 8, -7)), '_');
        }
        return '\\'.$className;
    }

    public static function getClassName($shortName)
    {
        if (0 === strpos($shortName, '\\')) {
            return $shortName;
        }
        $shortName = str_replace('_', '\\', $shortName);
        if (false === strpos($shortName, '\\')) {
            $shortName .= '\\';
        }
        return '\\cPay\\Gateway\\'.$shortName;
    }

    public static function toFloat($value)
    {
        if (!is_string($value) && !is_int($value) && !is_float($value)) {
            throw new Exception('Data type is not a valid decimal number.');
        }

        if (is_string($value)) {
            if (!preg_match('/^[-]?[0-9]+(\.[0-9]*)?$/', $value)) {
                throw new Exception('String is not a valid decimal number.');
            }
        }
        return (float)$value;
    }
}
