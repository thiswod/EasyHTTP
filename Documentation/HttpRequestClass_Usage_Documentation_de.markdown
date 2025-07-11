# HttpRequestClass Nutzungsdokumentation

Dieses Dokument bietet eine umfassende Anleitung zur Verwendung von `HttpRequestClass` und seinen zugehörigen Klassen (`CookieManager`, `HttpRequestParameter`, `HttpResponseData`) in PHP. Diese Klassen sind darauf ausgelegt, HTTP-Anfragen zu vereinfachen und unterstützen Methoden wie GET, POST sowie Funktionen für Cookies, Header, Proxys und SSL-Konfigurationen.

## Inhaltsverzeichnis
1. [Überblick](#überblick)
2. [Verzeichnisstruktur](#verzeichnisstruktur)
3. [Schlüsselklassen und ihre Zwecke](#schlüsselklassen-und-ihre-zwecke)
4. [Installation und Einrichtung](#installation-und-einrichtung)
5. [Grundlegende Nutzung](#grundlegende-nutzung)
    - [GET-Anfrage initiieren](#get-anfrage-initiieren)
    - [POST-Anfrage initiieren](#post-anfrage-initiieren)
    - [Benutzerdefinierte Header setzen](#benutzerdefinierte-header-setzen)
    - [Cookies verwalten](#cookies-verwalten)
    - [Proxy konfigurieren](#proxy-konfigurieren)
    - [SSL-Verifizierung](#ssl-verifizierung)
6. [Erweiterte Nutzung](#erweiterte-nutzung)
    - [Benutzerdefinierte HTTP-Methoden](#benutzerdefinierte-http-methoden)
    - [Umleitungen behandeln](#umleitungen-behandeln)
    - [Timeout einstellen](#timeout-einstellen)
    - [Benutzerdefinierte DNS-Auflösung](#benutzerdefinierte-dns-auflösung)
7. [Fehlerbehandlung](#fehlerbehandlung)
8. [Beispielcode](#beispielcode)
9. [Klassenreferenz](#klassenreferenz)
    - [HttpRequestClass](#httprequestclass)
    - [CookieManager](#cookiemanager)
    - [HttpRequestParameter](#httprequestparameter)
    - [HttpResponseData](#httpresponsedata)

## Überblick

`HttpRequestClass` ist eine PHP-basierte Klasse, die die cURL-Bibliothek nutzt, um HTTP-Anfragen auszuführen. Sie bietet eine flexible verkettbare Schnittstelle zur Konfiguration von Anfragen, Verwaltung von Cookies, Einstellung von Headern und Verarbeitung von Antworten. Unterstützende Klassen umfassen `CookieManager` (für die Cookie-Verwaltung), `HttpRequestParameter` (zur Speicherung von Anfrageparametern) und `HttpResponseData` (zur Speicherung von Antwortdaten).

Wichtige Funktionen umfassen:
- Unterstützung für mehrere HTTP-Methoden (GET, POST, HEAD, PUT, OPTIONS, DELETE, TRACE, CONNECT).
- Cookie-Verwaltung über `CookieManager`.
- Flexible Header-Konfiguration im String- oder Array-Format.
- Proxy-Unterstützung (mit Authentifizierung).
- SSL-Verifizierungsoptionen.
- Automatische Verarbeitung von Antwortheadern, Cookies und Inhalten.

## Verzeichnisstruktur

Das Projekt ist wie folgt organisiert:

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

- **`EasyHTTP/`**: Stammverzeichnis mit Unterverzeichnissen und wichtigen Dateien.
  - **`EasyHTTP/`**: Enthält die Kern-PHP-Klassen.
    - **`CookieManager.php`**: Verwaltet Cookie-Operationen.
    - **`HttpRequestClass.php`**: Kernklasse zur Ausführung von Anfragen.
    - **`HttpRequestParameter.php`**: Verwaltet Anfrageparameter.
    - **`HttpResponseData.php`**: Speichert Antwortdaten.
  - **`Demo.php`**: Befindet sich im Stammverzeichnis und enthält Nutzungsbeispiele.
  - **`autoload.php`**: Befindet sich im Stammverzeichnis und implementiert SPL-Autoloading.

## Schlüsselklassen und ihre Zwecke

- **HttpRequestClass**: Hauptklasse zur Initiierung und Sendung von HTTP-Anfragen, Verwaltung von cURL-Handles und Koordination anderer Klassen.
- **CookieManager**: Verarbeitet die Speicherung, Einstellung und Abruf von Cookies, unterstützt einzelne Cookies und Cookie-Strings.
- **HttpRequestParameter**: Speichert Anfrageparameter (z. B. URL, Methode, Header, Proxy-Einstellungen) und bietet eine verkettbare Konfigurationsschnittstelle.
- **HttpResponseData**: Speichert Antwortdaten, einschließlich Statuscode, Header, Inhalt und Cookies.

## Installation und Einrichtung

1. Stellen Sie sicher, dass PHP mit der aktivierten cURL-Erweiterung auf dem Server installiert ist.
2. Speichern Sie den bereitgestellten PHP-Code als Datei (z. B. `HttpRequestClass.php`).
3. Fügen Sie die Datei in Ihr PHP-Projekt ein:

```php
require_once 'HttpRequestClass.php';
```

## Grundlegende Nutzung

### GET-Anfrage initiieren

Führen Sie eine einfache GET-Anfrage aus:

```php
try {
    $response = (new HttpRequestClass("https://www.example.com"))->Send()->GetResponse();
    echo $response->body; // Gibt den Antwortkörper aus
    echo $response->cookieManager->getCookieString(); // Gibt Cookies aus
} catch (Exception $e) {
    echo "Fehler: " . $e->getMessage();
}
```

Oder verwenden Sie die `open`-Methode:

```php
$http = new HttpRequestClass();
$http->open("https://www.example.com");
$http->Send();
echo $http->GetResponse()->body; // Gibt den Antwortkörper aus
```

### POST-Anfrage initiieren

Führen Sie eine POST-Anfrage mit Daten aus:

```php
try {
    $response = (new HttpRequestClass("https://www.example.com", 1))
        ->Send(["KeyWord" => "Post request"])
        ->GetResponse();
    echo $response->body; // Gibt den Antwortkörper aus
} catch (Exception $e) {
    echo "Fehler: " . $e->getMessage();
}
```

### Benutzerdefinierte Header setzen

Setzen Sie Header mit einem Array oder String:

```php
$http = new HttpRequestClass("https://www.example.com");
$http->set()->set_headers_arr([
    "Content-Type: application/json",
    "Authorization: Bearer token123"
]);
$response = $http->Send()->GetResponse();
```

### Cookies verwalten

Setzen Sie Cookies mit `CookieManager`:

```php
$http = new HttpRequestClass("https://www.example.com");
$http->CookieManager()->setCookie("session_id", "abc123"); // Setzt einen einzelnen Cookie
$http->CookieManager()->setCookieString("user_id=xyz789; theme=dark"); // Setzt einen Cookie-String
$response = $http->Send()->GetResponse();
echo $response->cookieManager->getCookieString(); // Ausgabe: session_id=abc123; user_id=xyz789; theme=dark
```

Löschen von Cookies:

```php
$http->CookieManager()->clearCookie();
```

### Proxy konfigurieren

Setzen Sie einen Proxy (mit optionaler Authentifizierung):

```php
$http = new HttpRequestClass("https://www.example.com");
$http->set_proxy("127.0.0.1:7890", "username", "password");
$response = $http->Send()->GetResponse();
```

### SSL-Verifizierung

Aktivieren oder deaktivieren Sie die SSL-Verifizierung:

```php
$http = new HttpRequestClass("https://www.example.com");
$http->setSslVerification(false, false); // Deaktiviert SSL-Verifizierung
$response = $http->Send()->GetResponse();
```

## Erweiterte Nutzung

### Benutzerdefinierte HTTP-Methoden

Unterstützung für verschiedene HTTP-Methoden, angegeben über den `$method`-Parameter (0=GET, 1=POST, etc.):

```php
$http = new HttpRequestClass("https://www.example.com", 3); // PUT-Anfrage
$http->Send(["data" => "value"])->GetResponse();
```

### Umleitungen behandeln

Aktivieren Sie das Folgen von Umleitungen:

```php
$http = new HttpRequestClass("https://www.example.com");
$http->set()->set_followLocation(true);
$response = $http->Send()->GetResponse();
```

### Timeout einstellen

Stellen Sie den Anfragetimeout ein (in Sekunden):

```php
$http = new HttpRequestClass("https://www.example.com");
$http->set()->timeout = 30; // 30 Sekunden
$response = $http->Send()->GetResponse();
```

### Benutzerdefinierte DNS-Auflösung

Geben Sie eine benutzerdefinierte DNS-Auflösung an:

```php
$http = new HttpRequestClass("https://www.example.com");
$http->set()->hosts = ["example.com:443:93.184.216.34"];
$response = $http->Send()->GetResponse();
```

## Fehlerbehandlung

Die Klassen werfen Ausnahmen bei ungültigen Eingaben oder cURL-Fehlern. Verwenden Sie immer einen `try-catch`-Block:

```php
try {
    $response = (new HttpRequestClass("invalid-url"))->Send()->GetResponse();
} catch (Exception $e) {
    echo "Fehler: " . $e->getMessage();
}
```

Häufige Ausnahmen:
- `InvalidArgumentException`: Ungültige URL oder leere POST-Daten.
- `Exception`: cURL-Fehler, einschließlich Fehlercode und Meldung.

## Beispielcode

Das folgende ist ein umfassendes Beispiel, das mehrere Funktionen demonstriert:

```php
try {
    // Anfrage initialisieren
    $http = new HttpRequestClass("https://www.example.com", 1); // POST-Anfrage
    $http->set_userAgent("CustomAgent/1.0"); // User-Agent setzen
    $http->set()->set_headers_arr([ // Header setzen
        "Content-Type: application/json"
    ]);
    $http->CookieManager()->setCookie("user", "john_doe"); // Cookie setzen
    $http->set_proxy("127.0.0.1:7890"); // Proxy setzen
    $http->set()->set_followLocation(true); // Umleitung aktivieren
    $http->set()->timeout = 20; // Timeout setzen

    // Anfrage mit Daten senden
    $response = $http->Send(["key" => "value"])->GetResponse();

    // Ergebnisse ausgeben
    echo "Statuscode: " . $response->statusCode . "\n";
    echo "Antwortkörper: " . $response->body . "\n";
    echo "Cookies: " . $response->cookieManager->getCookieString() . "\n";
    echo "Antwortheader: " . print_r($response->responseHeadersArray, true) . "\n";
} catch (Exception $e) {
    echo "Fehler: " . $e->getMessage();
}
```

## Klassenreferenz

### HttpRequestClass

- **Konstruktor**: `HttpRequestClass(string|null $url, int $method=0, mixed &$cookieManager=null)`
- **Methoden**:
  - `open(string|null $url, int $method=0)`: Setzt URL und Methode.
  - `set()`: Greift auf `HttpRequestParameter` für Konfiguration zu.
  - `Send(string|array|null $data=null)`: Sendet Anfrage.
  - `GetResponse()`: Ruft Antwortdaten ab.
  - `CookieManager()`: Greift auf `CookieManager` zu.
  - `set_userAgent(string $userAgent)`: Setzt User-Agent.
  - `set_Cookie_str(string $cookie)`: Setzt Cookie-String.
  - `set_proxy(string $ip, string $user, string $pwd)`: Konfiguriert Proxy.
  - `setSslVerification(bool $verifyPeer, bool $verifyHost)`: Konfiguriert SSL-Verifizierung.
  - `bindcookie(mixed &$cookieManager)`: Bindet externen Cookie-Manager (synchronisiert mit internem `CookieManager`).

### CookieManager

- **Konstruktor**: `CookieManager()`
- **Methoden**:
  - `setCookie(string $name, string $value)`: Setzt einen einzelnen Cookie.
  - `setCookieString(string $string)`: Parst und setzt Cookies aus einem String.
  - `getCookieString()`: Ruft alle Cookies als String ab.
  - `clearCookie()`: Löscht alle Cookies.

### HttpRequestParameter

- **Eigenschaften**:
  - `url`: Anfrage-URL.
  - `method`: HTTP-Methode (0=GET, 1=POST, etc.).
  - `data`: POST-Daten.
  - `headers`: Header als String.
  - `headers_arr`: Header als Array.
  - `CookieManager`: Cookie-Manager-Instanz.
  - `timeout`: Anfragetimeout.
  - `proxy`, `proxyUsername`, `proxyPassword`: Proxy-Einstellungen.
  - `followLocation`: Aktiviert/deaktiviert Umleitungen.
  - `completeProtocolHeaders`: Aktiviert/deaktiviert Standard-Header.
  - `hosts`: Benutzerdefinierte DNS-Auflösungen.
- **Methoden**:
  - `send(string|array|null $data)`: Sendet Anfrage über die übergeordnete `HttpRequestClass`.
  - `set_proxyUsername(string $parm)`: Setzt Proxy-Benutzernamen.
  - `set_followLocation(bool $parm)`: Setzt Umleitungsverhalten.
  - `set_headers_arr(array $parm)`: Setzt Header-Array.

### HttpResponseData

- **Eigenschaften**:
  - `statusCode`: HTTP-Statuscode.
  - `requestHeaders`: Anfrage-Header als String.
  - `requestHeadersArray`: Anfrage-Header als Array.
  - `responseHeaders`: Antwort-Header als String.
  - `responseHeadersArray`: Antwort-Header als Array.
  - `body`: Antwortkörper.
  - `cookieManager`: Cookie-Manager-Instanz.
  - `Cookie`: Cookie-String.