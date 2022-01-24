# Doofinder Management API

Doofinder's management API allows you to perform the same administrative tasks you can do on your search engines using the Doofinder control panel, directly from your code.

- API version: 2.0

For more information, please visit [https://doofinder.com/support](https://app.doofinder.com/api/v2/)

<!-- TOC depthFrom:2 -->

- [Requirements](#requirements)
- [Installation & Usage](#installation--usage)
    - [Composer](#composer)
    - [Manual Installation](#manual-installation)
- [Tests](#tests)
- [Quick & Dirty](#quick--dirty)
- [Documentation](#documentation-for-clients-methods)

<!-- /TOC -->

## Requirements

PHP 5.6 and later

## Installation & Usage
### Composer

To install the bindings via [Composer](http://getcomposer.org/), run the following:

`composer install`

### Manual Installation

Download the files and include `autoload.php`:

```php
    require_once('/path/to/php-doofinder/vendor/autoload.php');
```

## Tests

To run the unit tests:

```
composer tests
```

## Quick & Dirty
### Search Engine
```php
<?php

require_once(__DIR__ . '/../vendor/autoload.php');

const HOST = 'https://eu1-api.doofinder.com';
const TOKEN = 'your_api_token';
const USER_ID = 'your_user_id';

$managementClient = \Doofinder\Management\ManagementClient::create(
    HOST,
    TOKEN,
    USER_ID
);

// **** Create search engine ****
$searchEngineParams = [
    'currency' => 'EUR',
    'language' => 'es',
    'name' => 'search_engine_test',
    'site_url' => 'https://fake_site.es',
    'stopwords' => false,
    'platform' => 'api',
    'has_grouping' => false,
];

$response = $managementClient->createSearchEngine($searchEngineParams);
/** @var \Doofinder\Management\Model\SearchEngine $searchEngine */
$searchEngine = $response->getBody();

// **** Update search engine ****
$searchEngineParams = [
    'currency' => 'USD',
    'language' => 'en',
    'name' => 'search_engine_test',
];

$response = $managementClient->updateSearchEngine($searchEngine->getHashid(), $searchEngineParams);
/** @var \Doofinder\Management\Model\SearchEngine $searchEngine */
$searchEngine = $response->getBody();

// **** Get search engine ****
$response = $managementClient->getSearchEngine($searchEngine->getHashid());
/** @var \Doofinder\Management\Model\SearchEngine $searchEngine */
$searchEngine = $response->getBody();

// **** List search engines ****
$response = $managementClient->listSearchEngines();
/** @var \Doofinder\Management\Model\SearchEngine[] $searchEngines */
$searchEngines = $response->getBody();

// **** Process search engine ****
$managementClient->processSearchEngine($searchEngine->getHashid());

// **** Get search engine process ****
$managementClient->getSearchEngineProcessStatus($searchEngine->getHashid());

// **** Deletes a search engine ****
$managementClient->deleteSearchEngine($searchEngine->getHashid());
```

### Index
```php
<?php

require_once(__DIR__ . '/../vendor/autoload.php');

const HOST = 'https://eu1-api.doofinder.com';
const TOKEN = 'your_api_token';
const USER_ID = 'your_user_id';

$managementClient = \Doofinder\Management\ManagementClient::create(
    HOST,
    TOKEN,
    USER_ID
);

// **** Create index ****
$indexParams = [
    'name' => 'index_test',
    'preset' => 'product',
    'options' => [
        'exclude_out_of_stock_items' => true
    ],
    'datasources' => [
        [
            'type' => 'file',
            'options' => [
                'page_size' => 100,
                'url' => 'https://people.sc.fsu.edu/~jburkardt/data/csv/addresses.csv'
            ]
        ]
    ]
];
$response = $managementClient->createIndex($searchEngine->getHashid(), $indexParams);
/** @var \Doofinder\Management\Model\Index $index */
$index = $response->getBody();

// **** Update index ****
$indexParams = [
    'options' => [
        'exclude_out_of_stock_items' => false
    ]
];
$response = $managementClient->updateIndex($searchEngine->getHashid(), $index->getName(), $indexParams);
/** @var \Doofinder\Management\Model\Index $index */
$index = $response->getBody();

// **** Get index ****
$response = $managementClient->getIndex($searchEngine->getHashid(), $index->getName());
/** @var \Doofinder\Management\Model\Index $index */
$index = $response->getBody();

// **** List index ****
$response = $managementClient->listIndexes($searchEngine->getHashid());
/** @var \Doofinder\Management\Model\Index[] $indexList */
$indexList = $response->getBody();

// **** Create temporary index ****
$managementClient->createTemporaryIndex($searchEngine->getHashid(), $index->getName());

// **** Reindex into temporary index ****
$managementClient->reindexIntoTemporary($searchEngine->getHashid(), $index->getName());

//// **** Reindex status ****
$managementClient->reindexTaskStatus($searchEngine->getHashid(), $index->getName());

// **** Reindex status ****
$managementClient->replaceIndex($searchEngine->getHashid(), $index->getName());

// **** Deletes temporary index ****
$managementClient->deleteTemporaryIndex($searchEngine->getHashid(), $index->getName());

// **** Deletes an index ****
$managementClient->deleteIndex($searchEngine->getHashid(), $index->getName());
```

### Items
```php
<?php

require_once(__DIR__ . '/../vendor/autoload.php');

const HOST = 'https://eu1-api.doofinder.com';
const TOKEN = 'your_api_token';
const USER_ID = 'your_user_id';

$managementClient = \Doofinder\Management\ManagementClient::create(
    HOST,
    TOKEN,
    USER_ID
);

// **** Create item ****
$itemParams = [
    'id' => 'this_is_my_item_id',
    'group_id' => 'group_test',
    'df_group_leader' => true,
    'df_manual_boost' => null,
    'categories' => [
        'parent category > inner category > leaft category',
        'other parent > other inner > other leaft'
    ],
    'best_price' => '123.45',
    'custom_field_1' => 'fake_value_1',
    'custom_field_2' => 'fake_value_2',
];

$response = $managementClient->createItem($searchEngine->getHashid(), $index->getName(), $itemParams);
/** @var \Doofinder\Management\Model\Item $item */
$item = $response->getBody();

// **** Update item ****
$itemParams = [
    'group_id' => 'group_test',
    'df_group_leader' => true,
    'df_manual_boost' => 1.1,
    'best_price' => '99.95',
    'custom_field_1' => 'new_fake_value_1',
];

$response = $managementClient->updateItem($searchEngine->getHashid(), $index->getName(), $item->getId(), $itemParams);
/** @var \Doofinder\Management\Model\Item $item */
$item = $response->getBody();

// **** Get item ****
$response = $managementClient->getItem($searchEngine->getHashid(), $index->getName(), $item->getId());
/** @var \Doofinder\Management\Model\Item $item */
$item = $response->getBody();

// **** Count items ****
$managementClient->countItems($searchEngine->getHashid(), $index->getName());

// **** Find items ****
$idParams = [
    [
        'id' => 'this_is_my_item_id',
    ]
];
$response = $managementClient->findItems($searchEngine->getHashid(), $index->getName(), $idParams);
/** @var \Doofinder\Management\Model\Item $item */
$item = $response->getBody()[0]['item'];

// **** Scroll index ****
$scrollParams = [
    'rpp' => 5,
    'group_id' => 'group_test',
];
$response = $managementClient->scrollIndex($searchEngine->getHashid(), $index->getName(), $scrollParams);
$scrollItems = $response->getBody();
/** @var \Doofinder\Management\Model\Item[] $items */
$items = $scrollItems['items'];

// **** Deletes an item ****
$managementClient->deleteItem($searchEngine->getHashid(), $index->getName(), $item->getId());

// **** Create items in bulk ****
$itemParams = [
    [
        'id' => 'this_is_my_item_id_2',
        'group_id' => 'group_test',
        'best_price' => '1.23',
        'custom_field_3' => 'fake_value_3',
    ],
    [
        'id' => 'this_is_my_item_id_3',
        'group_id' => 'group_test',
        'best_price' => '2.34',
        'custom_field_4' => 'fake_value_4',
    ],
];

$response = $managementClient->createItemsInBulk($searchEngine->getHashid(), $index->getName(), $itemParams);
/** @var \Doofinder\Management\Model\Item[] $items */
$items = $response->getBody();

// **** Update items in bulk ****
$itemParams = [
    [
        'id' => 'this_is_my_item_id_2',
        'best_price' => '0.99',
    ],
    [
        'id' => 'this_is_my_item_id_3',
        'custom_field_4' => 'new_fake_value_4',
    ],
];
$response = $managementClient->updateItemsInBulk($searchEngine->getHashid(), $index->getName(), $itemParams);
/** @var \Doofinder\Management\Model\Item[] $items */
$items = $response->getBody();

// **** Delete items in bulk ****
$itemParams = [
    [
        'id' => 'this_is_my_item_id_2',
    ],
];
$managementClient->deleteItemsInBulk($searchEngine->getHashid(), $index->getName(), $itemParams);

// **** Create item in temporal index ****
$itemParams = [
    'id' => 'this_is_my_item_id_2',
    'group_id' => 'group_test',
    'best_price' => '1.23',
    'custom_field_3' => 'fake_value_3',
];
$response = $managementClient->createItemInTemporalIndex($searchEngine->getHashid(), $index->getName(), $itemParams);
/** @var \Doofinder\Management\Model\Item $temporalItem */
$temporalItem = $response->getBody();

// **** Update item in temporal index ****
$itemParams = [
    'best_price' => '0.99',
];
$response = $managementClient->updateItemInTemporalIndex($searchEngine->getHashid(), $index->getName(), $items[0]->getId(), $itemParams);
/** @var \Doofinder\Management\Model\Item $temporalItem */
$temporalItem = $response->getBody();

// **** Get item from temporal index ****
$response = $managementClient->getItemFromTemporalIndex($searchEngine->getHashid(), $index->getName(), $temporalItem->getId());
/** @var \Doofinder\Management\Model\Item $temporalItem */
$temporalItem = $response->getBody();

// **** Find items from temporal index ****
$idParams = [
    'id' => $temporalItem->getId(),
];
$response = $managementClient->findItemsFromTemporalIndex($searchEngine->getHashid(), $index->getName(), $idParams);
/** @var \Doofinder\Management\Model\Item $temporalItem */
$temporalItem = $response->getBody()[0]['item'];

// **** Delete item from temporal ****
$managementClient->deleteItemFromTemporalIndex($searchEngine->getHashid(), $index->getName(), $temporalItem->getId());

// **** Create items in temporal index in bulk ****
$itemParams = [
    [
        'id' => 'this_is_my_item_id_2',
        'group_id' => 'group_test',
        'best_price' => '1.23',
        'custom_field_3' => 'fake_value_3',
    ]
];
$response = $managementClient->createItemsInBulkInTemporalIndex($searchEngine->getHashid(), $index->getName(), $itemParams);
/** @var \Doofinder\Management\Model\Item[] $temporalItems */
$temporalItems = $response->getBody();

// **** Update items in temporal index in bulk ****
$itemParams = [
    'id' => $temporalItem->getId(),
    'best_price' => '0.99',
];
$response = $managementClient->updateItemsInBulkInTemporalIndex($searchEngine->getHashid(), $index->getName(), $itemParams);
/** @var \Doofinder\Management\Model\Item[] $temporalItems */
$temporalItems = $response->getBody();

// **** Delete items in temporary index in bulk ****
$itemParams = [
    [
        'id' => 'this_is_my_item_id_2',
    ],
];
$managementClient->deleteItemsInBulkInTemporalIndex($searchEngine->getHashid(), $index->getName(), $itemParams);
```
## Documentation for Client's Methods

| Method                               | Description                                                                     | Return type                                                                |
|--------------------------------------|---------------------------------------------------------------------------------|----------------------------------------------------------------------------|
| **createSearchEngine**               | Creates a search engine                                                         | [Doofinder\Management\Model\SearchEngine](Model/SearchEngine.php)          |
| **updateSearchEngine**               | Updates a search engine                                                         | [Doofinder\Management\Model\SearchEngine](Model/SearchEngine.php)          |
| **getSearchEngine**                  | Gets a search engine                                                            | [Doofinder\Management\Model\SearchEngine](Model/SearchEngine.php)          |
| **listSearchEngines**                | Gets a list of search engines                                                   | Array of [Doofinder\Management\Model\SearchEngine](Model/SearchEngine.php) |
| **deleteSearchEngine**               | Deletes a search engine                                                         | void                                                                       |
| **processSearchEngine**              | Schedules a task for processing all search engine's data sources.               | Array                                                                      |
| **getSearchEngineProcessStatus**     | Gets the status of the last process task                                        | Array                                                                      |
| **createIndex**                      | Creates an index                                                                | [Doofinder\Management\Model\Index](Model/Index.php)                        |
| **updateIndex**                      | Updates an index                                                                | [Doofinder\Management\Model\Index](Model/Index.php)                        |
| **getIndex**                         | Gets an index                                                                   | [Doofinder\Management\Model\Index](Model/Index.php)                        |
| **listIndexes**                      | Gets a list of index                                                            | Array of  [Doofinder\Management\Model\Index](Model/Index.php)              |
| **deleteIndex**                      | Deletes an index                                                                | void                                                                       |
| **createItem**                       | Creates an item                                                                 | [Doofinder\Management\Model\Item](Model/Item.php)                          |
| **updateItem**                       | Updates an item                                                                 | [Doofinder\Management\Model\Item](Model/Item.php)                          |
| **getItem**                          | Gets an item                                                                    | [Doofinder\Management\Model\Item](Model/Item.php)                          |
| **scrollIndex**                      | Scrolls an index and return an item list                                        | Array                                                                      |
| **deleteItem**                       | Deletes an item                                                                 | void                                                                       |
| **createTemporaryIndex**             | Creates a temporary index                                                       | Array                                                                      |
| **deleteTemporaryIndex**             | Deletes a temporary index                                                       | void                                                                       |
| **replaceIndex**                     | Replaces an index with the temporary index content                              | Array                                                                      |
| **reindexIntoTemporary**             | Reindex between from production index to temporary one                          | Array                                                                      |
| **reindexTaskStatus**                | Gets the status of the last scheduled reindexing tasks                          | Array                                                                      |
| **createItemInTemporalIndex**        | Creates an item with the data provided in the temporal index                    | [Doofinder\Management\Model\Item](Model/Item.php)                          |
| **updateItemInTemporalIndex**        | Partially updates an item in the temporal index given its id                    | [Doofinder\Management\Model\Item](Model/Item.php)                          |
| **getItemFromTemporalIndex**         | Gets an item from the temporal index by its id                                  | [Doofinder\Management\Model\Item](Model/Item.php)                          |
| **deleteItemFromTemporalIndex**      | Deletes an item from the temporal index given its id                            | void                                                                       |
| **findItemsFromTemporalIndex**       | Finds a list items from a temporal index in a single operation by a list of ids | Array of [Doofinder\Management\Model\Item](Model/Item.php)                 |
| **findItems**                        | Finds a list items in a single operation by a list of ids                       | Array of [Doofinder\Management\Model\Item](Model/Item.php)                 |
| **countItems**                       | Returns the total number of items in an index                                   | Array                                                                      |
| **createItemsInBulkInTemporalIndex** | Creates a list of items in the temporal index in a single bulk operation        | Array of [Doofinder\Management\Model\Item](Model/Item.php)                 |
| **updateItemsInBulkInTemporalIndex** | Updates a list of items in the temporal index in a single bulk operation        | Array of [Doofinder\Management\Model\Item](Model/Item.php)                 |
| **deleteItemsInBulkInTemporalIndex** | Deletes a list of items in the temporal index in a single bulk operation        | Array                                                                      |
| **createItemsInBulk**                | Creates a list of items in the index in a single bulk operation                 | Array of [Doofinder\Management\Model\Item](Model/Item.php)                 |
| **updateItemsInBulk**                | Updates a list of items from the index in a single bulk operation               | Array of [Doofinder\Management\Model\Item](Model/Item.php)                 |
| **deleteItemsInBulk**                | Deletes a list of items from the index in a single bulk operation               | Array                                                                      |

## Authorization
We use [JWT](https://en.wikipedia.org/wiki/JSON_Web_Token) in http header for authenticate requests.
```
{
  "Authorization" : "Bearer {my_jwt_token}"
}
```