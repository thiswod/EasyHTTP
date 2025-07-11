# HttpRequestClass 使用文檔

此文件為 PHP 中使用 `HttpRequestClass` 及其相關類（`CookieManager`、`HttpRequestParameter`、`HttpResponseData`）提供全面指南。這些類旨在簡化 HTTP 請求操作，支持 GET、POST 等方法，並提供 cookie、頭資訊、代理和 SSL 配置功能。

## 目錄
1. [概述](#概述)
2. [目錄結構](#目錄結構)
3. [關鍵類及其用途](#關鍵類及其用途)
4. [安裝與設定](#安裝與設定)
5. [基本使用](#基本使用)
    - [發起 GET 請求](#發起-get-請求)
    - [發起 POST 請求](#發起-post-請求)
    - [設定自訂頭資訊](#設定自訂頭資訊)
    - [管理 cookie](#管理-cookie)
    - [配置代理](#配置代理)
    - [SSL 驗證](#ssl-驗證)
6. [進階使用](#進階使用)
    - [自訂 HTTP 方法](#自訂-http-方法)
    - [處理重新導向](#處理重新導向)
    - [設定超時](#設定超時)
    - [自訂 DNS 解析](#自訂-dns-解析)
7. [錯誤處理](#錯誤處理)
8. [範例程式碼](#範例程式碼)
9. [類參考](#類參考)
    - [HttpRequestClass](#httprequestclass)
    - [CookieManager](#cookiemanager)
    - [HttpRequestParameter](#httprequestparameter)
    - [HttpResponseData](#httpresponsedata)

## 概述

`HttpRequestClass` 是一個基於 PHP 的類，利用 cURL 庫執行 HTTP 請求。它提供靈活的鏈式介面，用於配置請求、管理 cookie、設定頭資訊和處理回應。輔助類包括 `CookieManager`（處理 cookie）、`HttpRequestParameter`（儲存請求參數）和 `HttpResponseData`（儲存回應數據）。

主要特性包括：
- 支持多種 HTTP 方法（GET、POST、HEAD、PUT、OPTIONS、DELETE、TRACE、CONNECT）。
- 通過 `CookieManager` 實現 cookie 管理。
- 支持字符串或陣列格式的靈活頭資訊配置。
- 提供代理支持（含認證）。
- 支持 SSL 驗證選項。
- 自動處理回應頭、cookie 和主體內容。

## 目錄結構

項目組織結構如下：

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

- **`EasyHTTP/`**：根目錄，包含子目錄和關鍵文件。
  - **`EasyHTTP/`**：存放核心 PHP 類。
    - **`CookieManager.php`**：管理 cookie 操作。
    - **`HttpRequestClass.php`**：核心請求執行類。
    - **`HttpRequestParameter.php`**：管理請求參數。
    - **`HttpResponseData.php`**：儲存回應數據。
  - **`Demo.php`**：位於根目錄，包含使用範例。
  - **`autoload.php`**：位於根目錄，實現 SPL 自動載入。

## 關鍵類及其用途

- **HttpRequestClass**：主類，用於發起和發送 HTTP 請求，管理 cURL 句柄並協調其他類。
- **CookieManager**：處理 cookie 儲存、設定和獲取，支持單一 cookie 和 cookie 字符串。
- **HttpRequestParameter**：儲存請求參數（如 URL、方法、頭資訊、代理設定），提供鏈式配置介面。
- **HttpResponseData**：儲存回應數據，包括狀態碼、頭資訊、主體和 cookie。

## 安裝與設定

1. 確保伺服器安裝 PHP 並啟用 cURL 擴展。
2. 將提供的 PHP 程式碼儲存為文件（例如 `HttpRequestClass.php`）。
3. 在 PHP 專案中包含文件：

```php
require_once 'HttpRequestClass.php';
```

## 基本使用

### 發起 GET 請求

執行簡單 GET 請求：

```php
try {
    $response = (new HttpRequestClass("https://www.example.com"))->Send()->GetResponse();
    echo $response->body; // 輸出回應主體
    echo $response->cookieManager->getCookieString(); // 輸出 cookie
} catch (Exception $e) {
    echo "錯誤: " . $e->getMessage();
}
```

或使用 `open` 方法：

```php
$http = new HttpRequestClass();
$http->open("https://www.example.com");
$http->Send();
echo $http->GetResponse()->body; // 輸出回應主體
```

### 發起 POST 請求

執行帶資料的 POST 請求：

```php
try {
    $response = (new HttpRequestClass("https://www.example.com", 1))
        ->Send(["KeyWord" => "Post request"])
        ->GetResponse();
    echo $response->body; // 輸出回應主體
} catch (Exception $e) {
    echo "錯誤: " . $e->getMessage();
}
```

### 設定自訂頭資訊

使用陣列或字符串設定頭資訊：

```php
$http = new HttpRequestClass("https://www.example.com");
$http->set()->set_headers_arr([
    "Content-Type: application/json",
    "Authorization: Bearer token123"
]);
$response = $http->Send()->GetResponse();
```

### 管理 cookie

使用 `CookieManager` 設定 cookie：

```php
$http = new HttpRequestClass("https://www.example.com");
$http->CookieManager()->setCookie("session_id", "abc123"); // 設定單一 cookie
$http->CookieManager()->setCookieString("user_id=xyz789; theme=dark"); // 設定 cookie 字符串
$response = $http->Send()->GetResponse();
echo $response->cookieManager->getCookieString(); // 輸出: session_id=abc123; user_id=xyz789; theme=dark
```

清除 cookie：

```php
$http->CookieManager()->clearCookie();
```

### 配置代理

設定代理（可選認證）：

```php
$http = new HttpRequestClass("https://www.example.com");
$http->set_proxy("127.0.0.1:7890", "username", "password");
$response = $http->Send()->GetResponse();
```

### SSL 驗證

啟用或禁用 SSL 驗證：

```php
$http = new HttpRequestClass("https://www.example.com");
$http->setSslVerification(false, false); // 禁用 SSL 驗證
$response = $http->Send()->GetResponse();
```

## 進階使用

### 自訂 HTTP 方法

支持多種 HTTP 方法，通過 `$method` 參數指定（0=GET、1=POST 等）：

```php
$http = new HttpRequestClass("https://www.example.com", 3); // PUT 請求
$http->Send(["data" => "value"])->GetResponse();
```

### 處理重新導向

啟用跟隨重新導向：

```php
$http = new HttpRequestClass("https://www.example.com");
$http->set()->set_followLocation(true);
$response = $http->Send()->GetResponse();
```

### 設定超時

設定請求超時（秒）：

```php
$http = new HttpRequestClass("https://www.example.com");
$http->set()->timeout = 30; // 30 秒
$response = $http->Send()->GetResponse();
```

### 自訂 DNS 解析

指定自訂 DNS 解析：

```php
$http = new HttpRequestClass("https://www.example.com");
$http->set()->hosts = ["example.com:443:93.184.216.34"];
$response = $http->Send()->GetResponse();
```

## 錯誤處理

類在無效輸入或 cURL 錯誤時拋出異常。始終使用 `try-catch` 塊：

```php
try {
    $response = (new HttpRequestClass("invalid-url"))->Send()->GetResponse();
} catch (Exception $e) {
    echo "錯誤: " . $e->getMessage();
}
```

常見異常：
- `InvalidArgumentException`: 無效 URL 或空 POST 數據。
- `Exception`: cURL 錯誤，包含錯誤碼和訊息。

## 範例程式碼

以下是一個綜合範例，展示多種功能：

```php
try {
    // 初始化請求
    $http = new HttpRequestClass("https://www.example.com", 1); // POST 請求
    $http->set_userAgent("CustomAgent/1.0"); // 設定 User-Agent
    $http->set()->set_headers_arr([ // 設定頭資訊
        "Content-Type: application/json"
    ]);
    $http->CookieManager()->setCookie("user", "john_doe"); // 設定 cookie
    $http->set_proxy("127.0.0.1:7890"); // 設定代理
    $http->set()->set_followLocation(true); // 啟用重新導向
    $http->set()->timeout = 20; // 設定超時

    // 發送帶資料的請求
    $response = $http->Send(["key" => "value"])->GetResponse();

    // 輸出結果
    echo "狀態碼: " . $response->statusCode . "\n";
    echo "回應主體: " . $response->body . "\n";
    echo "Cookies: " . $response->cookieManager->getCookieString() . "\n";
    echo "回應頭: " . print_r($response->responseHeadersArray, true) . "\n";
} catch (Exception $e) {
    echo "錯誤: " . $e->getMessage();
}
```

## 類參考

### HttpRequestClass

- **建構函數**: `HttpRequestClass(string|null $url, int $method=0, mixed &$cookieManager=null)`
- **方法**:
  - `open(string|null $url, int $method=0)`: 設定 URL 和方法。
  - `set()`: 存取 `HttpRequestParameter` 進行配置。
  - `Send(string|array|null $data=null)`: 發送請求。
  - `GetResponse()`: 取得回應數據。
  - `CookieManager()`: 存取 `CookieManager`。
  - `set_userAgent(string $userAgent)`: 設定 User-Agent。
  - `set_Cookie_str(string $cookie)`: 設定 cookie 字符串。
  - `set_proxy(string $ip, string $user, string $pwd)`: 配置代理。
  - `setSslVerification(bool $verifyPeer, bool $verifyHost)`: 配置 SSL 驗證。
  - `bindcookie(mixed &$cookieManager)`: 綁定外部 cookie 管理器（與內部 `CookieManager` 同步）。

### CookieManager

- **建構函數**: `CookieManager()`
- **方法**:
  - `setCookie(string $name, string $value)`: 設定單一 cookie。
  - `setCookieString(string $string)`: 從字符串解析並設定 cookie。
  - `getCookieString()`: 以字符串形式取得所有 cookie。
  - `clearCookie()`: 清除所有 cookie。

### HttpRequestParameter

- **屬性**:
  - `url`: 請求 URL。
  - `method`: HTTP 方法（0=GET、1=POST 等）。
  - `data`: POST 數據。
  - `headers`: 頭資訊字符串。
  - `headers_arr`: 頭資訊陣列。
  - `CookieManager`: cookie 管理器實例。
  - `timeout`: 請求超時。
  - `proxy`, `proxyUsername`, `proxyPassword`: 代理設定。
  - `followLocation`: 啟用/禁用重新導向。
  - `completeProtocolHeaders`: 啟用/禁用預設頭資訊。
  - `hosts`: 自訂 DNS 解析。
- **方法**:
  - `send(string|array|null $data)`: 通過父類 `HttpRequestClass` 發送請求。
  - `set_proxyUsername(string $parm)`: 設定代理用戶名。
  - `set_followLocation(bool $parm)`: 設定重新導向行為。
  - `set_headers_arr(array $parm)`: 設定頭資訊陣列。

### HttpResponseData

- **屬性**:
  - `statusCode`: HTTP 狀態碼。
  - `requestHeaders`: 請求頭資訊字符串。
  - `requestHeadersArray`: 請求頭資訊陣列。
  - `responseHeaders`: 回應頭資訊字符串。
  - `responseHeadersArray`: 回應頭資訊陣列。
  - `body`: 回應主體。
  - `cookieManager`: cookie 管理器實例。
  - `Cookie`: cookie 字符串。