<?php

declare(strict_types=1);

namespace Yansongda\Artful\Service;

use Yansongda\Artful\Artful;
use Yansongda\Artful\Contract\HttpClientFactoryInterface;
use Yansongda\Artful\Contract\ServiceProviderInterface;
use Yansongda\Artful\Exception\ContainerException;
use Yansongda\Artful\HttpClientFactory;

class HttpServiceProvider implements ServiceProviderInterface
{
    /**
     * @throws ContainerException
     */
    public function register(mixed $data = null): void
    {
        $container = Artful::getContainer();

        Artful::set(HttpClientFactoryInterface::class, new HttpClientFactory($container));
    }
}
