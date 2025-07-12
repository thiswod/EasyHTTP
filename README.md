# EasyHTTP Library Documentation

## Language Navigation
[中文文档](Documentation/README_ZH.markdown) | [Documentación en Español](Documentation/README_ES.markdown) | [日本語ドキュメント](Documentation/README_JA.markdown) | [한국어 문서](Documentation/README_KO.markdown) | [Документация на русском](Documentation/README_RU.markdown) | [Dokumentation auf Deutsch](Documentation/README_DE.markdown)

## Overview

The **EasyHTTP** library is a versatile and user-friendly PHP library designed to simplify HTTP request operations. Built with simplicity and functionality in mind, it provides a robust set of features for handling HTTP requests, including GET, POST, PUT, DELETE, and more, while offering advanced capabilities such as cookie management, proxy configuration, SSL verification control, and custom header support. The library leverages PHP's cURL extension to ensure reliable and efficient HTTP communication, making it an excellent choice for developers seeking a lightweight yet powerful solution for web interactions.

This documentation provides a comprehensive guide to the EasyHTTP library, highlighting its ease of use, rich feature set, and practical examples to help developers quickly integrate it into their projects.

## Key Features

The EasyHTTP library is designed to be both intuitive and feature-rich, offering the following capabilities:

- **Flexible HTTP Methods**: Supports a wide range of HTTP methods (GET, POST, HEAD, PUT, OPTIONS, DELETE, TRACE, CONNECT) with simple configuration.
- **Cookie Management**: Provides a dedicated `CookieManager` class for seamless cookie handling, including setting, retrieving, and clearing cookies.
- **Customizable Headers**: Allows both string-based and array-based header configurations, with automatic default headers for convenience.
- **Proxy Support**: Configures proxy settings with optional authentication for secure and flexible network routing.
- **SSL Verification Control**: Enables or disables SSL verification for peer and host, accommodating various security requirements.
- **Redirect Handling**: Supports enabling or disabling automatic redirect following, with access to redirect locations.
- **Timeout Configuration**: Allows setting custom timeout periods to manage request durations effectively.
- **User-Agent Customization**: Simplifies setting custom User-Agent strings for requests.
- **Comprehensive Response Handling**: Returns detailed response data, including status codes, headers, body, and cookies, in a structured format.
- **Error Handling**: Implements robust exception handling to ensure reliable operation and clear error messages.
- **Fluent Interface**: Offers a chainable method design for intuitive and readable code.

## Installation

To use the EasyHTTP library, ensure you have PHP installed with the cURL extension enabled. Follow these steps:

1. Place the `EasyHTTP` directory containing the library files (`HttpRequestClass.php`, `HttpRequestParameter.php`, `HttpResponseData.php`, `CookieManager.php`) in your project directory.
2. Include the `autoload.php` file in your project to automatically load the required classes:

```php
require_once __DIR__ . '/autoload.php';
```

3. Use the `EasyHTTP` namespace to access the library's classes:

```php
use EasyHTTP\HttpRequestClass;
```

## Usage Examples

The following examples demonstrate the library's ease of use and versatility, covering common use cases and advanced features.

### 1. Basic GET Request

Perform a simple GET request to retrieve data from a URL:

```php
use EasyHTTP\HttpRequestClass;

try {
    $response = (new HttpRequestClass('http://example.com'))
        ->Send()
        ->getResponse();
    echo $response->body; // Output response body
} catch (Exception $e) {
    echo $e->getMessage();
}
```

This example showcases the library's straightforward syntax for initiating a GET request and retrieving the response body.

### 2. Alternative GET Request Pattern

The library supports an alternative pattern for configuring requests:

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

The `open` method allows setting the URL and method dynamically, enhancing flexibility.

### 3. GET Request with Custom User-Agent

Customize the User-Agent for a GET request:

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

This feature is useful for mimicking specific browser behaviors or meeting API requirements.

### 4. POST Request with Form Data

Send a POST request with form data:

```php
try {
    $response = (new HttpRequestClass('https://postman-echo.com/post', 1)) // 1 = POST
        ->Send([
            'username' => 'john_doe',
            'password' => 'secure123'
        ])
        ->getResponse();
    echo "POST Response: " . $response->body;
} catch (Exception $e) {
    echo $e->getMessage();
}
```

The library simplifies sending POST data, supporting both array and string formats.

### 5. Cookie Management

Manage cookies for persistent sessions:

