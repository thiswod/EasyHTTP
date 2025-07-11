# HttpRequestClass 使用ドキュメント

このドキュメントは、PHP で `HttpRequestClass` およびその関連クラス（`CookieManager`、`HttpRequestParameter`、`HttpResponseData`）を使用するための包括的なガイドを提供します。これらのクラスは HTTP リクエスト操作を簡素化することを目的としており、GET、POST などのメソッドをサポートし、cookie、ヘッダー、プロキシ、SSL 設定機能を提供します。

## 目次
1. [概要](#概要)
2. [ディレクトリ構造](#ディレクトリ構造)
3. [主要クラスとその用途](#主要クラスとその用途)
4. [インストールと設定](#インストールと設定)
5. [基本的な使用](#基本的な使用)
    - [GET リクエストの開始](#get-リクエストの開始)
    - [POST リクエストの開始](#post-リクエストの開始)
    - [カスタムヘッダーの設定](#カスタムヘッダーの設定)
    - [クッキーの管理](#クッキーの管理)
    - [プロキシの設定](#プロキシの設定)
    - [SSL 検証](#ssl-検証)
6. [高度な使用](#高度な使用)
    - [カスタム HTTP メソッド](#カスタム-http-メソッド)
    - [リダイレクトの処理](#リダイレクトの処理)
    - [タイムアウトの設定](#タイムアウトの設定)
    - [カスタム DNS 解決](#カスタム-dns-解決)
7. [エラー処理](#エラー処理)
8. [サンプルコード](#サンプルコード)
9. [クラスリファレンス](#クラスリファレンス)
    - [HttpRequestClass](#httprequestclass)
    - [CookieManager](#cookiemanager)
    - [HttpRequestParameter](#httprequestparameter)
    - [HttpResponseData](#httpresponsedata)

## 概要

`HttpRequestClass` は、cURL ライブラリを利用して HTTP リクエストを実行する PHP ベースのクラスです。リクエストの設定、クッキーの管理、ヘッダーの設定、応答の処理のための柔軟なチェーン可能なインターフェースを提供します。補助クラスには、クッキーを処理する `CookieManager`、リクエストパラメータを保存する `HttpRequestParameter`、応答データを保存する `HttpResponseData` が含まれます。

主な特徴には以下が含まれます：
- 複数の HTTP メソッド（GET、POST、HEAD、PUT、OPTIONS、DELETE、TRACE、CONNECT）のサポート。
- `CookieManager` を通じたクッキー管理。
- 文字列または配列形式の柔軟なヘッダー設定。
- 認証付きのプロキシサポート。
- SSL 検証オプション。
- 応答ヘッダー、クッキー、ボディコンテンツの自動処理。

## ディレクトリ構造

プロジェクトは以下の通り構成されています：

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

- **`EasyHTTP/`**：ルートディレクトリ、含まれるサブディレクトリと主要ファイル。
  - **`EasyHTTP/`**：コア PHP クラスの格納場所。
    - **`CookieManager.php`**: クッキー操作の管理。
    - **`HttpRequestClass.php`**: コアリクエスト実行クラス。
    - **`HttpRequestParameter.php`**: リクエストパラメータの管理。
    - **`HttpResponseData.php`**: 応答データの保存。
  - **`Demo.php`**: ルートディレクトリにあり、使用例を含む。
  - **`autoload.php`**: ルートディレクトリにあり、SPL オートロードを実装。

## 主要クラスとその用途

- **HttpRequestClass**: HTTP リクエストの開始と送信を担当するメインクラス。cURL ハンドルの管理と他のクラスの調整を行う。
- **CookieManager**: クッキーの保存、設定、取得を処理し、単一クッキーとクッキーストリングをサポート。
- **HttpRequestParameter**: URL、メソッド、ヘッダー、プロキシ設定などのリクエストパラメータを保存し、チェーン可能な設定インターフェースを提供。
- **HttpResponseData**: ステータスコード、ヘッダー、ボディ、クッキーを含む応答データを保存。

## インストールと設定

1. サーバーに PHP がインストールされ、cURL 拡張が有効であることを確認。
2. 提供された PHP コードをファイル（例: `HttpRequestClass.php`）として保存。
3. PHP プロジェクトにファイルをインクルード：

```php
require_once 'HttpRequestClass.php';
```

## 基本的な使用

### GET リクエストの開始

シンプルな GET リクエストを実行：

```php
try {
    $response = (new HttpRequestClass("https://www.example.com"))->Send()->GetResponse();
    echo $response->body; // 応答ボディを出力
    echo $response->cookieManager->getCookieString(); // クッキーを出力
} catch (Exception $e) {
    echo "エラー: " . $e->getMessage();
}
```

または `open` メソッドを使用：

```php
$http = new HttpRequestClass();
$http->open("https://www.example.com");
$http->Send();
echo $http->GetResponse()->body; // 応答ボディを出力
```

### POST リクエストの開始

データ付きの POST リクエストを実行：

```php
try {
    $response = (new HttpRequestClass("https://www.example.com", 1))
        ->Send(["KeyWord" => "Post request"])
        ->GetResponse();
    echo $response->body; // 応答ボディを出力
} catch (Exception $e) {
    echo "エラー: " . $e->getMessage();
}
```

### カスタムヘッダーの設定

配列または文字列でヘッダーを設定：

```php
$http = new HttpRequestClass("https://www.example.com");
$http->set()->set_headers_arr([
    "Content-Type: application/json",
    "Authorization: Bearer token123"
]);
$response = $http->Send()->GetResponse();
```

### クッキーの管理

`CookieManager` を使用してクッキーを設定：

```php
$http = new HttpRequestClass("https://www.example.com");
$http->CookieManager()->setCookie("session_id", "abc123"); // 単一クッキーを設定
$http->CookieManager()->setCookieString("user_id=xyz789; theme=dark"); // クッキーストリングを設定
$response = $http->Send()->GetResponse();
echo $response->cookieManager->getCookieString(); // 出力: session_id=abc123; user_id=xyz789; theme=dark
```

クッキーのクリア：

```php
$http->CookieManager()->clearCookie();
```

### プロキシの設定

プロキシを設定（認証オプション付き）：

```php
$http = new HttpRequestClass("https://www.example.com");
$http->set_proxy("127.0.0.1:7890", "username", "password");
$response = $http->Send()->GetResponse();
```

### SSL 検証

SSL 検証を有効または無効に：

```php
$http = new HttpRequestClass("https://www.example.com");
$http->setSslVerification(false, false); // SSL 検証を無効化
$response = $http->Send()->GetResponse();
```

## 高度な使用

### カスタム HTTP メソッド

`$method` パラメータ（0=GET、1=POST 等）でさまざまな HTTP メソッドをサポート：

```php
$http = new HttpRequestClass("https://www.example.com", 3); // PUT リクエスト
$http->Send(["data" => "value"])->GetResponse();
```

### リダイレクトの処理

リダイレクトの追跡を有効に：

```php
$http = new HttpRequestClass("https://www.example.com");
$http->set()->set_followLocation(true);
$response = $http->Send()->GetResponse();
```

### タイムアウトの設定

リクエストタイムアウトを設定（秒単位）：

```php
$http = new HttpRequestClass("https://www.example.com");
$http->set()->timeout = 30; // 30 秒
$response = $http->Send()->GetResponse();
```

### カスタム DNS 解決

カスタム DNS 解決を指定：

```php
$http = new HttpRequestClass("https://www.example.com");
$http->set()->hosts = ["example.com:443:93.184.216.34"];
$response = $http->Send()->GetResponse();
```

## エラー処理

クラスは無効な入力や cURL エラー時に例外をスローします。常に `try-catch` ブロックを使用：

```php
try {
    $response = (new HttpRequestClass("invalid-url"))->Send()->GetResponse();
} catch (Exception $e) {
    echo "エラー: " . $e->getMessage();
}
```

一般的な例外：
- `InvalidArgumentException`: 無効な URL または空の POST データ。
- `Exception`: cURL エラー、含まれるエラーコードとメッセージ。

## サンプルコード

以下は複数の機能をデモンストレートする包括的な例です：

```php
try {
    // リクエストの初期化
    $http = new HttpRequestClass("https://www.example.com", 1); // POST リクエスト
    $http->set_userAgent("CustomAgent/1.0"); // User-Agent を設定
    $http->set()->set_headers_arr([ // ヘッダーを設定
        "Content-Type: application/json"
    ]);
    $http->CookieManager()->setCookie("user", "john_doe"); // クッキーを設定
    $http->set_proxy("127.0.0.1:7890"); // プロキシを設定
    $http->set()->set_followLocation(true); // リダイレクトを有効化
    $http->set()->timeout = 20; // タイムアウトを設定

    // データ付きリクエストを送信
    $response = $http->Send(["key" => "value"])->GetResponse();

    // 結果を出力
    echo "ステータスコード: " . $response->statusCode . "\n";
    echo "応答ボディ: " . $response->body . "\n";
    echo "クッキー: " . $response->cookieManager->getCookieString() . "\n";
    echo "応答ヘッダー: " . print_r($response->responseHeadersArray, true) . "\n";
} catch (Exception $e) {
    echo "エラー: " . $e->getMessage();
}
```

## クラスリファレンス

### HttpRequestClass

- **コンストラクタ**: `HttpRequestClass(string|null $url, int $method=0, mixed &$cookieManager=null)`
- **メソッド**:
  - `open(string|null $url, int $method=0)`: URL とメソッドを設定。
  - `set()`: `HttpRequestParameter` にアクセスして設定。
  - `Send(string|array|null $data=null)`: リクエストを送信。
  - `GetResponse()`: 応答データを取得。
  - `CookieManager()`: `CookieManager` にアクセス。
  - `set_userAgent(string $userAgent)`: User-Agent を設定。
  - `set_Cookie_str(string $cookie)`: クッキーストリングを設定。
  - `set_proxy(string $ip, string $user, string $pwd)`: プロキシを構成。
  - `setSslVerification(bool $verifyPeer, bool $verifyHost)`: SSL 検証を構成。
  - `bindcookie(mixed &$cookieManager)`: 外部クッキーマネージャをバインド（内部 `CookieManager` と同期）。

### CookieManager

- **コンストラクタ**: `CookieManager()`
- **メソッド**:
  - `setCookie(string $name, string $value)`: 単一クッキーを設定。
  - `setCookieString(string $string)`: 文字列からクッキーを解析して設定。
  - `getCookieString()`: すべてのクッキーを文字列として取得。
  - `clearCookie()`: すべてのクッキーをクリア。

### HttpRequestParameter

- **プロパティ**:
  - `url`: リクエスト URL。
  - `method`: HTTP メソッド（0=GET、1=POST 等）。
  - `data`: POST データ。
  - `headers`: ヘッダー文字列。
  - `headers_arr`: ヘッダー配列。
  - `CookieManager`: クッキーマネージャインスタンス。
  - `timeout`: リクエストタイムアウト。
  - `proxy`, `proxyUsername`, `proxyPassword`: プロキシ設定。
  - `followLocation`: リダイレクトの有効/無効。
  - `completeProtocolHeaders`: デフォルトヘッダーの有効/無効。
  - `hosts`: カスタム DNS 解決。
- **メソッド**:
  - `send(string|array|null $data)`: 親クラス `HttpRequestClass` を介してリクエストを送信。
  - `set_proxyUsername(string $parm)`: プロキシユーザー名を設定。
  - `set_followLocation(bool $parm)`: リダイレクト動作を設定。
  - `set_headers_arr(array $parm)`: ヘッダー配列を設定。

### HttpResponseData

- **プロパティ**:
  - `statusCode`: HTTP ステータスコード。
  - `requestHeaders`: リクエストヘッダー文字列。
  - `requestHeadersArray`: リクエストヘッダー配列。
  - `responseHeaders`: 応答ヘッダー文字列。
  - `responseHeadersArray`: 応答ヘッダー配列。
  - `body`: 応答ボディ。
  - `cookieManager`: クッキーマネージャインスタンス。
  - `Cookie`: クッキーストリング。