<?php

declare(strict_types=1);

namespace Yansongda\Artful\Contract;

use Yansongda\Supports\Collection;

interface PackerInterface
{
    public function pack(null|array|Collection $payload, ?array $params = null): string;

    public function unpack(string $payload, ?array $params = null): ?array;
}
