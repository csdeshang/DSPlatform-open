<?php

declare(strict_types=1);

namespace Yansongda\Supports;

use ArrayAccess;
use InvalidArgumentException;

/**
 * Most of the methods in this file come from illuminate/support and hyperf/support,
 * thanks provide such a useful class.
 */
class Arr
{
    public static function accessible(mixed $value): bool
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }

    public static function add(array $array, string $key, mixed $value): array
    {
        if (is_null(static::get($array, $key))) {
            static::set($array, $key, $value);
        }

        return $array;
    }

    public static function collapse(array $array): array
    {
        $results = [];
        foreach ($array as $values) {
            if ($values instanceof Collection) {
                $values = $values->all();
            } elseif (!is_array($values)) {
                continue;
            }
            $results[] = $values;
        }

        return array_merge([], ...$results);
    }

    public static function crossJoin(...$arrays): array
    {
        $results = [[]];
        foreach ($arrays as $index => $array) {
            $append = [];
            foreach ($results as $product) {
                foreach ($array as $item) {
                    $product[$index] = $item;
                    $append[] = $product;
                }
            }
            $results = $append;
        }

        return $results;
    }

    public static function divide(array $array): array
    {
        return [array_keys($array), array_values($array)];
    }

    public static function dot(array $array, string $prepend = ''): array
    {
        $results = [];
        foreach ($array as $key => $value) {
            if (is_array($value) && !empty($value)) {
                $results = array_merge($results, static::dot($value, $prepend.$key.'.'));
            } else {
                $results[$prepend.$key] = $value;
            }
        }

        return $results;
    }

    public static function except(array $array, array|string $keys): array
    {
        static::forget($array, $keys);

        return $array;
    }

    public static function exists(array|ArrayAccess $array, int|string $key): bool
    {
        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        }

        return array_key_exists($key, $array);
    }

    public static function first(array $array, ?callable $callback = null, mixed $default = null): mixed
    {
        if (is_null($callback)) {
            if (empty($array)) {
                return $default;
            }
            foreach ($array as $item) {
                return $item;
            }
        }
        foreach ($array as $key => $value) {
            if (call_user_func($callback, $value, $key)) {
                return $value;
            }
        }

        return $default;
    }

    public static function last(array $array, ?callable $callback = null, mixed $default = null): mixed
    {
        if (is_null($callback)) {
            return empty($array) ? $default : end($array);
        }

        return static::first(array_reverse($array, true), $callback, $default);
    }

    public static function flatten(array $array, float|int $depth = INF): array
    {
        $result = [];
        foreach ($array as $item) {
            $item = $item instanceof Collection ? $item->all() : $item;
            if (!is_array($item)) {
                $result[] = $item;
            } elseif (1 === $depth) {
                $result = array_merge($result, array_values($item));
            } else {
                $result = array_merge($result, static::flatten($item, $depth - 1));
            }
        }

        return $result;
    }

    public static function forget(array &$array, array|string $keys): void
    {
        $original = &$array;
        $keys = (array) $keys;
        if (0 === count($keys)) {
            return;
        }
        foreach ($keys as $key) {
            // if the exact key exists in the top-level, remove it
            if (static::exists($array, $key)) {
                unset($array[$key]);

                continue;
            }
            $parts = explode('.', $key);
            // clean up before each pass
            $array = &$original;
            while (count($parts) > 1) {
                $part = array_shift($parts);
                if (isset($array[$part]) && is_array($array[$part])) {
                    $array = &$array[$part];
                } else {
                    continue 2;
                }
            }
            unset($array[array_shift($parts)]);
        }
    }

    public static function get(array|ArrayAccess $array, null|int|string $key = null, mixed $default = null): mixed
    {
        if (!static::accessible($array)) {
            return $default;
        }
        if (is_null($key)) {
            return $array;
        }
        if (static::exists($array, $key)) {
            return $array[$key];
        }
        if (!is_string($key) || !str_contains($key, '.')) {
            return $array[$key] ?? $default;
        }
        foreach (explode('.', $key) as $segment) {
            if (static::accessible($array) && static::exists($array, $segment)) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }

        return $array;
    }

    public static function has(array|ArrayAccess $array, null|array|string $keys): bool
    {
        if (is_null($keys)) {
            return false;
        }
        $keys = (array) $keys;
        if (!$array) {
            return false;
        }
        if ([] === $keys) {
            return false;
        }
        foreach ($keys as $key) {
            $subKeyArray = $array;
            if (static::exists($array, $key)) {
                continue;
            }
            foreach (explode('.', $key) as $segment) {
                if (static::accessible($subKeyArray) && static::exists($subKeyArray, $segment)) {
                    $subKeyArray = $subKeyArray[$segment];
                } else {
                    return false;
                }
            }
        }

        return true;
    }

    public static function isAssoc(array $array): bool
    {
        $keys = array_keys($array);

        return array_keys($keys) !== $keys;
    }

    public static function only(array $array, array|string $keys): array
    {
        return array_intersect_key($array, array_flip((array) $keys));
    }

    public static function pluck(array $array, array|string $value, null|array|string $key = null): array
    {
        $results = [];

        foreach ($array as $item) {
            $itemValue = data_get($item, $value);
            // If the key is "null", we will just append the value to the array and keep
            // looping, Otherwise we will key the array using the value of the key we
            // received from the developer. Then we'll return the final array form.
            if (is_null($key)) {
                $results[] = $itemValue;
            } else {
                $itemKey = data_get($item, $key);
                if (is_object($itemKey) && method_exists($itemKey, '__toString')) {
                    $itemKey = (string) $itemKey;
                }
                $results[$itemKey] = $itemValue;
            }
        }

        return $results;
    }

    public static function prepend(array $array, mixed $value, mixed $key = null): array
    {
        if (is_null($key)) {
            array_unshift($array, $value);
        } else {
            $array = [$key => $value] + $array;
        }

        return $array;
    }

    public static function pull(array &$array, string $key, mixed $default = null): mixed
    {
        $value = static::get($array, $key, $default);
        static::forget($array, $key);

        return $value;
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function random(array $array, int $number = 1): array
    {
        $count = count($array);
        if ($number > $count) {
            throw new InvalidArgumentException("You requested {$number} items, but there are only {$count} items available.");
        }

        if (0 === $number) {
            return [];
        }

        $keys = array_rand($array, $number);
        $results = [];
        foreach ((array) $keys as $key) {
            $results[] = $array[$key];
        }

        return $results;
    }

    /**
     * Set an array item to a given value using "dot" notation.
     * If no key is given to the method, the entire array will be replaced.
     */
    public static function set(array &$array, null|int|string $key, mixed $value): array
    {
        if (is_null($key)) {
            return $array = $value;
        }
        if (!is_string($key)) {
            $array[$key] = $value;

            return $array;
        }
        $keys = explode('.', $key);
        while (count($keys) > 1) {
            $key = array_shift($keys);
            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }
            $array = &$array[$key];
        }
        $array[array_shift($keys)] = $value;

        return $array;
    }

    public static function shuffle(array $array, ?int $seed = null): array
    {
        if (is_null($seed)) {
            shuffle($array);
        } else {
            srand($seed);
            usort($array, function () {
                return rand(-1, 1);
            });
        }

        return $array;
    }

    public static function sort(array $array, callable $callback): array
    {
        $results = [];

        foreach ($array as $key => $value) {
            $results[$key] = $callback($value);
        }

        return $results;
    }

    public static function sortRecursive(array $array): array
    {
        foreach ($array as &$value) {
            if (is_array($value)) {
                $value = static::sortRecursive($value);
            }
        }
        if (static::isAssoc($array)) {
            ksort($array);
        } else {
            sort($array);
        }

        return $array;
    }

    public static function query(array $array, int $encodingType = PHP_QUERY_RFC1738): string
    {
        return http_build_query($array, '', '&', $encodingType);
    }

    public static function toString(array $array, string $separator = '&'): string
    {
        $result = '';

        foreach ($array as $key => $value) {
            $result .= $key.'='.$value.$separator;
        }

        return substr($result, 0, 0 - Str::length($separator));
    }

    public static function where(array $array, callable $callback): array
    {
        return array_filter($array, $callback, ARRAY_FILTER_USE_BOTH);
    }

    public static function wrap(mixed $value): array
    {
        if (is_null($value)) {
            return [];
        }

        return !is_array($value) ? [$value] : $value;
    }

    public static function wrapJson(string $json): ?array
    {
        $result = json_decode($json, true);

        return is_array($result) ? $result : null;
    }

    public static function wrapXml(string $xml): array
    {
        if (empty($xml)) {
            return [];
        }

        $data = json_decode(json_encode(
            simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA),
            JSON_UNESCAPED_UNICODE
        ), true);

        if (JSON_ERROR_NONE === json_last_error()) {
            return $data;
        }

        return [];
    }

    /**
     * @param bool $raw         是否原始解析，有些情况下，原始解析会更好
     * @param bool $spaceToPlus 是否将空格转换为加号
     */
    public static function wrapQuery(string $query, bool $raw = false, bool $spaceToPlus = false): array
    {
        if (!$raw) {
            parse_str($query, $data);

            return $spaceToPlus ? self::querySpaceToPlus($data) : $data;
        }

        if (empty($query) || !Str::contains($query, '=')) {
            return [];
        }

        $result = [];

        foreach (explode('&', $query) as $item) {
            $pos = strpos($item, '=');

            $result[substr($item, 0, $pos)] = substr($item, $pos + 1);
        }

        return $result;
    }

    public static function querySpaceToPlus(array $data): array
    {
        foreach ($data as $key => $item) {
            $data[$key] = is_array($item) ? self::querySpaceToPlus($item) : str_replace(' ', '+', $item);
        }

        return $data;
    }

    public static function unique(array $array): array
    {
        $result = [];
        foreach ($array as $key => $item) {
            if (is_array($item)) {
                $result[$key] = self::unique($item);
            } else {
                $result[$key] = $item;
            }
        }

        if (!self::isAssoc($result)) {
            return array_unique($result);
        }

        return $result;
    }

    public static function merge(array $array1, array $array2, bool $unique = true): array
    {
        $isAssoc = static::isAssoc($array1 ?: $array2);
        if ($isAssoc) {
            foreach ($array2 as $key => $value) {
                if (is_array($value)) {
                    $array1[$key] = static::merge($array1[$key] ?? [], $value, $unique);
                } else {
                    $array1[$key] = $value;
                }
            }
        } else {
            foreach ($array2 as $value) {
                if ($unique && in_array($value, $array1, true)) {
                    continue;
                }
                $array1[] = $value;
            }

            $array1 = array_values($array1);
        }

        return $array1;
    }

    public static function encoding(array $array, string $to_encoding, string $from_encoding = 'gb2312'): array
    {
        $encoded = [];

        foreach ($array as $key => $value) {
            $encoded[$key] = is_array($value) ? self::encoding($value, $to_encoding, $from_encoding) :
                                                mb_convert_encoding($value, $to_encoding, $from_encoding);
        }

        return $encoded;
    }

    public static function camelCaseKey(mixed $data): mixed
    {
        if (!self::accessible($data)
            && !(is_object($data) && method_exists($data, 'toArray'))) {
            return $data;
        }

        $result = [];

        foreach ((is_object($data) ? $data->toArray() : $data) as $key => $value) {
            $result[is_string($key) ? Str::camel($key) : $key] = self::camelCaseKey($value);
        }

        return $result;
    }

    public static function snakeCaseKey(mixed $data): mixed
    {
        if (!self::accessible($data)
            && !(is_object($data) && method_exists($data, 'toArray'))) {
            return $data;
        }

        $result = [];

        foreach ((is_object($data) ? $data->toArray() : $data) as $key => $value) {
            $result[is_string($key) ? Str::snake($key) : $key] = self::snakeCaseKey($value);
        }

        return $result;
    }
}
