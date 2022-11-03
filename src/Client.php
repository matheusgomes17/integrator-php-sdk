<?php

declare(strict_types=1);

namespace MidiaSimples\IntegratorSDK;

use MidiaSimples\IntegratorSDK\Contracts\TransportContract;
use MidiaSimples\IntegratorSDK\Contracts\XmlRpc\ClientContract;
use MidiaSimples\IntegratorSDK\Exception\ConfigurationException;
use MidiaSimples\IntegratorSDK\Transport\Request;
use MidiaSimples\IntegratorSDK\Transport\Response;
use MidiaSimples\IntegratorSDK\Transport\Stream;

class Client implements ClientContract
{
    /**
     * The url of the rpc server.
     *
     * @var string
     */
    private string $url;

    /**
     * The transport used to post requests.
     *
     * @var \MidiaSimples\IntegratorSDK\Contracts\TransportContract
     */
    private $transport;

    private array $credentials = [];

    /**
     * A list of output options, used with the xmlrpc_encode_request method.
     *
     * @var array
     */
    private array $outputOptions = [
        'output_type' => 'xml',
        'verbosity' => 'pretty',
        'escaping' => ['markup'],
        'version' => 'xmlrpc',
        'encoding' => 'utf-8',
    ];

    public function __construct(string $url, TransportContract $transport = null, array $outputOptions = [])
    {
        if (!function_exists('xmlrpc_encode_request')) {
            throw ConfigurationException::missingPhpXmlRpcExtension();
        }

        $this->url = $url;
        $this->transport = $transport ?: new Stream();
        $this->outputOptions = array_merge($this->outputOptions, $outputOptions);
    }

    /**
     * @param string $name
     * @param array $args
     * @return object|null
     */
    public function call(string $name, array $args = []): ?object
    {
        $request = Request::encode($name, array_merge($args, $this->credentials));

        $response = $this->transport->post($this->url, $request);

        $contents = $response->getContents();

        if (null === $contents) {
            return null;
        }

        $result = Response::decode($contents);

        return $result;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getTransport(): TransportContract
    {
        return $this->transport;
    }

    public function setTransport(TransportContract $transport): self
    {
        $this->transport = $transport;

        return $this;
    }

    public function getOutputOptions(): array
    {
        return $this->outputOptions;
    }

    public function setOutputOptions(array $outputOptions): self
    {
        $this->outputOptions = $outputOptions;

        return $this;
    }

    public function setCredentials($user, $password): self
    {
        $this->credentials['_user'] = $user;

        $this->credentials['_passwd'] = $password;

        return $this;
    }

    public function getCredentials(): array
    {
        return $this->credentials;
    }
}
