# HttpRequestClass 使用文档

本文档为 PHP 中使用 `HttpRequestClass` 及其相关类（`CookieManager`、`HttpRequestParameter`、`HttpResponseData`）提供全面指南。这些类旨在简化 HTTP 请求操作，支持 GET、POST 等方法，并提供 cookie、头信息、代理和 SSL 配置功能。

## 目录
1. [概述](#概述)
2. [目录结构](#目录结构)
3. [关键类及其用途](#关键类及其用途)
4. [安装与设置](#安装与设置)
5. [基本使用](#基本使用)
    - [发起 GET 请求](#发起-get-请求)
    - [发起 POST 请求](#发起-post-请求)
    - [设置自定义头信息](#设置自定义头信息)
    - [管理 cookie](#管理-cookie)
    - [配置代理](#配置代理)
    - [SSL 验证](#ssl-验证)
6. [高级使用](#高级使用)
    - [自定义 HTTP 方法](#自定义-http-方法)
    - [处理重定向](#处理重定向)
    - [设置超时](#设置超时)
    - [自定义 DNS 解析](#自定义-dns-解析)
7. [错误处理](#错误处理)
8. [示例代码](#示例代码)
9. [类参考](#类参考)
    - [HttpRequestClass](#httprequestclass)
    - [CookieManager](#cookiemanager)
    - [HttpRequestParameter](#httprequestparameter)
    - [HttpResponseData](#httpresponsedata)

## 概述

`HttpRequestClass` 是一个基于 PHP 的类，利用 cURL 库执行 HTTP 请求。它提供灵活的链式接口，用于配置请求、管理 cookie、设置头信息和处理响应。辅助类包括 `CookieManager`（处理 cookie）、`HttpRequestParameter`（存储请求参数）和 `HttpResponseData`（存储响应数据）。

主要特性包括：
- 支持多种 HTTP 方法（GET、POST、HEAD、PUT、OPTIONS、DELETE、TRACE、CONNECT）。
- 通过 `CookieManager` 实现 cookie 管理。
- 支持字符串或数组格式的灵活头信息配置。
- 提供代理支持（含认证）。
- 支持 SSL 验证选项。
- 自动处理响应头、cookie 和主体内容。

## 目录结构

项目组织结构如下：

```
EasyHTTP
├── EasyHTTP
│   ├── CookieManager.php
│   ├── HttpRequestClass.php
│   ├── HttpRequestParameter.php
│   └── HttpResponseData.php
├── Demo.php
└── autoload.php
```

- **`EasyHTTP/`**：根目录，包含子目录和关键文件。
    - **`EasyHTTP/`**：存放核心 PHP 类。
        - **`CookieManager.php`**：管理 cookie 操作。
        - **`HttpRequestClass.php`**：核心请求执行类。
        - **`HttpRequestParameter.php`**：管理请求参数。
        - **`HttpResponseData.php`**：存储响应数据。
    - **`Demo.php`**：位于根目录，包含使用示例。
    - **`autoload.php`**：位于根目录，实现 SPL 自动加载。

## 关键类及其用途

- **HttpRequestClass**：主类，用于发起和发送 HTTP 请求，管理 cURL 句柄并协调其他类。
- **CookieManager**：处理 cookie 存储、设置和获取，支持单个 cookie 和 cookie 字符串。
- **HttpRequestParameter**：存储请求参数（如 URL、方法、头信息、代理设置），提供链式配置接口。
- **HttpResponseData**：存储响应数据，包括状态码、头信息、主体和 cookie。

## 安装与设置

1. 确保服务器安装 PHP 并启用 cURL 扩展。
2. 将提供的 PHP 代码保存为文件（例如 `HttpRequestClass.php`）。
3. 在 PHP 项目中包含文件：

```php
require_once 'HttpRequestClass.php';
```

## 基本使用

### 发起 GET 请求

执行简单 GET 请求：

```php
try {
    $response = (new HttpRequestClass("https://www.example.com"))->Send()->GetResponse();
    echo $response->body; // 输出响应主体
    echo $response->cookieManager->getCookieString(); // 输出 cookie
} catch (Exception $e) {
    echo "错误: " . $e->getMessage();
}
```

或使用 `open` 方法：

```php
$http = new HttpRequestClass();
$http->open("https://www.example.com");
$http->Send();
echo $http->GetResponse()->body; // 输出响应主体
```

### 发起 POST 请求

执行带数据的 POST 请求：

```php
try {
    $response = (new HttpRequestClass("https://www.example.com", 1))
        ->Send(["KeyWord" => "Post request"])
        ->GetResponse();
    echo $response->body; // 输出响应主体
} catch (Exception $e) {
    echo "错误: " . $e->getMessage();
}
```

### 设置自定义头信息

使用数组或字符串设置头信息：

```php
$http = new HttpRequestClass("https://www.example.com");
$http->set()->set_headers_arr([
    "Content-Type: application/json",
    "Authorization: Bearer token123"
]);
$response = $http->Send()->GetResponse();
```

### 管理 cookie

使用 `CookieManager` 设置 cookie：

```php
$http = new HttpRequestClass("https://www.example.com");
$http->CookieManager()->setCookie("session_id", "abc123"); // 设置单个 cookie
$http->CookieManager()->setCookieString("user_id=xyz789; theme=dark"); // 设置 cookie 字符串
$response = $http->Send()->GetResponse();
echo $response->cookieManager->getCookieString(); // 输出: session_id=abc123; user_id=xyz789; theme=dark
```

清除 cookie：

```php
$http->CookieManager()->clearCookie();
```

### 配置代理

设置代理（可选认证）：

```php
$http = new HttpRequestClass("https://www.example.com");
$http->set_proxy("127.0.0.1:7890", "username", "password");
$response = $http->Send()->GetResponse();
```

### SSL 验证

启用或禁用 SSL 验证：

```php
$http = new HttpRequestClass("https://www.example.com");
$http->setSslVerification(false, false); // 禁用 SSL 验证
$response = $http->Send()->GetResponse();
```

## 高级使用

### 自定义 HTTP 方法

支持多种 HTTP 方法，通过 `$method` 参数指定（0=GET、1=POST 等）：

```php
$http = new HttpRequestClass("https://www.example.com", 3); // PUT 请求
$http->Send(["data" => "value"])->GetResponse();
```

### 处理重定向

启用跟随重定向：

```php
$http = new HttpRequestClass("https://www.example.com");
$http->set()->set_followLocation(true);
$response = $http->Send()->GetResponse();
```

### 设置超时

设置请求超时（秒）：

```php
$http = new HttpRequestClass("https://www.example.com");
$http->set()->timeout = 30; // 30 秒
$response = $http->Send()->GetResponse();
```

### 自定义 DNS 解析

指定自定义 DNS 解析：

```php
$http = new HttpRequestClass("https://www.example.com");
$http->set()->hosts = ["example.com:443:93.184.216.34"];
$response = $http->Send()->GetResponse();
```

## 错误处理

类在无效输入或 cURL 错误时抛出异常。始终使用 `try-catch` 块：

```php
try {
    $response = (new HttpRequestClass("invalid-url"))->Send()->GetResponse();
} catch (Exception $e) {
    echo "错误: " . $e->getMessage();
}
```

常见异常：
- `InvalidArgumentException`：无效 URL 或空 POST 数据。
- `Exception`：cURL 错误，包含错误码和消息。

## 示例代码

以下是一个综合示例，展示多种功能：

```php
try {
    // 初始化请求
    $http = new HttpRequestClass("https://www.example.com", 1); // POST 请求
    $http->set_userAgent("CustomAgent/1.0"); // 设置 User-Agent
    $http->set()->set_headers_arr([ // 设置头信息
        "Content-Type: application/json"
    ]);
    $http->CookieManager()->setCookie("user", "john_doe"); // 设置 cookie
    $http->set_proxy("127.0.0.1:7890"); // 设置代理
    $http->set()->set_followLocation(true); // 启用重定向
    $http->set()->timeout = 20; // 设置超时

    // 发送带数据的请求
    $response = $http->Send(["key" => "value"])->GetResponse();

    // 输出结果
    echo "状态码: " . $response->statusCode . "\n";
    echo "响应主体: " . $response->body . "\n";
    echo "Cookies: " . $response->cookieManager->getCookieString() . "\n";
    echo "响应头: " . print_r($response->responseHeadersArray, true) . "\n";
} catch (Exception $e) {
    echo "错误: " . $e->getMessage();
}
```

## 类参考

### HttpRequestClass

- **构造函数**：`HttpRequestClass(string|null $url, int $method=0, mixed &$cookieManager=null)`
- **方法**：
    - `open(string|null $url, int $method=0)`：设置 URL 和方法。
    - `set()`：访问 `HttpRequestParameter` 进行配置。
    - `Send(string|array|null $data=null)`：发送请求。
    - `GetResponse()`：获取响应数据。
    - `CookieManager()`：访问 `CookieManager`。
    - `set_userAgent(string $userAgent)`：设置 User-Agent。
    - `set_Cookie_str(string $cookie)`：设置 cookie 字符串。
    - `set_proxy(string $ip, string $user, string $pwd)`：配置代理。
    - `setSslVerification(bool $verifyPeer, bool $verifyHost)`：配置 SSL 验证。
    - `bindcookie(mixed &$cookieManager)`：绑定外部 cookie 管理器（与内部 `CookieManager` 同步）。

### CookieManager

- **构造函数**：`CookieManager()`
- **方法**：
    - `setCookie(string $name, string $value)`：设置单个 cookie。
    - `setCookieString(string $string)`：从字符串解析并设置 cookie。
    - `getCookieString()`：以字符串形式获取所有 cookie。
    - `clearCookie()`：清除所有 cookie。

### HttpRequestParameter

- **属性**：
    - `url`：请求 URL。
    - `method`：HTTP 方法（0=GET、1=POST 等）。
    - `data`：POST 数据。
    - `headers`：头信息字符串。
    - `headers_arr`：头信息数组。
    - `CookieManager`：cookie 管理器实例。
    - `timeout`：请求超时。
    - `proxy`, `proxyUsername`, `proxyPassword`：代理设置。
    - `followLocation`：启用/禁用重定向。
    - `completeProtocolHeaders`：启用/禁用默认头信息。
    - `hosts`：自定义 DNS 解析。
- **方法**：
    - `send(string|array|null $data)`：通过父类 `HttpRequestClass` 发送请求。
    - `set_proxyUsername(string $parm)`：设置代理用户名。
    - `set_followLocation(bool $parm)`：设置重定向行为。
    - `set_headers_arr(array $parm)`：设置头信息数组。

### HttpResponseData

- **属性**：
    - `statusCode`：HTTP 状态码。
    - `requestHeaders`：请求头信息字符串。
    - `requestHeadersArray`：请求头信息数组。
    - `responseHeaders`：响应头信息字符串。
    - `responseHeadersArray`：响应头信息数组。
    - `body`：响应主体。
    - `cookieManager`：cookie 管理器实例。
    - `Cookie`：cookie 字符串。
