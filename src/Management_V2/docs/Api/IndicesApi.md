# Swagger\Client\IndicesApi

All URIs are relative to *https://{search_zone}-api.doofinder.com*

Method | HTTP request | Description
------------- | ------------- | -------------
[**getReindexingStatus**](IndicesApi.md#getreindexingstatus) | **GET** /api/v2/search_engines/{hashid}/indices/{name}/_reindex_to_temp/ | Return the status of the current reindexing task.
[**indexCreate**](IndicesApi.md#indexcreate) | **POST** /api/v2/search_engines/{searchengine_hashid}/indices | Create an index
[**indexDelete**](IndicesApi.md#indexdelete) | **DELETE** /api/v2/search_engines/{searchengine_hashid}/indices/{name} | Delete an Index
[**indexIndex**](IndicesApi.md#indexindex) | **GET** /api/v2/search_engines/{searchengine_hashid}/indices | List indices
[**indexShow**](IndicesApi.md#indexshow) | **GET** /api/v2/search_engines/{searchengine_hashid}/indices/{name} | Get an Index
[**indexUpdate**](IndicesApi.md#indexupdate) | **PATCH** /api/v2/search_engines/{searchengine_hashid}/indices/{name} | Update an index
[**reindexToTemp**](IndicesApi.md#reindextotemp) | **POST** /api/v2/search_engines/{hashid}/indices/{name}/_reindex_to_temp/ | Reindex the content of the real index into the temporary one.
[**replaceByTemp**](IndicesApi.md#replacebytemp) | **POST** /api/v2/search_engines/{hashid}/indices/{name}/_replace_by_temp/ | Replace the real index with the temporary one.
[**temporaryIndexCreate**](IndicesApi.md#temporaryindexcreate) | **POST** /api/v2/search_engines/{hashid}/indices/{name}/temp/ | Creates a temporary index
[**temporaryIndexDelete**](IndicesApi.md#temporaryindexdelete) | **DELETE** /api/v2/search_engines/{hashid}/indices/{name}/temp/ | Deletes the temporary index.

# **getReindexingStatus**
> object getReindexingStatus($hashid, $name)

Return the status of the current reindexing task.

This return the status of the current reindexing tasks if there is any.

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
// Configure API key authorization: api_token
$config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');// Configure API key authorization: jwt_token
$config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

$apiInstance = new Swagger\Client\Api\IndicesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | Search engine identifier (hashid)
$name = "name_example"; // string | Name of the Index

try {
    $result = $apiInstance->getReindexingStatus($hashid, $name);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling IndicesApi->getReindexingStatus: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **hashid** | **string**| Search engine identifier (hashid) |
 **name** | **string**| Name of the Index |

### Return type

**object**

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **indexCreate**
> \Swagger\Client\Model\Index indexCreate($body, $searchengine_hashid)

Create an index

Create new index for the given search engine

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
// Configure API key authorization: api_token
$config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');// Configure API key authorization: jwt_token
$config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

$apiInstance = new Swagger\Client\Api\IndicesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$body = new \Swagger\Client\Model\Index(); // \Swagger\Client\Model\Index | Index data
$searchengine_hashid = "searchengine_hashid_example"; // string | Search engine identifier (hashid)

try {
    $result = $apiInstance->indexCreate($body, $searchengine_hashid);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling IndicesApi->indexCreate: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **body** | [**\Swagger\Client\Model\Index**](../Model/Index.md)| Index data |
 **searchengine_hashid** | **string**| Search engine identifier (hashid) |

### Return type

[**\Swagger\Client\Model\Index**](../Model/Index.md)

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **indexDelete**
> object indexDelete($searchengine_hashid, $name)

Delete an Index

Delete an Index for the given search engine and name

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
// Configure API key authorization: api_token
$config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');// Configure API key authorization: jwt_token
$config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

$apiInstance = new Swagger\Client\Api\IndicesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$searchengine_hashid = "searchengine_hashid_example"; // string | Search engine identifier (hashid)
$name = "name_example"; // string | Name of the Index

try {
    $result = $apiInstance->indexDelete($searchengine_hashid, $name);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling IndicesApi->indexDelete: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **searchengine_hashid** | **string**| Search engine identifier (hashid) |
 **name** | **string**| Name of the Index |

### Return type

**object**

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **indexIndex**
> \Swagger\Client\Model\Indices indexIndex($searchengine_hashid)

List indices

List the indices of the given search engine

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
// Configure API key authorization: api_token
$config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');// Configure API key authorization: jwt_token
$config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

$apiInstance = new Swagger\Client\Api\IndicesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$searchengine_hashid = "searchengine_hashid_example"; // string | Search engine identifier (hashid)

try {
    $result = $apiInstance->indexIndex($searchengine_hashid);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling IndicesApi->indexIndex: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **searchengine_hashid** | **string**| Search engine identifier (hashid) |

### Return type

[**\Swagger\Client\Model\Indices**](../Model/Indices.md)

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **indexShow**
> \Swagger\Client\Model\Index indexShow($searchengine_hashid, $name)

Get an Index

Get index of the given search engine and name

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
// Configure API key authorization: api_token
$config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');// Configure API key authorization: jwt_token
$config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

$apiInstance = new Swagger\Client\Api\IndicesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$searchengine_hashid = "searchengine_hashid_example"; // string | Search engine identifier (hashid)
$name = "name_example"; // string | Name of the Index

try {
    $result = $apiInstance->indexShow($searchengine_hashid, $name);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling IndicesApi->indexShow: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **searchengine_hashid** | **string**| Search engine identifier (hashid) |
 **name** | **string**| Name of the Index |

### Return type

[**\Swagger\Client\Model\Index**](../Model/Index.md)

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **indexUpdate**
> \Swagger\Client\Model\Index indexUpdate($body, $searchengine_hashid, $name)

Update an index

Update an index for the given search engine and name

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
// Configure API key authorization: api_token
$config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');// Configure API key authorization: jwt_token
$config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

$apiInstance = new Swagger\Client\Api\IndicesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$body = new \Swagger\Client\Model\UpdateIndex(); // \Swagger\Client\Model\UpdateIndex | Index data
$searchengine_hashid = "searchengine_hashid_example"; // string | Search engine identifier (hashid)
$name = "name_example"; // string | Name of the Index

try {
    $result = $apiInstance->indexUpdate($body, $searchengine_hashid, $name);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling IndicesApi->indexUpdate: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **body** | [**\Swagger\Client\Model\UpdateIndex**](../Model/UpdateIndex.md)| Index data |
 **searchengine_hashid** | **string**| Search engine identifier (hashid) |
 **name** | **string**| Name of the Index |

### Return type

[**\Swagger\Client\Model\Index**](../Model/Index.md)

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **reindexToTemp**
> object reindexToTemp($hashid, $name)

Reindex the content of the real index into the temporary one.

This executes a reindexing operation between the real index and the temporary one, taking all items from real and creating them in the temporary. This will return a 404 (Not found) if there is no temporary index.

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
// Configure API key authorization: api_token
$config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');// Configure API key authorization: jwt_token
$config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

$apiInstance = new Swagger\Client\Api\IndicesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | Search engine identifier (hashid)
$name = "name_example"; // string | Name of the Index

try {
    $result = $apiInstance->reindexToTemp($hashid, $name);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling IndicesApi->reindexToTemp: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **hashid** | **string**| Search engine identifier (hashid) |
 **name** | **string**| Name of the Index |

### Return type

**object**

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **replaceByTemp**
> object replaceByTemp($hashid, $name)

Replace the real index with the temporary one.

This request takes the temporary index and \"overwrites\" the real one. Any content in the real index will be lost with this operation.

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
// Configure API key authorization: api_token
$config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');// Configure API key authorization: jwt_token
$config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

$apiInstance = new Swagger\Client\Api\IndicesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | Search engine identifier (hashid)
$name = "name_example"; // string | Name of the Index

try {
    $result = $apiInstance->replaceByTemp($hashid, $name);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling IndicesApi->replaceByTemp: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **hashid** | **string**| Search engine identifier (hashid) |
 **name** | **string**| Name of the Index |

### Return type

**object**

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **temporaryIndexCreate**
> object temporaryIndexCreate($hashid, $name)

Creates a temporary index

Creates a new temporary index for the given index. There could not be two temporary index at the same time so any request made to this endpoint when there is one created will fail. Creating a temporary index also set a lock preventing any changes on the search engine.

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
// Configure API key authorization: api_token
$config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');// Configure API key authorization: jwt_token
$config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

$apiInstance = new Swagger\Client\Api\IndicesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | Search engine identifier (hashid)
$name = "name_example"; // string | Name of the Index

try {
    $result = $apiInstance->temporaryIndexCreate($hashid, $name);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling IndicesApi->temporaryIndexCreate: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **hashid** | **string**| Search engine identifier (hashid) |
 **name** | **string**| Name of the Index |

### Return type

**object**

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **temporaryIndexDelete**
> object temporaryIndexDelete($hashid, $name)

Deletes the temporary index.

Deletes the temporary index. This also removes the lock in the search engine. If there is no temporary index this will return a 404 (Not found).

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
// Configure API key authorization: api_token
$config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');// Configure API key authorization: jwt_token
$config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

$apiInstance = new Swagger\Client\Api\IndicesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | Search engine identifier (hashid)
$name = "name_example"; // string | Name of the Index

try {
    $result = $apiInstance->temporaryIndexDelete($hashid, $name);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling IndicesApi->temporaryIndexDelete: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **hashid** | **string**| Search engine identifier (hashid) |
 **name** | **string**| Name of the Index |

### Return type

**object**

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

