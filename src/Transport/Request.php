<?php

namespace MidiaSimples\IntegratorSDK\Transport;

class Request
{
    /**
     * @param string $method
     * @param array $params
     * @return string
     */
    public static function encode(string $method, array $params = []): string
    {
        // payload header
        $payload = '<?xml version="1.0" encoding="iso-8859-1"?>' . "\r\n";
        $payload .= "<methodCall>\r\n";

        // payload method
        $payload .= "<methodName>{$method}</methodName>\r\n";

        // payload parms
        $payload .= "<params>\r\n";

        foreach ($params as $param => $value) {
            $payload .= "<param name=\"{$param}\">\r\n" . self::encodeType($value) . "</param>\r\n";
        }

        // payload close
        $payload .= "</params>\r\n";
        $payload .= "</methodCall>\r\n";

        return $payload;
    }

    /**
     * @param $param
     * @return false|string
     */
    public static function encodeType($param)
    {
        switch (gettype($param)) {
            case 'boolean':
                $value = "<value><boolean>{$param}</boolean></value>\r\n";
                break;

            case 'double':
                $value = "<value><double>{$param}</double></value>\r\n";
                break;

            case 'integer':
                $value = "<value><int>{$param}</int></value>\r\n";
                break;

            case 'string':
                $value = "<value><string>{$param}</string></value>\r\n";
                break;

            case 'array':
                $value = self::encodeArray($param);
                break;

            default:
                $value = false;
                break;
        }

        return $value;
    }

    /**
     * @param $array
     * @return string
     */
    private static function encodeArray($array): string
    {
        if (self::isAssoc($array)) {
            $encoded_array = "<struct>\r\n";

            foreach ($array as $key => $value) {
                $encoded_array .= "<member>\r\n";
                $encoded_array .= "<name>{$key}</name>\r\n";
                $encoded_array .= self::encodeType($value);
                $encoded_array .= "</member>\r\n";
            }

            $encoded_array .= "</struct>\r\n";
        } else {
            $encoded_array = "<array>\r\n";
            $encoded_array .= "<data>\r\n";

            foreach ($array as $value)
                $encoded_array .= self::encodeType($value);

            $encoded_array .= "</data>\r\n";
            $encoded_array .= "</array>\r\n";
        }

        return $encoded_array;
    }

    /**
     * for some reason that is beyond me PHP lacks a function to check and see if an array is associative or not
     * this is the quickest way I could come up with to check.
     *
     * @param array $array - the array we want to check
     * @return bool         - bool value if the array is associative or not
     */
    private static function isAssoc(array $array): bool
    {
        $arrayKeys = array_keys($array);

        if (is_array($array) && !is_numeric(array_shift($arrayKeys)))
            return true;

        return false;
    }
}
