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

use Hyperf\Contract\ContainerInterface;
use Hyperf\Pimple\Exception\InvalidDefinitionException;
use Hyperf\Pimple\Exception\NotFoundException;
use Hyperf\Pimple\Exception\NotSupportException;
use Pimple;
use Psr\Container\ContainerInterface as PsrContainerInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionParameter;

class Container implements ContainerInterface
{
    /**
     * @var ReflectionClass[]
     */
    protected $reflection = [];

    public function __construct(protected Pimple\Container $pimple)
    {
        $this->pimple[ContainerInterface::class] = $this;
        $this->pimple[PsrContainerInterface::class] = $this;
    }

    public function get($id)
    {
        if ($this->has($id)) {
            return $this->pimple[$id];
        }

        return $this->pimple[$id] = $this->make($id);
    }

    public function has($id): bool
    {
        return isset($this->pimple[$id]);
    }

    public function make(string $name, array $parameters = [])
    {
        if (! class_exists($name)) {
            throw new NotFoundException("Entry {$name} is not found.");
        }

        $ref = $this->reflection[$name] ?? new ReflectionClass($name);
        $constructor = $ref->getConstructor();
        $args = [];
        if ($constructor && $constructor->isPublic()) {
            $args = $this->resolveParameters($constructor, $parameters);
        }

        $instance = new $name(...$args);

        $this->reflection[$name] = $ref;

        return $instance;
    }

    public function set(string $name, $entry): void
    {
        $this->pimple[$name] = $entry;
    }

    public function define(string $name, $definition): void
    {
        throw new NotSupportException('Method define is not support.');
    }

    public function unbind(string $name): void
    {
        $this->pimple[$name] = null;
    }

    protected function resolveParameters(ReflectionMethod $method, $parameters = [])
    {
        $args = [];
        foreach ($method->getParameters() as $index => $parameter) {
            if (array_key_exists($parameter->getName(), $parameters)) {
                $value = $parameters[$parameter->getName()];
            } elseif (array_key_exists($index, $parameters)) {
                $value = $parameters[$index];
            } elseif ($parameter->getType() && $this->has($parameter->getType()->getName())) {
                $value = $this->get($parameter->getType()->getName());
            } else {
                if ($parameter->isDefaultValueAvailable() || $parameter->isOptional()) {
                    $value = $this->getParameterDefaultValue($parameter, $method);
                } else {
                    throw new InvalidDefinitionException(sprintf(
                        'Parameter $%s of %s has no value defined or guessable',
                        $parameter->getName(),
                        $this->getFunctionName($method)
                    ));
                }
            }

            $args[] = $value;
        }

        return $args;
    }

    protected function getParameterDefaultValue(ReflectionParameter $parameter, ReflectionMethod $function)
    {
        try {
            return $parameter->getDefaultValue();
        } catch (ReflectionException $e) {
            throw new InvalidDefinitionException(sprintf(
                'The parameter "%s" of %s has no type defined or guessable. It has a default value, '
                . 'but the default value can\'t be read through Reflection because it is a PHP internal class.',
                $parameter->getName(),
                $this->getFunctionName($function)
            ));
        }
    }

    private function getFunctionName(ReflectionMethod $method): string
    {
        return $method->getName() . '()';
    }
}
