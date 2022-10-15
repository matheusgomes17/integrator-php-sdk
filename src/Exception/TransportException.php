<?php

declare(strict_types=1);

namespace MidiaSimples\IntegratorSDK\Exception;

class TransportException extends RequestException
{
    /**
     * @param string $url
     * @param int $code
     * @param \Throwable|null $previous
     * @return static
     */
    public static function create(string $url, int $code = 0, \Throwable $previous = null): self
    {
        return new self(sprintf('Cannot access to URL "%s"', $url), $code, $previous);
    }
}
