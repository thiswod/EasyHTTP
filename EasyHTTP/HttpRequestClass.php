<?php
/**
 * 这是一个非常通用的HTTP请求类。
 * 该类提供了许多功能，如设置请求方法、请求URL、请求头、请求数据、Cookie管理、代理设置等。
 * 该类还实现了Cookie管理、代理设置等功能。
 *
 * @author Wod
 * @version 1.0.0
 * @license MIT
 * @link https://github.com/thiswod/EasyHTTP
 * @package Http
 * @category Http
 * @package EasyHTTP
 * @copyright (c) 2025 Wod. All rights reserved.
 */
namespace EasyHTTP;

class HttpRequestClass
{
    /**
     * 请求方法对象
     * @var HttpRequestParameter
     */
    private HttpRequestParameter $HttpRequestParameter;
    /**
     * HttpResponseData对象
     * @var HttpResponseData
     */
    private HttpResponseData $HttpResponseData;
    private \CurlHandle|null $ch = null;
    private string $userAgent = '';
    /**
     * 构造函数
     * @param string|null $url
     * @param int $method (0=GET, 1=POST, 2=HEAD, 3=PUT, 4=OPTIONS, 5=DELETE, 6=TRACE, 7=CONNECT)
     * @param cookieManager $cookieManager    绑定与外部Cookie管理器同步
     */
    public function __construct(string $url=null, int $method=0, mixed &$cookieManager=null)
    {
        $this->HttpResponseData = new HttpResponseData();
        $this->HttpRequestParameter = new HttpRequestParameter($this);
        $cookieManager = $this->HttpRequestParameter->CookieManager;
        $this->HttpRequestParameter->url = $url;
        $this->HttpRequestParameter->method = $method;
    }
    /**
     * 操作HttpRequestParameter类 设置和读取设置
     * @return HttpRequestParameter
     */
    public function set(): HttpRequestParameter
    {
        return $this->HttpRequestParameter;
    }

