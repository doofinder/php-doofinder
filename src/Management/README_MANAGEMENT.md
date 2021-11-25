# Doofinder Management API

Doofinder's management API allows you to perform the same administrative tasks you can do on your search engines using the Doofinder control panel, directly from your code.

- API version: 2.0

For more information, please visit [https://doofinder.com/support](https://app.doofinder.com/api/v2/)

<!-- TOC depthFrom:2 -->

- [Requirements](#requirements)
- [Installation & Usage](#installation--usage)
    - [Composer](#composer)
    - [Manual Installation](#manual-installation)
- [Tests](#tests)

<!-- /TOC -->

## Requirements

PHP 5.6 and later

## Installation & Usage
### Composer

To install the bindings via [Composer](http://getcomposer.org/), run the following:

`composer install`

### Manual Installation

Download the files and include `autoload.php`:

```php
    require_once('/path/to/php-doofinder/vendor/autoload.php');
```

## Tests

To run the unit tests:

```
composer install
./vendor/bin/phpunit
```
