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
<?php $df = new DoofinderApi('6a9abc4dc17351123b1e0198af92e6e9'); // specify hashid ?>
````

* Do the query, specify the page if you want

````php
<?php $df_results = $df->query('test query', 1); // 'page' = 1. optional ?>
````

* With the results object, fetch specific properties, or the results itself as an array

````php
<?php 
$df_results->getProperty('results_per_page'); // returns results per page.
$df_results->getProperty('page'); // returns the page of the results
$df_results->getProperty('total'); // total number of results
$df_results->getProperty('query'); // query used
$df_results->getProperty('hashid');
$df_results->getProperty('max_score'); // maximun score obtained in the search results
$df_results->getProperty('doofinder_status'); // special doofinder status. see below


foreach($df_results->getResults() as $result){
    echo $result['body']."\n"; // description of the item
    echo $result['dfid']."\n"; // doofinder id. uniquely identifies this item
    echo $result['price']."\n"; // string, may come with currency sign
    echo $result['sale_price']."\n"; // may or may not be present.
    echo $result['header']."\n"; // title of the item
    echo $result['href']."\n" ; // url of the item's page
    echo $result['image']."\n" ; // url of the item's image
    echo $result['type']."\n" ; // item's type. "product" at the moment 
    echo $result['id']."\n" ; // item's id, as it comes from the xml feed
}

````


                     
                      


A few more tips
---------------

## Extra Options When querying ##

````php
<?php
$df_results = $df->query('test query',           // query string
                         3,                      // page num. 
                         array(
                             'rpp' => 4,         // results per page
                             'timeout' => 8000,  // timeout in milisecs
                             'types' => array(   // types of item 
                                 'product', 
                                 'question'), 
                         ));
````

### Defaults ###
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

## "to_querystring" ##

````php
<?php
echo $df->to_querystring(3); // the argument is the page number. 
                        // if none specified, current page is used

// outputs querystring that represents the object's needed params to fetch results of page 3
// every param has the (configurable) "df_param_" prefix to avoid conflicts
// query=df_param_test+query&df_param_rpp=4&df_param_timeout=8000&df_param_page=3

````

you can use it to build links to searh results:

````html
'<a href="results.php?<?php echo $df->to_querystring(4)?>">Next Page</a>'

````

## "from_querystring" ##

````php
<?php
$df = DoofinderApi('6a9abc4dc17351123b1e0198af92e6e9');
$df->from_querystring(); // get search string, pagenum, rpp, etc from the request
$df_results = $df->query(); // no need to specify query or page, it's already set through the 'from_querystring' method
````

Also , the second arg in constructor has the same effect. This code is equivalent to the code above:
````php
<?php
$df = DoofinderApi('6a9abc4dc17351123b1e0198af92e6e9',
                   true  // call "from_querystring" when initializing
                   );
$df->results = $df->query();                  
````

## extra constructor options ##

````php
<?php
$df = DoofinderApi('6a9abc4dc17351123b1e0198af92e6e9', // hashid
                   true,                               // get params from request
                   array(
                     'prefix' => 'sp_df_df_',           // prefix to use with to_querystring
                     'api_version' => '3.0',           // api version of the search server
                     'restricted_request' => 'post',   // use only  params from 'post' or 'get' methods. 
                     'to_iso' => true                  // encode results in iso-8859-1 (default is utf8)
                   ));
                   
````

### Defaults ###
````php
<?php
$df = DoofinderApi('6a9abc4dc17351123b1e0198af92e6e9',  // hashid
                   false,                               // don't obtain status from request
                   array(
                      'prefix' => 'df_param_',
                      'api_version'=> '3.0',
                      'to_iso' => false
                      )); // if no restricted_request specified, $_REQUEST is used
````                      

## some other useful methods ##

````php
<?php
$df->has_next();     // boolean true if there is a next page of results
$df->has_prev();     // boolean true if there is a prev page of results
$df->num_pages();    // total number of pages
$df->get_page();     // get the actual page number
````

Account status info
-------------------

Regarding your account status, The results object have a ````status```` property and an ````isOk()```` method that may inform you if there's some 'special circumstance'

````
<?php
$df_results->status;   // 'success' : everything went fine. results should be available
                       // 'exhausted': the account has reached its query limit. no results provided
                       // 'notfound': no account could be found with the provided hashid. no results provided

$df_results->isOk();   // true if status is 'success'
````

One quick example
-----------------

````html
<form method="get" action="">
  <input type="text" name="df_param_query">
  <input type="hidden" name="df_param_rpp" value="3">
  <input type="submit" value="search!">
</form>

<?php
include('lib/doofinder_api.php');
$df = new DoofinderApi('6a96xxxdc173514cab1e0198a123e6e9',true);
$df_results = $df->query(); // if no df_param_query, no call is done, so no harm in this
?>

<ul>
<?php foreach($df_results->getResults() as $result) : ?>
  <li><?php echo $result['header']?></li>
<?php endforeach ?>
</ul>  

<?php if($df->has_prev()):?>
<a href="?<?php echo $df->to_querystring($df->get_page()-1)?>">Prev</a>
<?php endif?>
Number of pages: <?php echo $df->num_pages()?>
<?php if($df->has_next()):?>
<a href="?<?php echo $df->to_querystring($df->get_page()+1)?>">Next</a>
<?php endif?>

````
