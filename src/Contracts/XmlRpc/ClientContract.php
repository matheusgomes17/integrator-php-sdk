<?php

declare(strict_types=1);

namespace MidiaSimples\IntegratorSDK\Contracts\XmlRpc;

use MidiaSimples\IntegratorSDK\Contracts\TransportContract;

interface ClientContract
{
    /**
     * @throws \MidiaSimples\IntegratorSDK\Exception\RequestException when the request failed
     *
     * @return mixed
     */
    public function call(string $name, array $args = []);

    public function getUrl(): string;

    public function setUrl(string $url): self;

    public function getTransport(): TransportContract;

    public function setTransport(TransportContract $transport): self;

    public function getOutputOptions(): array;

    public function setOutputOptions(array $outputOptions): self;
}