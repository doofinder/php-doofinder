<?php
/**
 * DataSource
 *
 * PHP version 5
 *
 * @category Class
 * @package  DoofinderManagement
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */

/**
 * Doofinder Management API
 *
 * # Introduction  Doofinder's management API allows you to perform some of the administrative tasks you can do on your search engines using the Doofinder control panel, directly from your code.  # Basics  ## Endpoint  All requests should be done via `https` to the right endpoint:  ``` https://{search-zone}-api.doofinder.com ```  Where `{search-zone}` is the code of the datacenter where your search engines are located.  For instance:  ``` https://eu1-api.doofinder.com https://us1-api.doofinder.com ```  ## Authentication  To authenticate you need a management API key. If you don't have one you can generate it in the Doofinder Admin by going to your Account and then to API Keys.  A valid API key looks like this:  ``` ab46030xza33960aac71a10248489b6c26172f07 ```  ### API Token  You can authenticate with the previous API key in header as a Token. The correct way to authenticate is to send a HTTP Header with the name `Authorization` and the value `Token {api-key}`  I.e.:  ``` Authorization: Token ab46030xza33960aac71a10248489b6c26172f07 ```  ### JWT Token (Draft)  If you prefer you can authenticate with a [JSON Web Token](https://jwt.io). The token must be signed with an API management key and there are some claims required in the JWT payload. These claims are:    * `iat` (issued at): Creation datetime timestamp, i.e. the moment when the JWT was created.    * `exp` (expiration time): Expiration datetime timestamp, i.e. the moment when the JWT is going to expire and will no longer be valid. The time span between issued and expiration dates must be shorter than a week.    * `name`: Your user code. It is your unique identifier as doofinder user. You can find this code in your profile page in the Doofinder's administration panel.  To authenticate using JWT you must send a HTTP header with the name `Authorization` and the value `Bearer {JWT-token}`.  I.e.:  ``` Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJuYW1lIjoidGVzdCIsImlhdCI6MTUxNjIzOTAyMn0.QX_3HF-T2-vlvzGDbAzZyc1Cd-J9qROSes3bxlgB4uk ```  ## Conventions  Along most of the code samples you will find placeholders for some common variable values. They are:    * `{hashid}`: The search engine's unique id. e.g.: d8fdeab7fce96a19d3fc7b0ca7a1e98b.    * `{index}`: When storing items, they're always stored under a certain _index_. e.g.: product.    * `{token}`: Your personal authentication token obtained in the control panel.    * `{uid}`: The unique identificator of a Doofinder User.  # Objects  ## Search Engines  A **search engine**:  - Consists of a set of indices and options to configure them. - Must contain at least one index. - Can be uniquely identified by a hash we call `hashid`. - Can be _processed_, which involves reading the data from the provided data sources (usually URLs), indexing the data in temporary indices and finally make the indices ready for use.  ## Indices  An **index**:  - Is a set of **data items** and options to describe those items, the description of the **data sources** to get them and the way they can be searched. - May have one (and only one) temporary index. A temporary index shares the same options of the main index. There are operations to manage temporary indices like create, delete, reindex, etc.  The usual flow for an index is to create a temporary index, index items on it and replace the main index with the temporary one.  This way you can reindex your whole data having zero downtime of the search service.  ## Data Sources  A **data source**:  - Defines the location for retrieving items for indexing and the most common is just a file URL. - Is accessed when the search engine is being processed.  An index does not need a data source if you index the items directly using the API.  ## Items  Items:  - Are the objects stored in an index. - Are returned as search results. - May have different schemas (collections of fields) depending on their index **preset** (if any). There are some default presets, being `product` the most usual, which describes items with a price, category, etc.
 *
 * OpenAPI spec version: 2.0
 * Contact: support@doofinder.com
 * Generated by: https://github.com/swagger-api/swagger-codegen.git
 * Swagger Codegen version: 3.0.20
 */
/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Do not edit the class manually.
 */

namespace DoofinderManagement\Model;

use \ArrayAccess;
use \DoofinderManagement\ObjectSerializer;

