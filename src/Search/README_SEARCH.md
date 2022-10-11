# Official PHP Search Client for Doofinder

For more information, please visit the documentation: [Search-API](https://docs.doofinder.com/#section/Search-API)

<!-- TOC depthFrom:2 -->

- [Requirements](#requirements)
- [Installation & Usage](#installation-usage)
  - [Download Method](#download-method)
  - [Using Composer](#using-composer)
  - [Authorization](#authorization)
- [Quick & Dirty](#quick-dirty)
  - [Search](#search) 
  - [Documentation for Client's Methods](#documentation-for-clients-methods)
- [Responses](#responses)
    - [Search](#search)
    - [Banner](#banner)
    - [Term facet](#term-facet)
    - [Items](#items)
    - [Status response](#status)
- [API Reference](#api-reference)
- [Tests](#tests)
<!-- /TOC -->

## Requirements

Requires PHP 5.6 or later. Not tested in previous versions.

## Installation & Usage
To install the library you can download it from the project's [releases](https://github.com/doofinder/php-doofinder/releases) page or use [Composer](https://packagist.org/packages/doofinder/doofinder).



### Download Method

Include the provided autoload.php file and use:

```php
    require_once('/path/to/php-doofinder/vendor/autoload.php');
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


## Authorization
We use Api token in http header for authenticate requests.
```
{
  "Authorization" : "Token {my_api_token}"
}
```

## Quick & Dirty
### Search
```php
<?php

require_once(__DIR__ . '/../vendor/autoload.php');

const HOST = 'https://eu1-search.doofinder.com';
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

## Responses
### Search
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

### Banner
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

### Term facet
```php
[
    'items' => '(array of Items)',
    'selected' => '(array of Items)'
]
```

### Items
```php
[
    'count' => '(integer) number of elements',
    'name' => '(string) Name of term'
]
```

### Status
```php
[
  'status' => 'registered'
]
```

## API reference

#### `\Doofinder\Search\Client`

```php
$client->searchParams($params, $options);                               // Import search params from a request into an array
$client->dumpParams($options);                                          // Export latest search params in client into an array
$client->qs();                                                          // Export latest search params in client to a string

$client->search($params);                                               // Perform search
$client->getNextPage();                                                 // Perform a search for the next page of results
$client->getPreviousPage();                                             // Perform a search for the previous page of results

$client->getSearchParam($paramName, $defaultValue);                     // Get a search parameter from the client for the latest search done

$client->createSessionId();                                             // Create a hash to be used as session id
$client->registerSession($sessionId, $hashid);                          // Initializes session for the search client
$client->registerClick($sessionId, $hashid, $id, $options);             // Register a click in Doofinder
$client->registerCheckout($sessionId, $hashid);                         // Register a checkout in Doofinder
$client->registerImageClick($sessionId, $hashid, $imageId);             // Register a banner click in Doofinder
$client->registerRedirection($sessionId, $hashid,
                             $redirectionId, $link, $options);          // Register a redirection in Doofinder

$client->addToCart($sessionId, $hashid, $id, $amount, $options);        // Add an amount of item to the cart in the current session
$client->removeFromCart($sessionId, $hashid, $id, $amount, $options);   // Remove an amount of item from the cart in the current session
$client->clearCart($sessionId, $hashid);                                // Clear the cart in the current session

$client->setCustomHeaders($headers);                                    // Add custom headers to all requests
```

#### `\Doofinder\Search\Results`

```php
$results->getProperty($propertyName); // Get the property $propertyName
$results->getResults();               // Get results
$results->getFacetsNames();           // Array with facet names
$results->getFacet($facetName);       // Obtain search results for facet $facetName
$results->getFacets();                // All facets
$results->getAppliedFilters();        // Filters that have been applied to obtain these results
$results->isOk();                     // Checks if all went OK
$results->status;                     // Account status info. 'success', 'exhausted', 'notfound'
```


## Tests

To run the unit tests:

```
composer tests
```