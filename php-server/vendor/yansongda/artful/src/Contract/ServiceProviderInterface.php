<?php

declare(strict_types=1);

namespace Yansongda\Artful\Contract;

use Yansongda\Artful\Exception\ContainerException;

interface ServiceProviderInterface
{
    /**
     * @throws ContainerException
     */
    public function register(mixed $data = null): void;
}
