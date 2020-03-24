# Swagger\Client\StatsApi

All URIs are relative to *https://{search_zone}-api.doofinder.com*

Method | HTTP request | Description
------------- | ------------- | -------------
[**bannersClicks**](StatsApi.md#bannersclicks) | **GET** /api/v2/stats/banners/clicks | Get the total amount of clicks banners have got
[**bannersDisplay**](StatsApi.md#bannersdisplay) | **GET** /api/v2/stats/banners/displays | Get the total amount of displays banners have got
[**checkoutsByDate**](StatsApi.md#checkoutsbydate) | **GET** /api/v2/stats/checkouts | Get the checkouts by dates
[**clicksByDate**](StatsApi.md#clicksbydate) | **GET** /api/v2/stats/clicks | Get the clicks by dates
[**clicksByQuery**](StatsApi.md#clicksbyquery) | **GET** /api/v2/stats/clicks/by-query/{query} | Get the products clicked given a certain query
[**clicksTop**](StatsApi.md#clickstop) | **GET** /api/v2/stats/clicks/top | Get the most common clicks
[**initsByDate**](StatsApi.md#initsbydate) | **GET** /api/v2/stats/inits | Get the sessions started by dates
[**metrics**](StatsApi.md#metrics) | **GET** /api/v2/stats/metrics | Get the search engines usage.
[**redirects**](StatsApi.md#redirects) | **GET** /api/v2/stats/redirects | Get the total amount of redirects done
[**searchesByClick**](StatsApi.md#searchesbyclick) | **GET** /api/v2/stats/clicks/{dfid}/searches/top | Get the top searches that got a product clicked
[**searchesByDate**](StatsApi.md#searchesbydate) | **GET** /api/v2/stats/searches | Get the searches by dates
[**searchesTop**](StatsApi.md#searchestop) | **GET** /api/v2/stats/searches/top | Get the most common searches
[**usage**](StatsApi.md#usage) | **GET** /api/v2/stats/usage | Get the search engines usage.

# **bannersClicks**
> object bannersClicks($hashid, $to, $from, $tz, $id, $device, $interval, $format)

Get the total amount of clicks banners have got

Gets how many times a banner has been clicked

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

$apiInstance = new Swagger\Client\Api\StatsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | HashID of the search engine to query or a list in the format [hashid1,hashid2,...]
$to = "to_example"; // string | Date end of the interval in the format of UNIX timestamp or YYYYMMDD. Today, by default.
$from = "from_example"; // string | Date start of the interval in the format of UNIX timestamp or YYYYMMDD. By default, 10 days from current date.
$tz = "tz_example"; // string | Timezone for the given dates, by default assumes UTC.
$id = 56; // int | ID of the banner.
$device = "device_example"; // string | Device filter, by default is all
$interval = "1d"; // string | Time interval for aggregations
$format = "json"; // string | Indicates which response format should be used

try {
    $result = $apiInstance->bannersClicks($hashid, $to, $from, $tz, $id, $device, $interval, $format);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling StatsApi->bannersClicks: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **hashid** | **string**| HashID of the search engine to query or a list in the format [hashid1,hashid2,...] |
 **to** | **string**| Date end of the interval in the format of UNIX timestamp or YYYYMMDD. Today, by default. | [optional]
 **from** | **string**| Date start of the interval in the format of UNIX timestamp or YYYYMMDD. By default, 10 days from current date. | [optional]
 **tz** | **string**| Timezone for the given dates, by default assumes UTC. | [optional]
 **id** | **int**| ID of the banner. | [optional]
 **device** | **string**| Device filter, by default is all | [optional]
 **interval** | **string**| Time interval for aggregations | [optional] [default to 1d]
 **format** | **string**| Indicates which response format should be used | [optional] [default to json]

### Return type

**object**

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **bannersDisplay**
> object bannersDisplay($hashid, $to, $from, $tz, $id, $device, $interval, $format)

Get the total amount of displays banners have got

Gets how many times a banner has been shown

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

$apiInstance = new Swagger\Client\Api\StatsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | HashID of the search engine to query or a list in the format [hashid1,hashid2,...]
$to = "to_example"; // string | Date end of the interval in the format of UNIX timestamp or YYYYMMDD. Today, by default.
$from = "from_example"; // string | Date start of the interval in the format of UNIX timestamp or YYYYMMDD. By default, 10 days from current date.
$tz = "tz_example"; // string | Timezone for the given dates, by default assumes UTC.
$id = 56; // int | ID of the banner.
$device = "device_example"; // string | Device filter, by default is all
$interval = "1d"; // string | Time interval for aggregations
$format = "json"; // string | Indicates which response format should be used

try {
    $result = $apiInstance->bannersDisplay($hashid, $to, $from, $tz, $id, $device, $interval, $format);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling StatsApi->bannersDisplay: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **hashid** | **string**| HashID of the search engine to query or a list in the format [hashid1,hashid2,...] |
 **to** | **string**| Date end of the interval in the format of UNIX timestamp or YYYYMMDD. Today, by default. | [optional]
 **from** | **string**| Date start of the interval in the format of UNIX timestamp or YYYYMMDD. By default, 10 days from current date. | [optional]
 **tz** | **string**| Timezone for the given dates, by default assumes UTC. | [optional]
 **id** | **int**| ID of the banner. | [optional]
 **device** | **string**| Device filter, by default is all | [optional]
 **interval** | **string**| Time interval for aggregations | [optional] [default to 1d]
 **format** | **string**| Indicates which response format should be used | [optional] [default to json]

### Return type

**object**

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **checkoutsByDate**
> object checkoutsByDate($hashid, $to, $from, $tz, $device, $custom_results_id, $query_name, $total_hits, $interval, $format)

Get the checkouts by dates

Gets a total of the checkouts aggregated in a time period, separated by dates

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

$apiInstance = new Swagger\Client\Api\StatsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | HashID of the search engine to query or a list in the format [hashid1,hashid2,...]
$to = "to_example"; // string | Date end of the interval in the format of UNIX timestamp or YYYYMMDD. Today, by default.
$from = "from_example"; // string | Date start of the interval in the format of UNIX timestamp or YYYYMMDD. By default, 10 days from current date.
$tz = "tz_example"; // string | Timezone for the given dates, by default assumes UTC.
$device = "device_example"; // string | Device filter, by default is all
$custom_results_id = "custom_results_id_example"; // string | Filter by custom results
$query_name = "query_name_example"; // string | Type of query to filter by
$total_hits = 56; // int | Filter by total hits
$interval = "1d"; // string | Time interval for aggregations
$format = "json"; // string | Indicates which response format should be used

try {
    $result = $apiInstance->checkoutsByDate($hashid, $to, $from, $tz, $device, $custom_results_id, $query_name, $total_hits, $interval, $format);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling StatsApi->checkoutsByDate: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **hashid** | **string**| HashID of the search engine to query or a list in the format [hashid1,hashid2,...] |
 **to** | **string**| Date end of the interval in the format of UNIX timestamp or YYYYMMDD. Today, by default. | [optional]
 **from** | **string**| Date start of the interval in the format of UNIX timestamp or YYYYMMDD. By default, 10 days from current date. | [optional]
 **tz** | **string**| Timezone for the given dates, by default assumes UTC. | [optional]
 **device** | **string**| Device filter, by default is all | [optional]
 **custom_results_id** | **string**| Filter by custom results | [optional]
 **query_name** | **string**| Type of query to filter by | [optional]
 **total_hits** | **int**| Filter by total hits | [optional]
 **interval** | **string**| Time interval for aggregations | [optional] [default to 1d]
 **format** | **string**| Indicates which response format should be used | [optional] [default to json]

### Return type

**object**

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **clicksByDate**
> object clicksByDate($hashid, $to, $from, $tz, $device, $custom_results_id, $query_name, $total_hits, $interval, $format)

Get the clicks by dates

Gets a total of the clicks aggregated in a time period, separated by dates

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

$apiInstance = new Swagger\Client\Api\StatsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | HashID of the search engine to query or a list in the format [hashid1,hashid2,...]
$to = "to_example"; // string | Date end of the interval in the format of UNIX timestamp or YYYYMMDD. Today, by default.
$from = "from_example"; // string | Date start of the interval in the format of UNIX timestamp or YYYYMMDD. By default, 10 days from current date.
$tz = "tz_example"; // string | Timezone for the given dates, by default assumes UTC.
$device = "device_example"; // string | Device filter, by default is all
$custom_results_id = "custom_results_id_example"; // string | Filter by custom results
$query_name = "query_name_example"; // string | Type of query to filter by
$total_hits = 56; // int | Filter by total hits
$interval = "1d"; // string | Time interval for aggregations
$format = "json"; // string | Indicates which response format should be used

try {
    $result = $apiInstance->clicksByDate($hashid, $to, $from, $tz, $device, $custom_results_id, $query_name, $total_hits, $interval, $format);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling StatsApi->clicksByDate: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **hashid** | **string**| HashID of the search engine to query or a list in the format [hashid1,hashid2,...] |
 **to** | **string**| Date end of the interval in the format of UNIX timestamp or YYYYMMDD. Today, by default. | [optional]
 **from** | **string**| Date start of the interval in the format of UNIX timestamp or YYYYMMDD. By default, 10 days from current date. | [optional]
 **tz** | **string**| Timezone for the given dates, by default assumes UTC. | [optional]
 **device** | **string**| Device filter, by default is all | [optional]
 **custom_results_id** | **string**| Filter by custom results | [optional]
 **query_name** | **string**| Type of query to filter by | [optional]
 **total_hits** | **int**| Filter by total hits | [optional]
 **interval** | **string**| Time interval for aggregations | [optional] [default to 1d]
 **format** | **string**| Indicates which response format should be used | [optional] [default to json]

### Return type

**object**

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **clicksByQuery**
> object clicksByQuery($hashid, $query, $to, $from, $tz, $device, $interval, $format)

Get the products clicked given a certain query

Get the products clicked given a certain query

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

$apiInstance = new Swagger\Client\Api\StatsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | HashID of the search engine to query or a list in the format [hashid1,hashid2,...]
$query = "query_example"; // string | Search query term
$to = "to_example"; // string | Date end of the interval in the format of UNIX timestamp or YYYYMMDD. Today, by default.
$from = "from_example"; // string | Date start of the interval in the format of UNIX timestamp or YYYYMMDD. By default, 10 days from current date.
$tz = "tz_example"; // string | Timezone for the given dates, by default assumes UTC.
$device = "device_example"; // string | Device filter, by default is all
$interval = "1d"; // string | Time interval for aggregations
$format = "json"; // string | Indicates which response format should be used

try {
    $result = $apiInstance->clicksByQuery($hashid, $query, $to, $from, $tz, $device, $interval, $format);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling StatsApi->clicksByQuery: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **hashid** | **string**| HashID of the search engine to query or a list in the format [hashid1,hashid2,...] |
 **query** | **string**| Search query term |
 **to** | **string**| Date end of the interval in the format of UNIX timestamp or YYYYMMDD. Today, by default. | [optional]
 **from** | **string**| Date start of the interval in the format of UNIX timestamp or YYYYMMDD. By default, 10 days from current date. | [optional]
 **tz** | **string**| Timezone for the given dates, by default assumes UTC. | [optional]
 **device** | **string**| Device filter, by default is all | [optional]
 **interval** | **string**| Time interval for aggregations | [optional] [default to 1d]
 **format** | **string**| Indicates which response format should be used | [optional] [default to json]

### Return type

**object**

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **clicksTop**
> object clicksTop($hashid, $to, $from, $tz, $device, $interval, $query, $format)

Get the most common clicks

Gets a top of the clicks in a time period

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

$apiInstance = new Swagger\Client\Api\StatsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | HashID of the search engine to query or a list in the format [hashid1,hashid2,...]
$to = "to_example"; // string | Date end of the interval in the format of UNIX timestamp or YYYYMMDD. Today, by default.
$from = "from_example"; // string | Date start of the interval in the format of UNIX timestamp or YYYYMMDD. By default, 10 days from current date.
$tz = "tz_example"; // string | Timezone for the given dates, by default assumes UTC.
$device = "device_example"; // string | Device filter, by default is all
$interval = "1d"; // string | Time interval for aggregations
$query = "query_example"; // string | Filter by query done.
$format = "json"; // string | Indicates which response format should be used

try {
    $result = $apiInstance->clicksTop($hashid, $to, $from, $tz, $device, $interval, $query, $format);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling StatsApi->clicksTop: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **hashid** | **string**| HashID of the search engine to query or a list in the format [hashid1,hashid2,...] |
 **to** | **string**| Date end of the interval in the format of UNIX timestamp or YYYYMMDD. Today, by default. | [optional]
 **from** | **string**| Date start of the interval in the format of UNIX timestamp or YYYYMMDD. By default, 10 days from current date. | [optional]
 **tz** | **string**| Timezone for the given dates, by default assumes UTC. | [optional]
 **device** | **string**| Device filter, by default is all | [optional]
 **interval** | **string**| Time interval for aggregations | [optional] [default to 1d]
 **query** | **string**| Filter by query done. | [optional]
 **format** | **string**| Indicates which response format should be used | [optional] [default to json]

### Return type

**object**

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **initsByDate**
> object initsByDate($hashid, $to, $from, $tz, $device, $custom_results_id, $query_name, $total_hits, $interval, $format)

Get the sessions started by dates

Gets a total of the sessions started aggregated in a time period, separated by dates

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

$apiInstance = new Swagger\Client\Api\StatsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | HashID of the search engine to query or a list in the format [hashid1,hashid2,...]
$to = "to_example"; // string | Date end of the interval in the format of UNIX timestamp or YYYYMMDD. Today, by default.
$from = "from_example"; // string | Date start of the interval in the format of UNIX timestamp or YYYYMMDD. By default, 10 days from current date.
$tz = "tz_example"; // string | Timezone for the given dates, by default assumes UTC.
$device = "device_example"; // string | Device filter, by default is all
$custom_results_id = "custom_results_id_example"; // string | Filter by custom results
$query_name = "query_name_example"; // string | Type of query to filter by
$total_hits = 56; // int | Filter by total hits
$interval = "1d"; // string | Time interval for aggregations
$format = "json"; // string | Indicates which response format should be used

try {
    $result = $apiInstance->initsByDate($hashid, $to, $from, $tz, $device, $custom_results_id, $query_name, $total_hits, $interval, $format);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling StatsApi->initsByDate: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **hashid** | **string**| HashID of the search engine to query or a list in the format [hashid1,hashid2,...] |
 **to** | **string**| Date end of the interval in the format of UNIX timestamp or YYYYMMDD. Today, by default. | [optional]
 **from** | **string**| Date start of the interval in the format of UNIX timestamp or YYYYMMDD. By default, 10 days from current date. | [optional]
 **tz** | **string**| Timezone for the given dates, by default assumes UTC. | [optional]
 **device** | **string**| Device filter, by default is all | [optional]
 **custom_results_id** | **string**| Filter by custom results | [optional]
 **query_name** | **string**| Type of query to filter by | [optional]
 **total_hits** | **int**| Filter by total hits | [optional]
 **interval** | **string**| Time interval for aggregations | [optional] [default to 1d]
 **format** | **string**| Indicates which response format should be used | [optional] [default to json]

### Return type

**object**

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **metrics**
> object metrics($hashid, $to, $from, $tz, $device, $interval, $format)

Get the search engines usage.

Gets the search engines usage, close to the current minute, but slow.

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

$apiInstance = new Swagger\Client\Api\StatsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | HashID of the search engine to query or a list in the format [hashid1,hashid2,...]
$to = "to_example"; // string | Date end of the interval in the format of UNIX timestamp or YYYYMMDD. Today, by default.
$from = "from_example"; // string | Date start of the interval in the format of UNIX timestamp or YYYYMMDD. By default, 10 days from current date.
$tz = "tz_example"; // string | Timezone for the given dates, by default assumes UTC.
$device = "device_example"; // string | Device filter, by default is all
$interval = "1d"; // string | Time interval for aggregations
$format = "json"; // string | Indicates which response format should be used

try {
    $result = $apiInstance->metrics($hashid, $to, $from, $tz, $device, $interval, $format);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling StatsApi->metrics: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **hashid** | **string**| HashID of the search engine to query or a list in the format [hashid1,hashid2,...] |
 **to** | **string**| Date end of the interval in the format of UNIX timestamp or YYYYMMDD. Today, by default. | [optional]
 **from** | **string**| Date start of the interval in the format of UNIX timestamp or YYYYMMDD. By default, 10 days from current date. | [optional]
 **tz** | **string**| Timezone for the given dates, by default assumes UTC. | [optional]
 **device** | **string**| Device filter, by default is all | [optional]
 **interval** | **string**| Time interval for aggregations | [optional] [default to 1d]
 **format** | **string**| Indicates which response format should be used | [optional] [default to json]

### Return type

**object**

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **redirects**
> object redirects($hashid, $to, $from, $tz, $id, $device, $interval, $format)

Get the total amount of redirects done

Gets how many times there's been a redirect

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

$apiInstance = new Swagger\Client\Api\StatsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | HashID of the search engine to query or a list in the format [hashid1,hashid2,...]
$to = "to_example"; // string | Date end of the interval in the format of UNIX timestamp or YYYYMMDD. Today, by default.
$from = "from_example"; // string | Date start of the interval in the format of UNIX timestamp or YYYYMMDD. By default, 10 days from current date.
$tz = "tz_example"; // string | Timezone for the given dates, by default assumes UTC.
$id = 56; // int | ID of the redirection.
$device = "device_example"; // string | Device filter, by default is all
$interval = "1d"; // string | Time interval for aggregations
$format = "json"; // string | Indicates which response format should be used

try {
    $result = $apiInstance->redirects($hashid, $to, $from, $tz, $id, $device, $interval, $format);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling StatsApi->redirects: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **hashid** | **string**| HashID of the search engine to query or a list in the format [hashid1,hashid2,...] |
 **to** | **string**| Date end of the interval in the format of UNIX timestamp or YYYYMMDD. Today, by default. | [optional]
 **from** | **string**| Date start of the interval in the format of UNIX timestamp or YYYYMMDD. By default, 10 days from current date. | [optional]
 **tz** | **string**| Timezone for the given dates, by default assumes UTC. | [optional]
 **id** | **int**| ID of the redirection. | [optional]
 **device** | **string**| Device filter, by default is all | [optional]
 **interval** | **string**| Time interval for aggregations | [optional] [default to 1d]
 **format** | **string**| Indicates which response format should be used | [optional] [default to json]

### Return type

**object**

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **searchesByClick**
> object searchesByClick($hashid, $dfid, $to, $from, $tz, $device, $interval, $total_hits, $format)

Get the top searches that got a product clicked

Gets the top searches that got a click in a product, and how many times.

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

$apiInstance = new Swagger\Client\Api\StatsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | HashID of the search engine to query or a list in the format [hashid1,hashid2,...]
$dfid = "dfid_example"; // string | Doofinder ID to filter by
$to = "to_example"; // string | Date end of the interval in the format of UNIX timestamp or YYYYMMDD. Today, by default.
$from = "from_example"; // string | Date start of the interval in the format of UNIX timestamp or YYYYMMDD. By default, 10 days from current date.
$tz = "tz_example"; // string | Timezone for the given dates, by default assumes UTC.
$device = "device_example"; // string | Device filter, by default is all
$interval = "1d"; // string | Time interval for aggregations
$total_hits = 56; // int | Filter by total hits
$format = "json"; // string | Indicates which response format should be used

try {
    $result = $apiInstance->searchesByClick($hashid, $dfid, $to, $from, $tz, $device, $interval, $total_hits, $format);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling StatsApi->searchesByClick: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **hashid** | **string**| HashID of the search engine to query or a list in the format [hashid1,hashid2,...] |
 **dfid** | **string**| Doofinder ID to filter by |
 **to** | **string**| Date end of the interval in the format of UNIX timestamp or YYYYMMDD. Today, by default. | [optional]
 **from** | **string**| Date start of the interval in the format of UNIX timestamp or YYYYMMDD. By default, 10 days from current date. | [optional]
 **tz** | **string**| Timezone for the given dates, by default assumes UTC. | [optional]
 **device** | **string**| Device filter, by default is all | [optional]
 **interval** | **string**| Time interval for aggregations | [optional] [default to 1d]
 **total_hits** | **int**| Filter by total hits | [optional]
 **format** | **string**| Indicates which response format should be used | [optional] [default to json]

### Return type

**object**

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **searchesByDate**
> object searchesByDate($hashid, $to, $from, $tz, $device, $custom_results_id, $query_name, $source, $total_hits, $interval, $format)

Get the searches by dates

Gets a total of the searches in a time period, separated by dates

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

$apiInstance = new Swagger\Client\Api\StatsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | HashID of the search engine to query or a list in the format [hashid1,hashid2,...]
$to = "to_example"; // string | Date end of the interval in the format of UNIX timestamp or YYYYMMDD. Today, by default.
$from = "from_example"; // string | Date start of the interval in the format of UNIX timestamp or YYYYMMDD. By default, 10 days from current date.
$tz = "tz_example"; // string | Timezone for the given dates, by default assumes UTC.
$device = "device_example"; // string | Device filter, by default is all
$custom_results_id = "custom_results_id_example"; // string | Filter by custom results
$query_name = "query_name_example"; // string | Type of query to filter by
$source = "source_example"; // string | Filter by search source.
$total_hits = 56; // int | Filter by total hits
$interval = "1d"; // string | Time interval for aggregations
$format = "json"; // string | Indicates which response format should be used

try {
    $result = $apiInstance->searchesByDate($hashid, $to, $from, $tz, $device, $custom_results_id, $query_name, $source, $total_hits, $interval, $format);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling StatsApi->searchesByDate: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **hashid** | **string**| HashID of the search engine to query or a list in the format [hashid1,hashid2,...] |
 **to** | **string**| Date end of the interval in the format of UNIX timestamp or YYYYMMDD. Today, by default. | [optional]
 **from** | **string**| Date start of the interval in the format of UNIX timestamp or YYYYMMDD. By default, 10 days from current date. | [optional]
 **tz** | **string**| Timezone for the given dates, by default assumes UTC. | [optional]
 **device** | **string**| Device filter, by default is all | [optional]
 **custom_results_id** | **string**| Filter by custom results | [optional]
 **query_name** | **string**| Type of query to filter by | [optional]
 **source** | **string**| Filter by search source. | [optional]
 **total_hits** | **int**| Filter by total hits | [optional]
 **interval** | **string**| Time interval for aggregations | [optional] [default to 1d]
 **format** | **string**| Indicates which response format should be used | [optional] [default to json]

### Return type

**object**

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **searchesTop**
> object searchesTop($hashid, $to, $from, $tz, $device, $interval, $total_hits, $query_name, $exclude, $format)

Get the most common searches

Gets a top of the searches in a time period

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

$apiInstance = new Swagger\Client\Api\StatsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | HashID of the search engine to query or a list in the format [hashid1,hashid2,...]
$to = "to_example"; // string | Date end of the interval in the format of UNIX timestamp or YYYYMMDD. Today, by default.
$from = "from_example"; // string | Date start of the interval in the format of UNIX timestamp or YYYYMMDD. By default, 10 days from current date.
$tz = "tz_example"; // string | Timezone for the given dates, by default assumes UTC.
$device = "device_example"; // string | Device filter, by default is all
$interval = "1d"; // string | Time interval for aggregations
$total_hits = 56; // int | Filter by total hits
$query_name = "query_name_example"; // string | Type of query to filter by
$exclude = new \stdClass; // object | Exclude filters
$format = "json"; // string | Indicates which response format should be used

try {
    $result = $apiInstance->searchesTop($hashid, $to, $from, $tz, $device, $interval, $total_hits, $query_name, $exclude, $format);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling StatsApi->searchesTop: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **hashid** | **string**| HashID of the search engine to query or a list in the format [hashid1,hashid2,...] |
 **to** | **string**| Date end of the interval in the format of UNIX timestamp or YYYYMMDD. Today, by default. | [optional]
 **from** | **string**| Date start of the interval in the format of UNIX timestamp or YYYYMMDD. By default, 10 days from current date. | [optional]
 **tz** | **string**| Timezone for the given dates, by default assumes UTC. | [optional]
 **device** | **string**| Device filter, by default is all | [optional]
 **interval** | **string**| Time interval for aggregations | [optional] [default to 1d]
 **total_hits** | **int**| Filter by total hits | [optional]
 **query_name** | **string**| Type of query to filter by | [optional]
 **exclude** | [**object**](../Model/.md)| Exclude filters | [optional]
 **format** | **string**| Indicates which response format should be used | [optional] [default to json]

### Return type

**object**

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **usage**
> object usage($hashid, $to, $from, $tz, $device, $interval, $type, $format)

Get the search engines usage.

Gets the search engines usage, up until previous day, fast call.

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

$apiInstance = new Swagger\Client\Api\StatsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$hashid = "hashid_example"; // string | HashID of the search engine to query or a list in the format [hashid1,hashid2,...]
$to = "to_example"; // string | Date end of the interval in the format of UNIX timestamp or YYYYMMDD. Today, by default.
$from = "from_example"; // string | Date start of the interval in the format of UNIX timestamp or YYYYMMDD. By default, 10 days from current date.
$tz = "tz_example"; // string | Timezone for the given dates, by default assumes UTC.
$device = "device_example"; // string | Device filter, by default is all
$interval = "1d"; // string | Time interval for aggregations
$type = "type_example"; // string | Filter by the given usage type.
$format = "json"; // string | Indicates which response format should be used

try {
    $result = $apiInstance->usage($hashid, $to, $from, $tz, $device, $interval, $type, $format);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling StatsApi->usage: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **hashid** | **string**| HashID of the search engine to query or a list in the format [hashid1,hashid2,...] |
 **to** | **string**| Date end of the interval in the format of UNIX timestamp or YYYYMMDD. Today, by default. | [optional]
 **from** | **string**| Date start of the interval in the format of UNIX timestamp or YYYYMMDD. By default, 10 days from current date. | [optional]
 **tz** | **string**| Timezone for the given dates, by default assumes UTC. | [optional]
 **device** | **string**| Device filter, by default is all | [optional]
 **interval** | **string**| Time interval for aggregations | [optional] [default to 1d]
 **type** | **string**| Filter by the given usage type. | [optional]
 **format** | **string**| Indicates which response format should be used | [optional] [default to json]

### Return type

**object**

### Authorization

[api_token](../../README.md#api_token), [jwt_token](../../README.md#jwt_token)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

