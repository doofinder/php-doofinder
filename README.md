[![Build Status](https://api.travis-ci.org/doofinder/php-doofinder.svg?branch=master)](https://travis-ci.org/doofinder/php-doofinder)
# Official PHP Client for doofinder

<!-- MarkdownTOC depth=3 -->

- [Installation](#installation)
  - [Download Method](#download-method)
  - [Using Composer](#using-composer)
- [Search Client](#search-client)
  - [Quick & Dirty](#quick--dirty)
  - [Searching from HTML Forms](#searching-from-html-forms)
  - [Tips](#tips)
  - [Extra Search Options](#extra-search-options)
  - [Extra Constructor Options](#extra-constructor-options)
  - [API reference](#api-reference)
  - [One quick example](#one-quick-example)
- [Management API](#management-api)
  - [Quick & Dirty](#quick--dirty-1)

<!-- /MarkdownTOC -->

## Installation

To install the library you can download it from the project's [releases](https://github.com/doofinder/php-doofinder/releases) page or use [Composer](https://packagist.org/packages/doofinder/doofinder).

Requires PHP 5.3 or later. Not tested in previous versions.

### Download Method

Just include the provided `autoload.php` file and use:

```php
require_once('path/to/php-doofinder/autoload.php');
$client = new \Doofinder\Api\Search\Client(HASHID, API_KEY);
```

### Using Composer

Add Doofinder to your `composer.json` file by running:

```bash
$ composer require doofinder/doofinder
```

If you're already using composer your autoload.php file will be updated. If not, a new one will be generated and you will have to include it:

```php
<?php
require_once dirname(__FILE__).'/vendor/autoload.php';

use \Doofinder\Api\Search\Client as SearchClient;
use \Doofinder\Api\Management\Client as ManagementClient;

$client = new SearchClient(HASHID, API_KEY);
```

## Search Client

### Quick & Dirty

```php
require_once('path/to/php-doofinder/autoload.php');

define('HASHID', '6a9gbc4dcx735x123b1e0198gf92e6e9');
define('API_KEY', 'eu1-384fdag73c7ff0a59g589xf9f4083bxb9727f9c3')

// Set hashid and API Key
$client = new \Doofinder\Api\Search\Client(HASHID, API_KEY);

// You can specify filters
$client->setFilter('brand', array('nike', 'converse')); // brand must be 'nike' or 'converse' AND ...
$client->setFilter('color', array('red', 'blue'));      // ... color must be 'red' or 'blue' AND ...
$client->setFilter('price', array('from'=>33.2));       // ... price must be upper than 33.2

// You can also use more specific methods for that.
$client->addTerm('brand', 'adidas');    // add 'adidas' to the 'brand' filter
$client->removeTerm('brand', 'nike');   // remove 'nike' from the 'brand' filter
$client->setRange('price', null, 99.9); // add an upper limit to the price

// Feeling adventurous? sort!

$client->addSort('price', 'desc'); // sort by price (descending)...
$client->addSort('title', 'asc');  // ... and then by title (ascending)

// Do the query, specify the page if you want.
// 'page' = 1. optional . 'transformer' = 'dflayer'. optional.
$results = $client->query('test query', 1, array('transformer' => 'dflayer'));

// With the results object, fetch specific properties, facets or the results
// itself as an array.

$results->getProperty('results_per_page');  // returns results per page.
$results->getProperty('page');              // returns the page of the results
$results->getProperty('total');             // total number of results
$results->getProperty('query');             // query used
$results->getProperty('hashid');
$results->getProperty('max_score');         // maximum score obtained in the search results
$results->getProperty('doofinder_status');  // special Doofinder status, see below

// If you use the 'dflayer' transformer ...
foreach($results->getResults() as $result){
  echo $result['body']."\n";        // description of the item
  echo $result['dfid']."\n";        // doofinder id. uniquely identifies this item
  echo $result['price']."\n";       // string, may come with currency sign
  echo $result['sale_price']."\n";  // may or may not be present
  echo $result['header']."\n";      // title of the item
  echo $result['href']."\n" ;       // url of the item's page
  echo $result['image']."\n" ;      // url of the item's image
  echo $result['type']."\n" ;       // item's type. "product" at the moment
  echo $result['id']."\n" ;         // item's id, as it comes from the xml feed
}

$category_facet = $results->getFacet('category');

foreach($category_facet['terms'] as $term) {
  // Category: Trousers : 5 results found
  echo "Category: ".$term['term']." : ".$term['count']." results found\n";
}

$price_facet = $results->getFacet('price');

echo "Min price found: ".$price_facet['ranges'][0]['min']."\n";
// Min price found: 33.6
echo "Max price found: ".$price_facet['ranges'][0]['max']."\n";
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
$results = $client->query("baby gloves");
// "query_name" is automatically set to be the same it used for the first query
// add a filter
$client->addTerm('category', 'More than 6 years');
// do the same query. this time filtered and with a specific query_name
$client->query("baby gloves");
```

or with a form parameter:

```html
<form>
  <input type="text" name="dfParam_query">
  <input type="hidden" name="dfParam_query_name" value="text_all">
  ...
</form>
```

In short:

- You make a query to Doofinder either with filters or not. You don't need to specify `query_name`. Doofinder will find the more suitable `query_name`.
- Once you got the results, you can use `$results->getProperty('query_name')` to know which `query_name` was the one Doofinder chose.
- If you want to make further filtering on those search results, you should instruct Doofinder to use the same `query_name` you got from the first search results.
- Each time you do any new query, don't specify `query_name`. Let Doofinder find the best.
- **Warning:** don't try to figure out a `query_name` on your own. query names may change in the future. You can always count on `$results->getParameter('query_name')` to get the `query_name` that led to those `$results`

#### `toQuerystring()`

Dumps the complete state of the client (query, page, filters, rpp) into a querystring. Every param has the (configurable) `dfParam_` prefix to avoid conflicts.

```php
$page = 3; // Results page number. Optional.
echo $client->toQuerystring($page);
// query=dfParam_test+query&dfParam_rpp=4&dfParam_timeout=8000&dfParam_page=3
```

You can use it to build links to search results:

```html
<a href="results.php?<?php echo $client->toQuerystring(4)?>">Next Page</a>
```

#### `fromQuerystring()`

Gets information from the PHP request globals and initialises the client with search parameters (query, page, filters, rpp).

```php
$client = new \Doofinder\Api\Search\Client(HASHID, API_KEY);
$client->fromQuerystring();
$results = $client->query();
```

You can do the same in the constructor by passing `true` as the third parameter. This code is equivalent to the code above:

```php
<?php
$client = new \Doofinder\Api\Search\Client(HASHID, API_KEY, true);
$results = $client->query();
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
<input name="dfParam_filter[price][from]" value="10.2">
```


This constructs the array:

```php
dfParam_filter = array(
  'color' => array('blue', 'red'),
  'price' => array('from' => 10.2)
);
```

#### Sort Parameters

As with other params, the parameters must be prefixed with the `prefix` specified in `$client` constructor (default: `dfParam_`).

If you sorting for one field in ascending order, you can simply send the `sort` parameter with the name of the field to sort by as value:

```html
<input name="dfParam_sort" value="price">
```

If you want to specify the sort direction, you'll have to to send, for the `sort` param, an array, being the key the field to sort on and the value either `asc` or `desc`:

```html
<input name="dfParam_sort[price]" value="desc".
```

If you want to sort by several fields, just compound the previous definition in an array.

__Example:__ sort in descending order by price and if same price, sort by title in ascending order.

```html
<input name="dfParam_sort[0][price]" value="desc">
<input name="dfParam_sort[1][title]" value="asc">
```

This constructs the array:

```php
dfParam_sort = array(
  array('price' => 'desc'),
  array('title', 'asc')
);
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
$query = 'test query';
$page = 3;
$results = $client->query($query, $page, array(
  // Results Per Page (default: 10)
  'rpp' => 4,
  // types of item to search (default: all)
  'types' => array('product', 'question'),
  // Template used to return items
  'transformer' => 'dflayer',
  // Filtering options
  'filter' => array(
    'brand' => array('nike', 'converse'),
    'price' => array('from'=> 33.2, 'to'=> 99),
  ),
  // Sorting options
  'sort' => array(
    array('price' => 'asc'),
    array('title' => 'desc'),
  )
));
```

### Extra Constructor Options

```php
$client = new \Doofinder\Api\Search\Client(
  HASHID,
  API_KEY,
  true, // Whether to import request parameters or not (default: false)
  array(
    // Prefix to use with toQuerystring() (default: dfParam_)
    'prefix' => 'sp_df_df_',
    // Parameter name to use for the query parameter (default: query)
    'queryParameter' => 'q',
    // API version of the search server (default: 5)
    'apiVersion' => '5',
    // Use only parameters from $_POST or $_GET methods
    // (default: unset, uses $_REQUEST)
    'restrictedRequest' => 'post'
  )
);
```

### API reference

#### `\Doofinder\Api\Search\Client`

```php
$client->query($query, $page, $options);    // Perform search
$client->hasNext();                         // Boolean, true if there is a next page of results
$client->hasPrev();                         // Boolean, true if there is a prev page of results
$client->numPages();                        // Total number of pages
$client->getPage();                         // Get the actual page number
$client->setFilter($filterName, $filter);   // Set a filter
$client->getFilter($filterName);            // Get a filter by name
$client->getFilters();                      // Get all filters
$client->addTerm($filterName, $term);       // Add a term to a terms type $filterName
$client->removeTerm($filterName, $term);
$client->setRange($filterName, $from, $to); // Specify parameters for a range filter
$client->getFilter($filter_name);           // Get filter specifications for $filter_name, if any
$client->getFilters();                      // Get filter specifications for all defined filters
$client->addSort($sortField, $direction);   // Tells doofinder to sort results
$client->setPrefix($prefix);                // Sets prefix for dumping/recovering from querystring
$client->toQuerystring($page);              // Dumps state info to a querystring
$client->fromQuerystring();                 // Recover state from a querystring
$client->nextPage();                        // Obtain results for the nextpage
$client->prevPage();                        // Obtain results for the prev page
$client->numPages();                        // Num of pages
$client->getRpp();                          // Get the number of results per page
$client->getTimeout();
$client->setApiVersion($apiVersion);        // Sets API version to use (default: 5)
```

#### `\Doofinder\Api\Search\Results`

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
  define('API_KEY', 'eu1-384fdag73c7ff0a59g589xf9f4083bxb9727f9c3')

  $client = new \Doofinder\Api\Search\Client(HASHID, API_KEY, true);
  // if no dfParam_query, fetch all the results, to fetch all possible facets
  $results = $client->query(null, null, array('transformer'=>'dflayer'));
?>

<form method="get" action="">
  <input type="text" name="dfParam_query" onchange="emptyQueryName()" value="<?php echo $results->getProperty('query') ?>">
  <input type="hidden" name="dfParam_rpp" value="3">
  <input type="hidden" name="dfParam_transformer" value="dflayer">
  <!-- this has to be removed via javascript if we want doofinder to find the best search for us. -->
  <input type="hidden" id="query_name" name="dfParam_query_name" value="<?php echo $results->getProperty('query_name') ?>">
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
              From: <input type="text" name="dfParam_filter[<?php echo $facetName?>][from]" value="<?php echo $range['selected_from']?>">
              To: <input type="text" name="dfParam_filter[<?php echo $facetName?>][to]" value="<?php echo $range['selected_to']?>">
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

<?php if ($client->hasPrev()): ?>
  <a href="?<?php echo $client->toQuerystring($client->getPage() - 1) ?>">Prev</a>
<?php endif?>
Number of pages: <?php echo $client->numPages() ?>
<?php if ($client->hasNext()): ?>
  <a href="?<?php echo $client->toQuerystring($client->getPage() + 1) ?>">Next</a>
<?php endif?>

<script>
  // if the search box changes, a new query is being made
  // don't tell doofinder which search type to use
  function emptyQueryName(){
    document.getElementById('query_name').value = '';
    return true;
  }
</script>
```

## Management API

### Quick & Dirty

```php
require_once('path/to/php-doofinder/autoload.php');

define('API_KEY', 'eu1-384fdag73c7ff0a59g589xf9f4083bxb9727f9c3')

// Instantiate the object, use your doofinder's API_KEY.
$client = new \Doofinder\Api\Management\Client(API_KEY);

// Get a list of search engines
$searchEngines = $client->getSearchEngines();
// From the list, we will choose the first one
$mySearchEngine = $searchEngines[0];
```

The `SearchEngine` object gives you methods to manage a search engine.

#### Types Management

```php
$types = $mySearchEngine->getTypes();             // Obtain search engine's datatypes
$new_types = $mySearchEngine->addType('product'); // Add new type
$mySearchEngine->deleteType('product');           // Remove the type and all items within it.
```

#### Items Management

##### Single Item

```php
// Obtain item info within 'product' type
$item = $mySearchEngine->getItem('product', '888493');
// Add a new item to the 'product' type
$added_item = $mySearchEngine->addItem('product', array('id'=> 'newid', 'title'=>'a title'));
// Remove item
$mySearchEngine->deleteItem('product', 'newid');
// Update the '888493' item belonging to the 'product' type.
$mySearchEngine->updateItem('product', '888493', array('title'=>'modifiled title'));
```

##### Bulk Add/Update/Delete

```php
$mySearchEngine->updateItems('product', array(
  array('title' => 'first item', 'id' => 'id1'),
  array('title' => 'second item', 'id' => 'id2'),
));

$mySearchEngine->addItems('product', array(
  array('title' => 'first item', 'id' => 'newid1'),
  array('title' => 'second item'),
));

$mySearchEngine->deleteItems('product', array('id1', 'id2'));
```

##### Iterating Items

If you want to go through **every item in your index** you can only do it forwards. To do that, you'll need to use the *listing/scrolling* method…

__WARNING:__ You won't be iterating a standard PHP array. Items will be provided as an iterator object instance.

```php
$items = $mySearchEngine->items('product');
foreach ($items as $item) {
  echo $item['title'];
}
```

You can't retrieve a specific item by index:

```php
$items = $mySearchEngine->items('product');
$item = $items[4]; // WRONG!!!!!
PHP Fatal error: Cannot use object of type ItemsRS as array…
```

#### Stats

```php
// Some PHP versions may need this
date_default_timezone_set('America/Havana');

// If not $from_date or $to_date provided, default is last 15 days
$from_date = new DateTime("2011-01-07");
$to_date = new DateTime("2011-02-07");

foreach ($mySearchEngine->stats($from_date, $to_date) as $key => $aggregated){
  echo $aggregated['date'];     // date of the aggregated data
  echo $aggregated['clicked'];  // # clicks in search results
  echo $aggregated['searches']; // # complete searches. i.e.: "mp3 player"
  echo $aggregated['api'];      // # requests made through our API
  echo $aggregated['parser'];   // # requests used in feeds parsing
                                // (1 per each 100 parsed items)
  echo $aggregated['queries'];  // # "raw" search requests
                                // i.e.: "mp3", "mp3 p", "mp3 pl" ..
  echo $aggregated['requests']; // total # of requests for that day
}

$top_clicked = $mySearchEngine->topTerms('clicked', $from_date, $to_date);

foreach ($top_clicked as $key => $clicked) {
  echo $clicked['term'];  // title of the clicked item
  echo $clicked['count']; // # of clicks on that item
}

$top_searches = $mySearchEngine->topTerms('searches', $from_date, $to_date);

foreach ($top_searches as $key => $search) {
  echo $search['term'];   // search terms used
  echo $search['count'];  // # of times it's been used
}

$top_opportunities = $mySearchEngine->topTerms('opportunities', $from_date, $to_date);

foreach ($top_opportunities as $key => $opportunity) {
  echo $opportunity['term'];  // search terms used (that haven't yielded any result)
  echo $opportunity['count']; // # of times it's been used
}
```

#### Tasks management

```php
// Ask our server to process a search engine's feeds
$taskResult = $mySearchEngine->process();
// Retrieve info about the last or current process of the search engine
$taskInfo = $mySearchEngine->processInfo();
// Retrieve info about a certain task
$taskInfo = $mySearchEngine->taskInfo($taskResult['task_id']);
// Get log info about the last processes
$logs = $mySearchEngine->logs();
```

## Run Tests

To run tests.

  - Make sure you have (phpunit) [https://phpunit.de/] (version 4.8) and (php-mock-phpunit)[https://github.com/php-mock/php-mock-phpunit] (version 1.1.*). If you have composer you can run
  ````shell
  $ composer.phar install
  ````

  - Run the tests!!
  ````shell
  $ phpunit
  ````
