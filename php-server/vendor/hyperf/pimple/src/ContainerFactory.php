<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace Hyperf\Pimple;

use Hyperf\Context\ApplicationContext;
use Pimple;

class ContainerFactory
{
    public function __construct(protected array $providers = [])
    {
    }

    public function __invoke()
    {
        $container = new Container(new Pimple\Container());
        foreach ($this->providers as $provider) {
            /** @var ProviderInterface $instance */
            $instance = new $provider();
            $instance->register($container);
        }

        return ApplicationContext::setContainer($container);
    }
}
