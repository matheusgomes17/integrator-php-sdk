<?php

declare(strict_types=1);

namespace MidiaSimples\IntegratorSDK\Transport;

class Response
{
    /**
     * @var mixed|null
     */
    private $headers;

    /**
     * @var string|null
     */
    private ?string $contents;

    /**
     * @param mixed|null $headers
     */
    public function __construct(string $contents = null, $headers = null)
    {
        $this->contents = $contents;
        $this->headers = $headers;
    }

    /**
     * @return mixed|null
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return string|null
     */
    public function getContents(): ?string
    {
        return $this->contents;
    }

    /**
     * @param string $contents
     * @return object
     */
    public static function decode(string $contents): object
    {
        $response = simplexml_load_string($contents, 'SimpleXMLElement', LIBXML_NOCDATA);

        $params = (array)$response->params;

        $data = [];

        if (isset($response->fault)) {
            $data['faultCode'] = (string)$response->fault->value->struct->member[0]->value->int ?? (string)$response->fault->value->struct->member[0]->value->string;
            $data['faultString'] = utf8_encode(urldecode((string)$response->fault->value->struct->member[1]->value->string));
        } elseif (isset($params['param'])) {
            foreach ($params['param'] as $param) {
                $index = (string)$param['name'];

                foreach ($param as $result) {
                    if (!empty($result->DOMElement)) {
                        if ($result->DOMElement->result) {
                            $result = (array) $result->DOMElement->result;

                            if (isset($result['row'])) {
                                foreach ($result['row'] as $id => $rows) {
                                    if (is_numeric($id)) {
                                        foreach ((array) $rows as $idx => $item) {
                                            $data[$index][$id][$idx] = self::formatItemValue($item);
                                        }
                                    } else {
                                        $data[$index][$id] = self::formatItemValue($rows);
                                    }
                                }
                            }
                        }
                    } else {
                        $index = (string)$param['name'];
                        $value = self::encodeType($result);
                        $data[$index] = $value;
                    }
                }
            }
        }

        return (object)$data;
    }

    /**
     * @param $result
     * @return bool|string|null
     */
    public static function encodeType($result)
    {
        $value = null;

        if (!empty($result->boolean) || (string) $result->boolean === '0') {
            $value = (boolean)(string)$result->boolean;
        }

        if (!empty($result->string)) {
            $value = utf8_decode(urldecode((string)$result->string));
        }

        if (!empty($result->double)) {
            $value = (string)$result->double;
        }

        return $value;
    }

    /**
     * @param $item
     * @return string|null
     */
    public static function formatItemValue($item): ?string
    {
        if (is_object($item)) {
            $item = (string) $item;
        }

        $item = utf8_decode(utf8_decode(trim($item)));

        if ($item !== '0') {
            $item = !empty($item) ? $item : null;
        }

        return $item;
    }
}