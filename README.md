# PTJ-Server - An Android RESTful server writen in PHP CodeIgniter

Build status:
![](https://img.shields.io/scrutinizer/build/g/filp/whoops.svg)
![](https://img.shields.io/github/license/mashape/apistatus.svg)

## Features:
* Login and register
* Collaborative Filtering Recommendation
* Push notification on Android
* ...

## Server Requirements

PHP version 5.6 or newer is recommended.

It should work on 5.4.8 as well, but we strongly advise you NOT to run such old versions of PHP, because of potential security and performance issues, as well as missing features.

## Installation Instructions

The server is installed in four steps:

1.Unzip the package.

2.Upload the CodeIgniter folders and files to your server. Normally the index.php file will be at your root.

If you use the MAMP or MAMP PRO, you can put your project in /Applications/MAMP/htdocs.

3.Open the application/config/config.php file with a text editor and set your base URL. If you intend to use encryption or sessions, set your encryption key.

If you use the MAMP or MAMP PRO, you can put your project in /Applications/MAMP/htdocs, then you can set your base URL. 

For example:

```php
$config['base_url'] = 'http://localhost:8888/PTJ-Server/';
```
---

4.If you intend to use a database, open the application/config/database.php file with a text editor and set your database settings.

For example:

```php
$db['default'] = array(
    'dsn'   => '',
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '123456',
    'database' => 'ptj-db',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);
```
---

Some database drivers (such as PDO) might require a full DSN string to be provided. If that is the case, you should use the ‘dsn’ configuration setting, as if you’re using the driver’s underlying native PHP extension, like this:

```php
// PDO
$db['default']['dsn'] = 'pgsql:host=localhost;port=5432;dbname=database_name';
```
---

## CodeIgniter Resources

[User Guide](https://codeigniter.com/docs)
[Language File Translations](https://github.com/bcit-ci/codeigniter3-translations)
[Community Forums](http://forum.codeigniter.com/)
[Community Wiki](https://github.com/bcit-ci/CodeIgniter/wiki)
[Community IRC](https://webchat.freenode.net/?channels=%23codeigniter)