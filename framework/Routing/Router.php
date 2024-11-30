<?php

namespace Framework\Routing;

class Router
{
    protected array $routes = [];

    protected Route $current;

    public function add(string $method, string $path, callable $handler)
    {
        $route = $this->routes[] = new Route(
            $method, $path, $handler
        );
    }

    private function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    private function getUri(): string
    {
        return $_SERVER['REQUEST_URI'] ?? '/';
    }

    public function dispatch()
    {
        $paths = $this->paths();
        $requestMethod = $this->getMethod();
        $requestPath = $this->getUri();

        $matching = $this->match($requestMethod, $requestPath);

        if ($matching) {
            try {
                return $matching->dispatch();
            } catch (\Throwable $e) {
                return $this->dispatchError();
            }
        }

        return $this->dispatchNotFound();
    }

    private function paths(): array
    {
        $paths = [];

        foreach ($this->routes as $route) {
            $paths[] = $route->path();
        }

        return $paths;
    }

    private function match(string $method, string $path): ?Route
    {
        foreach ($this->routes as $route) {
            if ($route->matches($method, $path)) {
                return $route;
            }
        }

        return null;
    }

    public function current(): ?Route
    {
        return $this->current;
    }
}