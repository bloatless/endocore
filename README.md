<p align="center">
    <img src="https://static.samtleben.me/github/shinycore_logo.svg" width="80" height="80">
</p>

# ShinyCore Framework

ShinyCore is a framework designed to quickly build web applications following the Action-Domain-Responder pattern.

## Installation

The easiest and recommended way to start a new ShinyCore based application is to use the [ShinyCoreApp](https://github.com/nekudo/shiny_core_app). This repository
provides you with a boilerplate application including all necessary files and folders to start your project.

You can install the ShinyCoreApp using composer:

```
php composer.phar create-project nekudo/shiny_core_app myshinyproject
``` 

## Documentation

Additionally to this documentation you should also have a look into the ShinyCoreApp sourcecode. It contains some
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
  * [(P)HTML Templates](#phtml-templates)
    + [Renderer configuration](#renderer-configuration)
    + [Views and layouts](#views-and-layouts)
    + [Template variables](#template-variables)
    + [Displaying data](#displaying-data)
- [Domains](#domains)
  * [Database Domain](#database-domain)
- [Query Builder](#query-builder)
  * [Connections](#connections)
  * [Factory](#factory)
  * [SELECT](#select)
    + [A simple select](#a-simple-select)
    + [Table and column alias](#table-and-column-alias)
    + [Get specific columns](#get-specific-columns)
    + [First row only](#first-row-only)
    + [Single column as array](#single-column-as-array)
    + [Counting rows](#counting-rows)
    + [Joins](#joins)
    + [Group by](#group-by)
    + [Order by](#order-by)
    + [Having](#having)
    + [Limit and Offset](#limit-and-offset)
    + [Distinct](#distinct)
  * [UPDATE](#update)
  * [DELETE](#delete)
  * [WHERE](#where)
    + [Simple where](#simple-where)
    + [Or where](#or-where)
    + [Where in](#where-in)
    + [Where not in](#where-not-in)
    + [Or where in](#or-where-in)
    + [Or where not in](#or-where-not-in)
    + [Where between](#where-between)
    + [Or where between](#or-where-between)
    + [Where null](#where-null)
    + [Where not null](#where-not-null)
    + [Or where null](#or-where-null)
    + [Or where not null](#or-where-not-null)
  * [INSERT](#insert)
    + [Single row](#single-row)
    + [Multiple rows](#multiple-rows)
    + [Last insert id](#last-insert-id)
  * [RAW Queries](#raw-queries)
    + [Raw select queries](#raw-select-queries)
    + [Other raw queries](#other-raw-queries)
  * [Reset](#reset)
  * [Security](#security)
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
supported by your application needs to be routed to an action. You can adjust routes using the `default.php` file in the
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

#### (P)HTML Templates

The `HtmlResponder` (accessible from within each `HtmlAction`) by default comes with a (P)HTML renderer. This feature
allows the basic usage of HTML templates and layout in your application.

##### Renderer Configuration

The paths to you view and layout files can be set in you `config/config.php` file:

```php
'paths' => [
    'views' => __DIR__ . '/../resources/views',
    'layouts' => __DIR__ . '/../resources/views/layouts',
],
```

##### Views and layouts

In its essence a `view` is nothing more than an html file. In a typical web-application every page corresponds to one
`view`. You could for example have a view `home.phtml` for your homepage, a `imprint.phtml` for your imprint and so on.

A `view` can be rendered and displayed from within an `action` like this:

```php
class HomeAction extends HtmlAction
{
    public function __invoke(array $arguments = []): Response
    {
        return $this->responder->show('home');
    }
}
```

A `layout` is normally some code that this shared by multiple views like e.g. the websites basic structure including
a page header and footer.

A `view` can extend a `layout` using a simple html comment:

```html
<!-- extends "default" -->
<h1>My Homeppage</h1>
<p>foo bar baz</p>
```

In this example a `view` (e.g. your `home.phtml`) would be embedded into the `default.phtml` layout. This layout file
would look something like this:

```html
...
<head>    
    <title>My website</title>
</head>

<body>
    <div class="container">
        <?php $this->out('content', false); ?>
    </div>
</body>
...
```

The only required part is the php code displaying the content. This is the place where the `view` will be included
into the `layout`.

##### Template variables

In most web-applications you would want to pass some kind of data from your domains to your template files. This
can be done using the `assign` method of the `HtmlResponder` or by using a the second argument of the show method:

```php
class HomeAction extends HtmlAction
{
    public function __invoke(array $arguments = []): Response
    {
        // using assign method:
        $this->responder->assign(['firstname' => 'Homer']);
        
        // passing to show method:
        return $this->responder->show('home', [
            'lastname' => 'Simpson',
        ]);
    }
}
```

This data you pass to the `HtmlResponder` to be displayed within you template are generally called `template variables`.

##### Displaying data

Displaying template variables inside your templates is pretty easy. You can simply use a method called `out` which is
available in every `view`or `layout`.

```html
<p>
    Hello <?php $this->out('firstname'); ?>,<br>
</p>
```

If you use the out method the corresponding variable is automatically sent through the `htmlentities` method of
PHP to prevent XSS attacks.

If you want to display unescaped data you do this like this:

```html
<p>
    Hello <?php $this->out('lastname', false); ?>,<br>
</p>
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

### Query Builder

As mentioned earlier Database-Domains provide a powerful Query Builder. This section explains the complete usage API
of the ShinyCore Query Builder.

#### Connections

You can define multiple database connections in your projects `config.php` file.

```php
'db' => [
    'connections' => [
        'db1' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'db1',
            'username' => 'root',
            'password' => 'your-password',
            'charset' => 'utf8', // Optional
            'timezone' => 'Europe/Berlin', // Optional
        ],
        
        // add additional connections here...
    ],

    'default_connection' => 'db1',
]
```

#### Factory

Within each `DatabaseDomain` you can create new QueryBuilder instances using a Factory which is available via
the `db` property of the domain.

```php
$selectQueryBuilder = $this->db->makeSelect();
$updateQueryBuilder = $this->db->makeUpdate();
$deleteQueryBuilder = $this->db->makeDelete();
$insertQueryBuilder = $this->db->makeInsert();
$rawQueryBuilder = $this->db->makeRaw();
```

With no arguments provided the default database connection is used. If you want to use a different connection you can
pass the connection name as an argument.

```php
$updateQueryBuilder = $this->db->makeUpdate('db2');
```

#### SELECT

##### A simple select

```php
$rows = $this->db->makeSelect()->from('customers')->get();
```

##### Table and column alias

Aliases can be used on table names as well as on column names.

```php
$rows = $this->db->makeSelect()
    ->cols(['customer_id AS id', 'firstname', 'lastname'])
    ->from('customers AS c')
    ->get();
```

##### Get specific columns

```php
$rows = $this->db->makeSelect()
    ->cols(['customer_id', 'firstname', 'lastname'])
    ->from('customers')
    ->get();
```

##### First row only

```php
$row = $this->db->makeSelect()
    ->from('customers')
    ->whereEquals('customer_id', 42)
    ->first();
```

##### Single column as array

```php
$names = $this->db->makeSelect()
    ->from('customers')
    ->pluck('firstname');
```

Will fetch an array containing all first names of the `customers` table.

You can specify a second column which will be used for the keys of the array:

```php
$names = $this->db->makeSelect()
    ->from('customers')
    ->pluck('firstname', 'customer_id');
```

Will fetch an array of all first names using the `customer_id` as array key.

##### Counting rows

```php
$rowCount = $this->db->makeSelect()
    ->from('customers')
    ->count();
```

##### Joins

You can join tables using the `join`, `leftJoin` or `rightJoin` methods. You can of course join multiple tables.

```php
$rows = $this->db->makeSelect()
    ->from('customers')
    ->join('orders', 'customers.customer_id', '=', 'orders.customer_id')
    ->get();
```

##### Group by

```php
$rows = $this->db->makeSelect()
    ->from('orders')
    ->groupBy('customer_id')
    ->get();
```

##### Order by

```php
$rows = $this->db->makeSelect()
    ->from('customers')
    ->orderBy('firstname', 'desc')
    ->get();
```

##### Having

```php
$rows = $this->db->makeSelect()
    ->from('orders')
    ->having('amount', '>', 10)
    ->orHaving('cart_items', '>' 5)
    ->get();
```

##### Limit and Offset

```php
$rows = $this->db->makeSelect()
    ->from('orders')
    ->limit(10)
    ->offset(20)
    ->get();
```

##### Distinct

```php
$rows = $this->db->makeSelect()
    ->distinct()
    ->from('orders')
    ->get();
```

#### UPDATE

```php
$rows = $this->db->makeUpdate()
    ->table('customers')
    ->whereEquals('customer_id', 42)
    ->update([
        'firstname' => 'Homer'
    ]);
```

#### DELETE

```php
$rows = $this->db->makeDelete()
    ->from('customers')
    ->whereEquals('customer_id', 42)
    ->delete();
```

#### WHERE

You can use various where clauses on all `select`, `update` and `delete` queries:

##### Simple where

```php
$rows = $this->db->makeSelect()
    ->from('customers')
    ->where('customer_id', '=', 42)
    ->where('customer_id', '>', 10)
    ->whereEquals('customer_id', 42)
    ->get();
```

##### Or where

```php
->orWhere('customer_id', '>', 5)
```

##### Where in

```php
->whereIn('customer_id', [1,2,3])
```

##### Where not in

```php
->whereNotIn('customer_id', [1,2,3])
```

##### Or where in

```php
->orWhereIn('customer_id', [1,2,3])
```

##### Or where not in

```php
->orWhereNotIn('customer_id', [1,2,3])
```

##### Where between

```php
->whereBetween('customer_id', 5, 10)
```

##### Or where between

```php
->orWhereBetween('customer_id', 5, 10)
```

##### Where null

```php
->whereNull('customer_id')
```

##### Where not null

```php
->whereNotNull('customer_id')
```

##### Or where null

```php
->orWhereNull('customer_id')
```

##### Or where not null

```php
->orWhereNotNull('customer_id')
```

#### INSERT

##### Single row

```php
$customerId = $this->db->makeInsert()
    ->into('customers')
    ->row([
        'firstname' => 'Homer',
        'lastname' => 'Simpson',
    ]);
```

When inserting a single row, the auto-increment value of the newly added row will be returned.

##### Multiple rows

You can insert multiple rows at once using the `rows` method:

```php
$this->db->makeInsert()
    ->into('customers')
    ->rows([
        [
            'firstname' => 'Homer',
            'lastname' => 'Simpson',
        ],
        [
            'firstname' => 'Marge',
            'lastname' => 'Simpson',
        ],
    ]);
```

##### Last insert id

In case you need to fetch the id if the last insert manually you can use the `getLastInsertId` method:

```php
$id = $insertQueryBuilder->getLastInsertId();
```

#### RAW Queries

There will always be some kind of queries you can not build using the methods of a query builder. In those cases you
can utilize the `RawQueryBuilder` which allows you to execute raw queries to the database.

##### Raw select queries

```php
$rows = $this->db->makeRaw()
    ->prepare("SELECT * FROM `orders` WHERE `customer_id` = :id", [
        'id' => 42,
    ])
    ->get();
```

##### Other raw queries

```php
$this->db->makeRaw()
    ->prepare("UPDATE `customers` SET `firstname` = :name WHERE `customer_id` = :id", [
        'name' => 'Homer',
        'id' => 42,
    ])
    ->run();
```

#### Reset

All query builders have a `reset` method. This method can be used to clear all previously set values without the need
of creating a new QueryBuilder object.

```php
$builder = $this->db->makeSelect()
    ->from('customers')
    ->whereEquals('customer_id', 42);

$builder->reset();

...
```

#### Security

All query builders internally user PDO parameter binding to reduce the risk of injection attacks as much as possible.
Additionally table names as well as field names are quoted - so you don't have to worry about that. This works simple
names or when using aliases. Nevertheless you should always filter your inputs properly! 

### Error Handling and Logging

The shiny core framework provides some basic tools to handle errors and logging.

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
[
    'paths' => [
        'logs' => __DIR__ . '/../logs',
    ],

    'logger' => [
        'min_level' => 'warning',
    ],
];
```

The log files will be stored per day with a filename like `2018-12-12_shinycore.log`.

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
        throw new ShinyCoreException('Something went wrong...');
    }
}
```

Throwing a `ShinyCoreException` will force the application to respond with an error 500 code. Also the error will be
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