/**
 * DataSource Class Doc Comment
 *
 * @category Class
 * @description Set of options and parameters of a datasource. They define a source of documents to be accessed and the required parameters for accessing the source.
 * @package  DoofinderManagement
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */
class DataSource implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $swaggerModelName = 'DataSource';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerTypes = [
        'options' => '\DoofinderManagement\Model\OneOfDataSourceOptions',
'type' => 'string',
'url' => 'AnyOfDataSourceUrl'    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerFormats = [
        'options' => null,
'type' => null,
'url' => null    ];

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function swaggerTypes()
    {
        return self::$swaggerTypes;
    }

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function swaggerFormats()
    {
        return self::$swaggerFormats;
    }

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'options' => 'options',
'type' => 'type',
'url' => 'url'    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'options' => 'setOptions',
'type' => 'setType',
'url' => 'setUrl'    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'options' => 'getOptions',
'type' => 'getType',
'url' => 'getUrl'    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @return array
     */
    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @return array
     */
    public static function setters()
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @return array
     */
    public static function getters()
    {
        return self::$getters;
    }

    /**
     * The original name of the model.
     *
     * @return string
     */
    public function getModelName()
    {
        return self::$swaggerModelName;
    }

    const TYPE_BIGCOMMERCE = 'bigcommerce';
const TYPE_EKM = 'ekm';
const TYPE_FILE = 'file';
const TYPE_SHOPIFY = 'shopify';

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getTypeAllowableValues()
    {
        return [
            self::TYPE_BIGCOMMERCE,
self::TYPE_EKM,
self::TYPE_FILE,
self::TYPE_SHOPIFY,        ];
    }

    /**
     * Associative array for storing property values
     *
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     *
     * @param mixed[] $data Associated array of property values
     *                      initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->container['options'] = isset($data['options']) ? $data['options'] : null;
        $this->container['type'] = isset($data['type']) ? $data['type'] : null;
        $this->container['url'] = isset($data['url']) ? $data['url'] : null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        if ($this->container['type'] === null) {
            $invalidProperties[] = "'type' can't be null";
        }
        $allowedValues = $this->getTypeAllowableValues();
        if (!is_null($this->container['type']) && !in_array($this->container['type'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'type', must be one of '%s'",
                implode("', '", $allowedValues)
            );
        }

        return $invalidProperties;
    }

    /**
     * Validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {
        return count($this->listInvalidProperties()) === 0;
    }


    /**
     * Gets options
     *
     * @return OneOfDataSourceOptions
     */
    public function getOptions()
    {
        return $this->container['options'];
    }

    /**
     * Sets options
     *
     * @param OneOfDataSourceOptions $options DataSource general options. They define required parameters for the DataSource to work or options that modify the access to the data feed.
     *
     * @return $this
     */
    public function setOptions($options)
    {
        $this->container['options'] = $options;

        return $this;
    }

    /**
     * Gets type
     *
     * @return string
     */
    public function getType()
    {
        return $this->container['type'];
    }

    /**
     * Sets type
     *
     * @param string $type Type of datasource
     *
     * @return $this
     */
    public function setType($type)
    {
        $allowedValues = $this->getTypeAllowableValues();
        if (!in_array($type, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'type', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['type'] = $type;

        return $this;
    }

    /**
     * Gets url
     *
     * @return AnyOfDataSourceUrl
     */
    public function getUrl()
    {
        return $this->container['url'];
    }

    /**
     * Sets url
     *
     * @param AnyOfDataSourceUrl $url This field defines the source of items for indexing. The schema is linked to the datasource type. For instance, `file` types should be written with domain and protocol like \"https://mydomain.com/feed\" while `shopify` types should contain only shopify domain like \"mydomain.shopify.com\".
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->container['url'] = $url;

        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param integer $offset Offset
     *
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     *
     * @param integer $offset Offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * Sets value based on offset.
     *
     * @param integer $offset Offset
     * @param mixed   $value  Value to be set
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     *
     * @param integer $offset Offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * Gets the string presentation of the object
     *
     * @return string
     */
    public function __toString()
    {
        if (defined('JSON_PRETTY_PRINT')) { // use JSON pretty print
            return json_encode(
                ObjectSerializer::sanitizeForSerialization($this),
                JSON_PRETTY_PRINT
            );
        }

        return json_encode(ObjectSerializer::sanitizeForSerialization($this));
    }
}
