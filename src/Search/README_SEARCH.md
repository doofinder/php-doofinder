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
- [Quick & Dirty](#quick--dirty)
- [Documentation](#documentation-for-clients-methods)

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
composer tests
```

## Quick & Dirty
### Search
```php
<?php

require_once(__DIR__ . '/../vendor/autoload.php');

const HOST = 'https://eu1-api.doofinder.com';
const TOKEN = 'your_api_token';

$searchClient = \Doofinder\Search\SearchClient::create(
    HOST,
    TOKEN
);

$hashId = 'fake_hash_id';

// **** Search ****
$searchParams = [
    "query" => "sneakers",
    "filter" => [
        "brand" => ["nike", "converse"],
        "color" => ["red", "blue"],
        "price" => ["from" => 33.2, "to" => 99]
    ],
    "sort" => [
        ["price" => "desc"],
        ["title" => "asc"]
    ],
];
$response = $searchClient->search($hashId, $searchEngineParams);
$search = $response->getBody();

$suggestionParams = [
    'indices' => [
        'product'
    ],
    'query' => 'cámara',
    'session_id' => 'fake_session_id',
    'stats' => trueº
];
$response = $searchClient->suggest($hashId, $suggestionParams);
$suggestions = $response->getBody();

// **** Init session ****
$searchClient->initSession($hashId, $sessionId);

// **** Logs redirection ****
$searchClient->logRedirection($hashId, $sessionId, $id, $query);

// **** Logs banner ****
$searchClient->logBanner($hashId, $sessionId, $id, $query);

// **** Logs checkout ****
$searchClient->logCheckout($hashId, $sessionId);

// **** Logs click ****
$searchClient->logClick($hashId, $sessionId, $id, $query);

// **** Logs add to cart ****
$searchClient->logAddToCart($hashId, $sessionId, $amount, $itemId, $indexId, $price, $title);

// **** Logs remove from cart ****
$searchClient->logRemoveFromCart($hashId, $sessionId, $amount, $itemId, $indexId);

// **** Logs clear cart ****
$searchClient->clearCart($hashId, $sessionId);
```

## Documentation for Client's Methods

| Method                | Description                                                                                 | Return type                         |
|-----------------------|---------------------------------------------------------------------------------------------|-------------------------------------|
| **search**            | Search through indexed items of a search engine                                             | [Search response](#search-response) |
| **suggest**           | Search through indexed suggestions of a search engine                                       | Array of strings                    |
| **initSession**       | Starts a session identified by a session_id                                                 | [Status response](#status-response) |
| **logRedirection**    | Logs a "redirection triggered" event in stats logs                                          | [Status response](#status-response) |
| **logBanner**         | Logs a "click on banner image" event in stats logs                                          | [Status response](#status-response) |
| **logCheckout**       | Logs a checkout event in stats logs                                                         | [Status response](#status-response) |
| **logClick**          | Save click event on doofinder statistics                                                    | [Status response](#status-response) |
| **logAddToCart**      | Adds an item to the cart, or creates a new cart for the given session if it does not exists | [Status response](#status-response) |
| **logRemoveFromCart** | Removes an amount from the given item in the cart                                           | [Status response](#status-response) |
| **clearCart**         | his call will erase completely a cart identified by the pair of hashid and session ID       | [Status response](#status-response) |

### Search response
```php
[
  'banner' => 'Banner response for a query search.',
  'count' => '(integer) Total number of items found in the search engine for the searched term.',
  'custom_results_id' => '(integer) Id of applied custom results. This field will not be included if none of the custom results apply.',
  'facets' => '(Array of Term Facet Response or Range Facet Response) Information about different groupings that can be made for certain fields in the search results.',
  'query_name' => '(string) In order to get the best possible results, Doofinder tries several types of querying. This is the type of the query Doofinder made to obtain these results.',
  'total' => '(integer) Total number of items that can be fetched.'
]
```

#### Banner response
```php
[
    'blank' => '(boolean) Display the banner link in a new window.'
    'html_code' => '(string) Html code to be used as banner instead of an image.'
    'id' => '(integer) Banner identification.',
    'image' => '(string) Image URL used as banner.',
    'link' => '(string) URL to be redirected when click over banner.'
    'mobile_image' => '(string) Image URL used as banner for mobile devices.'
]
```

#### Term facet response
```php
[
    'items' => '(array of Items)',
    'selected' => '(array of Items)'
]
```

#### Items
```php
[
    'count' => '(integer) number of elements',
    'name' => '(string) Name of term'
]
```

### Status response
```php
[
  'status' => 'registered'
]
```

## Authorization
We use Api token in http header for authenticate requests.
```
{
  "Authorization" : "Token {my_api_token}"
}
```