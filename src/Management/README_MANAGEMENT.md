# Doofinder Management API

Doofinder's management API allows you to perform the same administrative tasks you can do on your search engines using the Doofinder control panel, directly from your code. 

- API version: 2.0
For more information, please visit [https://doofinder.com/support](https://app.doofinder.com/api/v2/)

## Requirements

PHP 5.6 and later

## Installation & Usage
### Composer

To install the bindings via [Composer](http://getcomposer.org/), run the following:

`composer install`

`composer dump-autoload`

### Manual Installation

Download the files and include `autoload.php`:

```php
    require_once('/path/to/php-doofinder/vendor/autoload.php');
```

## Tests

To run the unit tests:

```
composer install
./vendor/bin/phpunit
```

## Quick & Dirty

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

define('YOUR_HOST', 'eu1-search.doofinder.com');
define('YOUR_API_KEY', '384fdag73c7ff0a59g589xf9f4083bxb9727f9c3');

$client = new Doofinder\Management\ManagementClient(YOUR_HOST, YOUR_API_KEY);

// Search Engine example body
$se_body = '{
    "inactive": false,
    "indices": [],
    "language": "en",
    "name": "my new search engine",
    "search_url": "http://eu1-search.doofinder.com",
    "site_url": "http://example.com",
    "stopwords": false
  }';

// Create a Search Engine
$client->createSearchEngine($body);

// List Search Engines
$client->listSearchEngines();


// Get a Search Engine
$hashid = "cc79e589e94b0350fb244e477e0f5b7a"; // string | Unique id of a search engine.
$client->getSearchEngine($hashid);

// Create an Index
// Indice example body
$index_body = '{
"options": {
"exclude_out_of_stock_items": false,
"group_variants": false
},
"datasources": [
{
"options": {
    "url": "https://yourserver/your_data_feed.xml"
 },
"type": "file"
}
],
"name": "product",
"preset": "product"
}';

$client->createIndex($hashid, $index_body);

// Create an item
$index_name = "product";
$item_body = '{
    "id": "1234",
    "link": "http://www.example.com/Cadillac/PM-265985.html",
    "image_link": "http://www.example.com/images/Cadillac-426176-95.jpg",
    "availability": "in stock",
    "description": "Lorem ipsum",
    "title": "Cadillac"
  }';

$client->createItem($hashid, $index_name, $item_body);

// Create a bulk of items

$bulk_body = '[{
    "id": "1234",
    "link": "http://www.example.com/Cadillac/PM-265985.html",
    "image_link": "http://www.example.com/images/Cadillac-426176-95.jpg",
    "availability": "in stock",
    "description": "Lorem ipsum",
    "title": "Cadillac"
  },{
    "id": "1235",
    "link": "http://www.example.com/Hummer/PM-265985.html",
    "image_link": "http://www.example.com/images/Hummer-426176-95.jpg",
    "availability": "in stock",
    "description": "Lorem ipsum",
    "title": "Hummer"
  }]';

$client->createBulk($hashid, $index_name, $bulk_body);

// Delete an item

$item_id = "1234"

$client->deleteItem($hashid, $item_id, $index_name);

// Delete a bulk of items

$index_name = "product";
$bulk_body = '[{"id": "1234"}, {"id": "1235"}]';

$result = $client->deleteBulk($hashid, $index_name, $bulk_body);

// Process your index

// First, create a temporary index

$client->createTemporaryIndex($hashid, $index_name);

// Reindex the content of the real index into the temporary one

$client->reindex($hashid, $index_name);

// Replace the real index with the temporary one

$client->replace($hashid, $index_name);

