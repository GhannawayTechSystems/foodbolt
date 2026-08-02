<?php

declare(strict_types=1);

/**
 * Tiny front-controller / router.
 *
 * Dispatches based on the `r` query parameter (e.g. /index.php?r=kitchen/show&id=...).
 * Keeps the app runnable on hosts without mod_rewrite, while still supporting
 * clean-ish URLs. Each route maps to a Controller class method.
 */

final class Router
{
    private array $routes = [];

    public function add(string $route, callable $handler): void
    {
        $this->routes[$route] = $handler;
    }

    public function dispatch(string $route): void
    {
        $route = $route ?: 'home/index';
        if (isset($this->routes[$route])) {
            ($this->routes[$route])();
            return;
        }
        http_response_code(404);
        echo view_string('errors/404', ['path' => $route]);
    }
}
