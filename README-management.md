doofinder-php Management API library
====================================

PHP Client for doofinder. Management API lbirary

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

* Types management

````php
  <?php $types = $mySearchEngine->getTypes(); // obtain search engine's datatypes
  $new_types = $mySearchEngine->addType('product'); // add new type
  $mySearchEngine->deleteType('product'); // remove the type and all items within it.
  ```
* Items management

````php
<?php $scrollId_items = $mySearchEngine.items('product'); // obtain first batch of paginated results of items belonging to 'product' type
$scollId = $scrollId_items['scroll_id']; // the pagination identificator
$items = $scrollId_items['results']; // the first batch of paginated results of 'product' type
$next_batch = $mySearchEngine.items('product', $scrollId); // the second batch

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

* Task management

````php

$task_result = $mySearchEngine->process(); // tells our server to process search engine's feeds

$task_info = $mySearchEngine->processInfo(); // retrieve info about the last or current process

$task_info = $mySearchEngine->taskInfo($taskId); // info about a certain task

$logs = $mySearchEngine->logs(); // logs about recent processes

````
