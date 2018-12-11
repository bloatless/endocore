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
- [Error Handling and Logging](#error-handling-and-logging)

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
    ->where('customer_id', 42)
    ->update([
        'firstname' => 'Homer'
    ]);
```

#### DELETE

```php
$rows = $this->db->makeDelete()
    ->from('customers')
    ->where('customer_id', 42)
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

### Error Handling and Logging

...

## License

MIT