```

## Documentation for Client's Methods

Method | Description | Return type
------------ | ------------- | -------------
[**returnReindexingStatus**] | Return the status of the current reindexing task. | \DoofinderManagement\Model\ReindexingTask Object.
[**createIndex**] | Creates an index. | \DoofinderManagement\Model\Index Object.
[**deleteIndex**] | Deletes an Index. | Void.
[**listIndices**] | Lists all indices. | \DoofinderManagement\Model\Indices Array.
[**getIndex**] | Gets an Index. | \DoofinderManagement\Model\Index Object.
[**updateIndex**] | Updates an index. | \DoofinderManagement\Model\Index Object.
[**reindex**] | Reindex the content of the real index into the temporary one. | Object.
[**replace**] | Replace the real index with the temporary one. | Object.
[**createTemporaryIndex**] | Creates a temporary index. | Object.
[**deleteTemporaryIndex**] | Deletes the temporary index. | Object.
[**createItem**] | Creates an item. | \DoofinderManagement\Model\Item Object.
[**deleteItem**] | Deletes an item from the index. | Void.
[**scrollsItems**] | Scrolls through all index items | \DoofinderManagement\Model\Scroller Array.
[**getItem**] | Gets an item from the index. | \DoofinderManagement\Model\Item Object.
[**createTempItem**] | Creates an item in the temporal index. | \DoofinderManagement\Model\Item Object.
[**deleteTempItem**] | Deletes an item in the temporal index. | Void.
[**getTempItem**] | Gets an item from the temporal index. | \DoofinderManagement\Model\Item Object.
[**updateTempItem**] | Partially updates an item in the temporal index. | \DoofinderManagement\Model\Item Object.
[**updateItem**] | Partially updates an item in the index. | \DoofinderManagement\Model\Item Object.
[**createBulk**] | Creates a bulk of item in the index. | \DoofinderManagement\Model\BulkResult Array.
[**deleteBulk**] | Deletes a bulk of items from the index. | \DoofinderManagement\Model\BulkResult Array.
[**updateBulk**] | Partial updates a bulk of items in the index. | \DoofinderManagement\Model\BulkResult Array.
[**createTempBulk**] | Creates a bulk of items in the temporal index. | \DoofinderManagement\Model\BulkResult Array.
[**deleteTempBulk**] | Deletes items in bulk in the temporal index. | \DoofinderManagement\Model\BulkResult Array.
[**updateTempBulk**] | Partial updates a bulk of items in the temporal index. | \DoofinderManagement\Model\BulkResult Array.
[**processSearchEngine**] | Process all search engine&#x27;s data sources. | \DoofinderManagement\Model\ProcessingTask Object.
[**getProcessStatus**] | Gets the status of the process task. | \DoofinderManagement\Model\ProcessingTask Object.
[**createSearchEngine**] | Creates a new search engine. | \DoofinderManagement\Model\SearchEngine Object.
[**deleteSearchEngine**] | Deletes a search engine. | Void.
[**listSearchEngines**] | Lists search engines. | \DoofinderManagement\Model\SearchEngines Array.
[**getSearchEngine**] | Gets a search engine. | \DoofinderManagement\Model\SearchEngine Object
[**updateSearchEngine**] | Updates a search engine. | \DoofinderManagement\Model\SearchEngine Object

## Documentation For Models

 - [BulkRequest](PhpClient/docs/Model/BulkRequest.md)
 - [BulkResult](PhpClient/docs/Model/BulkResult.md)
 - [BulkResultResults](PhpClient/docs/Model/BulkResultResults.md)
 - [DataSource](PhpClient/docs/Model/DataSource.md)
 - [DataSources](PhpClient/docs/Model/DataSources.md)
 - [Hashid](PhpClient/docs/Model/Hashid.md)
 - [Index](PhpClient/docs/Model/Index.md)
 - [IndexUpdate](PhpClient/docs/Model/IndexUpdate.md)
 - [Indices](PhpClient/docs/Model/Indices.md)
 - [InlineResponse200](PhpClient/docs/Model/InlineResponse200.md)
 - [InlineResponse2001](PhpClient/docs/Model/InlineResponse2001.md)
 - [Item](PhpClient/docs/Model/Item.md)
 - [Items](PhpClient/docs/Model/Items.md)
 - [ItemsIds](PhpClient/docs/Model/ItemsIds.md)
 - [ItemsIdsInner](PhpClient/docs/Model/ItemsIdsInner.md)
 - [OneOfDataSourceOptions](PhpClient/docs/Model/OneOfDataSourceOptions.md)
 - [OneOfDataSourceUrl](PhpClient/docs/Model/OneOfDataSourceUrl.md)
 - [OneOfIndexUpdateOptions](PhpClient/docs/Model/OneOfIndexUpdateOptions.md)
 - [OneOfhashid](PhpClient/docs/Model/OneOfhashid.md)
 - [OneOfqueryName](PhpClient/docs/Model/OneOfqueryName.md)
 - [ProcessingTask](PhpClient/docs/Model/ProcessingTask.md)
 - [QueryName](PhpClient/docs/Model/QueryName.md)
 - [ReindexingTask](PhpClient/docs/Model/ReindexingTask.md)
 - [Scroller](PhpClient/docs/Model/Scroller.md)
 - [SearchEngine](PhpClient/docs/Model/SearchEngine.md)
 - [SearchEngines](PhpClient/docs/Model/SearchEngines.md)
 - [StatsBannersResult](PhpClient/docs/Model/StatsBannersResult.md)
 - [StatsBannersResultResults](PhpClient/docs/Model/StatsBannersResultResults.md)
 - [StatsClicksResult](PhpClient/docs/Model/StatsClicksResult.md)
 - [StatsClicksResultInner](PhpClient/docs/Model/StatsClicksResultInner.md)
 - [StatsRedirectsResult](PhpClient/docs/Model/StatsRedirectsResult.md)
 - [StatsRedirectsResultResults](PhpClient/docs/Model/StatsRedirectsResultResults.md)
 - [StatsTimeResult](PhpClient/docs/Model/StatsTimeResult.md)
 - [StatsTimeResultResults](PhpClient/docs/Model/StatsTimeResultResults.md)
 - [StatsTopSearchesResult](PhpClient/docs/Model/StatsTopSearchesResult.md)
 - [StatsTopSearchesResultResults](PhpClient/docs/Model/StatsTopSearchesResultResults.md)

## Generate php client with Swagge codegen
### Download Swagger package.
You have to download the java package inside the swagger folder along side the other files there. You can download it from [here](https://github.com/swagger-api/swagger-codegen)

Run the script generate_client.sh:
```
./generate_client.sh
```

This will generate a new php client with some fixes inside some models.


## Changes in code generated by Swagger codegen
### Models that extends from ArrayAccess
The method deserialize() of ObjectSerializer class does not check array type data. There is a problem with the class Item too that swagger can not deserialize so we have to add the conditional like below:
```
} elseif ($class === 'object' or is_array($data)) {  // Add this conditional to return an array type data
    settype($data, 'array');
    return $data;
} elseif ($class === '\DoofinderManagement\Model\Item') { // Add this conditional to return Item objects
    $data = (object)$data;
    return $data;
} elseif ($class === '\DateTime') {
```

## Documentation For Authorization


## api_token

- **Type**: API key
- **API key parameter name**: Authorization
- **Location**: HTTP header

## jwt_token

- **Type**: API key
- **API key parameter name**: Authorization
- **Location**: HTTP header


## Author

domingo@doofinder.com
