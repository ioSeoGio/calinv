<?php

namespace lib;

class EnvGetter
{
    public static function get(string $env, mixed $default = null): mixed
    {
        return $_ENV[$env] ?? $default;
    }

    public static function getBool(string $env, mixed $default = null): bool
    {
        return (bool) self::get($env, $default);
    }

    public static function getInt(string $env, mixed $default = null): int
    {
        return (int) self::get($env, $default);
    }
}