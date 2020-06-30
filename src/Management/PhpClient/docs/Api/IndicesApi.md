# DoofinderManagement\IndicesApi

All URIs are relative to *https://{search_zone}-api.doofinder.com*

Method | HTTP request | Description
------------- | ------------- | -------------
[**getReindexingStatus**](IndicesApi.md#getreindexingstatus) | **GET** /api/v2/search_engines/{hashid}/indices/{name}/_reindex_to_temp/ | Return the status of the current reindexing task.
[**indexCreate**](IndicesApi.md#indexcreate) | **POST** /api/v2/search_engines/{hashid}/indices | Creates an index.
[**indexDelete**](IndicesApi.md#indexdelete) | **DELETE** /api/v2/search_engines/{hashid}/indices/{name} | Deletes an Index.
[**indexIndex**](IndicesApi.md#indexindex) | **GET** /api/v2/search_engines/{hashid}/indices | Lists all indices.
[**indexShow**](IndicesApi.md#indexshow) | **GET** /api/v2/search_engines/{hashid}/indices/{name} | Gets an Index.
[**indexUpdate**](IndicesApi.md#indexupdate) | **PATCH** /api/v2/search_engines/{hashid}/indices/{name} | Updates an index.
[**reindexToTemp**](IndicesApi.md#reindextotemp) | **POST** /api/v2/search_engines/{hashid}/indices/{name}/_reindex_to_temp/ | Reindex the content of the real index into the temporary one.
[**replaceByTemp**](IndicesApi.md#replacebytemp) | **POST** /api/v2/search_engines/{hashid}/indices/{name}/_replace_by_temp/ | Replace the real index with the temporary one.
[**temporaryIndexCreate**](IndicesApi.md#temporaryindexcreate) | **POST** /api/v2/search_engines/{hashid}/indices/{name}/temp/ | Creates a temporary index.
[**temporaryIndexDelete**](IndicesApi.md#temporaryindexdelete) | **DELETE** /api/v2/search_engines/{hashid}/indices/{name}/temp/ | Deletes the temporary index.

# **getReindexingStatus**
> \DoofinderManagement\Model\ReindexingTask getReindexingStatus($hashid, $name)

Return the status of the current reindexing task.

This return the status of the current reindexing tasks if there is any.

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
// Configure API key authorization: api_token
$config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');// Configure API key authorization: jwt_token
$config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

$apiInstance = new DoofinderManagement\Api\IndicesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | Unique id of a search engine.
$name = "name_example"; // string | Name of an index.

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
 **hashid** | **string**| Unique id of a search engine. |
 **name** | **string**| Name of an index. |

### Return type

[**\DoofinderManagement\Model\ReindexingTask**](../Model/ReindexingTask.md)

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **indexCreate**
> \DoofinderManagement\Model\Index indexCreate($body, $hashid)

Creates an index.

Creates a new index for the given search engine.

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
// Configure API key authorization: api_token
$config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');// Configure API key authorization: jwt_token
$config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

$apiInstance = new DoofinderManagement\Api\IndicesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$body = new \DoofinderManagement\Model\Index(); // \DoofinderManagement\Model\Index | 
$hashid = "hashid_example"; // string | Unique id of a search engine.

try {
    $result = $apiInstance->indexCreate($body, $hashid);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling IndicesApi->indexCreate: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **body** | [**\DoofinderManagement\Model\Index**](../Model/Index.md)|  |
 **hashid** | **string**| Unique id of a search engine. |

### Return type

[**\DoofinderManagement\Model\Index**](../Model/Index.md)

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **indexDelete**
> indexDelete($hashid, $name)

Deletes an Index.

Deletes an Index for the given search engine and index name.

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
// Configure API key authorization: api_token
$config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');// Configure API key authorization: jwt_token
$config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

$apiInstance = new DoofinderManagement\Api\IndicesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | Unique id of a search engine.
$name = "name_example"; // string | Name of an index.

try {
    $apiInstance->indexDelete($hashid, $name);
} catch (Exception $e) {
    echo 'Exception when calling IndicesApi->indexDelete: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **hashid** | **string**| Unique id of a search engine. |
 **name** | **string**| Name of an index. |

### Return type

void (empty response body)

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: Not defined

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **indexIndex**
> \DoofinderManagement\Model\Indices indexIndex($hashid)

Lists all indices.

List all indices of the given search engine.

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
// Configure API key authorization: api_token
$config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');// Configure API key authorization: jwt_token
$config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

$apiInstance = new DoofinderManagement\Api\IndicesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | Unique id of a search engine.

try {
    $result = $apiInstance->indexIndex($hashid);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling IndicesApi->indexIndex: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **hashid** | **string**| Unique id of a search engine. |

### Return type

[**\DoofinderManagement\Model\Indices**](../Model/Indices.md)

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **indexShow**
> \DoofinderManagement\Model\Index indexShow($hashid, $name)

Gets an Index.

Gets the index for the given search engine and index name.

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
// Configure API key authorization: api_token
$config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');// Configure API key authorization: jwt_token
$config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

$apiInstance = new DoofinderManagement\Api\IndicesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | Unique id of a search engine.
$name = "name_example"; // string | Name of an index.

try {
    $result = $apiInstance->indexShow($hashid, $name);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling IndicesApi->indexShow: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **hashid** | **string**| Unique id of a search engine. |
 **name** | **string**| Name of an index. |

### Return type

[**\DoofinderManagement\Model\Index**](../Model/Index.md)

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **indexUpdate**
> \DoofinderManagement\Model\Index indexUpdate($body, $hashid, $name)

Updates an index.

Updates an index for the given search engine and index name.

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
// Configure API key authorization: api_token
$config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');// Configure API key authorization: jwt_token
$config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

$apiInstance = new DoofinderManagement\Api\IndicesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$body = new \DoofinderManagement\Model\IndexUpdate(); // \DoofinderManagement\Model\IndexUpdate | 
$hashid = "hashid_example"; // string | Unique id of a search engine.
$name = "name_example"; // string | Name of an index.

try {
    $result = $apiInstance->indexUpdate($body, $hashid, $name);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling IndicesApi->indexUpdate: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **body** | [**\DoofinderManagement\Model\IndexUpdate**](../Model/IndexUpdate.md)|  |
 **hashid** | **string**| Unique id of a search engine. |
 **name** | **string**| Name of an index. |

### Return type

[**\DoofinderManagement\Model\Index**](../Model/Index.md)

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **reindexToTemp**
> object reindexToTemp($hashid, $name)

Reindex the content of the real index into the temporary one.

This executes a reindexing operation between the real index and the temporary one. It reads all items from the real and index them onto the temporary. This will return a 404 (Not found) if there is no temporary index.

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
// Configure API key authorization: api_token
$config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');// Configure API key authorization: jwt_token
$config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

$apiInstance = new DoofinderManagement\Api\IndicesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | Unique id of a search engine.
$name = "name_example"; // string | Name of an index.

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
 **hashid** | **string**| Unique id of a search engine. |
 **name** | **string**| Name of an index. |

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

This request replaces completely the real index with the temporary one. From this moment the contents of the temporary are now the contents of the real index.

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
// Configure API key authorization: api_token
$config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');// Configure API key authorization: jwt_token
$config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

$apiInstance = new DoofinderManagement\Api\IndicesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | Unique id of a search engine.
$name = "name_example"; // string | Name of an index.

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
 **hashid** | **string**| Unique id of a search engine. |
 **name** | **string**| Name of an index. |

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

Creates a temporary index.

Creates a new empty temporary index for the given index. There can not be two temporary indices at the same time, so any request made to this endpoint when there is one created will fail. Creating a temporary index also sets a lock preventing any changes on the search engine until the temporary index is deleted.

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
// Configure API key authorization: api_token
$config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');// Configure API key authorization: jwt_token
$config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

$apiInstance = new DoofinderManagement\Api\IndicesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | Unique id of a search engine.
$name = "name_example"; // string | Name of an index.

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
 **hashid** | **string**| Unique id of a search engine. |
 **name** | **string**| Name of an index. |

### Return type

**object**

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **temporaryIndexDelete**
> temporaryIndexDelete($hashid, $name)

Deletes the temporary index.

Deletes the temporary index. This also removes the lock in the search engine. If there is no temporary index this will return a 404 (Not found).

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
// Configure API key authorization: api_token
$config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');// Configure API key authorization: jwt_token
$config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = DoofinderManagement\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

$apiInstance = new DoofinderManagement\Api\IndicesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | Unique id of a search engine.
$name = "name_example"; // string | Name of an index.

try {
    $apiInstance->temporaryIndexDelete($hashid, $name);
} catch (Exception $e) {
    echo 'Exception when calling IndicesApi->temporaryIndexDelete: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **hashid** | **string**| Unique id of a search engine. |
 **name** | **string**| Name of an index. |

### Return type

void (empty response body)

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: Not defined

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

