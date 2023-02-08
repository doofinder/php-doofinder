# Official PHP client for the Doofinder Management API

Doofinder's management API allows you to perform the same administrative tasks you can do on your search engines using the Doofinder control panel, directly from your code.

- API version: 2.0

For more information, please visit the documentation: [Management API V2](https://docs.doofinder.com/api/management/v2/)

<!-- TOC depthFrom:2 -->

- [Requirements](#requirements)
- [Installation & Usage](#installation--usage)
    - [Using Composer](#using-composer)
    - [Manual Installation](#manual-installation)
- [Authorization](#authorization)
  - [API Token](#api-token)
  - [JKW](#jkw)
- [Tests](#tests)
- [API for Search Engine](#search-engine)
- [API for Index](#index)
- [API for Items](#items)
- [Responses](#responses)
    - [Search Engine response](#search-engine-response)
    - [Index response](#index-response)
    - [Index Options response](#index-options-response)
    - [Data Source response](#data-source-response)
    - [Item response](#item-response)
    - [Status response](#status-response)

<!-- /TOC -->

## Requirements

Requires PHP 5.6 or later. Not tested in previous versions.

## Installation & Usage

### Using Composer

You can also download the library using [Composer](https://packagist.org/packages/doofinder/doofinder). 

Run this command to add the Doofinder library to your `composer.json` file:

```bash
composer require doofinder/doofinder
```

If you are already using Composer your `autoload.php` file will be updated. If not, a new one will be generated and you will have to include it:

```php
<?php
require_once dirname(__FILE__)."/vendor/autoload.php";

use \Doofinder\Management\ManagementClient;

const HOST = 'https://eu1-api.doofinder.com';
const API_KEY = 'your_api_token';
const USER_ID = 'your_user_id';

$managementClient = ManagementClient::create(HOST, API_KEY, USER_ID);
```

### Manual Installation

To install the library you can download it from the [releases](https://github.com/doofinder/php-doofinder/releases) page of the project and include the `autoload.php` file provided to use it:

```php
require_once('/path/to/php-doofinder/vendor/autoload.php');
```

## Authorization

To authenticate you need a Doofinder `API key`. If you don't have one you can generate it in the Doofinder Admin by going to your Account and then to API Keys, [here](https://app.doofinder.com/es/admin/api/).

```plaintext
ab46030xza33960aac71a10248489b6c26172f07
```

### API Token

You can authenticate with the previous API key. The correct way to authenticate is to send a `HTTP Header` with the name `Authorization` and the value `Token {api-key}`.

```bash
{
  "Authorization" : "Token {my_api_token}"
}
```

For example, for the key shown above:

```plaintext
Authorization: Token ab46030xza33960aac71a10248489b6c26172f07
```

### JKW

If you prefer you can authenticate with a [JSON Web Token](https://jwt.io/). The token must be signed with an API management key and there are some claims required in the JWT payload. These claims are:

* `iat` (issued at): Creation datetime timestamp, i.e. the moment when the JWT was created.

* `exp` (expiration time): Expiration datetime timestamp, i.e. the moment when the JWT is going to expire and will no longer be valid. The time span between issued and expiration dates must be shorter than a week.

* `name`: Your user code. It is your unique identifier as doofinder user. You can find this code in your profile page in the Doofinder's administration panel.

To authenticate using JWT you must send a `HTTP header` with the name `Authorization` and the value `Bearer {JWT-token}`.

```bash
{
  "Authorization" : "Bearer {my_jwt_token}"
}
```

For example:
```plaintext
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJuYW1lIjoidGVzdCIsImlhdCI6MTUxNjIzOTAyMn0.QX_3HF-T2-vlvzGDbAzZyc1Cd-J9qROSes3bxlgB4uk
```

## Tests

To run the unit tests:

```bash
composer tests
```

## Search Engine

All search engines CRUD operations, including handling data sources processing.

```php
<?php

require_once(__DIR__ . '/../vendor/autoload.php');

const HOST = 'https://eu1-api.doofinder.com';
const API_KEY = 'your_api_token';
const USER_ID = 'your_user_id';

$managementClient = \Doofinder\Management\ManagementClient::create(
    HOST,
    API_KEY,
    USER_ID
);

// **** Create search engine ****
$searchEngineParams = [
    'currency' => 'EUR',
    'language' => 'es',
    'name' => 'search_engine_test',
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

### Documentation for Search Engine methods

| Method | Description | Return type |
|-|-|-|
| **createSearchEngine** | Creates a search engine | [Search Engine response](#search-engine-response) |
| **updateSearchEngine** | Updates a search engine | [Search Engine response](#search-engine-response) |
| **getSearchEngine** | Gets a search engine | [Search Engine response](#search-engine-response) |
| **listSearchEngines** | Gets a list of search engines | Array of [Search Engine response](#search-engine-response) |
| **deleteSearchEngine** | Deletes a search engine | void |
| **processSearchEngine** | Schedules a task for processing all search engine's data sources. | Array |
| **getSearchEngineProcessStatus** | Gets the status of the last process task | Array |

## Index

All indices and temporary indices CRUD operations.

```php
<?php

require_once(__DIR__ . '/../vendor/autoload.php');

const HOST = 'https://eu1-api.doofinder.com';
const API_KEY = 'your_api_token';
const USER_ID = 'your_user_id';

$managementClient = \Doofinder\Management\ManagementClient::create(
    HOST,
    API_KEY,
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

// **** Replace status ****
$managementClient->replaceIndex($searchEngine->getHashid(), $index->getName());

// **** Deletes temporary index ****
$managementClient->deleteTemporaryIndex($searchEngine->getHashid(), $index->getName());

// **** Deletes an index ****
$managementClient->deleteIndex($searchEngine->getHashid(), $index->getName());
```

### Documentation for Index methods

| Method | Description | Return type |
|-|-|-|
| **createIndex** | Creates an index | [Index response](#index-response) |
| **updateIndex** | Updates an index | [Index response](#index-response) |
| **getIndex** | Gets an index | [Index response](#index-response) |
| **listIndexes** | Gets a list of index | Array of  [Index response](#index-response) |
| **deleteIndex** | Deletes an index | void |
| **createTemporaryIndex** | Creates a temporary index | [Status response](#status-response) |
| **deleteTemporaryIndex** | Deletes a temporary index | void |
| **replaceIndex** | Replaces an index with the temporary index | [Status response](#status-response) |
| **reindexIntoTemporary** | Reindex between from production index to temporary one | [Status response](#status-response) |
| **reindexTaskStatus** | Gets the status of the last scheduled reindexing tasks | Array |

## Items

Handling content of indices. Allows to retrieve and update the items of an index.

```php
<?php

require_once(__DIR__ . '/../vendor/autoload.php');

const HOST = 'https://eu1-api.doofinder.com';
const API_KEY = 'your_api_token';
const USER_ID = 'your_user_id';

$managementClient = \Doofinder\Management\ManagementClient::create(
    HOST,
    API_KEY,
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
    ['id' => 'item_id'],
    ['id' => 'other_item_id'],
];
$response = $managementClient->findItems($searchEngine->getHashid(), $index->getName(), $idParams);
/** @var \Doofinder\Management\Model\Item[] $items */
$items = $response->getBody();

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
    ['id' => 'this_is_my_item_id_1'],
    ['id' => 'this_is_my_item_id_2']
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

### Documentation for Item methods

| Method | Description | Return type |
|-|-|-|
| **createItem** | Creates an item | [Item response](#item-response) |
| **updateItem** | Updates an item | [Item response](#item-response) |
| **getItem** | Gets an item | [Item response](#item-response) |
| **scrollIndex** | Scrolls an index and return an item list | Array of [Item response](#item-response) |
| **deleteItem** | Deletes an item | void |
| **createItemInTemporalIndex** | Creates an item with the data provided in the temporal index | [Item response](#item-response) |
| **updateItemInTemporalIndex** | Partially updates an item in the temporal index given its id | Array of list of results of each bulk operation |
| **getItemFromTemporalIndex** | Gets an item from the temporal index by its id | [Item response](#item-response) |
| **deleteItemFromTemporalIndex** | Deletes an item from the temporal index given its id | void |
| **findItemsFromTemporalIndex** | Finds a list items from a temporal index in a single operation by a list of ids | Array of [Item response](#item-response) |
| **findItems** | Finds a list items in a single operation by a list of ids | Array of [Item response](#item-response) |
| **countItems** | Returns the total number of items in an index | Array |
| **createItemsInBulkInTemporalIndex** | Creates a list of items in the temporal index in a single bulk operation | Array of list of results of each bulk operation |
| **updateItemsInBulkInTemporalIndex** | Updates a list of items in the temporal index in a single bulk operation | Array of list of results of each bulk operation |
| **deleteItemsInBulkInTemporalIndex** | Deletes a list of items in the temporal index in a single bulk operation | Array of list of results of each bulk operation |
| **createItemsInBulk** | Creates a list of items in the index in a single bulk operation | Array of list of results of each bulk operation |
| **updateItemsInBulk** | Updates a list of items from the index in a single bulk operation | Array of list of results of each bulk operation |
| **deleteItemsInBulk** | Deletes a list of items from the index in a single bulk operation | Array of list of results of each bulk operation |

## Responses

#### Search Engine response

```php
[
    "currency" => "(string) Currency used in the search engine in ISO 4217 Code",
    "hashid" => "(string) A unique code that identifies a search engine.",
    "indices" => "(Array) A list of indices for a search engine.", # Show Index response
    "inactive" => "(boolean) Indicates if the search engine has been deactivated and therefore it can not receive requests.",
    "language" => "(string) An ISO 639-1 language code that determines the language of the search engine. The language affects how the words indexed are tokenized and which stopwords to use.",
    "name" => "(string) A short name that helps identifying the search engine.",
    "search_url" => "(string) Indicates the search server domain for this search engine. You should use this domain to perform searches to this search engine.",
    "stopwords" => "(boolean) Default: false. Ignores high-frequency terms like 'the', 'and', 'is'. These words have a low weight and contribute little to the relevance score.",
    "platform" => "(string) Indicates which platform the search engine is associated with.",
    "has_grouping" => "(boolean) When this option is selected, only one of the item variants is returned in the search results. This only works if the indexed item have the group_id field."
]
```

#### Index response

```php
[ 
    "name" => "(string) Name of the Index. It works as the index identifier.",
    "preset" => "(string) Enum: ['generic' 'product' 'page' 'category'] Preset of the index. The preset defines a set of configuration parameters for the index like basic fields to be included, and field transformations. For instance, the product preset creates the best_price field.",
    "options" => "(Array (Index Options)) Options for an index.", # Show Index Options response
    "datasources" => "(Array (Data Sources)) List of datasources of an index." # Show Data Sources response
]
```

#### Index Options response

```php
[
    "exclude_out_of_stock_items" => "(boolean) When this option is selected, products without stock are not included in search results. In order to identify which of your products are out of stock you must use the availability field with 'in stock' / 'out of stock' values."
]
```

#### Data Source response

```php
[ 
    "type" => "(string) Enum: 'bigcommerce' 'ekm' 'file' 'magento2' 'shopify'. Type of datasource",
    "options" => "(Array of EKM Source Options or Magento2 Source Options or File Source Options) DataSource general options. They define required parameters for the DataSource to work or options that modify the access to the data feed."
]
```

#### Item response

```php
[
    "id" => "(string) Item id", 
    "group_id" => "(string or null) This field indicates the group to which this item belongs to. All items with the same group_id will be collapsed into one in search results, returning the most relevant one or the group leader if they all have the same score.",
    "df_group_leader" => "(boolean or null) This field indicates the item chosen as the default among its group. It will be returned in search results if there is no other item with a higher score.",
    "df_manual_boost" => "(number or null) A numeric score boosting. It multiplies the natural score of the item for a search. For instance, if boost is greater than 1.0 the item will appear higher in the results. If it is lower than 1.0, it will appear lower. The minimum value is 0.0.",
    "categories" => "(Array of strings or string) This field has special behaviour when Indice has product preset",
    "best_price" => "(number or null) Auto created field that gets the min value between price or sale_price fields, if added in the document. It gets null if doesn't find any of these fields.",
    "price" => "(number or null) A numeric field that indicates the price.",
    "sale_price" => "(number or null) A numeric field that indicates the actual sale price."
]
```

#### Status response

```php
[
    "status" => "OK"
]
```