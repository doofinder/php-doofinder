# Swagger\Client\ItemsApi

All URIs are relative to *https://{search_zone}-api.doofinder.com*

Method | HTTP request | Description
------------- | ------------- | -------------
[**itemCreate**](ItemsApi.md#itemcreate) | **POST** /api/v2/search_engines/{hashid}/indices/{name}/items/ | Creates an item.
[**itemDelete**](ItemsApi.md#itemdelete) | **DELETE** /api/v2/search_engines/{hashid}/indices/{name}/items/{item_id} | Deletes an item from the index.
[**itemIndex**](ItemsApi.md#itemindex) | **GET** /api/v2/search_engines/{hashid}/indices/{name}/items/ | Scrolls through all index items
[**itemShow**](ItemsApi.md#itemshow) | **GET** /api/v2/search_engines/{hashid}/indices/{name}/items/{item_id} | Gets an item from the index.
[**itemTempCreate**](ItemsApi.md#itemtempcreate) | **POST** /api/v2/search_engines/{hashid}/indices/{name}/temp/items/ | Creates an item in the temporal index.
[**itemTempDelete**](ItemsApi.md#itemtempdelete) | **DELETE** /api/v2/search_engines/{hashid}/indices/{name}/temp/items/{item_id} | Deletes an item in the temporal index.
[**itemTempShow**](ItemsApi.md#itemtempshow) | **GET** /api/v2/search_engines/{hashid}/indices/{name}/temp/items/{item_id} | Gets an item from the temporal index.
[**itemTempUpdate**](ItemsApi.md#itemtempupdate) | **PATCH** /api/v2/search_engines/{hashid}/indices/{name}/temp/items/{item_id} | Partially updates an item in the temporal index.
[**itemUpdate**](ItemsApi.md#itemupdate) | **PATCH** /api/v2/search_engines/{hashid}/indices/{name}/items/{item_id} | Partially updates an item in the index.
[**itemsBulkCreate**](ItemsApi.md#itemsbulkcreate) | **POST** /api/v2/search_engines/{hashid}/indices/{name}/items/_bulk | Creates a bulk of item in the index.
[**itemsBulkDelete**](ItemsApi.md#itemsbulkdelete) | **DELETE** /api/v2/search_engines/{hashid}/indices/{name}/items/_bulk | Deletes a bulk of items from the index.
[**itemsBulkUpdate**](ItemsApi.md#itemsbulkupdate) | **PATCH** /api/v2/search_engines/{hashid}/indices/{name}/items/_bulk | Partial updates a bulk of items in the index.
[**itemsTempBulkCreate**](ItemsApi.md#itemstempbulkcreate) | **POST** /api/v2/search_engines/{hashid}/indices/{name}/temp/items/_bulk | Creates a bulk of items in the temporal index.
[**itemsTempBulkDelete**](ItemsApi.md#itemstempbulkdelete) | **DELETE** /api/v2/search_engines/{hashid}/indices/{name}/temp/items/_bulk | Deletes items in bulk in the temporal index.
[**itemsTempBulkUpdate**](ItemsApi.md#itemstempbulkupdate) | **PATCH** /api/v2/search_engines/{hashid}/indices/{name}/temp/items/_bulk | Partial updates a bulk of items in the temporal index.

# **itemCreate**
> \Swagger\Client\Model\Item itemCreate($body, $hashid, $name)

Creates an item.

Creates an item in the index with the data provided.

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

$apiInstance = new Swagger\Client\Api\ItemsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$body = new \Swagger\Client\Model\map(); // map[string,object] | 
$hashid = "hashid_example"; // string | Unique id of a search engine.
$name = "name_example"; // string | Name of an index.

try {
    $result = $apiInstance->itemCreate($body, $hashid, $name);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ItemsApi->itemCreate: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **body** | [**map[string,object]**](../Model/map.md)|  |
 **hashid** | **string**| Unique id of a search engine. |
 **name** | **string**| Name of an index. |

### Return type

[**\Swagger\Client\Model\Item**](../Model/Item.md)

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **itemDelete**
> itemDelete($hashid, $name, $item_id)

Deletes an item from the index.

Deletes an item from the index given its id.

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

$apiInstance = new Swagger\Client\Api\ItemsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | Unique id of a search engine.
$name = "name_example"; // string | Name of an index.
$item_id = "item_id_example"; // string | Unique identifier of an item inside an index.

try {
    $apiInstance->itemDelete($hashid, $name, $item_id);
} catch (Exception $e) {
    echo 'Exception when calling ItemsApi->itemDelete: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **hashid** | **string**| Unique id of a search engine. |
 **name** | **string**| Name of an index. |
 **item_id** | **string**| Unique identifier of an item inside an index. |

### Return type

void (empty response body)

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: Not defined

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **itemIndex**
> \Swagger\Client\Model\Scroller itemIndex($hashid, $name, $scroll_id, $rpp)

Scrolls through all index items

Scrolls through all index items. The first request starts the scroll and generate a scroll id that can be traversed with each successive requests. There is a limited time period on which the traverse is possible. After 5 minutes the scroll expires and it is no longer accesible, a new request should be made to traverse items again from the beginning.

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

$apiInstance = new Swagger\Client\Api\ItemsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | Unique id of a search engine.
$name = "name_example"; // string | Name of an index.
$scroll_id = "scroll_id_example"; // string | Unique identifier for the scroll. The scroll saves a \"pointer\" to the last fetched page so each successive request to the same scroll_id return a new page.
$rpp = 56; // int | _Results per page_. How many items are fetched per page/request.

try {
    $result = $apiInstance->itemIndex($hashid, $name, $scroll_id, $rpp);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ItemsApi->itemIndex: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **hashid** | **string**| Unique id of a search engine. |
 **name** | **string**| Name of an index. |
 **scroll_id** | **string**| Unique identifier for the scroll. The scroll saves a \&quot;pointer\&quot; to the last fetched page so each successive request to the same scroll_id return a new page. | [optional]
 **rpp** | **int**| _Results per page_. How many items are fetched per page/request. | [optional]

### Return type

[**\Swagger\Client\Model\Scroller**](../Model/Scroller.md)

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **itemShow**
> \Swagger\Client\Model\Item itemShow($hashid, $name, $item_id)

Gets an item from the index.

Gets an item from the index by its id.

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

$apiInstance = new Swagger\Client\Api\ItemsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | Unique id of a search engine.
$name = "name_example"; // string | Name of an index.
$item_id = "item_id_example"; // string | Unique identifier of an item inside an index.

try {
    $result = $apiInstance->itemShow($hashid, $name, $item_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ItemsApi->itemShow: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **hashid** | **string**| Unique id of a search engine. |
 **name** | **string**| Name of an index. |
 **item_id** | **string**| Unique identifier of an item inside an index. |

### Return type

[**\Swagger\Client\Model\Item**](../Model/Item.md)

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **itemTempCreate**
> \Swagger\Client\Model\Item itemTempCreate($body, $hashid, $name)

Creates an item in the temporal index.

Creates an item with the data provided in the temporal index.

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

$apiInstance = new Swagger\Client\Api\ItemsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$body = new \Swagger\Client\Model\map(); // map[string,object] | 
$hashid = "hashid_example"; // string | Unique id of a search engine.
$name = "name_example"; // string | Name of an index.

try {
    $result = $apiInstance->itemTempCreate($body, $hashid, $name);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ItemsApi->itemTempCreate: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **body** | [**map[string,object]**](../Model/map.md)|  |
 **hashid** | **string**| Unique id of a search engine. |
 **name** | **string**| Name of an index. |

### Return type

[**\Swagger\Client\Model\Item**](../Model/Item.md)

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **itemTempDelete**
> itemTempDelete($hashid, $name, $item_id)

Deletes an item in the temporal index.

Deletes an item from the temporal index given its id.

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

$apiInstance = new Swagger\Client\Api\ItemsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | Unique id of a search engine.
$name = "name_example"; // string | Name of an index.
$item_id = "item_id_example"; // string | Unique identifier of an item inside an index.

try {
    $apiInstance->itemTempDelete($hashid, $name, $item_id);
} catch (Exception $e) {
    echo 'Exception when calling ItemsApi->itemTempDelete: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **hashid** | **string**| Unique id of a search engine. |
 **name** | **string**| Name of an index. |
 **item_id** | **string**| Unique identifier of an item inside an index. |

### Return type

void (empty response body)

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: Not defined

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **itemTempShow**
> \Swagger\Client\Model\Item itemTempShow($hashid, $name, $item_id)

Gets an item from the temporal index.

Gets an item from the temporal index by its id.

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

$apiInstance = new Swagger\Client\Api\ItemsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | Unique id of a search engine.
$name = "name_example"; // string | Name of an index.
$item_id = "item_id_example"; // string | Unique identifier of an item inside an index.

try {
    $result = $apiInstance->itemTempShow($hashid, $name, $item_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ItemsApi->itemTempShow: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **hashid** | **string**| Unique id of a search engine. |
 **name** | **string**| Name of an index. |
 **item_id** | **string**| Unique identifier of an item inside an index. |

### Return type

[**\Swagger\Client\Model\Item**](../Model/Item.md)

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **itemTempUpdate**
> \Swagger\Client\Model\Item itemTempUpdate($body, $hashid, $name, $item_id)

Partially updates an item in the temporal index.

Partially updates an item in the temporal index given its id. The operation will return the updated item.

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

$apiInstance = new Swagger\Client\Api\ItemsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$body = new \Swagger\Client\Model\map(); // map[string,object] | 
$hashid = "hashid_example"; // string | Unique id of a search engine.
$name = "name_example"; // string | Name of an index.
$item_id = "item_id_example"; // string | Unique identifier of an item inside an index.

try {
    $result = $apiInstance->itemTempUpdate($body, $hashid, $name, $item_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ItemsApi->itemTempUpdate: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **body** | [**map[string,object]**](../Model/map.md)|  |
 **hashid** | **string**| Unique id of a search engine. |
 **name** | **string**| Name of an index. |
 **item_id** | **string**| Unique identifier of an item inside an index. |

### Return type

[**\Swagger\Client\Model\Item**](../Model/Item.md)

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **itemUpdate**
> \Swagger\Client\Model\Item itemUpdate($body, $hashid, $name, $item_id)

Partially updates an item in the index.

Partially updates an item in the index. The operation returns the updated item.

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

$apiInstance = new Swagger\Client\Api\ItemsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$body = new \Swagger\Client\Model\map(); // map[string,object] | 
$hashid = "hashid_example"; // string | Unique id of a search engine.
$name = "name_example"; // string | Name of an index.
$item_id = "item_id_example"; // string | Unique identifier of an item inside an index.

try {
    $result = $apiInstance->itemUpdate($body, $hashid, $name, $item_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ItemsApi->itemUpdate: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **body** | [**map[string,object]**](../Model/map.md)|  |
 **hashid** | **string**| Unique id of a search engine. |
 **name** | **string**| Name of an index. |
 **item_id** | **string**| Unique identifier of an item inside an index. |

### Return type

[**\Swagger\Client\Model\Item**](../Model/Item.md)

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **itemsBulkCreate**
> \Swagger\Client\Model\BulkResult itemsBulkCreate($body, $hashid, $name)

Creates a bulk of item in the index.

Creates an array of items in the index in a single bulk operation.

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

$apiInstance = new Swagger\Client\Api\ItemsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$body = array(new \Swagger\Client\Model\ItemsIdsInner()); // \Swagger\Client\Model\ItemsIdsInner[] | 
$hashid = "hashid_example"; // string | Unique id of a search engine.
$name = "name_example"; // string | Name of an index.

try {
    $result = $apiInstance->itemsBulkCreate($body, $hashid, $name);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ItemsApi->itemsBulkCreate: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **body** | [**\Swagger\Client\Model\ItemsIdsInner[]**](../Model/ItemsIdsInner.md)|  |
 **hashid** | **string**| Unique id of a search engine. |
 **name** | **string**| Name of an index. |

### Return type

[**\Swagger\Client\Model\BulkResult**](../Model/BulkResult.md)

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **itemsBulkDelete**
> \Swagger\Client\Model\BulkResult itemsBulkDelete($body, $hashid, $name)

Deletes a bulk of items from the index.

Deletes an array of items from the index in a single bulk operation.

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

$apiInstance = new Swagger\Client\Api\ItemsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$body = array(new \Swagger\Client\Model\Item()); // \Swagger\Client\Model\Item[] | 
$hashid = "hashid_example"; // string | Unique id of a search engine.
$name = "name_example"; // string | Name of an index.

try {
    $result = $apiInstance->itemsBulkDelete($body, $hashid, $name);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ItemsApi->itemsBulkDelete: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **body** | [**\Swagger\Client\Model\Item[]**](../Model/Item.md)|  |
 **hashid** | **string**| Unique id of a search engine. |
 **name** | **string**| Name of an index. |

### Return type

[**\Swagger\Client\Model\BulkResult**](../Model/BulkResult.md)

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **itemsBulkUpdate**
> \Swagger\Client\Model\BulkResult itemsBulkUpdate($body, $hashid, $name)

Partial updates a bulk of items in the index.

Updates an array of items in a single bulk operation.

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

$apiInstance = new Swagger\Client\Api\ItemsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$body = array(new \Swagger\Client\Model\Item()); // \Swagger\Client\Model\Item[] | 
$hashid = "hashid_example"; // string | Unique id of a search engine.
$name = "name_example"; // string | Name of an index.

try {
    $result = $apiInstance->itemsBulkUpdate($body, $hashid, $name);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ItemsApi->itemsBulkUpdate: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **body** | [**\Swagger\Client\Model\Item[]**](../Model/Item.md)|  |
 **hashid** | **string**| Unique id of a search engine. |
 **name** | **string**| Name of an index. |

### Return type

[**\Swagger\Client\Model\BulkResult**](../Model/BulkResult.md)

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **itemsTempBulkCreate**
> \Swagger\Client\Model\BulkResult itemsTempBulkCreate($body, $hashid, $name)

Creates a bulk of items in the temporal index.

Creates an array of items in the temporal index in a single bulk operation.

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

$apiInstance = new Swagger\Client\Api\ItemsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$body = array(new \Swagger\Client\Model\Item()); // \Swagger\Client\Model\Item[] | 
$hashid = "hashid_example"; // string | Unique id of a search engine.
$name = "name_example"; // string | Name of an index.

try {
    $result = $apiInstance->itemsTempBulkCreate($body, $hashid, $name);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ItemsApi->itemsTempBulkCreate: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **body** | [**\Swagger\Client\Model\Item[]**](../Model/Item.md)|  |
 **hashid** | **string**| Unique id of a search engine. |
 **name** | **string**| Name of an index. |

### Return type

[**\Swagger\Client\Model\BulkResult**](../Model/BulkResult.md)

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **itemsTempBulkDelete**
> \Swagger\Client\Model\BulkResult itemsTempBulkDelete($body, $hashid, $name)

Deletes items in bulk in the temporal index.

Deletes an array of items in a single bulk operation in the temporal index.

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

$apiInstance = new Swagger\Client\Api\ItemsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$body = array(new \Swagger\Client\Model\ItemsIdsInner()); // \Swagger\Client\Model\ItemsIdsInner[] | 
$hashid = "hashid_example"; // string | Unique id of a search engine.
$name = "name_example"; // string | Name of an index.

try {
    $result = $apiInstance->itemsTempBulkDelete($body, $hashid, $name);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ItemsApi->itemsTempBulkDelete: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **body** | [**\Swagger\Client\Model\ItemsIdsInner[]**](../Model/ItemsIdsInner.md)|  |
 **hashid** | **string**| Unique id of a search engine. |
 **name** | **string**| Name of an index. |

### Return type

[**\Swagger\Client\Model\BulkResult**](../Model/BulkResult.md)

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **itemsTempBulkUpdate**
> \Swagger\Client\Model\BulkResult itemsTempBulkUpdate($body, $hashid, $name)

Partial updates a bulk of items in the temporal index.

Updates an array of items in a single bulk operation in the temporal index.

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

$apiInstance = new Swagger\Client\Api\ItemsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$body = array(new \Swagger\Client\Model\Item()); // \Swagger\Client\Model\Item[] | 
$hashid = "hashid_example"; // string | Unique id of a search engine.
$name = "name_example"; // string | Name of an index.

try {
    $result = $apiInstance->itemsTempBulkUpdate($body, $hashid, $name);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ItemsApi->itemsTempBulkUpdate: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **body** | [**\Swagger\Client\Model\Item[]**](../Model/Item.md)|  |
 **hashid** | **string**| Unique id of a search engine. |
 **name** | **string**| Name of an index. |

### Return type

[**\Swagger\Client\Model\BulkResult**](../Model/BulkResult.md)

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

