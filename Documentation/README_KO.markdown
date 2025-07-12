# EasyHTTP 라이브러리 문서

## Language Navigation
[中文文档](README_ZH.markdown) | [Documentación en Español](README_ES.markdown) | [日本語ドキュメント](README_JA.markdown) | [한국어 문서](README_KO.markdown) | [Документация на русском](README_RU.markdown) | [Dokumentation auf Deutsch](README_DE.markdown)

## 개요

**EasyHTTP**는 HTTP 요청 작업을 단순화하도록 설계된 다목적이고 사용하기 쉬운 PHP 라이브러리입니다. 단순성과 기능성을 중점으로 개발되었으며, GET, POST, PUT, DELETE 등 다양한 HTTP 요청을 처리하기 위한 강력한 기능 세트를 제공합니다. 또한 쿠키 관리, 프록시 설정, SSL 검증 제어, 사용자 정의 헤더 지원과 같은 고급 기능도 지원합니다. 이 라이브러리는 PHP의 cURL 확장을 활용하여 안정적이고 효율적인 HTTP 통신을 보장하며, 가볍지만 강력한 웹 상호작용 솔루션을 찾는 개발자에게 탁월한 선택입니다.

이 문서는 EasyHTTP 라이브러리에 대한 포괄적인 가이드를 제공하며, 사용의 용이성, 풍부한 기능 세트, 실제 예제를 강조하여 개발자가 프로젝트에 빠르게 통합할 수 있도록 돕습니다.

## 주요 기능

EasyHTTP 라이브러리는 직관적이고 기능이 풍부하도록 설계되었으며, 다음과 같은 기능을 제공합니다:

- **유연한 HTTP 메서드**: GET, POST, HEAD, PUT, OPTIONS, DELETE, TRACE, CONNECT 등 다양한 HTTP 메서드를 간단한 설정으로 지원.
- **쿠키 관리**: 전용 `CookieManager` 클래스를 통해 쿠키 설정, 가져오기, 지우기를 원활히 처리.
- **사용자 정의 헤더**: 문자열 기반 및 배열 기반 헤더 설정을 지원하며, 편의를 위해 기본 헤더를 자동 추가.
- **프록시 지원**: 안전하고 유연한 네트워크 라우팅을 위한 인증 옵션이 포함된 프록시 설정.
- **SSL 검증 제어**: 피어 및 호스트에 대한 SSL 검증을 활성화하거나 비활성화하여 다양한 보안 요구사항에 대응.
- **리디렉션 처리**: 자동 리디렉션 추적을 활성화하거나 비활성화하고, 리디렉션 위치에 접근 가능.
- **타임아웃 설정**: 요청 지속 시간을 효과적으로 관리하기 위한 사용자 정의 타임아웃 설정.
- **사용자 에이전트 사용자 정의**: 요청에 대한 사용자 에이전트 문자열 설정을 단순화.
- **포괄적인 응답 처리**: 상태 코드, 헤더, 본문, 쿠키를 포함한 상세 응답 데이터를 구조화된 형식으로 반환.
- **오류 처리**: 안정적인 작동과 명확한 오류 메시지를 보장하는 견고한 예외 처리.
- **유창한 인터페이스**: 직관적이고 읽기 쉬운 코드를 위한 체인 가능한 메서드 설계.

## 설치

EasyHTTP 라이브러리를 사용하려면 PHP가 설치되어 있고 cURL 확장이 활성화되어 있어야 합니다. 다음 단계를 따르세요:

1. 라이브러리 파일(`HttpRequestClass.php`, `HttpRequestParameter.php`, `HttpResponseData.php`, `CookieManager.php`)이 포함된 `EasyHTTP` 디렉토리를 프로젝트 디렉토리에 배치합니다.
2. 프로젝트에 `autoload.php` 파일을 포함하여 필요한 클래스를 자동으로 로드합니다:

```php
require_once __DIR__ . '/autoload.php';
```

3. `EasyHTTP` 네임스페이스를 사용하여 라이브러리 클래스에 접근합니다:

