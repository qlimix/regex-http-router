# regex-http-router

[![Travis CI](https://api.travis-ci.org/qlimix/regex-http-router.svg?branch=master)](https://travis-ci.org/qlimix/regex-http-router)
[![Coveralls](https://img.shields.io/coveralls/github/qlimix/regex-http-router.svg)](https://coveralls.io/github/qlimix/regex-http-router)
[![Packagist](https://img.shields.io/packagist/v/qlimix/regex-http-router.svg)](https://packagist.org/packages/qlimix/regex-http-router)
[![MIT License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](https://github.com/qlimix/regex-http-router/blob/master/LICENSE)

Regex HTTP-Router

## Install

Using Composer:

~~~
$ composer require qlimix/regex-http-router
~~~

## usage

Add routes to the container. Use the Method object to create any method. 

The locator should be able to find the handling object via the handler passed to the route.

```php
<?php

use Qlimix\Http\Router\Container;
use Qlimix\Http\Router\Method;
use Qlimix\Http\Router\HttpRoute;

$container = new Container();

$container->add(new HttpRoute(Method::createGet(), '/foo', 'foo.list', []));

```


Add routes to the container via the configurator.

```php
<?php

use Qlimix\Http\Router\Configurator;
use Qlimix\Http\Router\Container;
use Qlimix\Http\Router\Regex\PlaceHolder;
use Qlimix\Http\Router\Regex\PlaceHolderMatcher;
use Qlimix\Http\Router\Regex\Registry;
use Qlimix\Http\Router\Tokenize\CharTokenizer;
use Qlimix\Http\Router\Tokenize\PlaceHolderTokenizer;
use Qlimix\Http\Router\Tokenize\Tokenizer;

$container = new Container();
$registry = new Registry();

$tokenizer = new Tokenizer([
    new PlaceHolderTokenizer(),
    new CharTokenizer(),
]);

$placeHolderMatcher = new PlaceHolderMatcher($tokenizer, $registry);

$configurator = new Configurator($container, $placeHolderMatcher);

$configurator->get('/foo', 'foo.list', []);
$configurator->get('/foo/{id}', 'foo.list', [new PlaceHolder('id', '([\d]+)')]);
$configurator->post('/foo', 'foo.create', []);
$configurator->put('/foo/{id}', 'foo.update', [new PlaceHolder('id', '([\d]+)')]);
$configurator->patch('/foo/{id}', 'foo.patch', [new PlaceHolder('id', '([\d]+)')]);
$configurator->head('/foo/{id}', 'foo.head', [new PlaceHolder('id', '([\d]+)')]);
$configurator->delete('/foo/{id}', 'foo.delete', [new PlaceHolder('id', '([\d]+)')]);
$configurator->options('/foo/{id}', 'foo.options', [new PlaceHolder('id', '([\d]+)')]);
```

You can add global placeholders in the registry if using the configurator.

```php
<?php

use Qlimix\Http\Router\Regex\PlaceHolder;
use Qlimix\Http\Router\Regex\Registry;

$registry = new Registry();
$registry->add(new PlaceHolder('id', '([\d]+)'));
```

Generating the regex expression.

```php
<?php

use Qlimix\Http\Router\Container;
use Qlimix\Http\Router\Match\Builder;
use Qlimix\Http\Router\Match\MatchBuilder;
use Qlimix\Http\Router\Match\MatcherIteratorFactory;
use Qlimix\Http\Router\Tokenize\CharTokenizer;
use Qlimix\Http\Router\Tokenize\PlaceHolderTokenizer;
use Qlimix\Http\Router\Tokenize\Tokenizer;

$container = new Container();

$builder = new Builder(new MatcherIteratorFactory());

$tokenizer = new Tokenizer([
    new PlaceHolderTokenizer(),
    new CharTokenizer(),
]);

$matchBuilder = new MatchBuilder($builder, $tokenizer);

$regex = $matchBuilder->build($container->getAll());
```

Using the router.

```php
<?php

use Qlimix\Http\Router\Container;
use Qlimix\Http\Router\Regex\Matcher;
use Qlimix\Http\Router\RegexHttpRouter;

$container = new Container();
$matcher = new Matcher('regex');
$httpRouter = new RegexHttpRouter($container, $matcher);

$route = $httpRouter->route($request);
```

## Testing
To run all unit tests locally with PHPUnit:

~~~
$ vendor/bin/phpunit
~~~

## Quality
To ensure code quality run grumphp which will run all tools:

~~~
$ vendor/bin/grumphp run
~~~

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.