```php
try {
    $http = new HttpRequestClass('http://example.com');
    $http->set_Cookie_str('session_id=abc123; user_pref=dark_mode');
    $response = $http->Send()->getResponse();
    echo $response->cookieManager->getCookieString(); // Output cookies
} catch (Exception $e) {
    echo $e->getMessage();
}
```

The `CookieManager` class handles cookie parsing, storage, and retrieval, automatically maintaining cookies across requests.

### 6. Advanced Cookie Management

For more granular control, use the `CookieManager` directly:

```php
try {
    $http = new HttpRequestClass();
    $cookieManager = $http->CookieManager();
    $cookieManager->setCookie('language', 'zh-CN')
                  ->setCookie('theme', 'dark');
    $response = $http->open('http://example.com')
                     ->Send()
                     ->getResponse();
    echo "Current Cookies: " . $cookieManager->getCookieString();
} catch (Exception $e) {
    echo $e->getMessage();
}
```

This allows fine-tuned cookie operations, such as setting or deleting specific cookies.

### 7. Handling Redirects

Control redirect behavior and access redirect information:

```php
try {
    $http = new HttpRequestClass('http://example.com/redirect');
    $http->set()->followLocation = false; // Disable auto-redirect
    $response = $http->Send()->getResponse();
    if ($response->statusCode >= 300 && $response->statusCode < 400) {
        $location = $response->responseHeadersArray['Location'] ?? '';
        echo "Redirect to: " . $location;
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
```

This feature is useful for debugging or manually handling redirects.

### 8. Proxy Configuration

Configure a proxy for requests:

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

The library supports authenticated proxies, making it suitable for secure environments.

### 9. SSL Verification Control

Disable SSL verification for specific scenarios:

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

This flexibility is ideal for testing or environments with self-signed certificates.

### 10. Custom Headers

Set custom headers for requests:

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

The library supports both array-based and string-based headers, with intelligent merging of defaults.

### 11. Timeout Configuration

Set a custom timeout for requests:

```php
try {
    $http = new HttpRequestClass('http://example.com');
    $http->set()->timeout = 5; // Set timeout to 5 seconds
    $response = $http->Send()->getResponse();
    echo $response->body;
} catch (Exception $e) {
    echo "Timeout Error: " . $e->getMessage();
}
```

This ensures requests do not hang indefinitely, improving application reliability.

## Class Structure

The EasyHTTP library consists of four main classes, each with a specific role:

1. **HttpRequestClass**: The core class for initiating and configuring HTTP requests. It provides methods for setting URLs, methods, headers, proxies, and more, with a fluent interface for chaining operations.
2. **HttpRequestParameter**: Manages request parameters, such as URL, method, headers, cookies, and timeouts, allowing fine-grained control over request settings.
3. **HttpResponseData**: Stores response data, including the body, status code, headers, and cookies, in a structured format for easy access.
4. **CookieManager**: Handles cookie operations, including setting, retrieving, and clearing cookies, with support for string and key-value pair formats.

## Error Handling

The library uses PHP's exception handling to manage errors gracefully. Common exceptions include:

- **InvalidArgumentException**: Thrown for invalid URLs or empty POST data.
- **Exception**: Thrown for cURL errors, with detailed error codes and messages.

All methods that interact with the network are wrapped in try-catch blocks in the provided examples, ensuring robust error handling in production code.

## Best Practices

To maximize the library's effectiveness, consider the following:

- **Use Fluent Interface**: Chain methods like `open`, `set`, `Send`, and `getResponse` for readable code.
- **Handle Exceptions**: Always wrap network operations in try-catch blocks to handle errors gracefully.
- **Leverage Cookie Management**: Use the `CookieManager` for session persistence across multiple requests.
- **Set Appropriate Timeouts**: Configure timeouts to prevent long-running requests from blocking your application.
- **Validate URLs**: Ensure URLs are valid before sending requests to avoid exceptions.

## Conclusion

The EasyHTTP library is a powerful and intuitive tool for PHP developers, offering a comprehensive set of features wrapped in a simple, chainable interface. Whether you're performing basic GET requests, managing complex cookie sessions, or configuring proxies and custom headers, EasyHTTP provides the flexibility and reliability needed for modern web development. Its robust error handling, detailed response data, and support for advanced HTTP features make it an ideal choice for both simple scripts and complex applications.

For further details or to contribute to the library, visit [https://github.com/Wod/HttpRequestClass](https://github.com/Wod/HttpRequestClass).
