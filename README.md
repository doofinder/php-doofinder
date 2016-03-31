doofinder-php
=============

PHP Client for doofinder

Comes in two flavours:

  * [Doofinder Search Api PHP Client](#doofinder-search-api-php-client)
    - [Quick & Dirty](#quick--dirty)
    - [To use in an html form](#to-use-in-an-html-form)
      * [Caution: the "query_name" parameter] (#caution-the-query_name-parameter)
      * [toquerystring](#toquerystring)
      * [fromquerystring](#fromquerystring)
      * [Filter parameters format](#filter-parameters-format)
    - [A few more tips](#a-few-more-tips)
      * [Empty queries](#empty-queries)
      * [UTF-8 encoding](#utf-8-encoding)
      * [Extra option when querying](#extra-options-when-querying)
      * [Extra constructor options](#extra-constructor-options)
    - [API reference](#api-reference)
      * [DoofinderApi object](#doofinderapi-object)
      * [DoofinderResults object](#doofinderresults-object)
    - [One quick example](#one-quick-example)
  * [Doofinder Management Api PHP Client](#doofinder-management-api-php-client)
    - [Quick & Dirty](#management-quick--dirty-1)
      * [Types management](#types-management)
      * [Items management](#items-management)
      * [Tasks management](#tasks-management)


# Doofinder Search API Client


Quick & Dirty
-------------

* Include the lib

````php
<?php include('lib/doofinder_api.php');
````

* Instantiate the object

````php
<?php $df = new DoofinderApi('6a9abc4dc17351123b1e0198af92e6e9',
                             'eu1-384fd8a73c7ff0859a5891f9f4083b1b9727f9c3'); // specify hashid and API KEY ?>
````

* If you feel like it, you can specify filters.

````php
<?php
$df->setFilter('brand', array('nike', 'converse')); // brand must be 'nike' or 'converse' AND ...
$df->setFilter('color', array('red', 'blue')); // ... color must be 'red' or 'blue' AND ...
$df->setFilter('price', array('from'=>33.2)); // ... price must be upper than 33.2
?>
````

* You can also use more specific methods for that.

````php
<?php
$df->addTerm('brand', 'adidas'); // add 'adidas' to the 'brand' filter
$df->removeTerm('brand', 'nike'); // remove 'nike' from the 'brand' filter
$df->setRange('price', null, 99.9); // add an upper limit to the price
?>
````

* Do the query, specify the page if you want

````php
<?php $dfResults = $df->query('test query', 1, array('transformer'=>'dflayer')); // 'page' = 1. optional . 'transformer' = 'dflayer'. optional. ?>
````


* With the results object, fetch specific properties, facets or the results itself as an array

````php
<?php
$dfResults->getProperty('results_per_page'); // returns results per page.
$dfResults->getProperty('page'); // returns the page of the results
$dfResults->getProperty('total'); // total number of results
$dfResults->getProperty('query'); // query used
$dfResults->getProperty('hashid');
$dfResults->getProperty('max_score'); // maximun score obtained in the search results
$dfResults->getProperty('doofinder_status'); // special doofinder status. see below

// If you use the 'dflayer' transformer ...

foreach($dfResults->getResults() as $result){
    echo $result['body']."\n"; // description of the item
    echo $result['dfid']."\n"; // doofinder id. uniquely identifies this item
    echo $result['price']."\n"; // string, may come with currency sign
    echo $result['sale_price']."\n"; // may or may not be present
    echo $result['header']."\n"; // title of the item
    echo $result['href']."\n" ; // url of the item's page
    echo $result['image']."\n" ; // url of the item's image
    echo $result['type']."\n" ; // item's type. "product" at the moment
    echo $result['id']."\n" ; // item's id, as it comes from the xml feed
}

$category_facet = $dfResults->getFacet('category');

foreach($category_facet['terms'] as $term){
  echo "Category: ".$term['term']." : ".$term['count']." results found\n"; // Category: Trousers : 5 results found
}

$price_facet = $dfResults->getFacet('price');

echo "Min price found: ".$price_facet['ranges'][0]['min']."\n"; // Min price found: 33.6
echo "Max price found: ".$price_facet['ranges'][0]['max']."\n";

````


To use in an html form
----------------------

Following are some methods and uses of the library within a standard html form


### Caution: the "query_name" parameter ###

When you issue a query to doofinder, the search engine tries different types of search in order to provide the best possible results. This "types of search" are controlled by the ````query_name```` parameter.

However, if you're going to apply filters to a query, that means you're going to make the search again with certain added restrictions, therefore you're not interested in let doofinder find the best "type of search" for you again, but you rather
do the search exactly the same way you did when first querying, so the results with applied filters are consistent
with that.

````$dfResults->getProperty('query_name')```` gives you the type of query that was used to fetch those results.
If you plan to filteron those, you should use the same  type of query. you can do that with
````php
<?php
// make the initial query. no filters and no "query_name" specified
$dfResults = $df->query("baby gloves");
// set $df to keep using the same "query_name" it used for the first query
$df->setQueryName($dfResults->getProperty('query_name'));
// add a filter
$df->addTerm('category', 'More than 6 years');
// do the same query. this time filtered and with a specific query_name
$df->query("baby gloves");
````
or with a form parameter

````html
<form>
  <input type="text" name="dfParam_query">
  <input type="hidden" name="dfParam_query_name" value="text_all">
  ...
</form>
````

In short:
  - You make a query to doofinder either with filters or not. You don't need to specify ````query_name````. Doofinder will find the more suitable ````query_name````.
  - Once you got the results, you can use ````$dfResults->getProperty('query_name')```` to know which ````query_name```` was the one doofinder chose.
  - If you want to make further filtering on those search results, you should instruct doofinder to use the same ````query_name```` you got from the first search results.
  - Each time you do any new query, don't specify ````query_name````. Let doofinder find the best.
  - **Warning:** don't try to figure out a ````query_name```` on your own. query names may change in the future. You can always count on ````$dfResults->getParameter('query_name')```` to get the ````query_name```` that led to those ````$dfResults````


### "toQuerystring" ###

Pretty useful. Dumps the complete state (filters, page, rpp, query) into a querystring.

````php
<?php
echo $df->toQuerystring(3); // the argument is the page number.
                        // if none specified, current page is used
// outputs querystring that represents the object's needed params to fetch results of page 3
// every param has the (configurable) "dfParam_" prefix to avoid conflicts
// query=dfParam_test+query&dfParam_rpp=4&dfParam_timeout=8000&dfParam_page=3
````

you can use it to build links to searh results:

````html
<a href="results.php?<?php echo $df->toQuerystring(4)?>">Next Page</a>
````


### "fromQuerystring" ###

````php
<?php
$df = new DoofinderApi('6a9abc4dc17351123b1e0198af92e6e9',
                       'eu1-384fd8a73c7ff0859a5891f9f4083b1b9727f9c3');
$df->fromQuerystring(); // get search string, pagenum, rpp, etc from the request
$dfResults = $df->query(); // no need to specify query or page, it's already set through the 'fromQuerystring' method
````

Also , the second arg in constructor has the same effect. This code is equivalent to the code above:

````php
<?php
$df = new DoofinderApi('6a9abc4dc17351123b1e0198af92e6e9',
                        'eu1-384fd8a73c7ff0859a5891f9f4083b1b9727f9c3',
                   true  // call "fromQuerystring" when initializing
                   );
$dfResults = $df->query();
````


### Filter Parameters format ###

When specifying filters in request parameters, follow this convention:

  - All the filters are passed in an array called "filter" prefixed with the 'prefix' specified in $df constructor (default: "dfParam_")
  - Each key is a filter name. Each value is filter definition.
  - Filter definition for terms filter: array with terms
  - Filter definition for range filter: array with "from" and/or "to" keys.
  - Example: *color (terms filter) must be blue OR red AND price (range filter) must be GREATER than 10.2*


  ````html
  <input name="dfParam_filter[color][]" value="blue">
  <input name="dfParam_filter[color][]" value="red">
  <input name="dfParam_filter[price][from]" value="10.2">
  ````


  this constructs the array ````dfParam_filter = array('color'=>array('blue', 'red'), 'price'=>array('from'=>10.2))````



A few more tips
---------------

### empty queries ###

An empty query matchs all documents. Of course, if the query is filtered, even if the search term is none, the results are filtered too.


### UTF-8 encoding ###

The results are always in utf-8 encoding. If you're using it on an iso-8859-1 encoded page,
you can use utf8_decode

````php
<?php
foreach($dfResults->getResults() as $result){
    echo utf8_decode($result['body'])."\n";
}
````


### Extra Options When querying ###

````php
<?php
$dfResults = $df->query('test query',           // query string
                         3,                      // page num. (default: 1)
                         array(
                             'rpp' => 4,         // results per page (default 10)
                             'types' => array(   // types of item  (default all)
                                 'product',
                                 'question'
                             ),
                             'transformer' => 'dflayer', // transformer to use
                             'filter' => array(         // filter definitions
                                 'brand' => array('nike', 'converse'),
                                 'price' => array('from'=> 33.2, 'to'=> 99)
                             )
                         ));
````



### extra constructor options ###

````php
<?php
$df = new DoofinderApi('6a9abc4dc17351123b1e0198af92e6e9', //hashid
                   'eu1-384fd8a73c7ff0859a5891f9f4083b1b9727f9c3', // api_key
                   true,                               // get params from request. default false
                   array(
                     'prefix' => 'sp_df_df_',           // prefix to use with toQuerystring. default 'dfParam_'
                     'queryParameter' => 'q',         // parameter name to use for the query parameter . starting version 5.1.0 (default: 'query')
                     'apiVersion' => '3.0',           // api version of the search server. default '4'
                     'restrictedRequest' => 'post'    // use only  params from 'post' or 'get' methods. default: use any
                   ));

````


API reference
-------------

### DoofinderApi object ###

````php
<?php
$df->query($query, $page, $options); // do the query
$df->hasNext();     // boolean true if there is a next page of results
$df->hasPrev();     // boolean true if there is a prev page of results
$df->numPages();    // total number of pages
$df->getPage();     // get the actual page number
$df->setFilter($filterName, $filter); // set a filter
$df->getFilter($filterName); // get a filter
$df->getFilters(); // get all filters
$df->addTerm($filterName, $term); // add a term to a terms type $filterName
$df->removeTerm($filterName, $term);
$df->setRange($filterName, $from, $to); // specify parameters for a range filter
$df->getFilter($filter_name); // get filter specifications for $filter_name, if any
$df->getFilters(); // get filter specifications for all defined filters.
$df->setPrefix($prefix); // sets prefix to use when dumping/recovering from querystring
$df->toQuerystring($page); // dumps state info to a querystring
$df->fromQuerystring(); // recover state from a querystring
$df->nextPage(); // obtain results for the nextpage
$df->prevPage(); // obtain results for the prev page
$df->numPages(); // num of pages
$df->getRpp(); // get rpp value
$df->getTimeout();
$df->setApiVersion($apiVersion); // sets api version to use. defaults to '4'
$df->setQueryName($queryName); // sets 'query_name' parameter
````


### DoofinderResults object ###

````php
<?php
$dfResults->getProperty($propertyName); // get the property $propertyName
$dfResults->getResults(); // get results
$dfResults->getFacetsNames(); // array with facet names
$dfResults->getFacet($facetName); // obtain search results for facet $facetName
$dfResults->getFacets(); // all facets
$dfResults->getAppliedFilters(); // filters that have been applied to obtain these results
$dfResults->isOk(); // checks if all went well
$dfResults->status; // account status info. 'success', 'exhausted', 'notfound'
````


One quick example
-----------------

````html
<?php
include('lib/doofinder_api.php');
$df = new DoofinderApi('6azzz04dc173514cab1e0xxxxf92e6e9', 'eu1-384fd8a73c7ff0859a5891f9f4083b1b9727f9c3', true);
$dfResults = $df->query(null, null, array('transformer'=>'dflayer')); // if no dfParam_query,
                            // fetch all the results, to fetch all possible facets
?>

<form method="get" action="">
  <input type="text" name="dfParam_query" onchange="emptyQueryName()" value="<?php echo $dfResults->getProperty('query')?>">
  <input type="hidden" name="dfParam_rpp" value="3">
  <input type="hidden" name="dfParam_transformer" value="dflayer">
  <!-- this has to be removed via javascript if we want doofinder to find the best search for us. -->
  <input type="hidden" id="query_name" name="dfParam_query_name" value="<?php echo $dfResults->getProperty('query_name')?>">

  <input type="submit" value="search!">
  <br/>
  Filter by:
  <ul>
  <?php foreach($dfResults->getFacets() as $facetName => $facetResults):?>
  <li>
    <?php echo $facetName?>
    <ul>
    <?php if($facetResults['_type'] == 'terms'): ?>
      <?php foreach($facetResults['terms'] as $term):?>
      <li>
        <input type="checkbox" name="dfParam_filter[<?php echo $facetName ?>][]" <?php echo $term['selected'] ? 'checked': ''?> value="<?php echo $term['term']?>"><?php echo $term['term']?>: <?php echo $term['count']?>
      </li>
      <?php endforeach ?>
    <?php endif ?>
    <?php if($facetResults['_type'] == 'range'):
      $range = $facetResults['ranges'][0]?>
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
    <?php foreach($dfResults->getResults() as $result) : ?>
    <li><?php echo $result['header']?></li>
    <?php endforeach ?>
  </ul>





<?php if($df->hasPrev()):?>
<a href="?<?php echo $df->toQuerystring($df->getPage()-1)?>">Prev</a>
<?php endif?>
Number of pages: <?php echo $df->numPages()?>
<?php if($df->hasNext()):?>
<a href="?<?php echo $df->toQuerystring($df->getPage()+1)?>">Next</a>
<?php endif?>

<script>
/* if the search box changes, a new query is being made
 * don't tell doofinder which search type to use
 */
function emptyQueryName(){
  document.getElementById('query_name').value = '';
            return true;
}
</script>
````

# Doofinder Management Api PHP Client

Quick & Dirty
-------------

* Include the lib

````php
<?php include('lib/doofinder_management_api.php');
````

* Instantiate the object, use your doofinder's API_KEY.

````php
<?php $dma = new DoofinderManagementApi("eu1-d531af87f10969f90792a4296e2784b089b8a875");
?>
````

* Get a list of search engines

````php
<?php $searchEngines = $dma->getSearchEngines();
$mySearchEngine = $searchEngines[0];
````

* The `searchEngine` object gives you methods to do different stuff

### Types management ###

````php
  <?php $types = $mySearchEngine->getTypes(); // obtain search engine's datatypes
  $new_types = $mySearchEngine->addType('product'); // add new type
  $mySearchEngine->deleteType('product'); // remove the type and all items within it.
  ```

### Items management ###

#### The good stuff ####

````php
<?php

$item = $mySearchEngine->getItem('product', '888493'); // obtain item info within 'product' type.
$added_item = $mySearchEngine->addItem('product', array('id'=> 'newid', 'title'=>'a title')); //add a new item to the 'product' type
$mySearchEngine->deleteItem('product', 'newid'); // remove item

$mySearchEngine->updateItem('product', '888493', array('title'=>'modifiled title')); // update the '888493' item belonging to the 'product' type

$mySearchEngine->updateItems('product', array(
                                          array('title'=> 'first item', 'id'=>'id1'),
                                          array('title'=> 'second item', 'id'=>'id2')
                                          )); // bulk update


$mySearchEngine->addItems('product', array(
                                          array('title'=> 'first item', 'id'=>'newid1'),
                                          array('title'=> 'second item')
                                          )); // bulk add
````

#### The corner stuff ####
If you want to go through **every item in your index, and only forwards**, you'll need to use the *listing/scrolling* method... **It's not a standard php array, it's an iterator.**

```php
<?php
$items = $mySearchEngine.items('product'); // obtain items iterator
foreach($items as $item){   // only iterate through all the items.
    echo $item['title'];
}

````
Yo can't retrieve a specific item by index
````php
<?php
$item = $items[4]; // WRONG!!!!!
PHP Fatal error: Cannot use object of type ItemsRS as array...
````


### Tasks management ###

````php

$task_result = $mySearchEngine->process(); // tells our server to process search engine's feeds

$task_info = $mySearchEngine->processInfo(); // retrieve info about the last or current process

$task_info = $mySearchEngine->taskInfo($taskId); // info about a certain task

$logs = $mySearchEngine->logs(); // logs about recent processes

````
