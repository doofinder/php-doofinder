# Official PHP Search Client for Doofinder

<!-- TOC depthFrom:2 -->

- [Installation](#installation)
    - [Download Method](#download-method)
    - [Using Composer](#using-composer)
- [Search Client](#search-client)
    - [Quick & Dirty](#quick--dirty)
    - [Searching from HTML Forms](#searching-from-html-forms)
        - [Be careful with the `query_name` parameter](#be-careful-with-the-query_name-parameter)
        - [Load parameters from request: `searchParams()`](#load-parameters-from-request-searchparams)
        - [`dumpParams()`](#dumpparams)
        - [`qs()`](#qs)
        - [Filter Parameters](#filter-parameters)
        - [Sort Parameters](#sort-parameters)
    - [Tips](#tips)
        - [Empty queries](#empty-queries)
        - [UTF-8 encoding](#utf-8-encoding)
    - [Extra Search Options](#extra-search-options)
    - [The Doofinder Metrics](#the-doofinder-metrics)
        - [Register Session](#register-session)
        - [Register Checkout](#register-checkout)
        - [Register Result Click](#register-result-click)
        - [Register Banner Click](#register-banner-click)
        - [Register Redirection](#register-redirection)
    - [The special 'banner' and 'redirect' results properties](#the-special-banner-and-redirect-results-properties)
    - [API reference](#api-reference)
        - [`\Doofinder\Search\Client`](#\doofinder\search\client)
        - [`\Doofinder\Search\Results`](#\doofinder\search\results)
    - [One quick example](#one-quick-example)

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

## Search Client

### Quick & Dirty

```php
require_once("path/to/php-doofinder/autoload.php");

define("HASHID", "6a9gbc4dcx735x123b1e0198gf92e6e9");
define("SERVER", "eu1-search.doofinder.com");
define("API_KEY", "384fdag73c7ff0a59g589xf9f4083bxb9727f9c3")

// Set server and API Key
$client = new \Doofinder\Search\Client(SERVER, API_KEY);

$searchParams = [
    "hashid" => HASHID,
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
    "transformer" => "basic"
];

$results = $client->search($searchParams);

// With the results object, fetch specific properties, facets or the results
// itself as an array.

$results->getProperty('results_per_page');  // returns results per page.
$results->getProperty('page');              // returns the page of the results
$results->getProperty('total');             // total number of results
$results->getProperty('query');             // query used
$results->getProperty('hashid');
$results->getProperty('max_score');         // maximum score obtained in the search results
$results->getProperty('doofinder_status');  // special Doofinder status, see below

// special properties: banner and redirect (if defined in your control center)
$banner = $results->getProperty('banner'); // array with 'id', 'link', 'image' and 'blank' keys
$redirect = $results->getProperty('redirect'); // array with 'id' and 'url' keys

// If you use the 'basic' transformer ...
foreach($results->getResults() as $result){
  echo $result['description']."\n";        // description of the item
  echo $result['dfid']."\n";        // Doofinder id. uniquely identifies this item
  echo $result['price']."\n";       // string, may come with currency sign
  echo $result['sale_price']."\n";  // may or may not be present
  echo $result['title']."\n";      // title of the item
  echo $result['link']."\n" ;       // url of the item's page
  echo $result['image_link']."\n" ;      // url of the item's image
  echo $result['type']."\n" ;       // item's type. "product" at the moment
  echo $result['id']."\n" ;         // item's id, as it comes from the xml feed
}

$category_facet = $results->getFacet('category');

foreach($category_facet['terms'] as $term) {
  // Category: Trousers : 5 results found
  echo "Category: ".$term['term']." : ".$term['count']." results found\n";
}

$price_facet = $results->getFacet('price');

echo "Min price found: ".$price_facet['from']."\n";
// Min price found: 33.6
echo "Max price found: ".$price_facet['to']."\n";


// You can iterate through pages too:
$params = [
  'hashid' => HASHID,
  "filter" => [
    "country" => ["Spain"]
  ],
  "sort" => [
    ["city" => "asc"]
  ],
  "rpp" => 5
];

$results = $client->search($params);
while ($results) {
  foreach ($results->getResults() as $item) {
    $city = $item["city"];
    $title = $item["title"];
    echo "[$city] $title" . PHP_EOL;
  }
  $results = $client->getNextPage();
}
```


__Notice:__

- For non-numeric fields you'll have to set those fields as _sortable_ in Doofinder's control panel before you can sort by them.
- Every search request is made through secure protocol.

### Searching from HTML Forms

#### Be careful with the `query_name` parameter

When you issue a query to Doofinder, the search engine tries different types of search in order to provide the best possible results. This "types of search" are controlled by the `query_name` parameter.

However, if you're going to apply filters to a query, that means you're going to make the search again with certain added restrictions, therefore you're not interested in let Doofinder find the best _"_type of search_"_ for you again, but you rather do the search exactly the same way you did when first querying, so the results with applied filters are consistent with that.

`$results->getProperty('query_name')` gives you the type of query that was used to fetch those results. If you plan to filter on those, you should use the same type of query. You can do that with:

```php
// make the initial query. no filters and no "query_name" specified
$params = ['hashid' => HASHID, "query" => "baby gloves"]
$results = $client->search($params);

$params["query_name"] = $client->getSearchParam("query_name");
$params["filter"] = [
    "category" => ["More than 6 years"]
];

// do the same query. this time filtered and with a specific query_name
$client->search($params);
```

#### Load parameters from request: `searchParams()`

You can load form parameters:

```html
<form method="GET" action="search.php">
  <input type="hidden" name="hashid" value="aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa">
  <input type="text" name="query" value="sneakers">
  <input type="hidden" name="query_name" value="match_and">
  ...
</form>
```

```php
$params = $client->searchParams($_GET);
// [
//     "hashid" => "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
//     "query" => "sneakers",
//     "query_name" => "match_and"
//     …
// ]
```

You can change the name of the query parameter:

```html
<form method="GET" action="search.php">
  <input type="hidden" name="hashid" value="aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa">
  <input type="text" name="text" value="sneakers">
  <input type="hidden" name="query_name" value="match_and">
  ...
</form>
```

```php
$params = $client->searchParams($_GET, ["queryParameter" => "text"]);
// [
//     "hashid" => "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
//     "query" => "sneakers",
//     "query_name" => "match_and"
//     …
// ]
```

And you can add a prefix if necessary:

```html
<form method="GET" action="search.php">
  <input type="hidden" name="dfParam_hashid" value="aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa">
  <input type="text" name="dfParam_text" value="sneakers">
  <input type="hidden" name="dfParam_query_name" value="match_and">
  ...
</form>
```

```php
$params = $client->searchParams($_GET, ["prefix" => "dfParam_", "queryParameter" => "text"]);
// [
//     "hashid" => "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
//     "query" => "sneakers",
//     "query_name" => "match_and",
//     …
// ]
```

In short:

- You make a query to Doofinder either with filters or not. You don't need to specify `query_name`. Doofinder will find the more suitable `query_name`.
- Once you got the results, you can use `$results->getProperty('query_name')` to know which `query_name` was the one Doofinder chose in a specific set of results or use `$client->getSearchParam("query_name")` to get the query name from the latest call.
- If you want to make further filtering on those search results, you should instruct Doofinder to use the same `query_name` you got from the first search results.
- Each time you do any new query, don't specify `query_name`. Let Doofinder find the best.
- **Warning:** don't try to figure out a `query_name` on your own. Query names may change in the future. Always count on `$results->getParameter('query_name')` or `$client->getSearchParam("query_name")` to get the `query_name` that led to those `$results`.

#### `dumpParams()`

Dumps the parameters of the latest search done to an array.

```php
$client->search(["hashid" => HASHID, "query" => "sneakers"]);
$client->dumpParams(["prefix" => "dfParam_", "queryParameter" => "dfParam_"]);
// [
//     "hashid" => "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
//     "query" => "sneakers",
//     "query_name" => "match_and",
//     "page" => 1,
//     …
// ]
```

#### `qs()`

This is the same as the `dumpParams()` method but generates a querystring instead of an array:

```php
$client->search(["hashid" => HASHID, "query" => "sneakers"]);
$client->qs();
// hashid=aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa&query=sneakers&query_name=match_and&page=1
$client->qs(["queryParameter" => "text"]);
// hashid=aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa&text=sneakers&query_name=match_and&page=1
$client->qs(["prefix" => "dfParam_"]);
// dfParam_hashid=aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa&dfParam_query=sneakers&dfParam_query_name=match_and&dfParam_page=1
$client->qs(["prefix" => "dfParam_", "queryParameter" => "text"]);
// dfParam_hashid=aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa&dfParam_text=sneakers&dfParam_query_name=match_and&dfParam_page=1
```

You can use it to build links to search results:

```php
<a href="results.php?<?php echo $client->qs(['page' => 4])?>">Next Page</a>
```

#### Filter Parameters

When specifying filters in HTML forms, follow this convention:

- All the filters are passed in an array called _filter_ prefixed with the _prefix_ specified in `$client` constructor (default: `dfParam_`).
- Each key is a filter name. Each value is filter definition.
- Filter definition for terms filter: use an array with terms
- Filter definition for range filter: use an array with `from` and/or `to` keys.

__Example:__ _color_ (terms filter) must be `blue` or `red` and _price_ (range filter) must be greater than `10.2`.

```html
<input name="dfParam_filter[color][]" value="blue">
<input name="dfParam_filter[color][]" value="red">
<input name="dfParam_filter[price][gte]" value="10.2">
```


This constructs the array:

```php
dfParam_filter = [
  'color' => ['blue', 'red'],
  'price' => ['from' => 10.2],
];
```

#### Sort Parameters

As with other params, the parameters must be prefixed with the `prefix` specified in `$client` constructor (default: `dfParam_`).

If you're by only one field and in ascending order, you can simply send the `sort` parameter with the name of the field to sort by as value:

```html
<input name="dfParam_sort" value="price">
```

If you want to sort by one field and specify the sort direction, you'll have to to send, for the `sort` param, an array, being the key the field to sort on and the value either `asc` or `desc`:

```html
<input name="dfParam_sort[price]" value="desc".
```

If you want to sort by several fields, just compound the previous definition in an array.

__Note:__ When sorting for several fields, sort direction must be specified for every one.

__Example:__ sort in descending order by price and if same price, sort by title in ascending order.

```html
<input name="dfParam_sort[0][price]" value="desc">
<input name="dfParam_sort[1][title]" value="asc">
```

This constructs the array:

```php
dfParam_sort = [
  ['price' => 'desc'],
  ['title' => 'asc'],
];
```

Please read carefully the [sort parameters](http://www.doofinder.com/support/developer/api/search-api#sort-parameters) section in our search API documentation.

### Tips

#### Empty queries

An empty query matches all documents. Of course, if the query is filtered, even if the search term is none, the results are filtered too.

#### UTF-8 encoding

The results are always in UTF-8 encoding. If you're using it on an ISO-8859-1 encoded page, you can use `utf8_decode`:

```php
foreach ($results->getResults() as $result) {
  echo utf8_decode($result['body']).PHP_EOL;
}
```

### Extra Search Options

```php
$results = $client->search([
    "hashid" => HASHID,
    "query" => "test query",
    "page" => 3,
    // Results Per Page (default: 10)
    "rpp" => 4,
    // types of item to search (default: all)
    "types" => ["product", "question"],
    // Template used to return items
    "transformer" => "basic",
    // Filtering options
    "filter" => [
        "brand" => ["nike", "converse"],
        "price" => ["from"=> 33.2, "to"=> 99],
    ],
    // Sorting options
    "sort" => [
        ["price" => "asc"],
        ["title" => "desc"],
    ]
]);
```

### The Doofinder Metrics

In order to take the most of Doofinder stats, you can **register** in Doofinder certain events so you can have stats and metrics.

#### Register Session

All metrics require a unique session id. You must generate and register it before performing any search. It's your responsability to manage its duration (recommended 24h or until a checkout is done - see _Register Checkout_).

```php
$sessionId = $client->createSessionId();
$client->registerSession($sessionId, HASHID);
```

**IMPORTANT:** This method should only be used once per user until the session expires.

#### Register Checkout

Call this method when a goal is achieved (a user purchases something…). Use the session id previously generated and then, regenerate the session id.

```php
$client->registerCheckout($sessionId, HASHID);
$sessionId = $client->createSessionId();
```

#### Register Result Click

Register the fact of a user clicking a product after a search.

```php
$doofinderItemId = "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa@product@ffffffffffffffffffffffffffffffff";
$client->registerClick($sessionId, HASHID, $doofinderItemId);
```

If you want, you can provide the item's database id (the id you provided when indexing the item). Just provide the datatype used for indexing:

```php
$client->registerClick($sessionId, HASHID, 12345, ["datatype" => "product"]);
```

You can link the click to the search query for enhanced insights:

```php
$client->registerClick($sessionId, HASHID, 12345, [
    "datatype" => "product",
    "query" => "sneakers"
]);
```

#### Register Banner Click

Sometimes the response can hold banner info if you defined a banner for certain search conditions. You can register a click on a banner with:

```php
$banner = $results->getProperty("banner");
$client->registerImageClick($sessionId, HASHID, $banner["id"]);
```

#### Register Redirection

As with banners, you can register a redirection if you defined it in Doofinder. You're the responsible of redirecting the user or not, and you can send the event to Doofinder, optionally linking with the query, with:

```php
$redirection = $results->getProperty("redirection");
$query = $results->getProperty("query");
$client->registerRedirection($sessionId, HASHID, $redirection["id"], $redirection["url"], [
    "query" => $query
]);
```

### The special 'banner' and 'redirect' results properties

In the Doofinder control center you can create:

- **Banners**: Clickable image banners to be displayed for certain search terms.
- **Redirections**: The page the user should be redirected to for certain search terms.

If present in the response, you can get both properties along with their info by simply accesing them with `getProperty`:

```html+php
<?php
$results = $client->search("This search term produces banner in search results");
$banner = $results->getProperty("banner"); // if no banner, this is null
?>

<?php if ($banner): ?>
  <a href="<? php echo $banner['link'] ?>">
    <img src="<?php echo $banner["image"] ?>">
  </a>
<?php endif ?>
```

```php
$redirection = $results->getProperty("redirection"); // if no redirect, this is null

if ($redirection) {
   header("location: " . $redirection["url"]);
}
```

### API reference

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

### One quick example

```php
<?php
  require_once('path/to/php-doofinder/autoload.php');

  define('HASHID', '6a9gbc4dcx735x123b1e0198gf92e6e9');
  define('SERVER', 'eu1-search.doofinder.com');
  define('API_KEY', '384fdag73c7ff0a59g589xf9f4083bxb9727f9c3');

  $client = new \Doofinder\Search\Client(SERVER, API_KEY);
  // if no dfParam_query, fetch all the results, to fetch all possible facets
  $results = $client->search(array("transformer" => "basic"));
  $query = $results->getProperty("query");
  $queryName = $results->getProperty("query_name");
  $page = $results->getProperty("page", 1);
  $totalPages = $client->getTotalPages();
?>

<form method="GET" action="">
  <input type="text" name="dfParam_query" onchange="emptyQueryName()" value="<?php echo $query ?>">
  <input type="hidden" name="dfParam_rpp" value="3">
  <input type="hidden" name="dfParam_transformer" value="basic">
  <!-- this has to be removed via javascript if we want Doofinder to find the best search for us. -->
  <input type="hidden" id="query_name" name="dfParam_query_name" value="<?php echo $queryName ?>">
  <input type="submit" value="search!">

  <p>Filter by:</p>
  <ul>
    <?php foreach ($results->getFacets() as $facetName => $facetResults): ?>
      <li>
        <?php echo $facetName ?>
        <ul>
          <?php if ($facetResults['_type'] == 'terms'): ?>
            <?php foreach ($facetResults['terms'] as $term):?>
              <li>
                <input type="checkbox"
                  name="dfParam_filter[<?php echo $facetName ?>][]"
                  <?php echo $term['selected'] ? 'checked': ''?>
                  value="<?php echo $term['term']?>">
                <?php echo $term['term']?>: <?php echo $term['count']?>
              </li>
            <?php endforeach ?>
          <?php endif ?>
          <?php if $facetResults['_type'] == 'range'): $range = $facetResults['ranges'][0]; ?>
            <li>
              Range: <?php echo $range['min']?> -- <?php echo $range['max']?><br/>
              From: <input type="text" name="dfParam_filter[<?php echo $facetName?>][gte]" value="<?php echo $range['selected_from']?>">
              To: <input type="text" name="dfParam_filter[<?php echo $facetName?>][lte]" value="<?php echo $range['selected_to']?>">
            </li>
          <?php endif?>
        </ul>
      </li>
    <?php endforeach ?>
  </ul>
</form>

<h1>Results</h1>

<ul>
  <?php foreach ($results->getResults() as $result): ?>
  <li><?php echo $result['header'] ?></li>
  <?php endforeach ?>
</ul>

<?php if ($totalPages): ?>
    <?php if ($page > 1): ?>
        <a href="?<?php echo $client->qs(["page" => $page - 1]) ?>">Prev</a>
    <?php endif?>
    <?php if ($page < $totalPages): ?>
        <a href="?<?php echo $client->qs(["page" => $page + 1]) ?>">Next</a>
    <?php endif?>
<?php endif?>

<script>
  // if the search box changes, a new query is being made
  // don't tell Doofinder which search type to use
  function emptyQueryName(){
    document.getElementById('query_name').value = '';
    return true;
  }
</script>
```
