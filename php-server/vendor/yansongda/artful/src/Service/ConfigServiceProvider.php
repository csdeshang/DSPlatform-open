<?php

declare(strict_types=1);

namespace Yansongda\Artful\Service;

use Yansongda\Artful\Artful;
use Yansongda\Artful\Contract\ConfigInterface;
use Yansongda\Artful\Contract\ServiceProviderInterface;
use Yansongda\Artful\Exception\ContainerException;
use Yansongda\Supports\Config;

class ConfigServiceProvider implements ServiceProviderInterface
{
    private array $config = [
        'logger' => [
            'enable' => false,
            'file' => null,
            'identify' => 'yansongda.artful',
            'level' => 'debug',
            'type' => 'daily',
            'max_files' => 30,
        ],
        'http' => [
            'timeout' => 5.0,
            'connect_timeout' => 3.0,
            'headers' => [
                'User-Agent' => 'yansongda/artful-v1',
            ],
        ],
    ];

    /**
     * @throws ContainerException
     */
    public function register(mixed $data = null): void
    {
        $config = new Config(array_replace_recursive($this->config, $data ?? []));

        Artful::set(ConfigInterface::class, $config);
    }
}
