# DoofinderManagement\SearchEnginesApi

All URIs are relative to *https://{search_zone}-api.doofinder.com*

Method | HTTP request | Description
------------- | ------------- | -------------
[**process**](SearchEnginesApi.md#process) | **POST** /api/v2/search_engines/{hashid}/_process | Process all search engine&#x27;s data sources.
[**processStatus**](SearchEnginesApi.md#processstatus) | **GET** /api/v2/search_engines/{hashid}/_process | Gets the status of the process task.
[**searchEngineCreate**](SearchEnginesApi.md#searchenginecreate) | **POST** /api/v2/search_engines | Creates a new search engine.
[**searchEngineDelete**](SearchEnginesApi.md#searchenginedelete) | **DELETE** /api/v2/search_engines/{hashid} | Deletes a search engine.
[**searchEngineList**](SearchEnginesApi.md#searchenginelist) | **GET** /api/v2/search_engines | Lists search engines.
[**searchEngineShow**](SearchEnginesApi.md#searchengineshow) | **GET** /api/v2/search_engines/{hashid} | Gets a search engine.
[**searchEngineUpdate**](SearchEnginesApi.md#searchengineupdate) | **PATCH** /api/v2/search_engines/{hashid} | Updates a search engine.

# **process**
> \DoofinderManagement\Model\ProcessingTask process($hashid)

Process all search engine's data sources.

Schedules a task for processing all search engine's data sources.

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

$apiInstance = new DoofinderManagement\Api\SearchEnginesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | Unique id of a search engine.

try {
    $result = $apiInstance->process($hashid);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling SearchEnginesApi->process: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **hashid** | **string**| Unique id of a search engine. |

### Return type

[**\DoofinderManagement\Model\ProcessingTask**](../Model/ProcessingTask.md)

### Authorization

[api_token](../../../README_MANAGEMENT.md#api_token), [jwt_token](../../../README_MANAGEMENT.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../../README_MANAGEMENT.md#documentation-for-api-endpoints) [[Back to Model list]](../../../README_MANAGEMENT.md#documentation-for-models) [[Back to README]](../../../README_MANAGEMENT.md)

# **processStatus**
> \DoofinderManagement\Model\ProcessingTask processStatus($hashid)

Gets the status of the process task.

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

$apiInstance = new DoofinderManagement\Api\SearchEnginesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | Unique id of a search engine.

try {
    $result = $apiInstance->processStatus($hashid);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling SearchEnginesApi->processStatus: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **hashid** | **string**| Unique id of a search engine. |

### Return type

[**\DoofinderManagement\Model\ProcessingTask**](../Model/ProcessingTask.md)

### Authorization

[api_token](../../../README_MANAGEMENT.md#api_token), [jwt_token](../../../README_MANAGEMENT.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../../README_MANAGEMENT.md#documentation-for-api-endpoints) [[Back to Model list]](../../../README_MANAGEMENT.md#documentation-for-models) [[Back to README]](../../../README_MANAGEMENT.md)

# **searchEngineCreate**
> \DoofinderManagement\Model\SearchEngine searchEngineCreate($body)

Creates a new search engine.

Creates a new search engine with the provided data. It is not possible to run searches against the new search engine as it does not have any index yet. You must create an index belonging to the new search engine in order to be able to make searches.

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

$apiInstance = new DoofinderManagement\Api\SearchEnginesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$body = new \DoofinderManagement\Model\SearchEngine(); // \DoofinderManagement\Model\SearchEngine | 

try {
    $result = $apiInstance->searchEngineCreate($body);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling SearchEnginesApi->searchEngineCreate: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **body** | [**\DoofinderManagement\Model\SearchEngine**](../Model/SearchEngine.md)|  |

### Return type

[**\DoofinderManagement\Model\SearchEngine**](../Model/SearchEngine.md)

### Authorization

[api_token](../../../README_MANAGEMENT.md#api_token), [jwt_token](../../../README_MANAGEMENT.md#jwt_token)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../../README_MANAGEMENT.md#documentation-for-api-endpoints) [[Back to Model list]](../../../README_MANAGEMENT.md#documentation-for-models) [[Back to README]](../../../README_MANAGEMENT.md)

# **searchEngineDelete**
> searchEngineDelete($hashid)

Deletes a search engine.

Deletes a search engine given its hashid.

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

$apiInstance = new DoofinderManagement\Api\SearchEnginesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | Unique id of a search engine.

try {
    $apiInstance->searchEngineDelete($hashid);
} catch (Exception $e) {
    echo 'Exception when calling SearchEnginesApi->searchEngineDelete: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **hashid** | **string**| Unique id of a search engine. |

### Return type

void (empty response body)

### Authorization

[api_token](../../../README_MANAGEMENT.md#api_token), [jwt_token](../../../README_MANAGEMENT.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: Not defined

[[Back to top]](#) [[Back to API list]](../../../README_MANAGEMENT.md#documentation-for-api-endpoints) [[Back to Model list]](../../../README_MANAGEMENT.md#documentation-for-models) [[Back to README]](../../../README_MANAGEMENT.md)

# **searchEngineList**
> \DoofinderManagement\Model\SearchEngines searchEngineList()

Lists search engines.

Lists all user's search engines.

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

$apiInstance = new DoofinderManagement\Api\SearchEnginesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);

try {
    $result = $apiInstance->searchEngineList();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling SearchEnginesApi->searchEngineList: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters
This endpoint does not need any parameter.

### Return type

[**\DoofinderManagement\Model\SearchEngines**](../Model/SearchEngines.md)

### Authorization

[api_token](../../../README_MANAGEMENT.md#api_token), [jwt_token](../../../README_MANAGEMENT.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../../README_MANAGEMENT.md#documentation-for-api-endpoints) [[Back to Model list]](../../../README_MANAGEMENT.md#documentation-for-models) [[Back to README]](../../../README_MANAGEMENT.md)

# **searchEngineShow**
> \DoofinderManagement\Model\SearchEngine searchEngineShow($hashid)

Gets a search engine.

Returns a search engine details given its hashid.

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

$apiInstance = new DoofinderManagement\Api\SearchEnginesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | Unique id of a search engine.

try {
    $result = $apiInstance->searchEngineShow($hashid);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling SearchEnginesApi->searchEngineShow: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **hashid** | **string**| Unique id of a search engine. |

### Return type

[**\DoofinderManagement\Model\SearchEngine**](../Model/SearchEngine.md)

### Authorization

[api_token](../../../README_MANAGEMENT.md#api_token), [jwt_token](../../../README_MANAGEMENT.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../../README_MANAGEMENT.md#documentation-for-api-endpoints) [[Back to Model list]](../../../README_MANAGEMENT.md#documentation-for-models) [[Back to README]](../../../README_MANAGEMENT.md)

# **searchEngineUpdate**
> \DoofinderManagement\Model\SearchEngine searchEngineUpdate($body, $hashid)

Updates a search engine.

Updates a search engine identified by its hashid.

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

$apiInstance = new DoofinderManagement\Api\SearchEnginesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$body = new \DoofinderManagement\Model\SearchEngine(); // \DoofinderManagement\Model\SearchEngine | 
$hashid = "hashid_example"; // string | Unique id of a search engine.

try {
    $result = $apiInstance->searchEngineUpdate($body, $hashid);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling SearchEnginesApi->searchEngineUpdate: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **body** | [**\DoofinderManagement\Model\SearchEngine**](../Model/SearchEngine.md)|  |
 **hashid** | **string**| Unique id of a search engine. |

### Return type

[**\DoofinderManagement\Model\SearchEngine**](../Model/SearchEngine.md)

### Authorization

[api_token](../../../README_MANAGEMENT.md#api_token), [jwt_token](../../../README_MANAGEMENT.md#jwt_token)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../../README_MANAGEMENT.md#documentation-for-api-endpoints) [[Back to Model list]](../../../README_MANAGEMENT.md#documentation-for-models) [[Back to README]](../../../README_MANAGEMENT.md)

