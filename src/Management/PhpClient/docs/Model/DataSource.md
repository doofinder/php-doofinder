# DataSource

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**options** | [**OneOfDataSourceOptions**](OneOfDataSourceOptions.md) | DataSource general options. They define required parameters for the DataSource to work or options that modify the access to the data feed. | [optional] 
**type** | **string** | Type of datasource | 
**url** | [**OneOfDataSourceUrl**](OneOfDataSourceUrl.md) | This field defines the source of items for indexing. The schema is linked to the datasource type. For instance, &#x60;file&#x60; types should be written with domain and protocol like \&quot;https://mydomain.com/feed\&quot; while &#x60;shopify&#x60; types should contain only shopify domain like \&quot;mydomain.shopify.com\&quot;. | [optional] 

[[Back to Model list]](../../../README_MANAGEMENT.md#documentation-for-models) [[Back to API list]](../../../README_MANAGEMENT.md#documentation-for-api-endpoints) [[Back to README]](../../../README_MANAGEMENT.md)

