<?php

namespace Framework\Routing;

class Route
{
    private $__handler;

    protected array $errorHandlers = [];

    /**
     * @param string $__method
     * @param string $__path
     * @param callable $handler
     */
    public function __construct(private string $__method, private string $__path, callable $handler)
    {
        $this->__handler = $handler;
    }

    public function getMethod(): string
    {
        return $this->__method;
    }

    public function getPath(): string
    {
        return $this->__path;
    }

    public function matches(string $method, string $path): bool
    {
        return $this->__method === $method && $this->__path === $path;
    }

    public function errorHandler(int $code, callable $handler)
    {
        $this->errorHandlers[$code] = $handler;
    }

    public function dispatchNotAllowed()
    {
        $this->errorHandlers[400] ??= fn() => "not allowed";

        return $this->errorHandlers[400]();
    }

    public function dispatchNotFound()
    {
        $this->errorHandlers[404] ??= fn() => "not found";

        return $this->errorHandlers[404]();
    }

    public function dispatchError()
    {
        $this->errorHandlers[500] ??= fn() => "sever error";

        return $this->errorHandlers[500]();
    }

    public function redirect($path)
    {
        header(
            "Location: {$path}", true, 301
        );
        
        exit;
    }

    public function dispatch()
    {
        return call_user_func($this->__handler);
    }
}