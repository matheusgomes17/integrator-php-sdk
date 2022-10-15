<?php

declare(strict_types=1);

namespace MidiaSimples\IntegratorSDK\Contracts;

use MidiaSimples\IntegratorSDK\Transport\Response;

interface TransportContract
{
    /**
     * @throws \MidiaSimples\IntegratorSDK\Exception\TransportException when posting failed
     */
    public function post(string $url, string $xmlRequest): Response;
}
