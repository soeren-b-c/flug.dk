<?php
namespace Libs;

class Curl {

    const USER_AGENT = 'FLUG/1.0';

    private $curl;
    private $cookies = array();
    private $headers = array();
    public $options = array();
    public $curlErrorCode = '';
    public $curlErrorMsg = '';
    public $curlFailed = false;
    public $httpStatusCode = 0;
    public $httpError = false;
    public $error = false;
    public $requestHeaders = array();
    public $requestBody = '';
    public $responseHeaders = array();
    public $responseBody = '';
    public $transferInfo = array();

    public function __construct() {
        $this->initCurl();
    }

    public function initCurl() {
        if (!extension_loaded('curl')) {
            throw new Exception('cURL library is not loaded');
        }
        $this->reset();
        $this->curl = curl_init();
        $this->setUserAgent(self::USER_AGENT);
        $this->setOpt(CURLINFO_HEADER_OUT, true);
        $this->setOpt(CURLOPT_HEADER, true);
        $this->setOpt(CURLOPT_RETURNTRANSFER, true);
        $this->setOpt(CURLOPT_SSL_VERIFYHOST, false);
        $this->setOpt(CURLOPT_SSL_VERIFYPEER, false);
    }

    public function get($url, $data = array()) {
        $this->setopt(CURLOPT_URL, $this->buildURL($url, $data));
        $this->setopt(CURLOPT_HTTPGET, true);
        return $this->exec();
    }


    public function post($url, $data = '', $queryParams = array()) {
        $this->requestBody = $this->postfields($data);
        $this->setOpt(CURLOPT_URL, $this->buildURL($url, $queryParams));
        $this->setOpt(CURLOPT_POST, true);
        $this->setOpt(CURLOPT_POSTFIELDS, $this->requestBody);
        return $this->exec();
    }

    public function put($url, $data = '') {
        $this->requestBody = $this->postfields($data);
        $this->setOpt(CURLOPT_URL, $url);
        $this->setOpt(CURLOPT_CUSTOMREQUEST, 'PUT');
        $this->setOpt(CURLOPT_POSTFIELDS, $this->requestBody);
        return $this->exec();
    }

    public function patch($url, $data = array()) {
        $this->requestBody = $data;
        $this->setOpt(CURLOPT_URL, $url);
        $this->setOpt(CURLOPT_CUSTOMREQUEST, 'PATCH');
        $this->setOpt(CURLOPT_POSTFIELDS, $data);
        return $this->exec();
    }

    public function delete($url, $data =  '') {
    	$this->requestBody = $this->postfields($data);
    	$this->setOpt(CURLOPT_URL, $url);
        $this->setOpt(CURLOPT_CUSTOMREQUEST, 'DELETE');
        $this->setOpt(CURLOPT_POSTFIELDS, $this->requestBody);
        return $this->exec();
    }

    public function setHeader($key, $value) {
        $this->headers[$key] = $key . ': ' . $value;
        $this->setOpt(CURLOPT_HTTPHEADER, array_values($this->headers));
    }
    
    /**
     * Add a content type to the header.
     * 
     * @see http://en.wikipedia.org/wiki/MIME_type
     * @param string $type Content type
     */
    public function setContentType($type) {
        $this->setHeader('Content-Type', $type);
    }

    public function setReferrer($referrer) {
        $this->setOpt(CURLOPT_REFERER, $referrer);
    }

    public function setCookie($key, $value) {
        $this->cookies[$key] = $value;
        $this->setOpt(CURLOPT_COOKIE, http_build_query($this->cookies, '', '; '));
    }

    public function setCookieFile($cookieFile) {
        $this->setOpt(CURLOPT_COOKIEFILE, $cookieFile);
    }

    public function setCookieJar($cookieJar) {
        $this->setOpt(CURLOPT_COOKIEJAR, $cookieJar);
    }

