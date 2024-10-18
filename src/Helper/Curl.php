<?php

/**
 * @author noxter
 */

namespace Noxterr\Spirit\Helper;

class Curl
{
    public $data;
    public $header_size;
    public $http_code;
    protected $url;
    protected $method;
    protected $header = null;
    protected $debug;
    protected $header_info = null;
    protected $post_data = null;
    protected $auth_user = null;
    protected $auth_pass = null;
    protected $curlopt_header = null;
    protected $curlopt_ssl_verifypeer = null;
    protected $curlopt_ssl_verifyhost = null;
    protected $curlopt_useragent = null;
    protected $curl_post = null;
    protected $cookie = null;

    // CONSTRUCTOR
    public function __construct($params)
    {
        $this->url = $params['url'];
        if (isset($params['header'])) {
            if (!is_array($params['header'])) {
                throw new \Exception('Header must be an array');
            } else {
                $this->header = $params['header'];
            }
        }
        $this->method = isset($params['method']) ? $params['method'] : 'GET';

        // Trace header in cURL
        $this->header_info = isset($params['header_info']) ? $params['header_info'] : true;

        // POST Body parameters
        $this->post_data = isset($params['post_data']) ? $params['post_data'] : null;

        // Authentication in cURL via user + password
        $this->auth_user = isset($params['auth_user']) ? $params['auth_user'] : null;
        $this->auth_pass = isset($params['auth_pass']) ? $params['auth_pass'] : null;

        // Include header in response
        $this->curlopt_header = isset($params['curlopt_header']) ? $params['curlopt_header'] : null;

        // Verify SSL certificate
        $this->curlopt_ssl_verifypeer = isset($params['curlopt_ssl_verifypeer']) ? $params['curlopt_ssl_verifypeer'] : null;
        // Verify SSL certificate host
        $this->curlopt_ssl_verifyhost = isset($params['curlopt_ssl_verifyhost']) ? $params['curlopt_ssl_verifyhost'] : null;

        $this->curlopt_useragent = isset($params['curlopt_useragent']) ? $params['curlopt_useragent'] : null;

        $this->curl_post = isset($params['curl_post']) ? $params['curl_post'] : null;

        $this->cookie = isset($params['cookie']) ? $params['cookie'] : null;
    }

    // Debug
    public function setDebug($debug)
    {
        $this->debug = $debug;
    }


    // Executer of cURL
    // return: ClassReturn
    public function execute(): ClassReturn
    {
        $class_return = new \Noxterr\Spirit\Helper\ClassReturn();

        // Instancing cURL
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $this->method,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $this->post_data,
            CURLINFO_HEADER_OUT => $this->header_info,
        ]);
        if (!!$this->header) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $this->header);
        }
        if (!!$this->curlopt_header) {
            curl_setopt($curl, CURLOPT_HEADER, $this->curlopt_header);
        }
        if (!!$this->auth_user && !!$this->auth_pass) {
            curl_setopt($curl, CURLOPT_USERPWD, $this->auth_user . ":" . $this->auth_pass);
        }
        if (!!$this->curlopt_ssl_verifypeer) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $this->curlopt_ssl_verifypeer);
        }
        if (!!$this->curlopt_ssl_verifyhost) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, $this->curlopt_ssl_verifyhost);
        }
        if (!!$this->cookie) {
            curl_setopt($curl, CURLOPT_COOKIE, $this->cookie);
        }
        if (!!$this->curlopt_useragent) {
            curl_setopt($curl, CURLOPT_USERAGENT, $this->curlopt_useragent);
        }

        if ($this->debug) {
            echo "{$this->method} {$this->url}" . \PHP_EOL;
            echo "Header: " . json_encode($this->header) . \PHP_EOL;
            echo "Post data: " . json_encode($this->post_data) . \PHP_EOL;
        }

        $response = curl_exec($curl);
        $err = curl_error($curl);

        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        // If I have an error in cURL I return the error
        if ($err) {
            $class_return->errcode = 1;
            $class_return->message = $this->debug ? "cURL Error: $err " : "cURL general error";
        } else {
            $this->data = $response;
            $this->header_size = $header_size;
            $this->http_code = $http_code;
        }

        return $class_return;
    }

    public function getData()
    {
        return $this->data;
    }
}
