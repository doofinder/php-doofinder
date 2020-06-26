<?php

namespace Doofinder\Search\Test;

use \phpmock\phpunit\PHPMock;
use Doofinder\Search\Client;

class ClientTest extends \PHPUnit_Framework_TestCase {
  use PHPMock;

  public function setUp() {
    $this->hashid = 'ffffffffffffffffffffffffffffffff';
    $this->server = 'eu1-search.doofinder.com';

    $this->searchUrl = "https://eu1-search.doofinder.com/5/search";
    $this->optionsUrl = "https://eu1-search.doofinder.com/5/options";
    $this->statsUrl = "https://eu1-search.doofinder.com/5/stats";

    $ns = 'Doofinder\Search';
    $this->curl_init = $this->getFunctionMock($ns, "curl_init");
    $this->curl_setopt = $this->getFunctionMock($ns, "curl_setopt");
    $this->curl_getinfo = $this->getFunctionMock($ns, "curl_getinfo");
    $this->curl_exec = $this->getFunctionMock($ns, "curl_exec");
    $this->curl_close = $this->getFunctionMock($ns, 'curl_close');
    $this->curl_getinfo->expects($this->any())->willReturn(200);
    $this->curl_setopt->expects($this->any());

    $this->client = new Client($this->server, 'testApiToken');
  }

  private function _url($entryPoint, $params = null) {
    if (is_array($params)) {
      $params = http_build_query($params);
    }
    $url = "https://{$this->server}/5{$entryPoint}";
    return is_null($params) ? $url : "$url?$params";
  }

  private function _params($params = array()) {
    return array_merge([
      'hashid' => $this->hashid,
    ], $params);
  }

  private function _searchResponse($params = array()) {
    if (isset($params['query']) && $params['query']) {
      $queryName = 'match_and';
    } else {
      $queryName = 'match_all';
    }
    return array_merge([
      'hashid' => $this->hashid,
      'query_name' => $queryName,
      'results_per_page' => 10
    ], $params);
  }

  public function testBasicHeadersSent() {
    $this->curl_exec->expects($this->any())->willReturn(json_encode(array()));
    $this->curl_setopt->expects($this->any())->willReturnCallback(
      function($session, $option, $value) {
        if ($option == CURLOPT_HTTPHEADER) {
          $this->assertEquals($value, array('Expect:', 'Authorization: Token testApiToken'));
        }
      }
    );
    $this->client->options($this->hashid);
  }

  public function testOptionsCall() {
    $url = $this->_url("/options/{$this->hashid}");
    $this->curl_exec->expects($this->any())->willReturn(json_encode(array()));
    $this->curl_init->expects($this->once())->with($url)->willReturn(332);
    $this->client->options($this->hashid);
  }

  public function testBasicQuery() {
    $url = $this->_url('/search', $this->_params(['query' => 'abc', 'page' => 1]));
    $this->curl_exec->expects($this->any())->willReturn(json_encode($this->_searchResponse()));
    $this->curl_init->expects($this->once())->with($url);
    $this->client->search($this->_params(['query' => 'abc']));
  }

  public function testMatchAllQuery() {
    $url = $this->_url('/search', $this->_params(['page' => 1, 'query_name' => 'match_all']));
    $this->curl_exec->expects($this->any())->willReturn(json_encode($this->_searchResponse()));
    $this->curl_init->expects($this->once())->with($url);
    $this->client->search($this->_params());
  }

  public function testEmptyForcedQuery() {
    $url = $this->_url('/search', $this->_params(['query_name' => 'match_and', 'page' => 1]));
    $this->curl_exec->expects($this->any())->willReturn(json_encode($this->_searchResponse()));
    $this->curl_init->expects($this->once())->with($url);
    $this->client->search($this->_params(['query_name' => 'match_and']));
  }

