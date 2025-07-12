# EasyHTTP 库文档

## Language Navigation
[中文文档](README_ZH.markdown) | [Documentación en Español](README_ES.markdown) | [日本語ドキュメント](README_JA.markdown) | [한국어 문서](README_KO.markdown) | [Документация на русском](README_RU.markdown) | [Dokumentation auf Deutsch](README_DE.markdown)

## 概述

**EasyHTTP** 是一个多功能且易于使用的 PHP 库，旨在简化 HTTP 请求操作。该库以简单性和功能性为核心，提供了一系列强大的功能，用于处理包括 GET、POST、PUT、DELETE 等在内的 HTTP 请求，同时支持高级功能，如 Cookie 管理、代理配置、SSL 验证控制和自定义请求头支持。EasyHTTP 利用 PHP 的 cURL 扩展，确保可靠且高效的 HTTP 通信，是开发者寻求轻量级但功能强大的网络交互解决方案的理想选择。

本文档提供了 EasyHTTP 库的全面指南，重点介绍其易用性、丰富的功能集以及实际示例，帮助开发者快速将其集成到项目中。

## 主要功能

EasyHTTP 库设计直观且功能丰富，提供以下功能：

- **灵活的 HTTP 方法**：支持多种 HTTP 方法（GET、POST、HEAD、PUT、OPTIONS、DELETE、TRACE、CONNECT），配置简单。
- **Cookie 管理**：提供专用的 `CookieManager` 类，轻松处理 Cookie 的设置、获取和清除。
- **可自定义请求头**：支持基于字符串和数组的请求头配置，并自动添加默认请求头以提高便利性。
- **代理支持**：支持代理设置及可选的身份验证，适用于安全和灵活的网络路由。
- **SSL 验证控制**：支持启用或禁用对等方和主机的 SSL 验证，适应不同的安全需求。
- **重定向处理**：支持启用或禁用自动重定向，并可访问重定向位置。
- **超时配置**：允许设置自定义超时时间，有效管理请求时长。
- **用户代理自定义**：简化请求的用户代理字符串设置。
- **全面的响应处理**：以结构化格式返回详细的响应数据，包括状态码、头信息、正文和 Cookie。
- **错误处理**：实现强大的异常处理，确保操作可靠并提供清晰的错误信息。
- **流式接口**：提供可链式调用的方法设计，使代码直观且易读。

## 安装

使用 EasyHTTP 库需要安装 PHP 并启用 cURL 扩展。请按照以下步骤操作：

1. 将包含库文件（`HttpRequestClass.php`、`HttpRequestParameter.php`、`HttpResponseData.php`、`CookieManager.php`）的 `EasyHTTP` 目录放置在项目目录中。
2. 在项目中引入 `autoload.php` 文件以自动加载所需类：

```php
require_once __DIR__ . '/autoload.php';
```

3. 使用 `EasyHTTP` 命名空间访问库的类：

```php
use EasyHTTP\HttpRequestClass;
```

## 使用示例

以下示例展示了库的易用性和多功能性，涵盖常见用例和高级功能。

### 1. 基本 GET 请求

执行一个简单的 GET 请求以从 URL 获取数据：

```php
use EasyHTTP\HttpRequestClass;

try {
    $response = (new HttpRequestClass('http://example.com'))
        ->Send()
        ->getResponse();
    echo $response->body; // 输出响应正文
} catch (Exception $e) {
    echo $e->getMessage();
}
```

此示例展示了库用于发起 GET 请求并获取响应正文的简单语法。

### 2. 替代 GET 请求模式

库支持另一种配置请求的模式：

```php
$http = new HttpRequestClass();
try {
    $response = $http->open('http://example.com')
        ->Send()
        ->getResponse();
    echo $response->body;
} catch (Exception $e) {
    echo $e->getMessage();
}
```

`open` 方法允许动态设置 URL 和方法，增强灵活性。

### 3. 带自定义用户代理的 GET 请求

为 GET 请求自定义用户代理：

```php
try {
    $response = (new HttpRequestClass('http://example.com'))
        ->set_userAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64)')
        ->Send()
        ->getResponse();
    echo $response->body;
} catch (Exception $e) {
    echo $e->getMessage();
}
```

此功能适用于模拟特定浏览器行为或满足 API 要求。

### 4. 带表单数据的 POST 请求

发送带表单数据的 POST 请求：

```php
try {
    $response = (new HttpRequestClass('https://postman-echo.com/post', 1)) // 1 = POST
        ->Send([
            'username' => 'john_doe',
            'password' => 'secure123'
        ])
        ->getResponse();
    echo "POST 响应: " . $response->body;
} catch (Exception $e) {
    echo $e->getMessage();
}
```

库简化了 POST 数据的发送，支持数组和字符串格式。

### 5. Cookie 管理

管理 Cookie 以实现持久会话：

```php
try {
    $http = new HttpRequestClass('http://example.com');
    $http->set_Cookie_str('session_id=abc123; user_pref=dark_mode');
    $response = $http->Send()->getResponse();
    echo $response->cookieManager->getCookieString(); // 输出 Cookie
} catch (Exception $e) {
    echo $e->getMessage();
}
```

