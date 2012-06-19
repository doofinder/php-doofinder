doofinder-php
=============

PHP Client for doofinder

Quick & Dirty
-------------

* Include the lib

````php
<?php include('lib/doofinder_php_api.php');
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


    foreach($df_results->getResults() as $result){
        echo $result['body']."\n"; // description of the item
        echo $result['dfid']."\n"; // doofinder id. uniquely identifies this item
        echo $result['price']."\n"; // string, may come with currency sign
        echo $result['header']."\n"; // title of the item
        echo $result['href']."\n" ; // url of the item's page
        echo $result['image']."\n" ; // url of the item's image
        echo $result['type']."\n" ; // item's type. "product" at the moment 
        echo $result['id']."\n" ; // item's id, as it comes from the xml feed
    }

````


A few more tips
---------------

# Extra Options When querying #

````php

$df_results = $df->query('test query', // query string
                         3, // page num. if null is specified, defaults to 1
                         array(
                             'rpp' => 4, // results per page. default to 10
                             'timeout' => 8000, // timeout in milisecs for the server to drop the connection
                             'types' => array('product', 'question'), // types of item to search for. defauult to all
                         ));
                         
# "serialize" #

````php

echo $df->serialize(3); // the argument is the page number. 
                        // if none specified, current page is used

// outputs querystring that represents the object's needed params to fetch results of page 3
// query=df_param_test+query&df_param_rpp=4&df_param_timeout=8000&df_param_page=3

````

you can use it to build links to searh results:

````html
'<a href="results.php?<?php echo $df->serialize(4)?>">Next Page</a>'

````

# "unserialize" #

````php

$df = DoofinderApi('6a9abc4dc17351123b1e0198af92e6e9');
$df->unserialize(); // get search string, pagenum, rpp, etc from the request
$df_results = $df->query(); // no need to specify query or page.
````

# extra constructor options #

````php
$df = DoofinderApi('6a9abc4dc17351123b1e0198af92e6e9', // hashid
                   true, // use the request params from a previous $df->serialize()
                         // to set objet's state . defaults to false
                   array(
                     'prefix' => 'sp_df_df', // prefix to use when serializing to params. 
                                             // defaults to 'df_param_'
                     'api_version' => '3.0', // api version of the search server to use
                                             // defaults to '3.0'
                     'restricted_request' => 'post' // when unserializing , use only 
                                                    // params from 'post' or 'get' methods. 
                                                    // defaults to any
                   ));
                   
````

# some other useful methods #

````php
$df->has_next(); // boolean true if there is a next page of results
$df->has_prev(); // boolean true if there is a prev page of results
$df->num_pages(); // total number of pages
$df->get_page(); // get the actual page number
````
