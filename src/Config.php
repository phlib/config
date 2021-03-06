<?php
declare(strict_types=1);

namespace Phlib\Config;

use Phlib\Config\Exception\InvalidArgumentException;

/**
 * Get config item using "dot" notation.
 *
 * Function works like array_get but looks deep into the config array
 * name is a nested key key description, each key is listed separated by a full stop '.'
 *
 * @param array $config
 * @param string $name
 * @param mixed $default
 * @return mixed
 */
function get(array $config, string $name, $default = null)
{
    $value = $config;
    $key   = strtok($name, '.');
    do {
        if (!(is_array($value) && array_key_exists($key, $value))) {
            return $default;
        }

        $value = $value[$key];
    } while ($key = strtok('.'));

    return $value;
}

/**
 * Set a config item to a given value using "dot" notation.
 *
 * @param array $array
 * @param string $key
 * @param mixed $value
 * @return array
 */
function set(array &$array, string $key, $value): array
{
    $result = &$array;
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

    return $result;
}

/**
 * Remove an array item from a given array using "dot" notation.
 *
 * @param array $array
 * @param string $key
 * @return array
 */
function forget(array &$array, string $key): array
{
    $result = &$array;
    $keys = explode('.', $key);
    while (count($keys) > 1) {
        $key = array_shift($keys);
        if (!isset($array[$key]) || !is_array($array[$key])) {
            return $result;
        }
        $array = &$array[$key];
    }
    unset($array[array_shift($keys)]);

    return $result;
}

/**
 * Override the values from one array with values from another array
 *
 * @param array $base
 * @param array ...$arrays
 * @return array
 */
function override(array $base, ...$arrays): array
{
    if (count($arrays) == 0) {
        throw new InvalidArgumentException('Override requires at least two arrays');
    }

    $canBeOverridden = function($baseArray, $key, $value) {
        return (array_key_exists($key, $baseArray) &&
            is_array($value) &&
            !array_key_exists(0, $value) &&
            (!empty($value) || !array_key_exists(0, $baseArray[$key]))
        );
    };

    foreach ($arrays as $array) {
        foreach ($array as $key => $value) {
            if ($canBeOverridden($base, $key, $value)) {
                if (!is_array($base[$key])) {
                    $base[$key] = [];
                }
                $base[$key] = override($base[$key], $array[$key]);
            } else {
                $base[$key] = $value;
            }
        }
    }

    return $base;
}

/**
 * Flatten a multi-dimensional associative array with dots.
 *
 * @param array $config
 * @param string $prepend
 * @return array
 */
function flatten(array $config, string $prepend = ''): array
{
    $results = array();
    foreach ($config as $key => $value) {
        if (is_array($value)) {
            $results = array_merge($results, flatten($value, $prepend.$key.'.'));
        } else {
            $results[$prepend.$key] = $value;
        }
    }

    return $results;
}

/**
 * Expand an associative from "dot" notation into a multi-dimensional array.
 *
 * @param array $flatConfig
 * @return array
 */
function expand(array $flatConfig): array
{
    ksort($flatConfig);

    $result = [];
    foreach ($flatConfig as $fullKey => $value) {
        $target = &$result;
        $keyList = explode('.', $fullKey);
        foreach ($keyList as $pos => $key) {
            if (count($keyList) - 1 == $pos) {
                $target[$key] = $value;
            } else {
                if (!array_key_exists($key, $target)) {
                    $target[$key] = [];
                }
                $target = &$target[$key];
            }
        }
    }

    return $result;
}

