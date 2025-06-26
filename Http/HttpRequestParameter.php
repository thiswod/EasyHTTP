<?php

namespace Http;

class HttpRequestParameter
{
    /**
     * 请求地址 (URL)
     * @var string|null
     */
    public string|null $url = "";
    /**
     * 请求方法 (0=GET, 1=POST, 2=HEAD, 3=PUT, 4=OPTIONS, 5=DELETE, 6=TRACE, 7=CONNECT)
     * @var int
     */
    public int $method = 0;
    /**
     * POST数据 (如果是POST请求)
     * @var string|array
     */
    public string|array $data = "";
    /**
     * 协议头 (字符串)
     * @var string
     */
    public string $headers = "";
    /**
     * 是否验证SSL 默认false不检测
     * @var bool
     */
    public bool $sslVerifyHost = false;
    /**
     * 是否验证SSL 默认false不检测
     * @var bool
     */
    public bool $sslVerifyPeer = false;
    /**
     * 协议头 (数组)
     * @var array
     */
    public array $headers_arr = [];
    /**
     * Cookie管理器
     * @var CookieManager
     */
    public CookieManager $CookieManager;
    /**
     * 请求超时时间 (秒)
     * @var int
     */
    public int $timeout = 15;
    /**
     * 代理地址
     * @var string
     */
    public string $proxy = "";
    /**
     * 代理用户名
     * @var string
     */
    public string $proxyUsername = "";
    /**
     * 代理密码
     * @var string
     */
    public string $proxyPassword = "";
    /**
     * 是否跟随跳转 (默认为true)(是否允许重定向)
     * - true允许跟随重定向
     * - false禁止跟随重定向
     * @var bool
     */
    public bool $followLocation = false;
    /**
     * 是否完整协议头 (默认为true)
     * @var bool
     */
    public bool $completeProtocolHeaders = true;
    private HttpRequestClass $httpRequestClass;
    /**
     * 指定HOSTS解析（格式：domain:port:ip）
     * @var array
     */
    public array $hosts;
    public function __construct(HttpRequestClass $httpRequestClass)
    {
        $this->httpRequestClass = $httpRequestClass;
        $this->CookieManager = new CookieManager();
    }

    /**
     * 获取HttpRequestClass实例
     * @param $method
     * @param $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        if (method_exists($this->httpRequestClass, $method)) {
            return call_user_func_array([$this->httpRequestClass, $method], $args);
        }
        throw new \BadMethodCallException("Method $method does not exist");
    }

    /**
     * @param string|array|null $data
     * @return HttpRequestClass
     * - 使用 GetResponse() 方法获取响应内容
     * @throws \Exception
     */
    public function send(string|array $data=null): HttpRequestClass
    {
        /**
         * @return HttpRequestClass
         */
        try {
            return $this->httpRequestClass->send($data);
        } catch (\Exception $e) {
            //抛出异常
            throw new \Exception($e->getMessage());
        }
    }
    public function set_proxyUsername(string $parm): HttpRequestClass
    {
        $this->proxyUsername = $parm;
        return $this->httpRequestClass;
    }
    public function set_followLocation(bool $parm): HttpRequestClass
    {
        $this->followLocation = $parm;
        return $this->httpRequestClass;
    }
    public function set_headers_arr(array $parm): HttpRequestClass
    {
        $this->headers_arr = $parm;
        return $this->httpRequestClass;
    }
}