<?php declare(strict_types=1);

namespace Qlimix\Router;

use Qlimix\Router\Exception\ConfigurationException;
use Qlimix\Router\Regex\PlaceHolder;
use Qlimix\Router\Regex\PlaceHolderMatcher;
use Throwable;

final class Configurator
{
    private Container $container;

    private PlaceHolderMatcher $matcher;

    public function __construct(Container $container, PlaceHolderMatcher $matcher)
    {
        $this->container = $container;
        $this->matcher = $matcher;
    }

    /**
     * @param PlaceHolder[] $placeHolders
     *
     * @throws ConfigurationException
     */
    public function head(string $path, string $handler, array $placeHolders = []): void
    {
        $this->map(Method::createHead(), $path, $handler, $placeHolders);
    }

    /**
     * @param PlaceHolder[] $placeHolders
     *
     * @throws ConfigurationException
     */
    public function get(string $path, string $handler, array $placeHolders = []): void
    {
        $this->map(Method::createGet(), $path, $handler, $placeHolders);
    }

    /**
     * @param PlaceHolder[] $placeHolders
     *
     * @throws ConfigurationException
     */
    public function post(string $path, string $handler, array $placeHolders = []): void
    {
        $this->map(Method::createPost(), $path, $handler, $placeHolders);
    }

    /**
     * @param PlaceHolder[] $placeHolders
     *
     * @throws ConfigurationException
     */
    public function put(string $path, string $handler, array $placeHolders = []): void
    {
        $this->map(Method::createPut(), $path, $handler, $placeHolders);
    }

    /**
     * @param PlaceHolder[] $placeHolders
     *
     * @throws ConfigurationException
     */
    public function patch(string $path, string $handler, array $placeHolders = []): void
    {
        $this->map(Method::createPatch(), $path, $handler, $placeHolders);
    }

    /**
     * @param PlaceHolder[] $placeHolders
     *
     * @throws ConfigurationException
     */
    public function options(string $path, string $handler, array $placeHolders = []): void
    {
        $this->map(Method::createOptions(), $path, $handler, $placeHolders);
    }

    /**
     * @param PlaceHolder[] $placeHolders
     *
     * @throws ConfigurationException
     */
    public function delete(string $path, string $handler, array $placeHolders = []): void
    {
        $this->map(Method::createDelete(), $path, $handler, $placeHolders);
    }

    /**
     * @param PlaceHolder[] $placeHolders
     *
     * @throws ConfigurationException
     */
    private function map(Method $method, string $path, string $handler, array $placeHolders = []): void
    {
        try {
            $this->container->add(new HttpRoute($method, $path, $handler, $this->matcher->match($path, $placeHolders)));
        } catch (Throwable $exception) {
            throw new ConfigurationException('Failed to configure route', 0, $exception);
        }
    }
}
