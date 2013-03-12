<?php
/**
 * Author:: JoeZ99 (<jzarate@gmail.com>). all credit to Gilles Devaux (<gilles.devaux@gmail.com>) (https://github.com/flaptor/indextank-php)
 *                                        
 * License:: Apache License, Version 2.0
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

class DoofinderApi{
    /*
     * Basic client for an account.
     * It needs an API url to be constructed.
     * Its only method is to query the doofinder search server
     * Returns a DoofinderResults object
     */  
    //    const url = 'http://search1.doofinder.com';
    const url = 'http://localhost:8881';
    const DEFAULT_TIMEOUT = 10000;
    const DEFAULT_RPP = 10;
    const DEFAULT_PARAMS_PREFIX = 'df_param_';
    const DEFAULT_API_VERSION = '3.0';
    private $hashid = null; // hashid of the doofinder account

    private $api_version = null; 
    private $results = null;
    private $query = null;
    private $rpp = null; // results per page
    private $timeout = null; // after the timeout, the server drops the conn
    private $types = null; // types of index to query. default is all
    private $page = 1;
    private $total = null; // total number of results obtained
    private $query_string = null;
    private $max_score = null; 
    private $params_prefix = null;
    private $serialization_array = null;
    private $to_iso = null;

    /**
     * Constructor. account's hashid and api version set here
     *
     * @param string $hashid the account's hashid
     * @param boolean $from_params if set, the object is unserialized from GET or POST params
     * @param array $options. associative array with some options:
     *                -'prefix' (default: 'df_param_')=> the prefix to use when serializing. 
     *                -'api_version' (default: '3.0')=> the api of the search server to query
     *                -'to_iso' (default: false) => whether if convert results to iso-8859-1 or not
     *                         default charset is utf8
     *                -'restricted_request'(default: $_REQUEST):  =>restrict request object 
     *                         to look for params when unserializing. either 'get' or 'post'
     * @throws DoofinderException if $hashid is not a md5 hash or api is no 3.0 or 1.0
     */
    function __construct($hashid, $from_params=false, $options = array()){
        $this->params_prefix = array_key_exists('prefix', $options) ? 
            $options['prefix'] : self::DEFAULT_PARAMS_PREFIX;
        $this->api_version = array_key_exists('api_version', $options) ?
            $options['api_version'] : self::DEFAULT_API_VERSION;
        $this->serialization_array = $_REQUEST;
        if(array_key_exists('restricted_request', $options))
        {
            switch(strtolower($options['restricted_request'])){
                case 'get':
                    $this->serialization_array = $_GET;
                    break;
                case 'post':
                    $this->serialization_array = $_POST;
                    break;
            }
        }
        $this->to_iso = array_key_exists('to_iso', $options) ?
            $options['to_iso'] : false;

        $patt = '/^[0-9a-f]{32}$/i';
        if(!preg_match($patt, $hashid))
        {
            throw new DoofinderException("Wrong hashid");
        }
        if(!in_array($this->api_version, array('3.0','1.0')))
        {
            throw new DoofinderException('Wrong API');
        }
        $this->hashid = $hashid;
        if($from_params)
        {
            $this->from_querystring();
        }
        
    }
    

    private function api_call($params){
        $args = http_build_query($params);
        // remove the php special encoding of parameters
        // see http://www.php.net/manual/en/function.http-build-query.php#78603
        $args = preg_replace('/%5B(?:[0-9]|[1-9][0-9]+)%5D=/', '=', $args); 
        $url = self::url.'/'.$this->api_version.'/search?'.$args;

        $session = curl_init($url);
        curl_setopt($session, CURLOPT_CUSTOMREQUEST, 'GET'); 
        curl_setopt($session, CURLOPT_HEADER, false); // Tell curl not to return headers
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true); // Tell curl to return the response
        curl_setopt($session, CURLOPT_HTTPHEADER, array('Expect:')); //Fixes the HTTP/1.1 417 Expectation Failed
        $response = curl_exec($session);
        $http_code = curl_getinfo($session, CURLINFO_HTTP_CODE);
        curl_close($session);

        if (floor($http_code / 100) == 2) {
            return new DoofinderResults($response, $this->to_iso);
        }
        throw new DoofinderException($response);
    }

    /**
     * query. makes the query to the doofinder search server.
     * also set several search parameters through it's $options argument
     *
     * @param string $query the search query
     * @param int $page the page number or the results to show
     * @param arrray $options query options:
     *                   - 'rpp'=> number of results per page. default 10
     *                   - 'timeout' => timeout after which the search server drops the conn. 
     *                                  defaults to 10 seconds
     *                   - 'types' => types of index to search at. default: all.
     * @return DoofinderResults results
     */
    public function query($query=null, $page=null, $options = array()){
        
        $query = $query?$query:$this->query_string;
        $this->page = $page?(int)$page:$this->page ;

        $this->rpp = (int)array_key_exists('rpp', $options) ? 
            $options['rpp']:($this->rpp? $this->rpp : self::DEFAULT_RPP);

        $this->timeout = (int)array_key_exists('timeout', $options) ? 
            $options['timeout'] : ($this->timeout? $this->timeout : self::DEFAULT_TIMEOUT);

        $this->types = array_key_exists('types', $options) ? 
            $options['types'] : ($this->types? $this->types : array());




        $params = array(
                        'query'=>$query, 
                        'rpp'=>$this->rpp, 
                        'timeout'=>$this->timeout, 
                        'hashid'=>$this->hashid,
                        'page'=>$this->page,
                        'types'=>$this->types
                        );

        if(trim($query)== ''){
            $jsonstring = '{
                "results":[], 
                "results_per_page":'.$this->rpp.',
                "page": 1,
                "total": 0,
                "query": "",
                "hashid": "'.$this->hashid.'"
                }';
            return new DooFinderResults($jsonstring);
        }
        $df_results = $this->api_call($params);
        $this->page = $df_results->getProperty('page');
        $this->total = $df_results->getProperty('total');
        $this->query_string = $df_results->getProperty('query');
        $this->max_score = $df_results->getProperty('max_score');
        return $df_results;
    }

    /**
     * has_next
     *
     * @return boolean true if there is another page of results
     */
    public function has_next(){
        return $this->page*$this->rpp < $this->total;
    }

    /**
     * has_prev
     *
     * @return true if there is a previous page of results
     */
    public function has_prev(){
        return ($this->page-1)*$this->rpp > 0;
    }


    /**
     * get_page
     *
     * obtain the current page number
     * @return int the page number
     */
    public function get_page(){
        return $this->page;
    }

    /**
     * to_querystring
     *
     * 'serialize' the object's state to querystring params
     * @param int $page the pagenumber. defaults to the current page
     */
    public function to_querystring($page=null){
        $page = $page? $page : $this->page;
        if($page > 1)
        {
            $to_params[$this->params_prefix.'page'] =  $page;
        }
        if($this->rpp && $this->rpp!=self::DEFAULT_RPP)
        {
            $to_params[$this->params_prefix.'rpp'] = $this->rpp;
        }
        if($this->timeout && $this->timeout!=self::DEFAULT_TIMEOUT)
        {
            $to_params[$this->params_prefix.'timeout']= $this->timeout;
        }
        if($this->types && $this->types!=array())
        {
            $to_params[$this->params_prefix.'types']= $this->types;
        }
        $to_params[$this->params_prefix.'query']= $this->query_string;
        
        return http_build_query($to_params);
        
    }

    /**
     * from_querystring
     *
     * obtain object's state from querystring params
     * @param string $params  where to obtain params from:
     *                       - 'GET' $_GET params (default)
     *                       - 'POST' $_POST params
         */
    public function from_querystring(){
        $this->query_string = array_key_exists($this->params_prefix.'query', 
                                               $this->serialization_array)?
            $this->serialization_array[$this->params_prefix.'query']:null;
        $this->page = array_key_exists($this->params_prefix.'page', 
                                       $this->serialization_array)?
            (int)$this->serialization_array[$this->params_prefix.'page']:1;
        $this->rpp = array_key_exists($this->params_prefix.'rpp', 
                                      $this->serialization_array)? 
            (int) $this->serialization_array[$this->params_prefix.'rpp']: self::DEFAULT_RPP;
        $this->timeout = array_key_exists($this->params_prefix.'timeout', 
                                          $this->serialization_array) ? 
            (int) $this->serialization_array[$this->params_prefix.'timeout'] : 
            self::DEFAULT_TIMEOUT;
        $this->types = array_key_exists($this->params_prefix.'types', 
                                        $this->serialization_array) ? 
            $this->serialization_array[$this->params_prefix.'types'] : null;
    }

    /**
     * next_page
     *
     * obtain the results for the next page
     * @return DoofinderResults if there are results. 
     * @return null otherwise
     */
    public function next_page(){
        if($this->has_next())
        {
            return $this->query($this->query_string, array('page' => $this->page+1 ));
        }
        return null;
    }


    /**
     * prev_page
     *
     * obtain results for the previous page
     * @return DoofinderResults
     * @return null otherwise
     */
    public function prev_page(){
        if($this->has_prev())
        {
            return $this->query($this->query_string, array('page' => $this->page-1 ));
        }
        return null;
    }

    /**
     * num_pages
     *
     * @return integer the number of pages
     */
    public function num_pages(){
        return ceil($this->total / $this->rpp);
    }

    public function getRpp(){
        return $this->rpp;
    }
    public function getTimeout(){
        return $this->timeout;
    }

    /**
     * set_api_version
     *
     * sets the api version to use. 
     * @param string $api_version the api version , '1.0' or '3.0'
     */
    public function setApiVersion($api_version){
        $this->api_version = $api_version;
    }

    /**
     * set_prefix
     * 
     * sets the prefix that will be used for serialization to querystring params
     * @param string $prefix the prefix
     */
    public function set_prefix($prefix){
        $this->params_prefix = $prefix;
    }
    
    
}

