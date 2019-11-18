<p align="center">
    <img src="https://bloatless.org/img/logo.svg" width="60px" height="80px">
</p>

<h1 align="center">Bloatless Endocore</h1>

<p align="center">
    Endocore is a framework designed to quickly build web applications following the Action-Domain-Responder pattern.
</p>

## Installation

The easiest and recommended way to start a new Endocore based application is to use the
[Endocore Sample App](https://github.com/bloatless/endocore-app). This repository provides you with a boilerplate 
application including all necessary files and folders to start your project.

You can install the Endocore App using composer:

```
php composer.phar create-project bloatless/endocore-app my_sample_project
``` 

## Documentation

Additionally to this documentation you should also have a look into the Endocore App sourcecode. It contains some
well documented examples.


- [Directory Structure](#directory-structure)
- [Configuration](#configuration)
- [Routing](#routing)
  * [GET Route](#get-route)
  * [POST Route](#post-route)
  * [Other route types](#other-route-types)
  * [Route parameters](#route-parameters)
- [Actions and Responder](#actions-and-responder)
  * [Actions with JSON response](#actions-with-json-response)
  * [Actions with HTML response](#actions-with-html-response)
- [Domains](#domains)
- [Error Handling and Logging](#error-handling-and-logging)
  * [Using the file logger](#using-the-file-logger)
    + [Logger configuration](#logger-configuration)
    + [Log Levels](#log-levels)
  * [Error responses](#error-responses)
  * [Throwing exceptions](#throwing-exceptions)    
    + [Generic Exceptions](#generic-exceptions)
    + [HTTP Exceptions](#http-exceptions)

### Directory Structure

```
app/            Contains your applications basic files (actions, domains, ...)
bootstrap/      Contains the bootstrap file for your application
config/         Contains the configuration for your application
logs/           Contains log files
public/         Contains the entry script and public files of your application
routes/         Contains the routes file(s) of your application
vendor/         Contains the Endocore framework and other libraries
```

### Configuration

After installing the Endocore App you should check and adjust the `config.php` file the `config` folder. Most of the
settings should be fine with their default values but if your application needs to use a MySQL database e.g. you need
to add or adjust some values.

### Routing

The routes of your application define which Action will be executed when a specified URL is requested. Each URL
supported by your application needs to be routed to an action. You can adjust routes using the `default.php` file in the
`routes` folder.

#### GET Route

```php
return [
    'home' => [
        'method' => 'GET',
        'pattern' => '/about',
        'handler' => 'Bloatless\EndocoreApp\Actions\AboutAction',
    ],
];
```

This example routes the request path `/about` to the AboutAction in your applications Actions folder.

#### POST Route

```php
return [
    'home' => [
        'method' => 'POST',
        'pattern' => '/customers',
        'handler' => 'Bloatless\EndocoreApp\Actions\AddCustomerAction',
    ],
];
```

This example handles a POST request to `/customers` and calls the `AddCustomerAction` in your application.

#### Other route types

Of course you can also define `PUT`, `DELETE` or any other valid request types in the same manner.

#### Route parameters

You can use placeholders within route patterns. These parameters will be passed to your action using the `$arguments`
array.

```php
return [
    'customer' => [
        'method' => 'GET',
        'pattern' => '/customers/{id}',
        'handler' => 'Bloatless\EndocoreApp\Actions\CustomerAction',
    ],
];
```

This route would match URLs like e.g. `/customers/123`. The CustomerAction would receive an `$arguments` array like
this:

```php
[
    'id' => 123
]
```

You can additionally use a regular expression pattern to define which values the placeholder accepts.

```php
return [
    'customer' => [
        'method' => 'GET',
        'pattern' => '/customers/{id:[0-9]+}',
        'handler' => 'Bloatless\EndocoreApp\Actions\CustomerAction',
    ],
];
```

This route for example would match `/customers/123` but not `customers/abc`.

Segments wrapped in square brackets are considered optional like e.g.:
 
 `pattern' => '/customers[/{id:[0-9]+]'` 

### Actions and Responder

Every request the application receives will be dispatched to an action as defined in your routes. The action handles
this request. Typically by requesting data from a domain using input data from the request. The data from the domain
is than passed to a responder which builds the HTTP response. This response is than returned back into the application
by the action.

Endocore provides responders for HTML as well as JSON content. You can use this responders by extending the appropriate
actions. 

#### Actions with JSON response

```php
class MyJsonAction extends JsonAction
{
    public function __invoke(array $arguments = []): Response
    {
        $data = [
            'foo' => 'Some data...',
        ];
        return $this->responder->found($data);
    }
}
```

This example shows how an Action inherits the JsonResponder from the `JsonAction` and is than able to respond json data
using only methods provided by the framework.

#### Actions with HTML response

If you want to reply with HTML content you can inherit from the `HtmlAction` and make use auf the HtmlResponder.

```php
class MyHtmlAction extends HtmlAction
{
    public function __invoke(array $arguments = []): Response
    {        
        $data = [
            'body' => '<p>Hello World!</p>',
        ];
        return $this->responder->found($data);
    }
}
```

This example will output a simple Hello World paragraph.

### Domains

Domains handle the logic of your application. Domains can be a simple class or any kind of service.

### Error Handling and Logging

The Endocore framework provides some basic tools to handle errors and logging.

#### Using the file logger

From within any `Action` or `Domain` you have access to a PSR-3 compatible file logger.

```php
class MyHtmlAction extends HtmlAction
{
    public function __invoke(array $arguments = []): Response
    {
        $this->logger->warning('Some error occurred');  
    }
}
```
##### Logger Configuration

Using you configuration file `config/config.php` you can define the target folder for your log-files as well as the
min. log level:

```php
'logger' => [
    'path_logs' => __DIR__ . '/../logs',
    'min_level' => 'warning',
],
```

The log files will be stored per day with a filename like `2018-12-12_endocore.log`.

##### Log levels

A PSR-3 compatible logger can log at different levels. All events with a level lower than the min. level defined
in your configuration will be dropped. The available log levels are:

```php
$this->logger->debug('Some error occurred');
$this->logger->notice('Some error occurred');
$this->logger->info('Some error occurred');
$this->logger->warning('Some error occurred');
$this->logger->error('Some error occurred');
$this->logger->critial('Some error occurred');
$this->logger->alert('Some error occurred');
$this->logger->emergency('Some error occurred');
```

There is also a generic `log` method available:

```php
$this->logger->log('warning', 'Some error occurred');
```

Additionally it is possible to provide some context information:

```php
$this->logger->warning('Some error message', [
    'browser' => 'Firefox',
]);
```

#### Error responses

When using a `Responder` (typically from within an `Action`) you can use various methods in case an error occurrs in
your application and you need to stop the execution:

```php
class MyHtmlAction extends HtmlAction
{
    public function __invoke(array $arguments = []): Response
    {
        // some error occurs...
        return $this->responder->error(['data' => 'some additional data...']);
    }
}
```

This method will automatically respond with an HTTP status code 500 and render a simple error message.

For HTTP errors there are some additional methods which will set there corresponding HTTP status codes automatically:

```php
class MyHtmlAction extends HtmlAction
{
    public function __invoke(array $arguments = []): Response
    {
        return $this->responder->badRequest(); // 400
        return $this->responder->notFound(); // 404
        return $this->responder->methodNotAllowed(); // 405
    }
}
```

#### Throwing exceptions

In case you can not use a responder you are still able to respond with an error message using exceptions. You can
throw there exceptions from anywhere in your application. 

##### Generic Exceptions

```php
class MyDomain
{
    public function myMethod(): string
    {
        throw new EndocoreException('Something went wrong...');
    }
}
```

Throwing a `EndocoreException` will force the application to respond with an error 500 code. Also the error will be
logged to your logfile.

##### HTTP Exceptions

For each HTTP error supported by a `Responder` there is a corresponding Exception which you can throw anywhere in your
application:

```php
class MyDomain
{
    public function myMethod(): string
    {
        throw new BadRequestException(); // 400
        throw new NotFoundException(); // 404
        throw new MethodNotAllowedException(); // 405
    }
}
```

## License

MIT