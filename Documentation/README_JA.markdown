# EasyHTTPライブラリドキュメント

## 概要

**EasyHTTP**は、HTTPリクエスト操作を簡素化するために設計された、汎用性が高く使いやすいPHPライブラリです。シンプルさと機能性を重視して構築されており、GET、POST、PUT、DELETEなどのHTTPリクエストを処理するための強力な機能セットを提供します。さらに、クッキー管理、プロキシ設定、SSL検証制御、カスタムヘッダーサポートなどの高度な機能も備えています。このライブラリはPHPのcURL拡張を利用して、信頼性が高く効率的なHTTP通信を実現し、軽量かつ強力なWebインタラクションのソリューションを求める開発者にとって優れた選択肢です。

このドキュメントでは、EasyHTTPライブラリの包括的なガイドを提供し、その使いやすさ、豊富な機能セット、実際の例を強調して、開発者がプロジェクトに迅速に統合できるように支援します。

## 主な機能

EasyHTTPライブラリは直感的かつ機能豊富に設計されており、以下の機能を提供します：

- **柔軟なHTTPメソッド**：GET、POST、HEAD、PUT、OPTIONS、DELETE、TRACE、CONNECTなど、幅広いHTTPメソッドを簡単な設定でサポート。
- **クッキー管理**：専用の`CookieManager`クラスにより、クッキーの設定、取得、クリアをシームレスに処理。
- **カスタマイズ可能なヘッダー**：文字列ベースおよび配列ベースのヘッダー設定をサポートし、利便性のためにデフォルトヘッダーを自動追加。
- **プロキシサポート**：安全かつ柔軟なネットワークルーティングのための、オプションの認証付きプロキシ設定。
- **SSL検証制御**：ピアおよびホストのSSL検証を有効または無効にでき、さまざまなセキュリティ要件に対応。
- **リダイレクト処理**：自動リダイレクトの追従を有効または無効にでき、リダイレクト先の場所にアクセス可能。
- **タイムアウト設定**：リクエストの継続時間を効果的に管理するためのカスタムタイムアウト設定。
- **ユーザーエージェントのカスタマイズ**：リクエスト用のカスタムユーザーエージェント文字列の設定を簡素化。
- **包括的な応答処理**：ステータスコード、ヘッダー、本文、クッキーなど、詳細な応答データを構造化された形式で返却。
- **エラーハンドリング**：信頼性の高い操作と明確なエラーメッセージを保証する堅牢な例外処理。
- **流れるようなインターフェース**：直感的で読みやすいコードのためのチェーン可能なメソッド設計。

## インストール

EasyHTTPライブラリを使用するには、PHPがインストールされており、cURL拡張が有効になっていることを確認してください。以下の手順に従います：

1. ライブラリファイル（`HttpRequestClass.php`、`HttpRequestParameter.php`、`HttpResponseData.php`、`CookieManager.php`）を含む`EasyHTTP`ディレクトリをプロジェクトディレクトリに配置します。
2. プロジェクトに`autoload.php`ファイルを含めて、必要なクラスを自動的にロードします：

```php
require_once __DIR__ . '/autoload.php';
```

3. `EasyHTTP`名前空間を使用してライブラリのクラスにアクセスします：

```php
use EasyHTTP\HttpRequestClass;
```

## 使用例

以下の例は、ライブラリの使いやすさと多様性を示し、一般的な使用例と高度な機能をカバーします。

### 1. 基本的なGETリクエスト

URLからデータを取得する簡単なGETリクエストを実行します：

```php
use EasyHTTP\HttpRequestClass;

try {
    $response = (new HttpRequestClass('http://example.com'))
        ->Send()
        ->getResponse();
    echo $response->body; // 応答本文を出力
} catch (Exception $e) {
    echo $e->getMessage();
}
```

この例は、GETリクエストを開始し、応答本文を取得するためのライブラリの簡単な構文を示しています。

### 2. 代替のGETリクエストパターン

ライブラリはリクエスト設定のための代替パターンをサポートします：

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

`open`メソッドはURLとメソッドを動的に設定でき、柔軟性を高めます。

### 3. カスタムユーザーエージェントを使用したGETリクエスト

GETリクエストのユーザーエージェントをカスタマイズします：

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

この機能は、特定のブラウザの動作を模倣したり、API要件を満たすのに役立ちます。

### 4. フォームデータを含むPOSTリクエスト

フォームデータを含むPOSTリクエストを送信します：

```php
try {
    $response = (new HttpRequestClass('https://postman-echo.com/post', 1)) // 1 = POST
        ->Send([
            'username' => 'john_doe',
            'password' => 'secure123'
        ])
        ->getResponse();
    echo "POST応答: " . $response->body;
} catch (Exception $e) {
    echo $e->getMessage();
}
```

ライブラリは、配列および文字列形式をサポートし、POSTデータの送信を簡素化します。

### 5. クッキー管理

持続的なセッションのためのクッキー管理：

```php
try {
    $http = new HttpRequestClass('http://example.com');
    $http->set_Cookie_str('session_id=abc123; user_pref=dark_mode');
    $response = $http->Send()->getResponse();
    echo $response->cookieManager->getCookieString(); // クッキーを出力
} catch (Exception $e) {
    echo $e->getMessage();
}
```

