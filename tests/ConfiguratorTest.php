<?php declare(strict_types=1);

namespace Qlimix\Tests\Router;

use PHPUnit\Framework\TestCase;
use Qlimix\Router\Configurator;
use Qlimix\Router\Container;
use Qlimix\Router\Exception\ConfigurationException;
use Qlimix\Router\Regex\PlaceHolder;
use Qlimix\Router\Regex\PlaceHolderMatcher;
use Qlimix\Router\Regex\Registry;
use Qlimix\Router\Tokenize\CharTokenizer;
use Qlimix\Router\Tokenize\PlaceHolderTokenizer;
use Qlimix\Router\Tokenize\Tokenizer;

final class ConfiguratorTest extends TestCase
{
    public function testShouldConfigure(): void
    {
        $placeHolderRegistry = new Registry();
        $container = new Container();
        $placeHolderMatcher = new PlaceHolderMatcher(
            new Tokenizer([
                new PlaceHolderTokenizer(),
                new CharTokenizer(),
            ]),
            $placeHolderRegistry
        );

        $placeHolderRegistry->add(new PlaceHolder('id', '([\d]+)'));

        $configurator = new Configurator($container, $placeHolderMatcher);

        $configurator->get('/foo', 'get.foo', []);
        $configurator->post('/foo/{id}', 'post.foo', []);
        $configurator->put('/foo/{id}', 'put.foo', []);
        $configurator->patch('/foo/{id}', 'patch.foo', []);
        $configurator->head('/foo/{id}', 'head.foo', []);
        $configurator->options('/foo/{id}', 'options.foo', []);
        $configurator->delete('/foo/{id}', 'delete.foo', []);

        $this->assertCount(7, $container->getAll());
    }

    public function testShouldThrowOnPlaceHolderNotFound(): void
    {
        $placeHolderRegistry = new Registry();
        $container = new Container();
        $placeHolderMatcher = new PlaceHolderMatcher(
            new Tokenizer([
                new PlaceHolderTokenizer(),
                new CharTokenizer(),
            ]),
            $placeHolderRegistry
        );

        $configurator = new Configurator($container, $placeHolderMatcher);

        $this->expectException(ConfigurationException::class);
        $configurator->post('/foo/{id}', 'post.foo', []);
    }
}