```php
use EasyHTTP\HttpRequestClass;
```

## 사용 예제

다음 예제는 라이브러리의 사용 편의성과 다양성을 보여주며, 일반적인 사용 사례와 고급 기능을 다룹니다.

### 1. 기본 GET 요청

URL에서 데이터를 검색하는 간단한 GET 요청을 수행합니다:

```php
use EasyHTTP\HttpRequestClass;

try {
    $response = (new HttpRequestClass('http://example.com'))
        ->Send()
        ->getResponse();
    echo $response->body; // 응답 본문 출력
} catch (Exception $e) {
    echo $e->getMessage();
}
```

이 예제는 GET 요청을 시작하고 응답 본문을 가져오기 위한 라이브러리의 간단한 구문을 보여줍니다.

### 2. 대체 GET 요청 패턴

라이브러리는 요청 설정을 위한 대체 패턴을 지원합니다:

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

`open` 메서드는 URL과 메서드를 동적으로 설정하여 유연성을 높입니다.

### 3. 사용자 정의 사용자 에이전트를 사용한 GET 요청

GET 요청에 사용자 에이전트를 사용자 정의합니다:

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

이 기능은 특정 브라우저 동작을 모방하거나 API 요구사항을 충족하는 데 유용합니다.

### 4. � Hawkins 데이터가 포함된 POST 요청

폼 데이터를 포함한 POST 요청을 보냅니다:

```php
try {
    $response = (new HttpRequestClass('https://postman-echo.com/post', 1)) // 1 = POST
        ->Send([
            'username' => 'john_doe',
            'password' => 'secure123'
        ])
        ->getResponse();
    echo "POST 응답: " . $response->body;
} catch (Exception $e) {
    echo $e->getMessage();
}
```

라이브러리는 배열 및 문자열 형식을 지원하여 POST 데이터 전송을 단순화합니다.

### 5. 쿠키的管理

지속적인 세션을 위한 쿠키 관리:

```php
try {
    $http = new HttpRequestClass('http://example.com');
    $http->set_Cookie_str('session_id=abc123; user_pref=dark_mode');
    $response = $http->Send()->getResponse();
    echo $response->cookieManager->getCookieString(); // 쿠키 출력
} catch (Exception $e) {
    echo $e->getMessage();
}
```

`CookieManager` 클래스는 쿠키의 파싱, 저장, 검색을 처리하며, 여러 요청 간에 쿠키를 자동으로 유지합니다.

### 6. 고급 쿠키 관리

보다 세밀한 제어를 위해 `CookieManager`를 직접 사용합니다:

```php
try {
    $http = new HttpRequestClass();
    $cookieManager = $http->CookieManager();
    $cookieManager->setCookie('language', 'ko-KR')
                  ->setCookie('theme', 'dark');
    $response = $http->open('http://example.com')
                     ->Send()
                     ->getResponse();
    echo "현재 쿠키: " . $cookieManager->getCookieString();
} catch (Exception $e) {
    echo $e->getMessage();
}
```

이를 통해 특정 쿠키의 설정 또는 삭제와 같은 세밀한 쿠키 작업이 가능합니다.

### 7. 리디렉션 처리

리디렉션 동작을 제어하고 리디렉션 정보를 접근합니다:

```php
try {
    $http = new HttpRequestClass('http://example.com/redirect');
    $http->set()->followLocation = false; // 자동 리디렉션 비활성화
    $response = $http->Send()->getResponse();
    if ($response->statusCode >= 300 && $response->statusCode < 400) {
        $location = $response->responseHeadersArray['Location'] ?? '';
        echo "리디렉션 위치: " . $location;
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
```

이 기능은 디버깅 또는 수동 리디렉션 처리에 유용합니다.

### 8. 프록시 설정

요청에 프록시를 설정합니다:

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

라이브러리는 인증된 프록시를 지원하며, 보안 환경에 적합합니다.

### 9. SSL 검증 제어

특정 시나리오에서 SSL 검증을 비활성화합니다:

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

이 유연성은 테스트 또는 자체 서명 인증서를 사용하는 환경에 적합합니다.