`CookieManager`クラスは、クッキーの解析、保存、取得を処理し、複数のリクエスト間でクッキーを自動的に維持します。

### 6. 高度なクッキー管理

より詳細な制御のために、`CookieManager`を直接使用します：

```php
try {
    $http = new HttpRequestClass();
    $cookieManager = $http->CookieManager();
    $cookieManager->setCookie('language', 'ja-JP')
                  ->setCookie('theme', 'dark');
    $response = $http->open('http://example.com')
                     ->Send()
                     ->getResponse();
    echo "現在のクッキー: " . $cookieManager->getCookieString();
} catch (Exception $e) {
    echo $e->getMessage();
}
```

これにより、特定のクッキーの設定や削除などの細かい操作が可能です。

### 7. リダイレクトの処理

リダイレクトの動作を制御し、リダイレクト情報を取得します：

```php
try {
    $http = new HttpRequestClass('http://example.com/redirect');
    $http->set()->followLocation = false; // 自動リダイレクトを無効化
    $response = $http->Send()->getResponse();
    if ($response->statusCode >= 300 && $response->statusCode < 400) {
        $location = $response->responseHeadersArray['Location'] ?? '';
        echo "リダイレクト先: " . $location;
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
```

この機能は、デバッグや手動でのリダイレクト処理に役立ちます。

### 8. プロキシ設定

リクエストのためのプロキシを設定します：

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

ライブラリは認証付きプロキシをサポートし、安全な環境に適しています。

### 9. SSL検証制御

特定のシナリオでSSL検証を無効にします：

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

この柔軟性は、テストや自己署名証明書を使用する環境に最適です。

### 10. カスタムヘッダー

リクエストにカスタムヘッダーを設定します：

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

ライブラリは、配列ベースおよび文字列ベースのヘッダーをサポートし、デフォルトのインテリジェントなマージを行います。

### 11. タイムアウト設定

リクエストにカスタムタイムアウトを設定します：

```php
try {
    $http = new HttpRequestClass('http://example.com');
    $http->set()->timeout = 5; // タイムアウトを5秒に設定
    $response = $http->Send()->getResponse();
    echo $response->body;
} catch (Exception $e) {
    echo "タイムアウトエラー: " . $e->getMessage();
}
```

これにより、リクエストが無期限にハングアップしないようにし、アプリケーションの信頼性を向上させます。

## クラス構造

EasyHTTPライブラリは、特定の役割を持つ4つの主要なクラスで構成されています：

1. **HttpRequestClass**：HTTPリクエストの開始と設定を行うコアクラス。URL、メソッド、ヘッダー、プロキシなどを設定するメソッドを提供し、チェーン操作のための流れるようなインターフェースを備えています。
2. **HttpRequestParameter**：URL、メソッド、ヘッダー、クッキー、タイムアウトなどのリクエストパラメータを管理し、リクエスト設定の詳細な制御を可能にします。
3. **HttpResponseData**：本文、ステータスコード、ヘッダー、クッキーなどの応答データを構造化された形式で保存し、簡単にアクセスできます。
4. **CookieManager**：クッキーの設定、取得、クリアなどの操作を処理し、文字列およびキーバリューペア形式をサポートします。

## エラーハンドリング

ライブラリは、PHPの例外処理を使用してエラーを優雅に管理します。一般的な例外には以下が含まれます：

- **InvalidArgumentException**：無効なURLまたは空のPOSTデータに対してスローされます。
- **Exception**：cURLエラーに対してスローされ、詳細なエラーコードとメッセージが含まれます。

ネットワークと対話するすべてのメソッドは、提供された例でtry-catchブロックにラップされており、プロダクションコードでの堅牢なエラーハンドリングを保証します。

## ベストプラクティス

ライブラリの効果を最大限に引き出すために、以下を考慮してください：

- **流れるようなインターフェースの使用**：`open`、`set`、`Send`、`getResponse`などのメソッドをチェーンして、読みやすいコードを作成します。
- **例外の処理**：ネットワーク操作を常にtry-catchブロックでラップして、エラーを優雅に処理します。
- **クッキー管理の活用**：複数のリクエスト間でセッションの持続性を実現するために`CookieManager`を使用します。
- **適切なタイムアウトの設定**：長時間実行されるリクエストがアプリケーションをブロックしないようにタイムアウトを設定します。
- **URLの検証**：リクエストを送信する前にURLが有効であることを確認して、例外を回避します。

## 結論

EasyHTTPライブラリは、PHP開発者向けの強力かつ直感的なツールであり、シンプルでチェーン可能なインターフェースに包括的な機能セットを備えています。基本的なGETリクエストの実行、複雑なクッキーセッションの管理、プロキシやカスタムヘッダーの設定など、EasyHTTPは現代のWeb開発に必要な柔軟性と信頼性を提供します。堅牢なエラーハンドリング、詳細な応答データ、高度なHTTP機能のサポートにより、簡単なスクリプトから複雑なアプリケーションまで理想的な選択肢です。

詳細やライブラリへの貢献については、[https://github.com/thiswod/EasyHTTP](https://github.com/thiswod/EasyHTTP)を参照してください。