  public function testSequenceOfQueries() {
    $url1 = $this->_url('/search', $this->_params(['query' => 'abc', 'page' => 1]));
    $url2 = $this->_url('/search', $this->_params(['page' => 1, 'query_name' => 'match_all']));
    $this->curl_exec->expects($this->any())->willReturn(json_encode($this->_searchResponse()));
    $this->curl_init->expects($this->exactly(2))->withConsecutive([$url1], [$url2])->willReturn(333);
    $this->client->search($this->_params(['query' => 'abc']));
    $this->client->search($this->_params());
  }

  public function testFilteredQuery() {
    $params = $this->_params([
      'query' => 'abc',
      'filter' => [
        'color' => ['red', 'green']
      ],
      'exclude' => [
        'color' => ['black']
      ]
    ]);
    $url = $this->_url('/search', array_merge([], $params, ['page' => 1]));
    $this->curl_exec->expects($this->any())->willReturn(json_encode($this->_searchResponse()));
    $this->curl_init->expects($this->once())->with($url);
    $this->client->search($params);
  }

  public function testSanitize() {
    $params = $this->_params([
      'query' => 'abc',
      'query_name' => 'match_and'
    ]);

    $url = $this->_url('/search', array_merge([], $params, ['filter' => ['color' => ['red']], 'page' => 1]));
    $this->curl_exec->expects($this->any())->willReturn(json_encode($this->_searchResponse()));
    $this->curl_init->expects($this->once())->with($url);
    $this->client->search(array_merge($params, ['filter' => ['color' => ['red', 'brand' => '']]]));
  }

  public function testNextPage() {
    $params = $this->_params([
      'filter' => [
        'color' => ['red']
      ],
      'sort' => [['price' => 'desc']],
    ]);

    $url1 = $this->_url('/search', array_merge([], $params, ['page' => 1, 'query_name' => 'match_all']));
    $url2 = $this->_url('/search', array_merge([], $params, ['page' => 2, 'query_name' => 'match_all']));

    $response = ['total' => 44, 'page' => 1, 'query' => null];

    $this->curl_exec->expects($this->any())->willReturn(json_encode($this->_searchResponse($response)));
    $this->curl_init->expects($this->exactly(2))->withConsecutive([$url1], [$url2]);
    $this->client->search($params);
    $this->client->getNextPage();
  }

  public function testPreviousPage() {
    $params = $this->_params([
      'query' => 'abc',
      'filter' => [
        'color' => ['red']
      ],
      'sort' => [['price' => 'desc']],
      'page' => 2
    ]);

    $url1 = $this->_url('/search', array_merge([], $params, ['page' => 2]));
    $url2 = $this->_url('/search', array_merge([], $params, ['page' => 1, 'query_name' => 'match_and']));

    $response = ['total' => 44, 'page' => 2, 'query' => 'abc'];

    $this->curl_exec->expects($this->any())->willReturn(json_encode($this->_searchResponse($response)));
    $this->curl_init->expects($this->exactly(2))->withConsecutive([$url1], [$url2]);
    $this->client->search($params);
    $this->client->getPreviousPage();
  }

  public function testToQueryString() {
    $response = ['total' => 44, 'page' => 1, 'query'  => 'ab', 'query_name' => 'baba'];
    $params = $this->_params([
      'query' => 'abc',
      'query_name' => 'baba',
      'filter' => [
        'color' => ['red']
      ],
      'sort' => [['price' => 'desc']]
    ]);

    $serialization = "hashid={$this->hashid}&query=abc&query_name=baba&filter%5Bcolor%5D%5B0%5D=red&sort%5B0%5D%5Bprice%5D=desc&page=1";

    $this->curl_exec->expects($this->any())->willReturn(json_encode($this->_searchResponse($response)));
    $this->client->search($params);

    $this->assertEquals($this->client->qs(), $serialization);
  }