### 10. 사용자 정의 헤더

요청에 사용자 정의 헤더를 설정합니다:

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

라이브러리는 배열 및 문자열 기반 헤더를 지원하며, 기본값의 지능적인 병합을 수행합니다.

### 11. 타임아웃 설정

요청에 사용자 정의 타임아웃을 설정합니다:

```php
try {
    $http = new HttpRequestClass('http://example.com');
    $http->set()->timeout = 5; // 타임아웃을 5초로 설정
    $response = $http->Send()->getResponse();
    echo $response->body;
} catch (Exception $e) {
    echo "타임아웃 오류: " . $e->getMessage();
}
```

이를 통해 요청이 무기한 중단되지 않도록 하여 애플리케이션의 신뢰성을 향상시킵니다.

## 클래스 구조

EasyHTTP 라이브러리는 특정 역할을 가진 네 가지 주요 클래스로 구성됩니다:

1. **HttpRequestClass**: HTTP 요청을 시작하고 구성하는 핵심 클래스입니다. URL, 메서드, 헤더, 프록시 등을 설정하는 메서드를 제공하며, 체인 작업을 위한 유창한 interfaces를 제공합니다.
2. **HttpRequestParameter**: URL, 메서드, 헤더, 쿠키, 타임아웃 등 요청 파라미터를 관리하여 요청 설정에 대한 세밀한 제어를 가능하게 합니다.
3. **HttpResponseData**: 본문, 상태 코드, 헤더, 쿠키 등 응답 데이터를 구조화된 형식으로 저장하여 쉽게 접근할 수 있습니다.
4. **CookieManager**: 쿠키 설정, 가져오기, 지우기 등의 작업을 처리하며, 문자열 및 키-값 쌍 형식을 지원합니다.

## 오류 처리

라이브러리는 PHP의 예외 처리 메커니즘을 사용하여 오류를 우아하게 관리합니다. 일반적인 예외는 다음과 같습니다:

- **InvalidArgumentException**: 유효하지 않은 URL 또는 빈 POST 데이터에 대해 발생합니다.
- **Exception**: cURL 오류에 대해 발생하며, 상세한 오류 코드와 메시지가 포함됩니다.

네트워크와 상호작용하는 모든 메서드는 제공된 예제에서 try-catch 블록으로 래핑되어 프로덕션 코드에서 견고한 오류 처리를 보장합니다.

## 모범 사례

라이브러리의 효과를 극대화하려면 다음을 고려하세요:

- **유창한 인터페이스 사용**: `open`, `set`, `Send`, `getResponse`와 같은 메서드를 체인하여 읽기 쉬운 코드를 작성합니다.
- **예외 처리**: 네트워크 작업을 항상 try-catch 블록으로 래핑하여 오류를 우아하게 처리합니다.
- **쿠키 관리 활용**: 여러 요청 간 세션 지속성을 위해 `CookieManager`를 사용합니다.
- **적절한 타임아웃 설정**: 장시간 실행되는 요청이 애플리케이션을 차단하지 않도록 타임아웃을 설정합니다.
- **URL 검증**: 요청을 보내기 전에 URL이 유효한지 확인하여 예외를 방지합니다.

## 결론

EasyHTTP 라이브러리는 PHP 개발자를 위한 강력하고 직관적인 도구로, 간단하고 체인 가능한 인터페이스에 포괄적인 기능 세트를 제공합니다. 기본 GET 요청 수행, 복잡한 쿠키 세션 관리, 프록시 및 사용자 정의 헤더 설정 등, EasyHTTP는 현대 웹 개발에 필요한 유연성과 신뢰성을 제공합니다. 견고한 오류 처리, 상세한 응답 데이터, 고급 HTTP 기능 지원으로 간단한 스크립트와 복잡한 애플리케이션 모두에 이상적인 선택입니다.

자세한 내용 또는 라이브러리에 기여하려면 [https://github.com/thiswod/EasyHTTP](https://github.com/thiswod/EasyHTTP)를 방문하세요.