/**
 * @author JoeZ99 <jzarate@gmail.com>
 *
 * DoofinderResults
 *
 * Very thin wrapper of the results obtained from the doofinder server
 * it holds to accessor:
 * - getProperty : get single property of the search results (rpp, page, etc....)
 * - getResults: get an array with the results
 */
class DoofinderResults{

    // doofinder status
    const SUCCESS = 'success';      // everything ok
    const NOTFOUND = 'notfound';    // no account with the provided hashid found
    const EXHAUSTED = 'exhausted';  // the account has reached its query limit
    
    private $properties = null;
    private $results = null;
    public $status = null;
    
    /**
     * Constructor
     *
     * @param string $json_string stringified json returned by doofinder search server
     * @param boolean to_iso (default: false) whether to convert results in iso-8859-1. 
     *
     */
    function __construct($json_string, $to_iso=false){
        $raw_results = json_decode($json_string, true);
        foreach($raw_results as $kkey => $vall){
            if(!is_array($vall)){
                $this->properties[$kkey] = $vall;
            }
        }
        // doofinder status
        $this->status = isset($this->properties['doofinder_status'])? 
            $this->properties['doofinder_status'] : self::SUCCESS;

        
        $this->results = array();

        if(isset($raw_results['results']) && is_array($raw_results['results']))
        {
            if($to_iso){
                array_walk_recursive($raw_results['results'], array($this, 'toIso'));
            }
            $this->results = $raw_results['results'];
        }
    }

    private function toIso(&$value, $key){
        $value = utf8_decode($value);
    }
    
    /**
     * getProperty
     *
     * get single property from the results 
     * @param string @property_name: 'results_per_page', 'query', 'max_score', 'page', 'total', 'hashid'
     * @return mixed the value of the property
     */
    public function getProperty($property_name){
        return array_key_exists($property_name, $this->properties) ? 
            $this->properties[$property_name]: null;
    }

    /**
     * getResults
     *
     * @return array search results. at the moment, only the 'cooked' version.
     *                   Each result is of the form:
     *                     array('header'=>..., 
     *                           'body' => .., 
     *                           'price' => .., 
     *                           'href' => ..., 
     *                           'image' => ..., 
     *                           'type' => ..., 
     *                           'id' => ..)
     */
    public function getResults(){
        return $this->results;
    }
    /**
     * isOk
     *
     * checks if all went well
     * @return boolean true if the status is 'success'.
     *                 false if the status is not.
     */
    public function isOk(){
        return $this->status == self::SUCCESS;
    }
}


class DoofinderException extends Exception{
    
}

