# Official PHP Search Client for Doofinder

<!-- TOC depthFrom:2 -->

- [Installation](#installation)
    - [Download Method](#download-method)
    - [Using Composer](#using-composer)

<!-- /TOC -->

## Installation

To install the library you can download it from the project's [releases](https://github.com/doofinder/php-doofinder/releases) page or use [Composer](https://packagist.org/packages/doofinder/doofinder).

Requires PHP 5.6 or later. Not tested in previous versions.

### Download Method

Just include the provided `autoload.php` file and use:

```php
require_once('path/to/php-doofinder/autoload.php');
$client = new \Doofinder\Search\Client(HASHID, API_KEY);
```

### Using Composer

Add Doofinder to your `composer.json` file by running:

```bash
$ composer require doofinder/doofinder
```

If you're already using composer your autoload.php file will be updated. If not, a new one will be generated and you will have to include it:

```php
<?php
require_once dirname(__FILE__)."/vendor/autoload.php";

use \Doofinder\Search\Client as SearchClient;

$client = new SearchClient(HASHID, API_KEY);
```
