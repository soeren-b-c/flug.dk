<?php
namespace Libs;
require_once 'Curl.php';

class Blog {
    
    const API_KEY = 'testkey';
    const BLOG_API_URL = 'http://localhost:8080/asciidoc-archive-ws';
    
    private $curl;
    
    function __construct() {
        $this->curl = new \Libs\Curl();
    }
    
    public function getArticle($title) {
        $this->curl->setHeader('Accept', 'text/html');
        
        $queryParam = array(
            'apikey' => self::API_KEY
        );

        $listUrl = self::BLOG_API_URL.'/asciidoc/'.rawurlencode($title);
        $status = $this->curl->get($listUrl, $queryParam);
        if ($status) {
            return $this->curl->responseBody;
        }
    }
    
    public function getArticles() {
        $queryParam = array(
            'apikey' => self::API_KEY,
            'offset' => '0',
            'limit' => '10'
        );

        $listUrl = self::BLOG_API_URL.'/asciidoc/list';
        $status = $this->curl->get($listUrl, $queryParam);
        if ($status) {
            return json_decode($this->curl->responseBody);
        }
    }
    
}