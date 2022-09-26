<?php

namespace service\middleware;

/**
 * Class restful
 * restful helper
 */
class restful {

    //http状态码及含义
    private $HTTP_STATUS = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(Unused)',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported'
    ];

    //允许客户端请求的http方法
    private $METHOD_CONFIG = ['POST', 'DELETE', 'PUT', 'GET',];

    //客户端请求http方法
    public $REQUEST_METHOD = '';

    //客户端请求data
    public $REQUEST_DATA = [];

    /**
     * restful constructor.
     * @param null $http_status
     * @param null $allow_request_method
     */
    public function __construct($http_status = NULL, $allow_request_method = NULL) {

        //注入配置
        $this->HTTP_STATUS =
            (!empty($http_status)) ? ($http_status) : ($this->HTTP_STATUS);
        $this->METHOD_CONFIG =
            (!empty($allow_request_method)) ? ($allow_request_method) : ($this->METHOD_CONFIG);

        //方法检测
        $this->REQUEST_METHOD = $_SERVER['REQUEST_METHOD'];
        if(empty($this->REQUEST_METHOD) || !in_array($this->REQUEST_METHOD, $this->METHOD_CONFIG)) {
            $this->response(501);
        }
    }

    /**
     * Func: request
     * User: Force
     * Date: 2022/3/7
     * Time: 20:50
     * Desc: 请求
     */
    public function request($merge = 0) {

        $request = file_get_contents('php://input');
        $request = json_decode($request, true);

        switch ($merge) {

            //全部混合
            case 1: {
                $request = array_merge($request, $_GET, $_POST);
                break;
            }

            //仅GET混合
            case 2: {
                $request = array_merge($request, $_GET);
                break;
            }
        }

        $request = res_safe($request);

        return $request;
    }

    /**
     * Func: response
     * User: Force
     * Date: 2022/3/7
     * Time: 19:30
     * Desc: 响应
     */
    public function response($http_code = 200, $http_message = '', $rsp_data = [], $content_type = 'application/json') {

        if($http_code == 1) { $http_code = 200; }
        if($http_code == 0 || $http_code < 200) { $http_code = 500; }

        $protocol = $_SERVER['SERVER_PROTOCOL'];
        if ('HTTP/1.1' != $protocol && 'HTTP/1.0' != $protocol) {
            $protocol = 'HTTP/1.0';
        }
        $status_header = "$protocol {$http_code} {$this->HTTP_STATUS[$http_code]}";
        header($status_header);

        header('Content-Type: '.$content_type);

        $res_data = [
            'code' => ($http_code == 200) ? 1 : 0,
            'info' => $http_message ? : ($http_code . ' - ' . $this->HTTP_STATUS[$http_code]),
        ];
        if(!empty($rsp_data) && is_array($rsp_data)) {
            $res_data['data'] = $rsp_data;
        }
        echo json_encode($res_data);
        exit;
    }
}
