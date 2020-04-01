# Swagger\Client\SearchEnginesApi

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
> \Swagger\Client\Model\ProcessingTask process($hashid)

Process all search engine's data sources.

Schedules a task for processing all search engine's data sources.

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

$apiInstance = new Swagger\Client\Api\SearchEnginesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | Hashid of a search engine. This is the search engine unique identifier.

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
 **hashid** | **string**| Hashid of a search engine. This is the search engine unique identifier. |

### Return type

[**\Swagger\Client\Model\ProcessingTask**](../Model/ProcessingTask.md)

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **processStatus**
> \Swagger\Client\Model\ProcessingTask processStatus($hashid)

Gets the status of the process task.

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

$apiInstance = new Swagger\Client\Api\SearchEnginesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | Hashid of a search engine. This is the search engine unique identifier.

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
 **hashid** | **string**| Hashid of a search engine. This is the search engine unique identifier. |

### Return type

[**\Swagger\Client\Model\ProcessingTask**](../Model/ProcessingTask.md)

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **searchEngineCreate**
> \Swagger\Client\Model\SearchEngine searchEngineCreate($body)

Creates a new search engine.

Creates a new search engine with the data provided. It is not possible to execute searches against the new search engine as it does not have any index yet. You need to create an index belonging to the new search engine in order to be able to make searches.

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

$apiInstance = new Swagger\Client\Api\SearchEnginesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$body = new \Swagger\Client\Model\SearchEngine(); // \Swagger\Client\Model\SearchEngine | 

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
 **body** | [**\Swagger\Client\Model\SearchEngine**](../Model/SearchEngine.md)|  |

### Return type

[**\Swagger\Client\Model\SearchEngine**](../Model/SearchEngine.md)

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **searchEngineDelete**
> searchEngineDelete($hashid)

Deletes a search engine.

Deletes a search engine given its hashid.

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

$apiInstance = new Swagger\Client\Api\SearchEnginesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | Hashid of a search engine. This is the search engine unique identifier.

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
 **hashid** | **string**| Hashid of a search engine. This is the search engine unique identifier. |

### Return type

void (empty response body)

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: Not defined

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **searchEngineList**
> \Swagger\Client\Model\SearchEngines searchEngineList()

Lists search engines.

Lists all user's search engines.

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

$apiInstance = new Swagger\Client\Api\SearchEnginesApi(
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

[**\Swagger\Client\Model\SearchEngines**](../Model/SearchEngines.md)

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **searchEngineShow**
> \Swagger\Client\Model\SearchEngine searchEngineShow($hashid)

Gets a search engine.

Returns a search engine details given its hashid.

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

$apiInstance = new Swagger\Client\Api\SearchEnginesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | Hashid of a search engine. This is the search engine unique identifier.

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
 **hashid** | **string**| Hashid of a search engine. This is the search engine unique identifier. |

### Return type

[**\Swagger\Client\Model\SearchEngine**](../Model/SearchEngine.md)

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **searchEngineUpdate**
> \Swagger\Client\Model\SearchEngine searchEngineUpdate($body, $hashid)

Updates a search engine.

Updates a search engine identified by its hashid.

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

$apiInstance = new Swagger\Client\Api\SearchEnginesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$body = new \Swagger\Client\Model\SearchEngine(); // \Swagger\Client\Model\SearchEngine | 
$hashid = "hashid_example"; // string | Hashid of a search engine. This is the search engine unique identifier.

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
 **body** | [**\Swagger\Client\Model\SearchEngine**](../Model/SearchEngine.md)|  |
 **hashid** | **string**| Hashid of a search engine. This is the search engine unique identifier. |

### Return type

[**\Swagger\Client\Model\SearchEngine**](../Model/SearchEngine.md)

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

