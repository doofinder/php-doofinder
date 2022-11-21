# Official PHP client for the Doofinder Search API

Search API allows you to perform the search requests you can do on your search engines using the doofinder layer, directly from your code.

- API version: 6.0

For more information, please visit the documentation: [Search-API](https://docs.doofinder.com/api/search/v6/)

<!-- TOC depthFrom:2 -->

- [Requirements](#requirements)
- [Installation & Usage](#installation--usage)
    - [Using Composer](#using-composer)
    - [Manual Installation](#manual-installation)
- [Authorization](#authorization)
  - [API Token](#api-token)
- [Tests](#tests)
- [API for Search](#search)
- [API for Stats](#stats)

<!-- /TOC -->

## Requirements

Requires PHP 5.6 or later. Not tested in previous versions.

## Installation & Usage

### Using Composer

You can also download the library using [Composer](https://packagist.org/packages/doofinder/doofinder). 

Run this command to add the Doofinder library to your `composer.json` file:

```bash
composer require doofinder/doofinder
```

If you are already using Composer your `autoload.php` file will be updated. If not, a new one will be generated and you will have to include it:

```php
<?php
require_once dirname(__FILE__)."/vendor/autoload.php";

use \Doofinder\Search\SearchClient;

const HOST = 'https://eu1-search.doofinder.com';
const API_KEY = 'your_api_token';

$searchClient = SearchClient::create(HOST, API_KEY);
```

### Manual Installation

To install the library you can download it from the [releases](https://github.com/doofinder/php-doofinder/releases) page of the project and include the `autoload.php` file provided to use it:

```php
require_once('/path/to/php-doofinder/vendor/autoload.php');
```

## Authorization

To authenticate you need a Doofinder `API key`. If you don't have one you can generate it in the Doofinder Admin by going to your Account and then to API Keys, [here](https://app.doofinder.com/es/admin/api/).

```plaintext
eu1-ab46030xza33960aac71a10248489b6c26172f07
```

### API Token

You can authenticate with the previous API key. The correct way to authenticate is to send a `HTTP Header` with the name `Authorization` and the value `Token {api-key}`.

```bash
{
  "Authorization" : "Token {my_api_token}"
}
```

For example, for the key shown above:

```plaintext
Authorization: "Token eu1-ab46030xza33960aac71a10248489b6c26172f07"
```

## Tests

To run the unit tests:

```bash
composer tests
```

## Search

Functions to perform search operations.

```php
<?php

require_once(__DIR__ . '/../vendor/autoload.php');

const HOST = 'https://eu1-search.doofinder.com';
const API_KEY = 'your_api_token';

$searchClient = \Doofinder\Search\SearchClient::create(
    HOST,
    API_KEY
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
$response = $searchClient->search($hashId, $searchParams);
$search = $response->getBody();

// **** Suggestions ****
$suggestionParams = [
    'indices' => [
        'product'
    ],
    'query' => 'cámara',
    'session_id' => 'fake_session_id',
    'stats' => "true"
];
$response = $searchClient->suggest($hashId, $suggestionParams);
$suggestions = $response->getBody();
```

### Documentation for Search methods

| Method | Description | Return type |
|-|-|-|
| **search** | Search through indexed items of a search engine | [Search response](#search-response) |
| **suggest** | Search through indexed suggestions of a search engine | Array of strings |

#### Search response

```php
[
  'banner' => 'Banner response for a query search.', # [Show Banner response]
  'count' => '(integer) Total number of items found in the search engine for the searched term.',
  'custom_results_id' => '(integer) Id of applied custom results. This field will not be included if none of the custom results apply.',
  'facets' => '(Array of Term Facet Response or Range Facet Response) Information about the different groupings that can be made for certain fields in the search results.', # [Show Term facet response]
  'query_name' => '(string) To get the best possible results, Doofinder tries several query types. This is the type of query that Doofinder has performed to obtain these results.',
  'results' => '(array) List of items found in the search engine for the searched term.', # [Show Items response]
  'total' => '(integer) Total number of elements that can be obtained.'
]
```

#### Banner response

```php
[
    'blank' => '(boolean) Display the banner link in a new window.',
    'html_code' => '(string) Html code to be used as banner instead of an image.',
    'id' => '(integer) Banner identification.',
    'image' => '(string) Image URL used as banner.',
    'link' => '(string) URL to be redirected when click over banner.',
    'mobile_image' => '(string) Image URL used as banner for mobile devices.'
]
```

#### Term Facet response

```php
[
  
  'key' => '(string) Name of the aggregated field.',
   # Facet terms list
  'terms' => [
    'items' => [
      'count' => '(integer) number of elements',
      'name' => '(string) Name of term'
    ],
    'selected' => [
      'count' => '(integer) number of elements',
      'name' => '(string) Name of term'
    ]
  ]
]
```

#### Items response

```php
[
  'description' => '(string) Item description.',
  'dfid' => '(string) Doofinder result doofinder id. It comes in every Doofinder results for every item.',
  'id' => '(string) Unique identification of an indexed item.',
  'image_url' => '(string) Item image url.',
  'title' => '(string) Item title.',
  'url' => '(string) Item url.'
]
```

## Stats

Functions to perform statistical operations.

```php
<?php

require_once(__DIR__ . '/../vendor/autoload.php');

const HOST = 'https://eu1-search.doofinder.com';
const API_KEY = 'your_api_token';

$searchClient = \Doofinder\Search\SearchClient::create(
    HOST,
    API_KEY
);

$hashId = 'fake_hash_id';

// **** Init session ****
$response = $searchClient->initSession($hashId, $sessionId);
$body = $response->getBody();

// **** Logs redirection ****
$response = $searchClient->logRedirection($hashId, $sessionId, $id, $query);
$body = $response->getBody();

// **** Logs banner ****
$response = $searchClient->logBanner($hashId, $sessionId, $id, $query);
$body = $response->getBody();

// **** Logs checkout ****
$response = $searchClient->logCheckout($hashId, $sessionId);
$body = $response->getBody();

// **** Logs click ****
$response = $searchClient->logClick($hashId, $sessionId, $id, $query);
$body = $response->getBody();

// **** Logs add to cart ****
$response = $searchClient->logAddToCart($hashId, $sessionId, $amount, $itemId, $indexId, $price, $title);
$body = $response->getBody();

// **** Logs remove from cart ****
$response = $searchClient->logRemoveFromCart($hashId, $sessionId, $amount, $itemId, $indexId);
$body = $response->getBody();

// **** Logs clear cart ****
$response = $searchClient->clearCart($hashId, $sessionId);
$body = $response->getBody();
```

### Documentation for Stats methods

| Method | Description | Return type |
|-|-|-|
| **initSession** | Starts a session identified by a session_id | [Status response](#status-response) |
| **logRedirection** | Logs a "redirection triggered" event in stats logs | [Status response](#status-response) |
| **logBanner** | Logs a "click on banner image" event in stats logs | [Status response](#status-response) |
| **logCheckout** | Logs a checkout event in stats logs | [Status response](#status-response) |
| **logClick** | Save click event on doofinder statistics | [Status response](#status-response) |
| **logAddToCart** | Adds an item to the cart, or creates a new cart for the given session if it does not exists | [Status response](#status-response) |
| **logRemoveFromCart** | Removes an amount from the given item in the cart | [Status response](#status-response) |
| **clearCart** | his call will erase completely a cart identified by the pair of hashid and session ID | [Status response](#status-response) |

#### Status response

```php
[
  'status' => 'registered'
]
```