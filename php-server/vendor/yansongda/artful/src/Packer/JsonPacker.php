<?php

declare(strict_types=1);

namespace Yansongda\Artful\Packer;

use Yansongda\Artful\Contract\PackerInterface;
use Yansongda\Supports\Arr;
use Yansongda\Supports\Collection;

class JsonPacker implements PackerInterface
{
    public function pack(null|array|Collection $payload, ?array $params = null): string
    {
        if (($payload instanceof Collection && $payload->isEmpty()) || empty($payload)) {
            return '';
        }

        return Collection::wrap($payload)->toJson();
    }

    public function unpack(string $payload, ?array $params = null): ?array
    {
        return Arr::wrapJson($payload);
    }
}