    /**
     * 绑定与外部Cookie管理器同步
     * @param $cookieManager
     * @return $this
     */
    public function bindcookie(&$cookieManager): static
    {
        $cookieManager = $this->HttpRequestParameter->CookieManager;
        return $this;
    }
    /**
     * 判断是否为关联数组
     * @param array $array
     * @return bool
     */
    private static function isAssociativeArray(array $array): bool
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }

    /**
     * @param string|array|null $data
     * @return $this
     * - 使用 GetResponse() 方法获取响应内容
     * @throws \Exception
     */
    public function Send(string|array $data=null): object
    {
        if ($this->HttpRequestParameter->url === null || !filter_var($this->HttpRequestParameter->url, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException("Invalid or missing URL");
        }
        if ($data !== null && $this->HttpRequestParameter->method === 1) {
            if (is_array($data) && empty($data)) {
                throw new \InvalidArgumentException("POST data array cannot be empty");
            }
            $this->HttpRequestParameter->data = $data;
        }

        if (!$this->ch) {
            $this->ch = curl_init();
        } else {
            curl_reset($this->ch); // 重置 cURL 句柄以清除旧配置
        }
        // 设置POST数据 (如果是POST请求)
        if($data and $this->HttpRequestParameter->method == 1){
            $this->HttpRequestParameter->data = $data;
        }
        //$this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $this->HttpRequestParameter->url);
        // 设置请求方法
        $methods = ['GET', 'POST', 'HEAD', 'PUT', 'OPTIONS', 'DELETE', 'TRACE', 'CONNECT'];
        if (isset($methods[$this->HttpRequestParameter->method])) {
            if ($methods[$this->HttpRequestParameter->method] === 'HEAD') {
                // HEAD 请求特殊处理
                curl_setopt($this->ch, CURLOPT_NOBODY, true);
            } elseif ($methods[$this->HttpRequestParameter->method] === 'GET') {
                curl_setopt($this->ch, CURLOPT_HTTPGET, true);
            } else {
                // 其他方法
                curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, $methods[$this->HttpRequestParameter->method]);
                // POST/PUT等可以有请求体，由调用方通过CURLOPT_POSTFIELDS设置
            }
        }
        if($this->userAgent != ''){
            $this->HttpRequestParameter->headers_arr['User-Agent'] = $this->userAgent;
        }
        // 设置POST数据 (如果是POST请求)
        if ($this->HttpRequestParameter->method === 1) {
            curl_setopt($this->ch, CURLOPT_POST, true);
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->HttpRequestParameter->data);
        }
        // 设置Cookie
        if (!empty($this->HttpRequestParameter->CookieManager)) {
            $request_cookie = $this->HttpRequestParameter->CookieManager->getCookieString();//获取Cookie字符串
            curl_setopt($this->ch, CURLOPT_COOKIE, $request_cookie);
        }
        // 设置 SSL 选项（视情况而定）
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, $this->HttpRequestParameter->sslVerifyPeer);//验证主机
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, $this->HttpRequestParameter->sslVerifyHost);//验证主机

        // 设置 HTTP 版本
        //curl_setopt($this->ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);

        // ================== 请求头合并逻辑 ==================
        $allHeaders = [];

        // 处理字符串格式头
        if (!empty($this->HttpRequestParameter->headers)) {
            $headersFromString = array_filter(
                explode("\r\n", trim($this->HttpRequestParameter->headers)),
                function ($line) { return !empty(trim($line)); }
            );
            $allHeaders = array_merge($allHeaders, $headersFromString);
        }

        // 处理数组格式头（关联数组或索引数组）
        if (!empty($this->HttpRequestParameter->headers_arr)) {
            $processedHeaders = [];
            foreach ($this->HttpRequestParameter->headers_arr as $key => $value) {
                if (is_int($key)) { // 索引数组格式直接处理
                    if (strpos($value, ':') === false) continue;
                    $processedHeaders[] = $value;
                } else { // 关联数组转换为 "Key: Value" 格式
                    $processedHeaders[] = "$key: $value";
                }
            }
            $allHeaders = array_merge($allHeaders, $processedHeaders);
        }

        // ================== 智能添加默认协议头 ==================
        if ($this->HttpRequestParameter->completeProtocolHeaders) {
            // 解析已存在的头字段（统一小写键名）
            $existingHeaders = [];
            foreach ($allHeaders as $header) {
                if (str_contains($header, ':')) {
                    [$key] = explode(':', $header, 2);
                    $existingHeaders[strtolower(trim($key))] = true;
                }
            }

            // 定义默认协议头（键名必须小写）
            $defaultHeaders = [
                'accept'            => 'Accept: */*',
                'accept-language'   => 'Accept-Language: zh-cn',
                'user-agent'        => 'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.87 Safari/537.36',
                'referer'           => 'Referer: ' . $this->HttpRequestParameter->url,
                'pragma'            => 'Pragma: no-cache',
                'Connection'      => 'Connection: Keep-Alive'
            ];

            // 添加缺失的默认头
            foreach ($defaultHeaders as $lowerKey => $header) {
                if (!isset($existingHeaders[$lowerKey])) {
                    $allHeaders[] = $header;
                }
            }
        }

        // 统一设置请求头（自动去重，cURL以最后出现的为准）
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array_unique($allHeaders));

        // 指定HOSTS解析（格式：domain:port:ip）
        if(!empty($this->HttpRequestParameter->hosts)){
            curl_setopt($this->ch, CURLOPT_RESOLVE, $this->HttpRequestParameter->hosts);
        }
        //允许重定向
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, $this->HttpRequestParameter->followLocation);

        // 设置超时时间
        curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->HttpRequestParameter->timeout);

        // 设置代理 (如果有)
        if (!empty($this->HttpRequestParameter->proxy)) {
            curl_setopt($this->ch, CURLOPT_PROXY, $this->HttpRequestParameter->proxy);
            if (!empty($this->HttpRequestParameter->proxyUsername) && !empty($this->HttpRequestParameter->proxyPassword)) {
                curl_setopt($this->ch, CURLOPT_PROXYUSERPWD, "$this->HttpRequestParameter->proxyUsername:$this->HttpRequestParameter->proxyPassword");
            }
        }
        curl_setopt($this->ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1); // 强制使用HTTP/1.1
        // 返回响应内容
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);

        // 获取响应头
        curl_setopt($this->ch, CURLOPT_HEADER, true);
        curl_setopt($this->ch, CURLOPT_ENCODING, 'gzip, deflate'); // 自动处理压缩
        // 执行请求
        $response = curl_exec($this->ch);
        if ($response === false) {
            // 获取错误代码和描述
            $errorCode = curl_errno($this->ch);
            $errorMsg = curl_error($this->ch);
            throw new \Exception("cURL 错误代码: $errorCode, 错误信息: $errorMsg");//抛出异常
        }
        // 获取响应头和状态代码
        $headerSize = curl_getinfo($this->ch, CURLINFO_HEADER_SIZE);
        $responseHeaders = substr($response, 0, $headerSize);
        $responseBody = substr($response, $headerSize);
        $statusCode = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);

        // 关闭cURL句柄
        //curl_close($this->ch);
        // 解析并更新 Cookies
        if (!empty($this->HttpRequestParameter->CookieManager)) {
            if (preg_match_all('/^Set-Cookie:\s*([^\r\n]+)/mi', $responseHeaders, $matches)) {
                foreach ($matches[1] as $cookieStr) {
                    // 只取第一个分号前的键值对（忽略属性）
                    $cookieKeyValue = explode(';', $cookieStr, 2)[0];
                    // 分割键值对（允许值中包含等号）
                    $parts = explode('=', $cookieKeyValue, 2);
                    if (count($parts) !== 2) continue;
                    $name = trim($parts[0]);
                    $value = trim($parts[1]);

                    // 如果值为空则删除Cookie
                    if (strcasecmp($value, 'deleted') === 0) {
                        $this->HttpRequestParameter->CookieManager->setCookie($name, 'deleted');
                    } else {
                        $this->HttpRequestParameter->CookieManager->setCookie($name, $value);
                    }
                }
            }
        }
        // 查看Content-Encoding头部，判断是否为gzip压缩
        /*if (stripos($responseHeaders, 'Content-Encoding: gzip') !== false) {
            // 如果是gzip压缩的内容，使用gzdecode函数解压
            $decodedBody = gzdecode($responseBody);
            if ($decodedBody === FALSE) {
                // 解压失败时记录日志
                error_log('Failed to decompress the gzip content.');
                $responseBody = 'Failed to Retrieve the content.';
            } else {
                // 成功解压后输出内容
                $responseBody =  $decodedBody;
            }
        }*/
        // 解析响应头为键值对
        $responseHeadersArray = [];
        $cookiesKeyValue = []; // 存储所有Cookie键值对（同名保留多个）

        $headersLines = explode("\r\n", trim($responseHeaders));
        foreach ($headersLines as $line) {
            if (!str_contains($line, ':')) continue;

            list($key, $value) = explode(':', $line, 2);
            $key = trim($key);
            $value = trim($value);

            // 普通头字段直接存储
            if (strcasecmp($key, 'Set-Cookie') !== 0) {
                $responseHeadersArray[$key] = $value;
                continue;
            }

            // 处理Set-Cookie为键值对（不覆盖）
            $cookieParts = explode(';', $value, 2)[0]; // 取第一个分号前的键值对
            $kv = explode('=', $cookieParts, 2);
            if (count($kv) === 2) {
                $cookieName = trim($kv[0]);
                $cookieValue = trim($kv[1]);
                // 同名Cookie存储为数组
                if (!isset($cookiesKeyValue[$cookieName])) {
                    $cookiesKeyValue[$cookieName] = [];
                }
                $cookiesKeyValue[$cookieName][] = $cookieValue; // 保留所有值
            }

            // 原始Set-Cookie仍保留完整值
            $responseHeadersArray['Set-Cookie'][] = $value;
        }
        $this->HttpResponseData->body = $responseBody;
        $this->HttpResponseData->statusCode = $statusCode;
        $this->HttpResponseData->requestHeaders = $this->HttpRequestParameter->headers??json_encode($allHeaders);
        $this->HttpResponseData->requestHeadersArray = $allHeaders;
        $this->HttpResponseData->responseHeaders = $responseHeaders;
        $this->HttpResponseData->responseHeadersArray = $responseHeadersArray;
        $this->HttpResponseData->cookieManager = $this->HttpRequestParameter->CookieManager;
        $this->HttpResponseData->Cookie = $this->HttpRequestParameter->CookieManager->getCookieString();

        /*$this->HttpRequestParameter->headers_arr = [];//改回默认值
        $this->HttpRequestParameter->headers = "";//改回默认值
        $this->HttpRequestParameter->followLocation = false;//改回默认值
        $this->HttpRequestParameter->method = 0;//改回默认值*/
        return $this;
    }
    /**
     * 设置一个访问的URL和请求方法
     * @param string|null $url
     * @param int $method
     * @return $this
     */
    public function open(string $url=null,int $method=0): static
    {
        $this->HttpRequestParameter->url = $url;
        $this->HttpRequestParameter->method = $method;
        return $this;
    }

    /**
     * 设置SSL验证
     * @param bool $verifyPeer
     * @param bool $verifyHost
     * @return $this
     */
    public function setSslVerification(bool $verifyPeer = true, bool $verifyHost = true): self
    {
        $this->HttpRequestParameter->sslVerifyPeer = $verifyPeer;
        $this->HttpRequestParameter->sslVerifyHost = $verifyHost;
        return $this;
    }
    /**
     * @param $userAgent
     * @return $this
     */
    public function set_userAgent($userAgent){
        $this->userAgent = $userAgent;
        return $this;
    }
    /**
     * 设置Cookie字符串
     * @param $cookie
     * @return $this
     */
    public function set_Cookie_str($cookie):object
    {
        $this->CookieManager()->setCookieString($cookie);
        return $this;
    }

    /**
     * 设置代理
     * @param string $ip    代理IP+端口(127.0.0.1:7890)
     * @param string $user  代理用户名
     * @param string $pwd   代理密码
     * @return $this
     */
    public function set_proxy(string $ip='',string $user='',string $pwd=''): object
    {
        $this->HttpRequestParameter->proxy = $ip;
        $this->HttpRequestParameter->proxyUsername = $user;
        $this->HttpRequestParameter->proxyPassword = $pwd;
        return $this;
    }
    /**
     * 获取响应数据
     * @return HttpResponseData
     * - body: 响应正文
     * - statusCode: 响应状态码
     * - requestHeaders: 请求头 -不一定是提交的头，如果使用数组提交，则为空
     * - responseHeaders: 响应头
     * - responseHeadersArray: 响应头数组
     * - cookieManager: CookieManager对象
     * - Cookie: Cookie字符串
     */
    public function GetResponse(): HttpResponseData
    {
        return $this->HttpResponseData;
    }
    /**
     * 获取CookieManager对象
     * @return CookieManager
     */
    public function CookieManager(): CookieManager
    {
        return $this->HttpRequestParameter->CookieManager;
    }

    /**
     * 析构函数，关闭cURL资源
     */
    public function __destruct()
    {
        if ($this->ch instanceof \CurlHandle && !curl_errno($this->ch)) {
            curl_close($this->ch);
        }
    }

    /**
     * 将响应内容转换为字符串
     * @return string
     */
    public function __toString()
    {
        return $this->HttpResponseData->body;
    }
    /**
     * 获取所有返回信息
     * @return HttpResponseData[]
     */
    public function __debugInfo()
    {
        return [
            'HttpResponseData' => $this->HttpResponseData,
        ];
    }
}
