<?php

declare(strict_types=1);

namespace Yansongda\Artful\Direction;

use Psr\Http\Message\ResponseInterface;
use Yansongda\Artful\Contract\DirectionInterface;
use Yansongda\Artful\Contract\PackerInterface;
use Yansongda\Artful\Exception\Exception;
use Yansongda\Artful\Exception\InvalidResponseException;

class OriginResponseDirection implements DirectionInterface
{
    /**
     * @throws InvalidResponseException
     */
    public function guide(PackerInterface $packer, ?ResponseInterface $response, array $params = []): ?ResponseInterface
    {
        if (is_null($response)) {
            throw new InvalidResponseException(Exception::RESPONSE_EMPTY, '响应异常: 响应为空，不能进行 direction');
        }

        return $response;
    }
}
