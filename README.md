# ShinyCore Framework

ShinyCore is a framework designed to quickly build web applications following the Action-Domain-Responder pattern.

## Installation

The easiest and recommended way to start a new ShinyCore based application is to use the ShinyCoreApp. This repository
provides you with a boilerplate application including all necessary files and folders to start your project.

You can install the ShinyCoreApp using composer:

```
composer create-project nekudo/shiny_core_app myshinyproject
``` 

## Documentation

Additionally to this documentation you should also have a look into the ShinyCoreApp sourcecode. It contains some
well documented examples.

### Directory Structure

```
app/            Contains your applications basic files (actions, domains, ...)
bootstrap/      Contains the bootstrap file for your application
config/         Contains the configuration for your application
logs/           Contains log files
public/         Contains the entry script and public files of your application
resources/      Contains applications resouceres like views, templates, ...
routes/         Contains the routes file(s) of your application
vendor/         Contains the ShinyCore framework and other libraries
```

### Configuration

After installing the ShinyCoreApp you should check and adjust the `config.php` file the `config` folder. Most of the
settings should be fine with their default values but if your application needs to use a MySQL database e.g. you need
to adjust some values.

### Routing

The routes of your application define which Action will be executed when a specified URL is requested. Each URL
supported by you application needs to be routed to an action. You can adjust routes using the `default.php` file in the
`routes` folder.

#### GET Route

```php
return [
    'home' => [
        'method' => 'GET',
        'pattern' => '/about',
        'handler' => 'Nekudo\ShinyCoreApp\Actions\AboutAction',
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
        'handler' => 'Nekudo\ShinyCoreApp\Actions\AddCustomerAction',
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
        'handler' => 'Nekudo\ShinyCoreApp\Actions\CustomerAction',
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
        'handler' => 'Nekudo\ShinyCoreApp\Actions\CustomerAction',
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

ShinyCore provides responders for HTML as well as JSON content. You can use this responders by extending the appropriate
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

If you want to reply with HTML content you can inherit from the `HtmlAction` and make use auf the HtmlResponder. This
responder by default provides an PhtmlRenderer so you can use basic PHP/HTML templates in your application.

```php
class MyHtmlAction extends HtmlAction
{
    public function __invoke(array $arguments = []): Response
    {        
        $tmplVars = [
            'name' => 'Max Power',
        ];
        return $this->responder->show('home', $tmplVars);
    }
}
```

This example will render a template called `home.phtml` from your `resources/views/` folder. A `name` variable is passed
to this template. Your `home.phtml` could look something like this:

```html
<p>Hello <?php $this->out('name'); ?></p>
```

### Domains

Domains handle the logic of your application. Domains can be a simple class or any kind of service.

#### Database Domain

If your application needs to interact with a database you can inherit from the `DatabaseDomain` and make use of the
ShinyCore query builder.

```php
class MyDatabaseDomain extends DatabaseDomain
{
    public function getData(): \stdClass
    {
        $result = $this->db->makeSelect()
            ->from('customers')
            ->whereEquals('customer_id', 42)
            ->first();
        
        return $result;
    }
}
```

When extending the `DatabaseDomain` you have access to the query builder factory which provides a powerful tool to
execute your SQL statements.

## License

MIT