  public function testToQueryStringWithPrefix() {
    $response = ['total' => 44, 'page' => 1, 'query'  => 'ab', 'query_name' => 'baba'];
    $params = $this->_params([
      'query' => 'abc',
      'query_name' => 'baba',
      'filter' => [
        'color' => ['red']
      ],
      'sort' => [['price' => 'desc']]
    ]);

    $serialization = "df_hashid={$this->hashid}&df_query=abc&df_query_name=baba&df_filter%5Bcolor%5D%5B0%5D=red&df_sort%5B0%5D%5Bprice%5D=desc&df_page=1";

    $this->curl_exec->expects($this->any())->willReturn(json_encode($this->_searchResponse($response)));
    $this->client->search($params);

    $this->assertEquals($this->client->qs(['prefix' => 'df_']), $serialization);
  }

  public function testLoad()
  {
    $response = ['total' => 44, 'page' => 1, 'query'  => 'ab', 'query_name' => 'baba'];
    $_REQUEST = [
      'query' => 'ab',
      'query_name'=>'baba',
      'filter'=> array('color'=>array('red')),
      'sort'=>array(array('price'=>'desc')),
      'page' => 1,
      'hashid' => $this->hashid,
    ];
    $url = $this->searchUrl.'?query=ab&query_name=baba&filter%5Bcolor%5D%5B0%5D=red&sort%5B0%5D%5Bprice%5D=desc&page=1&hashid='.$this->hashid;
    $this->curl_exec->expects($this->any())->willReturn(json_encode($this->_searchResponse($response)));
    $this->curl_init->expects($this->once())->with($url);
    // unserialize client
    $client = new Client('eu1-search.doofinder.com', 'testApiToken');
    $params = $client->load($_REQUEST);
    $client->search($params);  // do the query
  }

  public function testLoadWithCustomPrefix() {
    $response = ['total' => 44, 'page' => 1, 'query'  => 'ab', 'query_name' => 'baba'];
    $_REQUEST = [
      'df_query' => 'ab',
      'df_query_name'=>'baba',
      'df_filter'=> array('color'=>array('red')),
      'df_sort'=>array(array('price'=>'desc')),
      'df_page' => 1,
      'df_hashid' => $this->hashid,
    ];
    $url = $this->searchUrl.'?query=ab&query_name=baba&filter%5Bcolor%5D%5B0%5D=red&sort%5B0%5D%5Bprice%5D=desc&page=1&hashid='.$this->hashid;
    $this->curl_exec->expects($this->any())->willReturn(json_encode($this->_searchResponse($response)));
    $this->curl_init->expects($this->once())->with($url);
    // unserialize client
    $client = new Client('eu1-search.doofinder.com', 'testApiToken');
    $params = $client->load($_REQUEST, ['prefix' => 'df_']);
    $client->search($params);  // do the query
  }

  public function testRegisterSession(){
    $initSessionRegex = '%'.$this->statsUrl.'/init\?session_id=\w{32}&hashid='.$this->hashid.'$%';
    $this->curl_exec->expects($this->any())->willReturn(json_encode('OK'));
    $this->curl_init->expects($this->once())->with($this->matchesRegularExpression($initSessionRegex))->willReturn(334);
    $sessionId = $this->client->createSessionId();
    $this->assertTrue($this->client->registerSession($sessionId, $this->hashid));
  }

  public function testRegisterClickWithDfId(){
    $dfid = "{$this->hashid}@product@{$this->hashid}";
    $url = $this->_url('/stats/click', [
      'session_id' => 'SESSID',
      'hashid' => $this->hashid,
      'dfid' => $dfid,
    ]);
    $this->curl_exec->expects($this->any())->willReturn(json_encode('OK'));
    $this->curl_init->expects($this->once())->with($url)->willReturn(334);
    $this->assertTrue($this->client->registerClick('SESSID', $this->hashid, $dfid));
  }

