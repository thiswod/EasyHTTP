<?php
require_once __DIR__ . '/autoload.php';
use EasyHTTP\HttpRequestClass;

class Demo
{
    public function __construct()
    {
        $this->run();
    }

    public function run(): void
    {
        /**
         * 1. Basic GET Request / 基本GET请求
         * First usage pattern / 第一种使用方式
         */
        try {
            $response = (new HttpRequestClass('http://example.com'))
                ->Send()
                ->getResponse();
            
            // 输出返回的内容 / Output response content
            echo $response->body;
            
            // 输出Cookie / Output cookies
            echo $response->cookieManager->getCookieString().PHP_EOL;
            echo $response->Cookie;
            
            // 完整响应转储 / Full response dump
            var_dump($response);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        /**
         * 2. Alternative GET Request Pattern / 第二种GET请求方式
         */
        $HttpGet = new HttpRequestClass();
        try {
            $response = $HttpGet->open('http://example.com')
                ->Send()
                ->getResponse();
            
            echo $response->body;
            echo $response->cookieManager->getCookieString().PHP_EOL;
            echo $response->Cookie;
            var_dump($response);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        /**
         * 3. GET Request with Custom User-Agent / 使用自定义用户代理的GET请求
         */
        try {
            $response = (new HttpRequestClass('http://example.com'))
                // 设置用户浏览器标识 / Set custom User-Agent
                ->set_userAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64)')
                ->Send()
                ->getResponse();
            
            echo $response->body;
            echo $response->cookieManager->getCookieString().PHP_EOL;
            echo $response->Cookie;
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        /**
         * 4. POST Request with Form Data / 发送表单数据的POST请求
         */
        try {
            $response = (new HttpRequestClass('https://postman-echo.com/post', 1)) // 1 = POST
                ->Send([
                    'username' => 'john_doe',
                    'password' => 'secure123'
                ])
                ->getResponse();
            
            echo "POST Response:".$response->body;
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        /**
         * 5. Request with Cookies / 带Cookie的请求
         */
        try {
            $response = (new HttpRequestClass('http://example.com'))
                // 设置初始Cookie / Set initial cookies
                ->set_Cookie_str('session_id=abc123; user_pref=dark_mode')
                ->Send()
                ->getResponse();
            
            // 后续请求会自动携带Cookie / Subsequent requests automatically include cookies
            $response2 = (new HttpRequestClass('http://example.com/dashboard'))
                ->Send()
                ->getResponse();
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        /**
         * 6. Advanced Cookie Management / 高级Cookie管理
         */
        try {
            $http = new HttpRequestClass();
            $cookieManager = $http->CookieManager();
            
            // 添加Cookie / Add cookies
            $cookieManager->setCookie('language', 'zh-CN')
                         ->setCookie('theme', 'dark');
            
            // 删除Cookie / Delete cookie
            $cookieManager->setCookie('old_session', 'deleted');
            
            $response = $http->open('http://example.com')
                ->Send()
                ->getResponse();
            
            // 获取所有Cookie / Get all cookies
            echo "Current Cookies: ".$cookieManager->getCookieString();
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        /**
         * 7. Handling Redirects / 处理重定向
         */
        try {
            $http = new HttpRequestClass('http://example.com/redirect');
            
            // 禁止自动重定向 / Disable auto-redirect
            $http->set()->followLocation = false;
            
            $response = $http->Send()->getResponse();
            
            // 获取重定向位置 / Get redirect location
            if ($response->statusCode >= 300 && $response->statusCode < 400) {
                $location = $response->responseHeadersArray['Location'] ?? '';
                echo "Redirect to: ".$location;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        /**
         * 8. Proxy Configuration / 代理配置
         */
        try {
            $response = (new HttpRequestClass('http://example.com'))
                // 设置代理 / Set proxy
                ->set_proxy('127.0.0.1:8080', 'proxy_user', 'proxy_pass')
                ->Send()
                ->getResponse();
            
            echo $response->body;
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        /**
         * 9. SSL Verification Control / SSL验证控制
         */
        try {
            $response = (new HttpRequestClass('https://example.com'))
                // 禁用SSL验证 / Disable SSL verification
                ->setSslVerification(false, false)
                ->Send()
                ->getResponse();
            
            echo $response->body;
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        /**
         * 10. Custom Headers / 自定义请求头
         */
        try {
            $http = new HttpRequestClass('http://example.com');
            $params = $http->set();
            
            // 设置自定义头 / Set custom headers
            $params->headers_arr = [
                'Authorization' => 'Bearer xyz123',
                'X-Custom-Header' => 'value'
            ];
            
            $response = $http->Send()->getResponse();
            
            // 获取响应头 / Get response headers
            var_dump($response->responseHeadersArray);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        /**
         * 11. Different HTTP Methods / 不同的HTTP方法
         */
        try {
            // PUT请求 / PUT request
            $putResponse = (new HttpRequestClass('https://api.example.com/resource', 3)) // 3 = PUT
                ->Send(['update' => 'new_data'])
                ->getResponse();
            
            // DELETE请求 / DELETE request
            $deleteResponse = (new HttpRequestClass('https://api.example.com/resource', 5)) // 5 = DELETE
                ->Send()
                ->getResponse();
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        /**
         * 12. Timeout Configuration / 超时配置
         */
        try {
            $http = new HttpRequestClass('http://example.com');
            
            // 设置超时时间为5秒 / Set timeout to 5 seconds
            $http->set()->timeout = 5;
            
            $response = $http->Send()->getResponse();
        } catch (Exception $e) {
            echo "Timeout Error: ".$e->getMessage();
        }
    }
}

(new Demo());
