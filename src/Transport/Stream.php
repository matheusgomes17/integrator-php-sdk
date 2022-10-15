<?php

declare(strict_types=1);

namespace MidiaSimples\IntegratorSDK\Transport;

use MidiaSimples\IntegratorSDK\Contracts\TransportContract;
use MidiaSimples\IntegratorSDK\Exception\TransportException;

class Stream implements TransportContract
{
    /**
     * @var array
     */
    private array $defaultOptions;

    public function __construct(array $defaultOptions = [])
    {
        $this->defaultOptions = $defaultOptions;
    }

    public function post(string $url, string $xmlRequest): Response
    {
        $options = array_merge_recursive(
            $this->defaultOptions,
            [
                'http' => [
                    'method' => 'POST',
                    'header' => 'Content-Type: text/xml',
                    'content' => $xmlRequest,
                ],
            ]
        );

        $context = stream_context_create($options);
        $result = @file_get_contents($url, false, $context);

        if (false === $result) {
            throw TransportException::create($url);
        }

        return new Response($result);
    }
}