<?php

declare(strict_types=1);

namespace Yansongda\Artful;

use Closure;
use Illuminate\Container\Container as LaravelContainer;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\MessageInterface;
use Throwable;
use Yansongda\Artful\Contract\ConfigInterface;
use Yansongda\Artful\Contract\DirectionInterface;
use Yansongda\Artful\Contract\HttpClientFactoryInterface;
use Yansongda\Artful\Contract\PackerInterface;
use Yansongda\Artful\Contract\PluginInterface;
use Yansongda\Artful\Contract\ServiceProviderInterface;
use Yansongda\Artful\Contract\ShortcutInterface;
use Yansongda\Artful\Direction\CollectionDirection;
use Yansongda\Artful\Exception\ContainerException;
use Yansongda\Artful\Exception\ContainerNotFoundException;
use Yansongda\Artful\Exception\Exception;
use Yansongda\Artful\Exception\InvalidParamsException;
use Yansongda\Artful\Exception\InvalidResponseException;
use Yansongda\Artful\Exception\ServiceNotFoundException;
use Yansongda\Artful\Packer\JsonPacker;
use Yansongda\Artful\Service\ConfigServiceProvider;
use Yansongda\Artful\Service\ContainerServiceProvider;
use Yansongda\Artful\Service\EventServiceProvider;
use Yansongda\Artful\Service\HttpServiceProvider;
use Yansongda\Artful\Service\LoggerServiceProvider;
use Yansongda\Supports\Collection;
use Yansongda\Supports\Config;
use Yansongda\Supports\Pipeline;

class Artful
{
    /**
     * @var string[]
     */
    private array $coreService = [
        ContainerServiceProvider::class,
        ConfigServiceProvider::class,
        LoggerServiceProvider::class,
        EventServiceProvider::class,
        HttpServiceProvider::class,
    ];

    private static null|Closure|ContainerInterface $container = null;

    /**
     * @throws ContainerException
     */
    private function __construct(array $config, null|Closure|ContainerInterface $container = null)
    {
        $this->registerServices($config, $container);

        Artful::set(DirectionInterface::class, CollectionDirection::class);
        Artful::set(PackerInterface::class, JsonPacker::class);
    }

    /**
     * @return mixed
     *
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    public static function __callStatic(string $service, array $config)
    {
        if (!empty($config)) {
            self::config(...$config);
        }

        return self::get($service);
    }

    /**
     * @throws ContainerException
     */
    public static function config(array $config = [], null|Closure|ContainerInterface $container = null): bool
    {
        if (self::hasContainer() && !($config['_force'] ?? false)) {
            return false;
        }

        new self($config, $container);

        return true;
    }

    /**
     * @codeCoverageIgnore
     *
     * @throws ContainerException
     */
    public static function set(string $name, mixed $value): void
    {
        try {
            $container = Artful::getContainer();

            if ($container instanceof LaravelContainer) {
                $container->singleton($name, $value instanceof Closure ? $value : static fn () => $value);

                return;
            }

            if (method_exists($container, 'set')) {
                $container->set(...func_get_args());

                return;
            }
        } catch (ContainerNotFoundException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new ContainerException('容器异常: '.$e->getMessage());
        }

        throw new ContainerException('容器异常: 当前容器类型不支持 `set` 方法');
    }

    /**
     * @codeCoverageIgnore
     *
     * @throws ContainerException
     */
    public static function make(string $service, array $parameters = []): mixed
    {
        try {
            $container = Artful::getContainer();

            if (method_exists($container, 'make')) {
                return $container->make(...func_get_args());
            }
        } catch (ContainerNotFoundException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new ContainerException('容器异常: '.$e->getMessage());
        }

        $parameters = array_values($parameters);

        return new $service(...$parameters);
    }

    /**
     * @throws ServiceNotFoundException
     * @throws ContainerException
     */
    public static function get(string $service): mixed
    {
        try {
            return Artful::getContainer()->get($service);
        } catch (NotFoundExceptionInterface $e) {
            throw new ServiceNotFoundException('服务未找到: '.$e->getMessage());
        } catch (ContainerNotFoundException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new ContainerException('容器异常: '.$e->getMessage());
        }
    }

    /**
     * @throws ContainerNotFoundException
     */
    public static function has(string $service): bool
    {
        return Artful::getContainer()->has($service);
    }

    public static function setContainer(null|Closure|ContainerInterface $container): void
    {
        self::$container = $container;
    }

    /**
     * @throws ContainerNotFoundException
     */
    public static function getContainer(): ContainerInterface
    {
        if (self::$container instanceof ContainerInterface) {
            return self::$container;
        }

        if (self::$container instanceof Closure) {
            return (self::$container)();
        }

        throw new ContainerNotFoundException('容器未找到: `getContainer()` 方法调用失败! 或许你应该先 `setContainer()`');
    }

