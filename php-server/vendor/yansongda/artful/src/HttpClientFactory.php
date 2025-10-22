<?php

declare(strict_types=1);

namespace Yansongda\Artful;

use GuzzleHttp\Client;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Yansongda\Artful\Contract\HttpClientInterface;
use Yansongda\Artful\Exception\ContainerException;
use Yansongda\Artful\Exception\Exception;
use Yansongda\Artful\Exception\InvalidParamsException;

class HttpClientFactory implements Contract\HttpClientFactoryInterface
{
    public function __construct(private ContainerInterface $container) {}

    /**
     * @throws ContainerExceptionInterface
     * @throws ContainerException
     * @throws InvalidParamsException
     * @throws NotFoundExceptionInterface
     */
    public function create(?array $options = []): ClientInterface
    {
        if ($this->container->has(HttpClientInterface::class)) {
            if (($http = $this->container->get(HttpClientInterface::class)) instanceof ClientInterface) {
                return $http;
            }

            throw new InvalidParamsException(Exception::PARAMS_HTTP_CLIENT_INVALID, '参数异常: `HttpClient` 不符合 PSR 规范');
        }

        return new Client($options);
    }
}
