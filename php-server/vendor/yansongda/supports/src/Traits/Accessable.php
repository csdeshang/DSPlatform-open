<?php

declare(strict_types=1);

namespace Yansongda\Supports\Traits;

use Yansongda\Supports\Str;

trait Accessable
{
    public function __get(string $key): mixed
    {
        return $this->get($key);
    }

    public function __isset(string $key): bool
    {
        return !is_null($this->get($key));
    }

    public function __unset(string $key): void
    {
        $this->offsetUnset($key);
    }

    public function __set(string $key, mixed $value): void
    {
        $this->set($key, $value);
    }

    public function get(?string $key = null, mixed $default = null): mixed
    {
        if (is_null($key)) {
            return method_exists($this, 'toArray') ? $this->toArray() : $default;
        }

        $method = 'get'.Str::studly($key);

        if (method_exists($this, $method)) {
            return $this->{$method}();
        }

        return $default;
    }

    public function set(string $key, mixed $value): self
    {
        $method = 'set'.Str::studly($key);

        if (method_exists($this, $method)) {
            $this->{$method}($value);
        }

        return $this;
    }

    public function offsetExists(mixed $offset): bool
    {
        return !is_null($this->get($offset));
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->get($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->set($offset, $value);
    }

    public function offsetUnset(mixed $offset): void {}
}