    public static function hasContainer(): bool
    {
        return self::$container instanceof ContainerInterface || self::$container instanceof Closure;
    }

    public static function clear(): void
    {
        self::$container = null;
    }

    /**
     * @throws ContainerException
     */
    public static function load(string $service, mixed $data = null): void
    {
        self::registerService($service, $data);
    }

    /**
     * @throws ContainerException
     */
    public static function registerService(string $service, mixed $data = null): void
    {
        $var = new $service();

        if ($var instanceof ServiceProviderInterface) {
            $var->register($data);
        }
    }

    /**
     * @throws ContainerException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
    public static function shortcut(string $shortcut, array $params = []): null|Collection|MessageInterface|Rocket
    {
        if (!class_exists($shortcut) || !in_array(ShortcutInterface::class, class_implements($shortcut))) {
            throw new InvalidParamsException(Exception::PARAMS_SHORTCUT_INVALID, "参数异常: [{$shortcut}] 未实现 `ShortcutInterface`");
        }

        /* @var ShortcutInterface $shortcutInstance */
        $shortcutInstance = self::get($shortcut);

        return self::artful($shortcutInstance->getPlugins($params), $params);
    }

    /**
     * @throws ContainerException
     * @throws InvalidParamsException
     */
    public static function artful(array $plugins, array $params): null|Collection|MessageInterface|Rocket
    {
        Event::dispatch(new Event\ArtfulStart($plugins, $params));

        self::verifyPlugin($plugins);

        /* @var Pipeline $pipeline */
        $pipeline = self::make(Pipeline::class);

        /* @var Rocket $rocket */
        $rocket = $pipeline
            ->send((new Rocket())->setParams($params)->setPayload(new Collection()))
            ->through($plugins)
            ->via('assembly')
            ->then(static fn ($rocket) => self::ignite($rocket));

        Event::dispatch(new Event\ArtfulEnd($rocket));

        if (!empty($params['_return_rocket'])) {
            return $rocket;
        }

        return $rocket->getDestination();
    }

    /**
     * @throws ContainerException
     * @throws InvalidParamsException
     * @throws InvalidResponseException
     * @throws ServiceNotFoundException
     */
    public static function ignite(Rocket $rocket): Rocket
    {
        if (!should_do_http_request($rocket->getDirection())) {
            return $rocket;
        }

        /* @var HttpClientFactoryInterface $httpFactory */
        $httpFactory = self::get(HttpClientFactoryInterface::class);
        /* @var Config $config */
        $config = self::get(ConfigInterface::class);

        if (!$httpFactory instanceof HttpClientFactoryInterface) {
            throw new InvalidParamsException(Exception::PARAMS_HTTP_CLIENT_FACTORY_INVALID, '参数异常: 配置的 `HttpClientFactoryInterface` 不符合规范');
        }

        Logger::info('[Artful] 准备请求第三方 API', $rocket->toArray());

        $http = $httpFactory->create(array_merge($config->get('http', []), $rocket->getPayload()?->get('_http') ?? []));

        Event::dispatch(new Event\HttpStart($rocket));

        try {
            $response = $http->sendRequest($rocket->getRadar());

            $rocket->setDestination(clone $response)
                ->setDestinationOrigin(clone $response);
        } catch (Throwable $e) {
            Logger::error('[Artful] 请求第三方 API 出错', ['message' => $e->getMessage(), 'rocket' => $rocket->toArray(), 'trace' => $e->getTrace()]);

            throw new InvalidResponseException(Exception::REQUEST_RESPONSE_ERROR, '响应异常: 请求第三方 API 出错 - '.$e->getMessage(), [], $e);
        }

        Logger::info('[Artful] 请求第三方 API 成功', ['rocket' => $rocket->toArray()]);

        Event::dispatch(new Event\HttpEnd($rocket));

        return $rocket;
    }

    /**
     * @throws InvalidParamsException
     */
    protected static function verifyPlugin(array $plugins): void
    {
        foreach ($plugins as $plugin) {
            if (is_callable($plugin)) {
                continue;
            }

            if ((is_object($plugin)
                    || (is_string($plugin) && class_exists($plugin)))
                && in_array(PluginInterface::class, class_implements($plugin))) {
                continue;
            }

            throw new InvalidParamsException(Exception::PARAMS_PLUGIN_INCOMPATIBLE, "参数异常: [{$plugin}] 插件未实现 `PluginInterface`");
        }
    }

    /**
     * @throws ContainerException
     */
    private function registerServices(array $config, null|Closure|ContainerInterface $container = null): void
    {
        foreach ($this->coreService as $service) {
            self::registerService($service, ContainerServiceProvider::class == $service ? $container : $config);
        }
    }
}
