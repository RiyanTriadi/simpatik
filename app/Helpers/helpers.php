<?php

if (!function_exists('role_prefix')) {

    function role_prefix(): string
    {
        return auth()->check() ? auth()->user()->role : 'admin';
    }
}

if (!function_exists('role_route')) {
    function role_route(string $name, $parameters = [], bool $absolute = true): string
    {
        return route(role_prefix() . '.' . $name, $parameters, $absolute);
    }
}