<?php

declare(strict_types=1);

namespace Yansongda\Artful\Service;

use Closure;
use Hyperf\Context\ApplicationContext as HyperfContainer;
use Hyperf\Pimple\ContainerFactory as DefaultContainer;
use Illuminate\Container\Container as LaravelContainer;
use Psr\Container\ContainerInterface;
use Yansongda\Artful\Artful;
use Yansongda\Artful\Contract\ServiceProviderInterface;
use Yansongda\Artful\Exception\ContainerException;
use Yansongda\Artful\Exception\ContainerNotFoundException;
use Yansongda\Artful\Exception\Exception;

/**
 * @codeCoverageIgnore
 */
class ContainerServiceProvider implements ServiceProviderInterface
{
    private array $detectApplication = [
        'laravel' => LaravelContainer::class,
        'hyperf' => HyperfContainer::class,
    ];

    /**
     * @throws ContainerException
     */
    public function register(mixed $data = null): void
    {
        if ($data instanceof ContainerInterface || $data instanceof Closure) {
            Artful::setContainer($data);

            return;
        }

        if (Artful::hasContainer()) {
            return;
        }

        foreach ($this->detectApplication as $framework => $application) {
            $method = $framework.'Application';

            if (class_exists($application) && method_exists($this, $method) && $this->{$method}()) {
                return;
            }
        }

        $this->defaultApplication();
    }

    /**
     * @throws ContainerException
     * @throws ContainerNotFoundException
     */
    protected function laravelApplication(): bool
    {
        Artful::setContainer(static fn () => LaravelContainer::getInstance());

        Artful::set(\Yansongda\Artful\Contract\ContainerInterface::class, LaravelContainer::getInstance());

        if (!Artful::has(ContainerInterface::class)) {
            Artful::set(ContainerInterface::class, LaravelContainer::getInstance());
        }

        return true;
    }

    /**
     * @throws ContainerException
     * @throws ContainerNotFoundException
     */
    protected function hyperfApplication(): bool
    {
        if (!HyperfContainer::hasContainer()) {
            return false;
        }

        Artful::setContainer(static fn () => HyperfContainer::getContainer());

        Artful::set(\Yansongda\Artful\Contract\ContainerInterface::class, HyperfContainer::getContainer());

        if (!Artful::has(ContainerInterface::class)) {
            Artful::set(ContainerInterface::class, HyperfContainer::getContainer());
        }

        return true;
    }

    /**
     * @throws ContainerException
     * @throws ContainerNotFoundException
     */
    protected function defaultApplication(): void
    {
        if (!class_exists(DefaultContainer::class)) {
            throw new ContainerNotFoundException('容器未找到: Init failed! Maybe you should install `hyperf/pimple` first', Exception::CONTAINER_NOT_FOUND);
        }

        $container = (new DefaultContainer())();

        Artful::setContainer($container);

        Artful::set(\Yansongda\Artful\Contract\ContainerInterface::class, $container);
    }
}
