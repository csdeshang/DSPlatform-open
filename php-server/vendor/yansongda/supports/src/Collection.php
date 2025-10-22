<?php

declare(strict_types=1);

namespace Yansongda\Supports;

use ArrayAccess;
use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use JsonSerializable;
use Traversable;
use Yansongda\Supports\Traits\Accessable;
use Yansongda\Supports\Traits\Arrayable;
use Yansongda\Supports\Traits\Serializable;

class Collection implements ArrayAccess, Countable, IteratorAggregate, JsonSerializable
{
    use Serializable;
    use Accessable;
    use Arrayable;

    protected array $items = [];

    public function __construct(mixed $items = [])
    {
        foreach ($this->getArrayableItems($items) as $key => $value) {
            $this->set($key, $value);
        }
    }

    public static function wrap(mixed $value): self
    {
        return $value instanceof self ? new static($value) : new static(Arr::wrap($value));
    }

    public static function wrapJson(string $json): self
    {
        return new static(Arr::wrapJson($json));
    }

    public static function wrapXml(string $xml): self
    {
        return new static(Arr::wrapXml($xml));
    }

    public static function wrapQuery(string $query, bool $raw = false, bool $spaceToPlus = false): self
    {
        return new static(Arr::wrapQuery($query, $raw, $spaceToPlus));
    }

    public static function unwrap(array|Collection $value): array
    {
        return $value instanceof self ? $value->all() : $value;
    }

    public function all(): array
    {
        return $this->items;
    }

    public function only(array $keys): array
    {
        $return = [];

        foreach ($keys as $key) {
            $value = $this->get($key);

            if (!is_null($value)) {
                $return[$key] = $value;
            }
        }

        return $return;
    }

    public function except(mixed $keys): self
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        return new static(Arr::except($this->items, $keys));
    }

    public function filter(?callable $callback = null): self
    {
        if ($callback) {
            return new static(Arr::where($this->items, $callback));
        }

        return new static(array_filter($this->items));
    }

    public function merge(mixed $items): self
    {
        return new static(array_merge($this->items, $this->getArrayableItems($items)));
    }

    public function has(int|string $key): bool
    {
        return !is_null(Arr::get($this->items, $key));
    }

    public function first(): mixed
    {
        return reset($this->items);
    }

    public function last(): mixed
    {
        $end = end($this->items);

        reset($this->items);

        return $end;
    }

    public function add(null|int|string $key, mixed $value): void
    {
        Arr::set($this->items, $key, $value);
    }

    public function set(null|int|string $key, mixed $value): void
    {
        Arr::set($this->items, $key, $value);
    }

    public function get(null|int|string $key = null, mixed $default = null): mixed
    {
        return Arr::get($this->items, $key, $default);
    }

    public function forget(int|string $key): void
    {
        Arr::forget($this->items, $key);
    }

    public function flatten(float|int $depth = INF): self
    {
        return new static(Arr::flatten($this->items, $depth));
    }

    public function map(callable $callback): self
    {
        $keys = array_keys($this->items);
        $items = array_map($callback, $this->items, $keys);

        return new static(array_combine($keys, $items));
    }

    public function pop(): mixed
    {
        return array_pop($this->items);
    }

    public function prepend(mixed $value, mixed $key = null): self
    {
        $this->items = Arr::prepend($this->items, $value, $key);

        return $this;
    }

    public function push(mixed $value): self
    {
        $this->offsetSet(null, $value);

        return $this;
    }

    public function pull(mixed $key, mixed $default = null): mixed
    {
        return Arr::pull($this->items, $key, $default);
    }

    public function put(mixed $key, mixed $value): self
    {
        $this->offsetSet($key, $value);

        return $this;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function random(?int $number = null): self
    {
        return new static(Arr::random($this->items, $number ?? 1));
    }

    public function reduce(callable $callback, mixed $initial = null): mixed
    {
        return array_reduce($this->items, $callback, $initial);
    }

    public function values(): self
    {
        return new static(array_values($this->items));
    }

    public function every(callable|string $key): bool
    {
        $callback = $this->valueRetriever($key);

        foreach ($this->items as $k => $v) {
            if (!$callback($v, $k)) {
                return false;
            }
        }

        return true;
    }

    public function chunk(int $size): self
    {
        if ($size <= 0) {
            return new static();
        }
        $chunks = [];
        foreach (array_chunk($this->items, $size, true) as $chunk) {
            $chunks[] = new static($chunk);
        }

        return new static($chunks);
    }

    public function sort(?callable $callback = null): self
    {
        $items = $this->items;
        $callback ? uasort($items, $callback) : asort($items);

        return new static($items);
    }

    public function sortBy(callable|string $callback, int $options = SORT_REGULAR, bool $descending = false): self
    {
        $results = [];
        $callback = $this->valueRetriever($callback);
        // First we will loop through the items and get the comparator from a callback
        // function which we were given. Then, we will sort the returned values
        // and grab the corresponding values for the sorted keys from this array.
        foreach ($this->items as $key => $value) {
            $results[$key] = $callback($value, $key);
        }
        $descending ? arsort($results, $options) : asort($results, $options);
        // Once we have sorted all the keys in the array, we will loop through them
        // and grab the corresponding model, so we can set the underlying items list
        // to the sorted version. Then we'll just return the collection instance.
        foreach (array_keys($results) as $key) {
            $results[$key] = $this->items[$key];
        }

        return new static($results);
    }

    public function sortByDesc(callable|string $callback, int $options = SORT_REGULAR): self
    {
        return $this->sortBy($callback, $options, true);
    }

    public function sortKeys(int $options = SORT_REGULAR, bool $descending = false): self
    {
        $items = $this->items;
        $descending ? krsort($items, $options) : ksort($items, $options);

        return new static($items);
    }

    public function sortKeysDesc(int $options = SORT_REGULAR): self
    {
        return $this->sortKeys($options, true);
    }

    public function query(int $encodingType = PHP_QUERY_RFC1738): string
    {
        return Arr::query($this->all(), $encodingType);
    }

    public function toString(string $separator = '&'): string
    {
        return Arr::toString($this->all(), $separator);
    }

    public function toArray(): array
    {
        return $this->all();
    }

    public function toXml(): string
    {
        $xml = '<xml>';

        foreach ($this->all() as $key => $val) {
            $xml .= is_numeric($val) ? '<'.$key.'>'.$val.'</'.$key.'>' :
                                       '<'.$key.'><![CDATA['.$val.']]></'.$key.'>';
        }

        $xml .= '</xml>';

        return $xml;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    public function offsetUnset(mixed $offset): void
    {
        if ($this->offsetExists($offset)) {
            $this->forget($offset);
        }
    }

    protected function useAsCallable(mixed $value): bool
    {
        return !is_string($value) && is_callable($value);
    }

    protected function valueRetriever(mixed $value): callable
    {
        if ($this->useAsCallable($value)) {
            return $value;
        }

        return function ($item) use ($value) {
            return data_get($item, $value);
        };
    }

    protected function getArrayableItems(mixed $items): array
    {
        if (is_array($items)) {
            return $items;
        }

        if ($items instanceof self) {
            return $items->all();
        }

        if ($items instanceof JsonSerializable) {
            return $items->jsonSerialize();
        }

        return (array) $items;
    }
}
