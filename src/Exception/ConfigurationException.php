<?php

declare(strict_types=1);

namespace MidiaSimples\IntegratorSDK\Exception;

use MidiaSimples\IntegratorSDK\Contracts\ExceptionContract;

class ConfigurationException extends \LogicException implements ExceptionContract
{
    /**
     * @return static
     */
    public static function missingPhpXmlRpcExtension(): self
    {
        return new self('The PHP extension "php-xmlrpc" must be enabled');
    }
}
