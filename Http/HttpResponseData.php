<?php

namespace Http;
/**
 * Http请求返回类型
 */
class HttpResponseData
{
    /**
     * 响应状态码
     * @var int
     */
    public int $statusCode;
    /**
     * 请求头
     * @var string
     */
    public string $requestHeaders;
    /**
     * 请求头数组
     * @var array
     */
    public array $requestHeadersArray;
    /**
     * 响应头
     * @var string
     */
    public string $responseHeaders;
    /**
     * 响应头数组
     * @var array
     */
    public array $responseHeadersArray;
    /**
     * 响应正文
     * @var string
     */
    public string $body;
    /**
     * CookieManager对象
     * @var CookieManager
     */
    public CookieManager $cookieManager;
    /**
     * Cookie字符串
     * @var string
     */
    public string $Cookie;
}