doofinder-php
=============

PHP Client for doofinder

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

Caution: the "query_name" parameter
-----------------------------------

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
  
The "transformer" parameter
---------------------------

Given an indexed item, a _transformer_ makes a transformation on its fields, producing a "normalized" version out of it, just before delivering it to the api client. If no transformation is applied, and the items you get when issuing a search request will have the very same fields they had when they were indexed.

You can control which _transformer_ to use via the `transformer` search option. You can command doofinder to use the "dflayer" transformer like this:

```php
<?php $dfResults = $df->query('test query', 1, array('transformer'=>'dflayer'));?>
```

A few more tips
---------------

### empty queries ###

On 3 version, and empty query made no hit on the search server, and produced 0 results. That's no longer the case. An empty query matchs all documents and that could be a method of iterating through all your searchable items. Of course, if the query is filtered, even if the search term is none, the results are filtered too.


### UTF-8 encoding ###

The results are always in utf-8 encoding. If you're using it on an iso-8859-1 encoded page,
you can use utf8_decode

````php
<?php
foreach($dfResults->getResults() as $result){
    echo utf8_decode($result['body'])."\n"; 
}
````

Of course, you can use any other decoding-encoding library of your choice. The doofinder-php
library will always produce utf8 data.

### Extra Options When querying ###

````php
<?php
$dfResults = $df->query('test query',           // query string
                         3,                      // page num. 
                         array(
                             'rpp' => 4,         // results per page
                             'timeout' => 8000,  // timeout in milisecs
                             'types' => array(   // types of item 
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

#### Defaults ####
````php
<?php
$df->query('test query') == $df->query('test query',
                                       1,                      // page num
                                       array(
                                           'rpp' => 10,        // 10 results per page
                                           'timeout' => 10000, // 10 secs timeout
                                           'types' => array()  // any type
                                           ));
````

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
'<a href="results.php?<?php echo $df->toQuerystring(4)?>">Next Page</a>'

````

### "fromQuerystring" ###

````php
<?php
$df = DoofinderApi('6a9abc4dc17351123b1e0198af92e6e9');
$df->fromQuerystring(); // get search string, pagenum, rpp, etc from the request
$dfResults = $df->query(); // no need to specify query or page, it's already set through the 'fromQuerystring' method
````

Also , the second arg in constructor has the same effect. This code is equivalent to the code above:

````php
<?php
$df = DoofinderApi('6a9abc4dc17351123b1e0198af92e6e9',
                   true  // call "fromQuerystring" when initializing
                   );
$dfResults = $df->query();                  
````

#### Filter Parameters format ####

When specifying filters in request parameters, follow this convention:

  - All the filters are passed in an array called "filter" prefixed with the 'prefix' specified in $df constructor (default: "dfParam_")
  - Each key is a filter name. Each value is filter definition.
  - Filter definition for terms filter: array with terms 
  - Filter definition for range filter: array with "from" and/or "to" keys.
  - Example: *color (terms filter) must be blue OR red AND price (range filter) must be GREATER than 10.2 *
  ````html
  <input name="dfParam_filter[color][]" value="blue">
  
  <input name="dfParam_filter[color][]" value="red">
  
  <input name="dfParam_filter[price][from]" value="10.2">
  ````
  this constructs the array ````dfParam_filter = array('color'=>array('blue', 'red'), 'price'=>array('from'=>10.2))````


### extra constructor options ###

````php
<?php
$df = DoofinderApi('6a9abc4dc17351123b1e0198af92e6e9', // hashid
                   true,                               // get params from request
                   array(
                     'prefix' => 'sp_df_df_',           // prefix to use with toQuerystring. CAN'T USE EMPTY STRINGS
                     'apiVersion' => '3.0',           // api version of the search server
                     'restrictedRequest' => 'post'    // use only  params from 'post' or 'get' methods. 
                   ));
                   
````

#### Defaults ####
````php
<?php
$df = DoofinderApi('6a9abc4dc17351123b1e0198af92e6e9',  // hashid
                   false,                               // don't obtain status from request
                   array(
                      'prefix' => 'dfParam_',
                      'apiVersion'=> '4'
                      )); // if no restrictedRequest specified, $_REQUEST is used
````                      

### Find your method of choice here ###

#### DoofinderApi object ####

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
$df->getRpp(); // set rpp. defaults 10
$df->getTimeout();
$df->setApiVersion($apiVersion); // sets api version to use. defaults to '4'
$df->setQueryName($queryName); // sets 'query_name' parameter
````

#### DoofinderResults object ####

````php
<?php
$dfResults->getProperty($propertyName); // get the property $propertyName
$dfResults->getResults(); // get results
$dfResults->getFacetsNames(); // array with facet names
$dfResults->getFacet($facetName); // obtain search results for facet $facetName
$dfResults->getFacets(); // all facets
$dfResults->getAppliedFilters(); // filters that have been applied to obtain these results
$dfResults->isOk(); // checks if all went well
````

Account status info
-------------------

Regarding your account status, The results object have a ````status```` property and an ````isOk()```` method that may inform you if there's some 'special circumstance'

````
<?php
$dfResults->status;   // 'success' : everything went fine. results should be available
                       // 'exhausted': the account has reached its query limit. no results provided
                       // 'notfound': no account could be found with the provided hashid. no results provided

$dfResults->isOk();   // true if status is 'success'
````

One quick example
-----------------

````html
<?php
include('lib/doofinder_api.php');  
$df = new DoofinderApi('6azzz04dc173514cab1e0xxxxf92e6e9',true);
$dfResults = $df->query(); // if no dfParam_query, 
                            // fetch all the results, to fetch all possible facets
?>

<form method="get" action="">
  <input type="text" name="dfParam_query" onchange="emptyQueryName()" value="<?php echo $dfResults->getProperty('query')?>">
  <input type="hidden" name="dfParam_rpp" value="3">
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

