# HttpRequestClass Usage Documentation

This document provides a comprehensive guide for using `HttpRequestClass` and its related classes (`CookieManager`, `HttpRequestParameter`, `HttpResponseData`) in PHP. These classes are designed to simplify HTTP request operations, supporting methods like GET, POST, and offering cookie, header, proxy, and SSL configuration features.

## Table of Contents
1. [Overview](#overview)
2. [Directory Structure](#directory-structure)
3. [Key Classes and Their Purposes](#key-classes-and-their-purposes)
4. [Installation and Setup](#installation-and-setup)
5. [Basic Usage](#basic-usage)
    - [Initiate GET Request](#initiate-get-request)
    - [Initiate POST Request](#initiate-post-request)
    - [Set Custom Headers](#set-custom-headers)
    - [Manage Cookies](#manage-cookies)
    - [Configure Proxy](#configure-proxy)
    - [SSL Verification](#ssl-verification)
6. [Advanced Usage](#advanced-usage)
    - [Custom HTTP Methods](#custom-http-methods)
    - [Handle Redirects](#handle-redirects)
    - [Set Timeout](#set-timeout)
    - [Custom DNS Resolution](#custom-dns-resolution)
7. [Error Handling](#error-handling)
8. [Example Code](#example-code)
9. [Class Reference](#class-reference)
    - [HttpRequestClass](#httprequestclass)
    - [CookieManager](#cookiemanager)
    - [HttpRequestParameter](#httprequestparameter)
    - [HttpResponseData](#httpresponsedata)

## Overview

`HttpRequestClass` is a PHP-based class that utilizes the cURL library to execute HTTP requests. It offers a flexible chainable interface for configuring requests, managing cookies, setting headers, and handling responses. Supporting classes include `CookieManager` (for cookie handling), `HttpRequestParameter` (for storing request parameters), and `HttpResponseData` (for storing response data).

Key features include:
- Support for multiple HTTP methods (GET, POST, HEAD, PUT, OPTIONS, DELETE, TRACE, CONNECT).
- Cookie management via `CookieManager`.
- Flexible header configuration in string or array format.
- Proxy support (with authentication).
- SSL verification options.
- Automatic handling of response headers, cookies, and body content.

## Directory Structure

The project is organized as follows:

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

- **`EasyHTTP/`**: Root directory containing subdirectories and key files.
  - **`EasyHTTP/`**: Houses core PHP classes.
    - **`CookieManager.php`**: Manages cookie operations.
    - **`HttpRequestClass.php`**: Core request execution class.
    - **`HttpRequestParameter.php`**: Manages request parameters.
    - **`HttpResponseData.php`**: Stores response data.
  - **`Demo.php`**: Located in the root directory, contains usage examples.
  - **`autoload.php`**: Located in the root directory, implements SPL autoloading.

## Key Classes and Their Purposes

- **HttpRequestClass**: Main class for initiating and sending HTTP requests, managing cURL handles and coordinating other classes.
- **CookieManager**: Handles cookie storage, setting, and retrieval, supporting single cookies and cookie strings.
- **HttpRequestParameter**: Stores request parameters (e.g., URL, method, headers, proxy settings), providing a chainable configuration interface.
- **HttpResponseData**: Stores response data, including status code, headers, body, and cookies.

## Installation and Setup

1. Ensure the server has PHP installed with the cURL extension enabled.
2. Save the provided PHP code as a file (e.g., `HttpRequestClass.php`).
3. Include the file in your PHP project:

```php
require_once 'HttpRequestClass.php';
```

## Basic Usage

### Initiate GET Request

Execute a simple GET request:

```php
try {
    $response = (new HttpRequestClass("https://www.example.com"))->Send()->GetResponse();
    echo $response->body; // Outputs response body
    echo $response->cookieManager->getCookieString(); // Outputs cookies
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

Or use the `open` method:

```php
$http = new HttpRequestClass();
$http->open("https://www.example.com");
$http->Send();
echo $http->GetResponse()->body; // Outputs response body
```

### Initiate POST Request

Execute a POST request with data:

```php
try {
    $response = (new HttpRequestClass("https://www.example.com", 1))
        ->Send(["KeyWord" => "Post request"])
        ->GetResponse();
    echo $response->body; // Outputs response body
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

### Set Custom Headers

Set headers using an array or string:

```php
$http = new HttpRequestClass("https://www.example.com");
$http->set()->set_headers_arr([
    "Content-Type: application/json",
    "Authorization: Bearer token123"
]);
$response = $http->Send()->GetResponse();
```

### Manage Cookies

Set cookies using `CookieManager`:

```php
$http = new HttpRequestClass("https://www.example.com");
$http->CookieManager()->setCookie("session_id", "abc123"); // Sets a single cookie
$http->CookieManager()->setCookieString("user_id=xyz789; theme=dark"); // Sets cookie string
$response = $http->Send()->GetResponse();
echo $response->cookieManager->getCookieString(); // Outputs: session_id=abc123; user_id=xyz789; theme=dark
```

Clear cookies:

```php
$http->CookieManager()->clearCookie();
```

### Configure Proxy

Set a proxy (with optional authentication):

```php
$http = new HttpRequestClass("https://www.example.com");
$http->set_proxy("127.0.0.1:7890", "username", "password");
$response = $http->Send()->GetResponse();
```

### SSL Verification

Enable or disable SSL verification:

```php
$http = new HttpRequestClass("https://www.example.com");
$http->setSslVerification(false, false); // Disable SSL verification
$response = $http->Send()->GetResponse();
```

## Advanced Usage

### Custom HTTP Methods

Support for various HTTP methods, specified via the `$method` parameter (0=GET, 1=POST, etc.):

```php
$http = new HttpRequestClass("https://www.example.com", 3); // PUT request
$http->Send(["data" => "value"])->GetResponse();
```

### Handle Redirects

Enable following redirects:

```php
$http = new HttpRequestClass("https://www.example.com");
$http->set()->set_followLocation(true);
$response = $http->Send()->GetResponse();
```

### Set Timeout

Set request timeout (in seconds):

```php
$http = new HttpRequestClass("https://www.example.com");
$http->set()->timeout = 30; // 30 seconds
$response = $http->Send()->GetResponse();
```

### Custom DNS Resolution

Specify custom DNS resolution:

```php
$http = new HttpRequestClass("https://www.example.com");
$http->set()->hosts = ["example.com:443:93.184.216.34"];
$response = $http->Send()->GetResponse();
```

## Error Handling

The classes throw exceptions for invalid inputs or cURL errors. Always use a `try-catch` block:

```php
try {
    $response = (new HttpRequestClass("invalid-url"))->Send()->GetResponse();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

Common exceptions:
- `InvalidArgumentException`: Invalid URL or empty POST data.
- `Exception`: cURL errors, including error code and message.

## Example Code

The following is a comprehensive example demonstrating multiple features:

```php
try {
    // Initialize request
    $http = new HttpRequestClass("https://www.example.com", 1); // POST request
    $http->set_userAgent("CustomAgent/1.0"); // Set User-Agent
    $http->set()->set_headers_arr([ // Set headers
        "Content-Type: application/json"
    ]);
    $http->CookieManager()->setCookie("user", "john_doe"); // Set cookie
    $http->set_proxy("127.0.0.1:7890"); // Set proxy
    $http->set()->set_followLocation(true); // Enable redirect
    $http->set()->timeout = 20; // Set timeout

    // Send request with data
    $response = $http->Send(["key" => "value"])->GetResponse();

    // Output results
    echo "Status Code: " . $response->statusCode . "\n";
    echo "Response Body: " . $response->body . "\n";
    echo "Cookies: " . $response->cookieManager->getCookieString() . "\n";
    echo "Response Headers: " . print_r($response->responseHeadersArray, true) . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

## Class Reference

### HttpRequestClass

- **Constructor**: `HttpRequestClass(string|null $url, int $method=0, mixed &$cookieManager=null)`
- **Methods**:
  - `open(string|null $url, int $method=0)`: Sets URL and method.
  - `set()`: Accesses `HttpRequestParameter` for configuration.
  - `Send(string|array|null $data=null)`: Sends request.
  - `GetResponse()`: Retrieves response data.
  - `CookieManager()`: Accesses `CookieManager`.
  - `set_userAgent(string $userAgent)`: Sets User-Agent.
  - `set_Cookie_str(string $cookie)`: Sets cookie string.
  - `set_proxy(string $ip, string $user, string $pwd)`: Configures proxy.
  - `setSslVerification(bool $verifyPeer, bool $verifyHost)`: Configures SSL verification.
  - `bindcookie(mixed &$cookieManager)`: Binds external cookie manager (syncs with internal `CookieManager`).

### CookieManager

- **Constructor**: `CookieManager()`
- **Methods**:
  - `setCookie(string $name, string $value)`: Sets a single cookie.
  - `setCookieString(string $string)`: Parses and sets cookies from a string.
  - `getCookieString()`: Retrieves all cookies as a string.
  - `clearCookie()`: Clears all cookies.

### HttpRequestParameter

- **Properties**:
  - `url`: Request URL.
  - `method`: HTTP method (0=GET, 1=POST, etc.).
  - `data`: POST data.
  - `headers`: Headers as a string.
  - `headers_arr`: Headers as an array.
  - `CookieManager`: Cookie manager instance.
  - `timeout`: Request timeout.
  - `proxy`, `proxyUsername`, `proxyPassword`: Proxy settings.
  - `followLocation`: Enable/disable redirects.
  - `completeProtocolHeaders`: Enable/disable default headers.
  - `hosts`: Custom DNS resolutions.
- **Methods**:
  - `send(string|array|null $data)`: Sends request via parent `HttpRequestClass`.
  - `set_proxyUsername(string $parm)`: Sets proxy username.
  - `set_followLocation(bool $parm)`: Sets redirect behavior.
  - `set_headers_arr(array $parm)`: Sets headers array.

### HttpResponseData

- **Properties**:
  - `statusCode`: HTTP status code.
  - `requestHeaders`: Request headers as a string.
  - `requestHeadersArray`: Request headers as an array.
  - `responseHeaders`: Response headers as a string.
  - `responseHeadersArray`: Response headers as an array.
  - `body`: Response body.
  - `cookieManager`: Cookie manager instance.
  - `Cookie`: Cookie string.