  public function testRegisterClickWithDfIdAndOptions(){
    $url = $this->_url('/stats/click', [
      'session_id' => 'SESSID',
      'hashid' => $this->hashid,
      'dfid' => 'test_id',
      'query' => 'abc',
      'custom_results_id' => 'test_id',
    ]);
    $this->curl_exec->expects($this->any())->willReturn(json_encode('OK'));
    $this->curl_init->expects($this->once())->with($url)->willReturn(334);
    $this->assertTrue($this->client->registerClick('SESSID', $this->hashid, 'test_id', ['query' => 'abc', 'custom_results_id' => 'test_id']));
  }

  public function testRegisterClickWithIdAndDatatype(){
    $url = $this->_url('/stats/click', [
      'session_id' => 'SESSID',
      'hashid' => $this->hashid,
      'id' => 'test_id',
      'datatype' => 'product',
    ]);
    $this->curl_exec->expects($this->any())->willReturn(json_encode('OK'));
    $this->curl_init->expects($this->once())->with($url)->willReturn(334);
    $this->assertTrue($this->client->registerClick('SESSID', $this->hashid, 'test_id', ['datatype' => 'product']));
  }

  public function testRegisterClickWithIdAndDatatypeAndOptions(){
    $url = $this->_url('/stats/click', [
      'session_id' => 'SESSID',
      'hashid' => $this->hashid,
      'id' => 'test_id',
      'datatype' => 'product',
      'query' => 'abc',
      'custom_results_id' => 'test_id',
    ]);
    $this->curl_exec->expects($this->any())->willReturn(json_encode('OK'));
    $this->curl_init->expects($this->once())->with($url)->willReturn(334);
    $this->assertTrue($this->client->registerClick('SESSID', $this->hashid, 'test_id', ['datatype' => 'product', 'query' => 'abc', 'custom_results_id' => 'test_id']));
  }

  public function testRegisterCheckout(){
    $url = $this->_url('/stats/checkout', [
      'session_id' => 'SESSID',
      'hashid' => $this->hashid,
    ]);
    $this->curl_exec->expects($this->any())->willReturn(json_encode('OK'));
    $this->curl_init->expects($this->once())->with($url)->willReturn(334);
    $this->assertTrue($this->client->registerCheckout('SESSID', $this->hashid));
  }

  public function testRegisterImageClick(){
    $url = $this->_url('/stats/img_click', [
      'session_id' => 'SESSID',
      'hashid' => $this->hashid,
      'img_id' => 'test_id',
    ]);
    $this->curl_exec->expects($this->any())->willReturn(json_encode('OK'));
    $this->curl_init->expects($this->once())->with($url)->willReturn(334);
    $this->assertTrue($this->client->registerImageClick('SESSID', $this->hashid, 'test_id'));
  }

  public function testRegisterRedirection(){
    $url = $this->_url('/stats/redirect', [
      'session_id' => 'SESSID',
      'hashid' => $this->hashid,
      'redirection_id' => 'test_id',
      'link' => 'https://www.google.com',
    ]);

    $this->curl_exec->expects($this->any())->willReturn(json_encode('OK'));
    $this->curl_init->expects($this->once())->with($url)->willReturn(334);
    $this->assertTrue($this->client->registerRedirection('SESSID', $this->hashid, 'test_id', 'https://www.google.com'));
  }

  public function testRegisterRedirectionWithOptions(){
    $url = $this->_url('/stats/redirect', [
      'session_id' => 'SESSID',
      'hashid' => $this->hashid,
      'redirection_id' => 'test_id',
      'link' => 'https://www.google.com',
      'query' => 'abc',
    ]);

    $this->curl_exec->expects($this->any())->willReturn(json_encode('OK'));
    $this->curl_init->expects($this->once())->with($url)->willReturn(334);
    $this->assertTrue($this->client->registerRedirection('SESSID', $this->hashid, 'test_id', 'https://www.google.com', ['query' => 'abc']));
  }
}