    public function setBasicAuthentication($username, $password) {
        $this->setOpt(CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $this->setOpt(CURLOPT_USERPWD, $username . ':' . $password);
    }

    public function close() {
        if (is_resource($this->curl)) {
            curl_close($this->curl);
            $this->curl = null;
        }
    }

    public function isInitialState() {
        if ($this->httpStatusCode !== 0) {
            return false;
        }
        return true;
    }
    
    public function reset() {
        $this->curl;
        $this->cookies = array();
        $this->headers = array();
        $this->options = array();
        $this->curlErrorCode = '';
        $this->curlErrorMsg = '';
        $this->curlFailed = false;
        $this->httpStatusCode = 0;
        $this->httpError = false;
        $this->error = false;
        $this->requestHeaders = array();
        $this->requestBody = '';
        $this->responseHeaders = array();
        $this->responseBody = '';
    }
    
    public function verbose($on = true) {
        $this->setOpt(CURLOPT_VERBOSE, $on);
    }

    public function setUserAgent($userAgent) {
        $this->setOpt(CURLOPT_USERAGENT, $userAgent);
    }
    
    public function isOpen() {
        if ($this->curl !== null) {
            return true;
        }
        return false;
    }

    public function setOpt($option, $value) {
        if ($this->curl === null) {
            throw new Exception('cURL has not been initialized');
        }

        $this->options[$option] = $value;

        $required_options = array(
            CURLINFO_HEADER_OUT => 'CURLINFO_HEADER_OUT',
            CURLOPT_HEADER => 'CURLOPT_HEADER',
            CURLOPT_RETURNTRANSFER => 'CURLOPT_RETURNTRANSFER',
        );

        if (in_array($option, array_keys($required_options), true) && !($value === true)) {
            trigger_error($required_options[$option] . ' is a required option', E_USER_WARNING);
        }

        return curl_setopt($this->curl, $option, $value);
    }
    
    public function getRequestHeader($headerKey) {
        foreach ($this->requestHeaders as $header) {
            $exp = explode(':', $header, 2);
            if (count($exp) == 2) {
                $header = strtolower(trim($exp[0]));
                $headerContent = trim($exp[1]);
                if ($header == strtolower($headerKey)) {
                    return $headerContent;
                }
            }
        }
        return '';
    }
    
    public function getResponseHeader($headerKey) {
        foreach ($this->responseHeaders as $header) {
            $exp = explode(':', $header, 2);
            if (count($exp) == 2) {
                $header = strtolower(trim($exp[0]));
                $headerContent = trim($exp[1]);
                if ($header == strtolower($headerKey)) {
                    return $headerContent;
                }
            }
        }
        return '';
    }

    private function exec() {
        $this->responseBody = curl_exec($this->curl);

        $curlErrorNo = curl_errno($this->curl);
        $this->curlErrorCode = $this->getErrorCode($curlErrorNo) . " ($curlErrorNo)";
        $this->curlErrorMsg = curl_error($this->curl);
        $this->curlFailed = $curlErrorNo !== 0;

        $this->httpStatusCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        $this->httpError = in_array(floor($this->httpStatusCode / 100), array(4, 5));
        $this->error = $this->curlFailed || $this->httpError;

        $this->requestHeaders = preg_split('/\r\n/', curl_getinfo($this->curl, CURLINFO_HEADER_OUT), null, PREG_SPLIT_NO_EMPTY);
        $this->responseHeaders = array();
        if (!(strpos($this->responseBody, "\r\n\r\n") === false)) {
            list($responseHeader, $this->responseBody) = explode("\r\n\r\n", $this->responseBody, 2);
            if ($responseHeader === 'HTTP/1.1 100 Continue') {
                list($responseHeader, $this->responseBody) = explode("\r\n\r\n", $this->responseBody, 2);
            }
            $this->responseHeaders = preg_split('/\r\n/', $responseHeader, null, PREG_SPLIT_NO_EMPTY);
        }
        
        $this->transferInfo = curl_getinfo($this->curl);

        return !$this->error;
    }

    public function buildURL($url, $data = array()) {
        return $url . (empty($data) ? '' : '?' . http_build_query($data));
    }

    private function postfields($data) {
        if (is_array($data)) {
            if ($this->isArrayMultiDim($data)) {
                $data = $this->httpBuildMultiQuery($data);
            } else {
                // Fix "Notice: Array to string conversion" when $value in
                // curl_setopt($ch, CURLOPT_POSTFIELDS, $value) is an array
                // that contains an empty array.
                foreach ($data as $key => $value) {
                    if (is_array($value) && empty($value)) {
                        $data[$key] = '';
                    }
                }
                $data = http_build_query($data);
            }
        }

        return $data;
    }

    private function isArrayMultiDim($array) {
        if (!is_array($array)) {
            return false;
        }

        return !(count($array) === count($array, COUNT_RECURSIVE));
    }

    private function httpBuildMultiQuery($data, $key = null) {
        $query = array();

        if (empty($data)) {
            return $key . '=';
        }

        $isArrayAssoc = $this->isArrayAssoc($data);

        foreach ($data as $k => $value) {
            if (is_string($value) || is_numeric($value)) {
                $brackets = $isArrayAssoc ? '[' . $k . ']' : '[]';
                $query[] = urlencode(is_null($key) ? $k : $key . $brackets) . '=' . rawurlencode($value);
            } else if (is_array($value)) {
                $nested = is_null($key) ? $k : $key . '[' . $k . ']';
                $query[] = $this->httpBuildMultiQuery($value, $nested);
            }
        }

        return implode('&', $query);
    }

    private function isArrayAssoc($array) {
        return (bool) count(array_filter(array_keys($array), 'is_string'));
    }

    private function getErrorCode($errorNo) {
        $errorCodes = array(
            0 => 'CURLE_OK',
            1 => 'CURLE_UNSUPPORTED_PROTOCOL',
            2 => 'CURLE_FAILED_INIT',
            3 => 'CURLE_URL_MALFORMAT',
            4 => 'CURLE_URL_MALFORMAT_USER',
            5 => 'CURLE_COULDNT_RESOLVE_PROXY',
            6 => 'CURLE_COULDNT_RESOLVE_HOST',
            7 => 'CURLE_COULDNT_CONNECT',
            8 => 'CURLE_FTP_WEIRD_SERVER_REPLY',
            9 => 'CURLE_REMOTE_ACCESS_DENIED',
            11 => 'CURLE_FTP_WEIRD_PASS_REPLY',
            13 => 'CURLE_FTP_WEIRD_PASV_REPLY',
            14 => 'CURLE_FTP_WEIRD_227_FORMAT',
            15 => 'CURLE_FTP_CANT_GET_HOST',
            17 => 'CURLE_FTP_COULDNT_SET_TYPE',
            18 => 'CURLE_PARTIAL_FILE',
            19 => 'CURLE_FTP_COULDNT_RETR_FILE',
            21 => 'CURLE_QUOTE_ERROR',
            22 => 'CURLE_HTTP_RETURNED_ERROR',
            23 => 'CURLE_WRITE_ERROR',
            25 => 'CURLE_UPLOAD_FAILED',
            26 => 'CURLE_READ_ERROR',
            27 => 'CURLE_OUT_OF_MEMORY',
            28 => 'CURLE_OPERATION_TIMEDOUT',
            30 => 'CURLE_FTP_PORT_FAILED',
            31 => 'CURLE_FTP_COULDNT_USE_REST',
            33 => 'CURLE_RANGE_ERROR',
            34 => 'CURLE_HTTP_POST_ERROR',
            35 => 'CURLE_SSL_CONNECT_ERROR',
            36 => 'CURLE_BAD_DOWNLOAD_RESUME',
            37 => 'CURLE_FILE_COULDNT_READ_FILE',
            38 => 'CURLE_LDAP_CANNOT_BIND',
            39 => 'CURLE_LDAP_SEARCH_FAILED',
            41 => 'CURLE_FUNCTION_NOT_FOUND',
            42 => 'CURLE_ABORTED_BY_CALLBACK',
            43 => 'CURLE_BAD_FUNCTION_ARGUMENT',
            45 => 'CURLE_INTERFACE_FAILED',
            47 => 'CURLE_TOO_MANY_REDIRECTS',
            48 => 'CURLE_UNKNOWN_TELNET_OPTION',
            49 => 'CURLE_TELNET_OPTION_SYNTAX',
            51 => 'CURLE_PEER_FAILED_VERIFICATION',
            52 => 'CURLE_GOT_NOTHING',
            53 => 'CURLE_SSL_ENGINE_NOTFOUND',
            54 => 'CURLE_SSL_ENGINE_SETFAILED',
            55 => 'CURLE_SEND_ERROR',
            56 => 'CURLE_RECV_ERROR',
            58 => 'CURLE_SSL_CERTPROBLEM',
            59 => 'CURLE_SSL_CIPHER',
            60 => 'CURLE_SSL_CACERT',
            61 => 'CURLE_BAD_CONTENT_ENCODING',
            62 => 'CURLE_LDAP_INVALID_URL',
            63 => 'CURLE_FILESIZE_EXCEEDED',
            64 => 'CURLE_USE_SSL_FAILED',
            65 => 'CURLE_SEND_FAIL_REWIND',
            66 => 'CURLE_SSL_ENGINE_INITFAILED',
            67 => 'CURLE_LOGIN_DENIED',
            68 => 'CURLE_TFTP_NOTFOUND',
            69 => 'CURLE_TFTP_PERM',
            70 => 'CURLE_REMOTE_DISK_FULL',
            71 => 'CURLE_TFTP_ILLEGAL',
            72 => 'CURLE_TFTP_UNKNOWNID',
            73 => 'CURLE_REMOTE_FILE_EXISTS',
            74 => 'CURLE_TFTP_NOSUCHUSER',
            75 => 'CURLE_CONV_FAILED',
            76 => 'CURLE_CONV_REQD',
            77 => 'CURLE_SSL_CACERT_BADFILE',
            78 => 'CURLE_REMOTE_FILE_NOT_FOUND',
            79 => 'CURLE_SSH',
            80 => 'CURLE_SSL_SHUTDOWN_FAILED',
            81 => 'CURLE_AGAIN',
            82 => 'CURLE_SSL_CRL_BADFILE',
            83 => 'CURLE_SSL_ISSUER_ERROR',
            84 => 'CURLE_FTP_PRET_FAILED',
            84 => 'CURLE_FTP_PRET_FAILED',
            85 => 'CURLE_RTSP_CSEQ_ERROR',
            86 => 'CURLE_RTSP_SESSION_ERROR',
            87 => 'CURLE_FTP_BAD_FILE_LIST',
            88 => 'CURLE_CHUNK_FAILED'
        );
        return isset($errorCodes[$errorNo]) ? $errorCodes[$errorNo] : 'UNKNOWN';
    }

    public function __destruct() {
        $this->close();
    }

}

?>
