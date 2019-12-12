<?php declare(strict_types=1);

namespace Qlimix\Router;

final class Method
{
    private const HEAD = 'head';
    private const GET = 'get';
    private const POST = 'post';
    private const PUT = 'put';
    private const PATCH = 'patch';
    private const DELETE = 'delete';
    private const OPTIONS = 'options';

    private string $method;

    public function __construct(string $method)
    {
        $this->method = $method;
    }

    public function equals(Method $method): bool
    {
        return $method->getMethod() === $this->method;
    }

    public static function createHead(): self
    {
        return new self(self::HEAD);
    }

    public function isHead(): bool
    {
        return $this->method === self::HEAD;
    }

    public static function createGet(): self
    {
        return new self(self::GET);
    }

    public function isGet(): bool
    {
        return $this->method === self::GET;
    }

    public static function createPost(): self
    {
        return new self(self::POST);
    }

    public function isPost(): bool
    {
        return $this->method === self::POST;
    }

    public static function createPut(): self
    {
        return new self(self::PUT);
    }

    public function isPut(): bool
    {
        return $this->method === self::PUT;
    }

    public static function createPatch(): self
    {
        return new self(self::PATCH);
    }

    public function isPatch(): bool
    {
        return $this->method === self::PATCH;
    }

    public static function createDelete(): self
    {
        return new self(self::DELETE);
    }

    public function isDelete(): bool
    {
        return $this->method === self::DELETE;
    }

    public static function createOptions(): self
    {
        return new self(self::OPTIONS);
    }

    public function isOptions(): bool
    {
        return $this->method === self::OPTIONS;
    }

    public function getMethod(): string
    {
        return $this->method;
    }
}
