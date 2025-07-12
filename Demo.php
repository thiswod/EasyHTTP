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
         * 简单的GET请求
         * 第一种使用方式
         */
        try {
            $response = (new HttpRequestClass('https://www.example.com'))->Send()->getResponse();
            echo $response->body;// 输出返回的内容
            echo $response->cookieManager->getCookieString().PHP_EOL;// 输出Cookie
            echo $response->Cookie;// 输出Cookie
            var_dump($response);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        /**
         * 第二种GET请求方式
         */
        $HttpGet = new HttpRequestClass();
        try {
            $response = $HttpGet->Open('https://www.example.com')->Send()->getResponse();
            echo $response->body;// 输出返回的内容
            echo $response->cookieManager->getCookieString().PHP_EOL;// 输出Cookie
            echo $response->Cookie;// 输出Cookie
            var_dump($response);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        /**
         * 使用自定义请求头发送GET请求
         */
        try {
            $response = (new HttpRequestClass('https://www.example.com'))
                //设置用户浏览器标识
                ->set_userAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.87 Safari/537.36')
                ->Send();
            echo $response->body;// 输出返回的内容
            echo $response->cookieManager->getCookieString().PHP_EOL;// 输出Cookie
            echo $response->Cookie;// 输出Cookie
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        /**
         * 发送POST请求
         */
        try {
            $response = (new HttpRequestClass('https://www.example.com', 1))
                ->Send(['data' => 'Post request']);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        /**
         * 带Cookie进行请求
         */
        try {
            $response = (new HttpRequestClass('https://www.example.com', 1))
                ->set_Cookie_str('Cookie1=value1; Cookie2=value2')
                ->Send();
            //$response->set_Cookie_str('Cookie1=value1; Cookie2=value2');
            //$response->Send();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        /**
         * 请求后获取响应头
         */
        try {
            $response = (new HttpRequestClass('https://www.example.com', 1))->Send();
            var_dump($response->GetResponse()->responseHeaders);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        /**
         * 不允许URL重定向跳转并且获取 Location
         */
        try {
            $http = new HttpRequestClass('https://www.example.com', 1);
            $http->set()->followLocation = false;
            $response = $http->Send();
            echo $response->getResponse()->responseHeadersArray['Location'];
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

(new Demo());
