[![Build Status](https://travis-ci.org/memran/MarwaDB.svg?branch=master)](https://travis-ci.org/memran/MarwaDB) [![Latest Stable Version](https://poser.pugx.org/memran/MarwaDB)](//packagist.org/packages/memran/MarwaDB) [![Total Downloads](https://poser.pugx.org/memran/MarwaDB/downloads)](//packagist.org/packages/memran/MarwaDB) [![Latest Unstable Version](https://poser.pugx.org/memran/MarwaDB)](//packagist.org/packages/memran/MarwaDB) [![License](https://poser.pugx.org/memran/MarwaDB/LICENSE.MD)](//packagist.org/packages/memran/MarwaDB)

<a href="https://phpstan.org/"><img src="https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat" alt="PHPStan Enabled"></a>

# MarwaDB for MarwaPHP Framework

**MarwaDB** is php mysql library for **MarwaPHP** framework based on PDO. It is robust, faster and simple. It is query builder with PDO connection. No External Library has been used. It is raw and simple PHP Mysql Library by focusing speed, simplicity and scalability. Function names are same as **Laravel** Eloquent Builder.

Just install the package, add the config and it is ready to use!

## Requirements

- PHP >= 7.2.0
- PDO Extension

## Features

- Easy to create flexible queries
- Supports any database compatible with PDO
- Simple to build complex queries with little code
- Blazing Fast

## Installation

This package is installable and PSR-4 autoloadable via [Composer](https://packagist.org/packages/memran/marwadb) as

    composer require memran/marwadb:dev-master

## Usage

Create a new DB class, and pass the configruation array to MarwaDB:

```php
require_once('../vendor/autoload.php');
use MarwaDB\DB;
$config = [
    'default'=>
        [
           'driver' => "mysql",
           'host' => "localhost",
           'port' => 3306,
           'database' => "test",
           'username' => "root",
           'password' => "1234",
           'charset' => "utf8mb4",
        ],
    'write'=>
        [
           'driver' => "mysql",
           'host' => "localhost",
           'port' => 3306,
           'database' => "test",
           'username' => "root",
           'password' => "1234",
           'charset' => "utf8mb4",
        ],
    'read'=>
        [
           'driver' => "mysql",
           'host' => "localhost",
           'port' => 3306,
           'database' => "test",
           'username' => "root",
           'password' => "1234",
           'charset' => "utf8mb4",
        ]
];
$db = new DB($config);
```

### DB Raw Query

```php
    $result = $db->raw('SELECT * FROM system WHERE id = ?',[1]);
    dump($result)
```

Alternatively , you can use following function also:

```php
    $result = $db->rawQuery('SELECT * FROM system WHERE id = ?',[1]);
    dump($result)
```

### Get Total Result

```php
    dump("Total Rows Returned >>> ".$db->rows());
```

### PDO Server Status

```php
    $db->status();
```

### Connection name Specified Query

```php
    $result=$db->connection('sqlSrv')->rawQuery('SELECT  *  FROM users WHERE id = ?',  [1]);
    dump($result);
```

### Change Result Fetch Mode

```php
    $result=$db->connection('sqlSrv')->setFetchMode('array')->rawQuery('SELECT  *  FROM users WHERE id = ?',  [1]);
    dump($result);
```

### Transaction

```php
    $db->transaction(function($db){
       $db->rawQuery('DELETE  FROM users WHERE id = ?',  [4]);
       dump($db->rows());
    });
```

### Simple Select Query without Placeholder

```php
    $result =  $db->select('SELECT  *  FROM users');
    dump($result);
```

### With Placeholder

```php
    $result=$db->select('SELECT  *  FROM users WHERE id = ?',  [1]);
    dump($result);
```

### PDO Bind Param

```php
    $result =  $db->raw("SELECT  *  FROM users WHERE id = :id",  ['id'  =>  '1']);
    dump($result);
```

### Get Connection Driver

```php
    dump($db->getDriver());
```

### Retrieving All Rows From A Table

```php
$db->table('users')->get();
```

### Retrieving A Single Row / Column From A Table

```php
$db->table('users')->where('name', 'Marwa')->first()->get();
```

### Retrieving A List Of Column Values

```php
$db->table('roles')->select(['title', 'name'])->get();
```

### Aggregates Function

```php
$users = $db->table('users')->count()->get();
$price = $db->table('orders')->max('price')->get();
$price = $db->table('orders')->avg('price')->get();
$price = $db->table('orders')->min('price')->get();
```

## Selects

#### Specifying A Select Clause

```php
$users = $db->table('users')->select(['name', 'email as user_email'])->get();
```

the _distinct_ method allows you to retrieve distinct results:

```php
$users = $db->table('users')->distinct()->get();
```

You may add column:

```php
$result =$db->table('users')->addSelect('age')->get();
```

## Joins

#### Inner Join Clause

```php
$users = $db->table('users')
            ->join('contacts', 'users.id', '=', 'contacts.user_id')
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->select('users.*', 'contacts.phone', 'orders.price')
            ->get();
```

#### Left Join / Right Join Clause

```php
$users = $db->table('users')
            ->leftJoin('posts', 'users.id', '=', 'posts.user_id')
            ->get();

$users = $db->table('users')
            ->rightJoin('posts', 'users.id', '=', 'posts.user_id')
            ->get();
```

## Unions

```php
$first = $db->table('users')
            ->whereNull('first_name');

$users = $db->table('users')
            ->whereNull('last_name')
            ->union($first)
            ->get();
```

## Where Clauses

#### Simple Where Clauses

```php
$users = $db->table('users')->where('votes', '=', 100)->get();
```

You may use a variety of other operators when writing a `where` clause:

```php
$users = $db->table('users')
                ->where('votes', '>=', 100)
                ->get();

$users = $db->table('users')
                ->where('votes', '<>', 100)
                ->get();

$users = $db->table('users')
                ->where('name', 'like', 'T%')
                ->get();
```

#### Or Statements

```php
$users = $db->table('users')
                    ->where('votes', '>', 100)
                    ->orWhere('name', 'John')
                    ->get();
```

#### Additional Where Clauses

**whereBetween / orWhereBetween**

```php
$users = $db->table('users')
           ->whereBetween('votes', [1, 100])
           ->get();
```

**whereNotBetween / orWhereNotBetween**

```php
$users = $db->table('users')
                    ->whereNotBetween('votes', [1, 100])
                    ->get();
```

**whereIn / whereNotIn / orWhereIn / orWhereNotIn**

```php
$users = $db->table('users')
                    ->whereIn('id', [1, 2, 3])
                    ->get();
```

```php
$users = $db->table('users')
                    ->whereNotIn('id', [1, 2, 3])
                    ->get();
```

**whereNull / whereNotNull / orWhereNull / orWhereNotNull**

```php
$users = $db->table('users')
                    ->whereNull('updated_at')
                    ->get();
```

```php
$users = $db->table('users')
                    ->whereNotNull('updated_at')
                    ->get();
```

**whereDate / whereMonth / whereDay / whereYear / whereTime**

```php
$users = $db->table('users')
                ->whereDate('created_at', '2016-12-31')
                ->get();
```

```php
$users = $db->table('users')
                ->whereMonth('created_at', '12')
                ->get();
```

```php
$users = $db->table('users')
                ->whereDay('created_at', '31')
                ->get();
```

```php
$users = $db->table('users')
                ->whereYear('created_at', '2016')
                ->get();
```

```php
$users = $db->table('users')
                ->whereTime('created_at', '=', '11:20:45')
                ->get();
```

### Where Exists Clauses

```php
$users = $db->table('users')
           ->whereExists(function ($query) {
               $query->select([1])
                     ->from('users')
                     ->where('id', '=','1');
           })
           ->get();
```

#### orderBy

```php
$users = $db->table('users')
                ->orderBy('name', 'desc')
                ->get();
```

#### latest / oldest

```php
$user = $db->table('users')
                ->latest()
                ->first()
                ->get();
```

#### inRandomOrder

```php
$randomUser = $db->table('users')
                ->inRandomOrder()
                ->first()
                ->get();
```

#### groupBy / having

```php
$users = $db->table('users')
                ->groupBy('account_id')
                ->having('account_id', '>', 100)
                ->get();
```

#### skip / take

```php
$users = $db->table('users')->skip(10)->take(5)->get();
```

## Inserts

```php
$db->table('users')->insert(
    ['email' => 'test@test.com', 'active' => 0]
);
```

Insert multiple records:

```php
$db->table('users')->insert([
    ['email' => 'test@test.com', 'active' => 0],
    ['email' => 'test1@test.com', 'active' => 1]
]);
```

## Updates

```php
$result= $db->table('users')
              ->where('id', 1)
              ->update(['active' => 1]);
```

#### Update Or Insert

```php
$db->table('users')
    ->updateOrInsert(
        ['email' => 'test@test.com', 'name' => 'Marwa'], //data for update
        ['active' => '1] // data for insert
    );
```

## Deletes

```php
$db->table('users')->delete();

$db->table('users')->where('active', '=', 0)->delete();
```

## Debugging

```php
//It will debug and die
$db->table('users')->where('active', '=', 1)->dd();
//it will only debug
$db->table('users')->where('active', '=', 1)->dump();
```

## Enable Sql Logging
```php
$db->enableQueryLog();
$db->table('users')->where('active', '=', 1)->get();
dump($db->getQueryLog());
```
## Print Sql Query
```php
dump($db->table('users')->where('active', '=', 1)->toSql());
```
## Contribution
Please see [CONTRIBUTING](https://github.com/memran/MarwaDB/blob/master/CONTRIBUTING.MD) for details.

## License
The MIT License (MIT). Please see  [License File](https://github.com/memran/MarwaDB/blob/master/LICENSE.MD) for more information.

