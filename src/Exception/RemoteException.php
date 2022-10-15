<?php

declare(strict_types=1);

namespace MidiaSimples\IntegratorSDK\Exception;

class RemoteException extends RequestException
{
    /**
     * @param int $faultCode
     * @param string $faultString
     * @return static
     */
    public static function create(int $faultCode, string $faultString): self
    {
        return new self($faultString, $faultCode);
    }
}
