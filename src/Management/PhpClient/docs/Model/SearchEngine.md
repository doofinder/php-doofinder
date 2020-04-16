# SearchEngine

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**hashid** | **string** | A unique code that identifies a search engine. | [optional] 
**inactive** | **bool** | Indicates if the search engine has been deactivated and therefore it can not receive requests. | [optional] 
**indices** | [**\DoofinderManagement\Model\Indices**](Indices.md) |  | [optional] 
**language** | **string** | An ISO 639-1 language code that determines the language of the search engine. The language affects how the words indexed are tokenized and which stopwords to use. | 
**name** | **string** | A short name that helps identifying the search engine. | 
**search_url** | **string** | Indicates the search server domain for this search engine. If you want to do searches to this search engine you should use this domain. | [optional] 
**site_url** | **string** | The URL of the site to be integrated with the search engine. It determines the default allowed domains for requests. | [optional] 
**stopwords** | **bool** | Ignores high-frequency terms like \&quot;the\&quot;, \&quot;and\&quot;, \&quot;is\&quot;. These words have a low weight and contribute little to the relevance score. | [optional] [default to false]

[[Back to Model list]](../../../README_MANAGEMENT.md#documentation-for-models) [[Back to API list]](../../../README_MANAGEMENT.md#documentation-for-api-endpoints) [[Back to README]](../../../README_MANAGEMENT.md)

