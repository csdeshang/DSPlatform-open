<?php

declare(strict_types=1);

namespace Yansongda\Artful\Direction;

use Psr\Http\Message\ResponseInterface;
use Yansongda\Artful\Contract\DirectionInterface;
use Yansongda\Artful\Contract\PackerInterface;

class NoHttpRequestDirection implements DirectionInterface
{
    public function guide(PackerInterface $packer, ?ResponseInterface $response, array $params = []): ?ResponseInterface
    {
        return $response;
    }
}
