<?php

declare(strict_types=1);

namespace Yansongda\Artful\Service;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Yansongda\Artful\Artful;
use Yansongda\Artful\Contract\EventDispatcherInterface;
use Yansongda\Artful\Contract\ServiceProviderInterface;
use Yansongda\Artful\Exception\ContainerException;

class EventServiceProvider implements ServiceProviderInterface
{
    /**
     * @throws ContainerException
     */
    public function register(mixed $data = null): void
    {
        if (class_exists(EventDispatcher::class)) {
            Artful::set(EventDispatcherInterface::class, new EventDispatcher());
        }
    }
}