`CookieManager` 类处理 Cookie 的解析、存储和检索，自动在多个请求中维护 Cookie。

### 6. 高级 Cookie 管理

如需更精细的控制，可直接使用 `CookieManager`：

```php
try {
    $http = new HttpRequestClass();
    $cookieManager = $http->CookieManager();
    $cookieManager->setCookie('language', 'zh-CN')
                  ->setCookie('theme', 'dark');
    $response = $http->open('http://example.com')
                     ->Send()
                     ->getResponse();
    echo "当前 Cookie: " . $cookieManager->getCookieString();
} catch (Exception $e) {
    echo $e->getMessage();
}
```

这允许对 Cookie 进行细粒度操作，如设置或删除特定 Cookie。

### 7. 处理重定向

控制重定向行为并访问重定向信息：

```php
try {
    $http = new HttpRequestClass('http://example.com/redirect');
    $http->set()->followLocation = false; // 禁用自动重定向
    $response = $http->Send()->getResponse();
    if ($response->statusCode >= 300 && $response->statusCode < 400) {
        $location = $response->responseHeadersArray['Location'] ?? '';
        echo "重定向至: " . $location;
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
```

此功能适用于调试或手动处理重定向。

### 8. 代理配置

为请求配置代理：

```php
try {
    $response = (new HttpRequestClass('http://example.com'))
        ->set_proxy('127.0.0.1:8080', 'proxy_user', 'proxy_pass')
        ->Send()
        ->getResponse();
    echo $response->body;
} catch (Exception $e) {
    echo $e->getMessage();
}
```

库支持带身份验证的代理，适用于安全环境。

### 9. SSL 验证控制

在特定场景下禁用 SSL 验证：

```php
try {
    $response = (new HttpRequestClass('https://example.com'))
        ->setSslVerification(false, false)
        ->Send()
        ->getResponse();
    echo $response->body;
} catch (Exception $e) {
    echo $e->getMessage();
}
```

这种灵活性适用于测试或使用自签名证书的环境。

### 10. 自定义请求头

为请求设置自定义头：

```php
try {
    $http = new HttpRequestClass('http://example.com');
    $http->set()->headers_arr = [
        'Authorization' => 'Bearer xyz123',
        'X-Custom-Header' => 'value'
    ];
    $response = $http->Send()->getResponse();
    var_dump($response->responseHeadersArray);
} catch (Exception $e) {
    echo $e->getMessage();
}
```

库支持基于数组和字符串的请求头，智能合并默认头。

### 11. 超时配置

为请求设置自定义超时：

```php
try {
    $http = new HttpRequestClass('http://example.com');
    $http->set()->timeout = 5; // 设置超时为 5 秒
    $response = $http->Send()->getResponse();
    echo $response->body;
} catch (Exception $e) {
    echo "超时错误: " . $e->getMessage();
}
```

这确保请求不会无限期挂起，提高应用程序可靠性。

## 类结构

EasyHTTP 库包含四个主要类，每个类都有特定作用：

1. **HttpRequestClass**：发起和配置 HTTP 请求的核心类，提供设置 URL、方法、头、代理等方法，支持链式操作的流式接口。
2. **HttpRequestParameter**：管理请求参数，如 URL、方法、头、Cookie 和超时，允许对请求设置进行细粒度控制。
3. **HttpResponseData**：以结构化格式存储响应数据，包括正文、状态码、头信息和 Cookie，便于访问。
4. **CookieManager**：处理 Cookie 操作，包括设置、获取和清除 Cookie，支持字符串和键值对格式。

## 错误处理

库使用 PHP 的异常处理机制优雅地管理错误。常见异常包括：

- **InvalidArgumentException**：因无效 URL 或空的 POST 数据抛出。
- **Exception**：因 cURL 错误抛出，包含详细的错误代码和消息。

所有与网络交互的方法在提供的示例中都包裹在 try-catch 块中，确保生产代码中的健壮错误处理。

## 最佳实践

为最大化库的效用，请考虑以下建议：

- **使用流式接口**：链式调用 `open`、`set`、`Send` 和 `getResponse` 等方法，以获得可读性强的代码。
- **处理异常**：始终将网络操作包裹在 try-catch 块中，以优雅地处理错误。
- **利用 Cookie 管理**：使用 `CookieManager` 实现多个请求的会话持久性。
- **设置适当的超时**：配置超时以防止长时间运行的请求阻塞应用程序。
- **验证 URL**：在发送请求前确保 URL 有效，以避免异常。

## 结论

EasyHTTP 库是 PHP 开发者的强大且直观的工具，提供了一套全面的功能，封装在简单、链式的接口中。无论是执行基本的 GET 请求、管理复杂的 Cookie 会话，还是配置代理和自定义头，EasyHTTP 提供了现代 Web 开发所需的灵活性和可靠性。其强大的错误处理、详细的响应数据和对高级 HTTP 功能的支持使其成为简单脚本和复杂应用的理想选择。

如需更多详情或为库贡献代码，请访问 [https://github.com/thiswod/EasyHTTP](https://github.com/thiswod/EasyHTTP)。
