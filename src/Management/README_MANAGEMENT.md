# Doofinder Management API

Doofinder's management API allows you to perform the same administrative tasks you can do on your search engines using the Doofinder control panel, directly from your code. 

- API version: 2.0
For more information, please visit [https://doofinder.com/support](https://app.doofinder.com/api/v2/)

## Requirements

PHP 7.3 and later

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

## Getting Started

Please follow the [installation procedure](#installation--usage) and then run the following:

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Create a new instance of SearchEngineClient wrapper.
$client = new Doofinder\Management\ManagementClient();
// Configure API key authorization: api_token
$client->setApiKey("YOUR_API_KEY");
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $client->setBearerToken("YOUR_API_KEY");

### Example

$hashid = "hashid_example"; // string | Unique id of a search engine.

try {
    $result = $client->getSearchEngine($hashid);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ManagementClient->getSearchEngine: ', $e->getMessage(), PHP_EOL;
}

?>
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

## Changes in code generated by Swagger codegen
### OneOf model references
We edit some models that includes reference to OneOfNameOfModel models. Swagger generated a wrong route to this models so it results into a 404 error.

References like `OneOfDataSourceOptions` had to be set as `\DoofinderManagement\Model\OneOfDataSourceOptions`.

### Return type of methods
We change return type of methods that must return an array of objects. Now, the return type of those methods is `'object'`.

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
