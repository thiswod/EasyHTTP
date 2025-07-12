# Dokumentation der EasyHTTP-Bibliothek

## Überblick

Die **EasyHTTP**-Bibliothek ist eine vielseitige und benutzerfreundliche PHP-Bibliothek, die entwickelt wurde, um HTTP-Anfragen zu vereinfachen. Mit Fokus auf Einfachheit und Funktionalität bietet sie eine robuste Sammlung von Funktionen zur Verarbeitung von HTTP-Anfragen wie GET, POST, PUT, DELETE und mehr, während sie erweiterte Funktionen wie Cookie-Verwaltung, Proxy-Konfiguration, SSL-Verifizierungssteuerung und Unterstützung für benutzerdefinierte Header bietet. Die Bibliothek nutzt die cURL-Erweiterung von PHP, um eine zuverlässige und effiziente HTTP-Kommunikation zu gewährleisten, was sie zu einer ausgezeichneten Wahl für Entwickler macht, die nach einer leichten, aber leistungsstarken Lösung für Webanwendungen suchen.

Diese Dokumentation bietet eine umfassende Anleitung zur EasyHTTP-Bibliothek, hebt ihre Benutzerfreundlichkeit, ihren umfangreichen Funktionsumfang und praktische Beispiele hervor, um Entwicklern eine schnelle Integration in ihre Projekte zu ermöglichen.

## Hauptfunktionen

Die EasyHTTP-Bibliothek ist so konzipiert, dass sie sowohl intuitiv als auch funktionsreich ist und die folgenden Funktionen bietet:

- **Flexible HTTP-Methoden**: Unterstützt eine Vielzahl von HTTP-Methoden (GET, POST, HEAD, PUT, OPTIONS, DELETE, TRACE, CONNECT) mit einfacher Konfiguration.
- **Cookie-Verwaltung**: Bietet eine dedizierte `CookieManager`-Klasse für die nahtlose Verwaltung von Cookies, einschließlich Setzen, Abrufen und Löschen von Cookies.
- **Anpassbare Header**: Ermöglicht die Konfiguration von Headern sowohl im String- als auch im Array-Format mit automatischem Hinzufügen von Standard-Headern zur Erleichterung.
- **Proxy-Unterstützung**: Konfiguriert Proxy-Einstellungen mit optionaler Authentifizierung für sicheres und flexibles Netzwerk-Routing.
- **SSL-Verifizierungssteuerung**: Ermöglicht das Aktivieren oder Deaktivieren der SSL-Verifizierung für Peer und Host, um verschiedenen Sicherheitsanforderungen gerecht zu werden.
- **Weiterleitungsverarbeitung**: Unterstützt das Aktivieren oder Deaktivieren der automatischen Weiterleitungsverfolgung mit Zugriff auf Weiterleitungsorte.
- **Timeout-Konfiguration**: Ermöglicht die Einstellung benutzerdefinierter Timeout-Zeiträume, um die Dauer von Anfragen effektiv zu verwalten.
- **Benutzeragent-Anpassung**: Vereinfacht die Einstellung benutzerdefinierter Benutzeragent-Strings für Anfragen.
- **Umfassende Antwortverarbeitung**: Liefert detaillierte Antwortdaten, einschließlich Statuscodes, Header, Body und Cookies, in einem strukturierten Format.
- **Fehlerbehandlung**: Implementiert eine robuste Ausnahmebehandlung, um einen zuverlässigen Betrieb und klare Fehlermeldungen zu gewährleisten.
- **Flüssige Schnittstelle**: Bietet ein verkettbares Methodendesign für intuitiven und lesbaren Code.

## Installation

Um die EasyHTTP-Bibliothek zu verwenden, stellen Sie sicher, dass PHP installiert ist und die cURL-Erweiterung aktiviert ist. Führen Sie die folgenden Schritte aus:

1. Platzieren Sie das Verzeichnis `EasyHTTP`, das die Bibliotheksdateien (`HttpRequestClass.php`, `HttpRequestParameter.php`, `HttpResponseData.php`, `CookieManager.php`) enthält, in Ihrem Projektverzeichnis.
2. Fügen Sie die Datei `autoload.php` in Ihr Projekt ein, um die erforderlichen Klassen automatisch zu laden:

```php
require_once __DIR__ . '/autoload.php';
```

3. Verwenden Sie den Namespace `EasyHTTP`, um auf die Klassen der Bibliothek zuzugreifen:

```php
use EasyHTTP\HttpRequestClass;
```

## Verwendungsbeispiele

Die folgenden Beispiele zeigen die Benutzerfreundlichkeit und Vielseitigkeit der Bibliothek und decken gängige Anwendungsfälle und erweiterte Funktionen ab.

### 1. Einfache GET-Anfrage

Führen Sie eine einfache GET-Anfrage aus, um Daten von einer URL abzurufen:

```php
use EasyHTTP\HttpRequestClass;

try {
    $response = (new HttpRequestClass('http://example.com'))
        ->Send()
        ->getResponse();
    echo $response->body; // Ausgabe des Antwortkörpers
} catch (Exception $e) {
    echo $e->getMessage();
}
```

Dieses Beispiel zeigt die unkomplizierte Syntax der Bibliothek zum Initiieren einer GET-Anfrage und zum Abrufen des Antwortkörpers.

### 2. Alternatives GET-Anfrage-Muster

Die Bibliothek unterstützt ein alternatives Muster zur Konfiguration von Anfragen:

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

Die Methode `open` ermöglicht das dynamische Setzen von URL und Methode, was die Flexibilität erhöht.

### 3. GET-Anfrage mit benutzerdefiniertem Benutzeragent

Anpassen des Benutzeragents für eine GET-Anfrage:

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

Diese Funktion ist nützlich, um bestimmte Browser-Verhalten nachzuahmen oder API-Anforderungen zu erfüllen.

### 4. POST-Anfrage mit Formulardaten

Senden einer POST-Anfrage mit Formulardaten:

```php
try {
    $response = (new HttpRequestClass('https://postman-echo.com/post', 1)) // 1 = POST
        ->Send([
            'username' => 'john_doe',
            'password' => 'secure123'
        ])
        ->getResponse();
    echo "POST-Antwort: " . $response->body;
} catch (Exception $e) {
    echo $e->getMessage();
}
```

Die Bibliothek vereinfacht das Senden von POST-Daten und unter wereld sowohl Array- als auch String-Formate.

### 5. Cookie-Verwaltung

Verwaltung von Cookies für dauerhafte Sitzungen:

```php
try {
    $http = new HttpRequestClass('http://example.com');
    $http->set_Cookie_str('session_id=abc123; user_pref=dark_mode');
    $response = $http->Send()->getResponse();
    echo $response->cookieManager->getCookieString(); // Ausgabe der Cookies
} catch (Exception $e) {
    echo $e->getMessage();
}
```

Die Klasse `CookieManager` übernimmt das Parsen, Speichern und Abrufen von Cookies und behält Cookies automatisch über mehrere Anfragen hinweg bei.

### 6. Erweiterte Cookie-Verwaltung

Für detailliertere Kontrolle verwenden Sie `CookieManager` direkt:

```php
try {
    $http = new HttpRequestClass();
    $cookieManager = $http->CookieManager();
    $cookieManager->setCookie('language', 'de-DE')
                  ->setCookie('theme', 'dark');
    $response = $http->open('http://example.com')
                     ->Send()
                     ->getResponse();
    echo "Aktuelle Cookies: " . $cookieManager->getCookieString();
} catch (Exception $e) {
    echo $e->getMessage();
}
```

Dies ermöglicht feinkörnige Cookie-Operationen, wie das Setzen oder Löschen bestimmter Cookies.

### 7. Weiterleitungsverarbeitung

Kontrolle des Weiterleitungsverhaltens und Zugriff auf Weiterleitungsinformationen:

```php
try {
    $http = new HttpRequestClass('http://example.com/redirect');
    $http->set()->followLocation = false; // Automatische Weiterleitung deaktivieren
    $response = $http->Send()->getResponse();
    if ($response->statusCode >= 300 && $response->statusCode < 400) {
        $location = $response->responseHeadersArray['Location'] ?? '';
        echo "Weiterleitung nach: " . $location;
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
```

Diese Funktion ist nützlich für Debugging oder die manuelle Verarbeitung von Weiterleitungen.

### 8. Proxy-Konfiguration

Konfiguration eines Proxys für Anfragen:

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

Die Bibliothek unterstützt authentifizierte Proxys, was sie für sichere Umgebungen geeignet macht.

### 9. SSL-Verifizierungssteuerung

Deaktivieren der SSL-Verifizierung für bestimmte Szenarien:

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

Diese Flexibilität ist ideal für Tests oder Umgebungen mit selbstsignierten Zertifikaten.

### 10. Benutzerdefinierte Header

Setzen von benutzerdefinierten Headern für Anfragen:

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

Die Bibliothek unterstützt sowohl Array- als auch String-basierte Header mit intelligenter Zusammenführung von Standardwerten.

### 11. Timeout-Konfiguration

Setzen eines benutzerdefinierten Timeouts für Anfragen:

```php
try {
    $http = new HttpRequestClass('http://example.com');
    $http->set()->timeout = 5; // Timeout auf 5 Sekunden setzen
    $response = $http->Send()->getResponse();
    echo $response->body;
} catch (Exception $e) {
    echo "Timeout-Fehler: " . $e->getMessage();
}
```

Dies stellt sicher, dass Anfragen nicht unendlich hängen bleiben, und verbessert die Zuverlässigkeit der Anwendung.

## Klassenstruktur

Die EasyHTTP-Bibliothek besteht aus vier Hauptklassen, die jeweils eine spezifische Rolle übernehmen:

1. **HttpRequestClass**: Die Kernklasse zum Initiieren und Konfigurieren von HTTP-Anfragen. Sie bietet Methoden zum Setzen von URLs, Methoden, Headern, Proxys und mehr mit einer flüssigen Schnittstelle für verkettete Operationen.
2. **HttpRequestParameter**: Verwaltet Anfrageparameter wie URL, Methode, Header, Cookies und Timeouts, was eine detaillierte Kontrolle über Anfrageeinstellungen ermöglicht.
3. **HttpResponseData**: Speichert Antwortdaten, einschließlich Body, Statuscode, Header und Cookies, in einem strukturierten Format für einfachen Zugriff.
4. **CookieManager**: Verwaltet Cookie-Operationen, einschließlich Setzen, Abrufen und Löschen von Cookies, mit Unterstützung für String- und Schlüssel-Wert-Paar-Formate.

## Fehlerbehandlung

Die Bibliothek verwendet den Ausnahmebehandlungsmechanismus von PHP, um Fehler elegant zu verwalten. Häufige Ausnahmen umfassen:

- **InvalidArgumentException**: Wird bei ungültigen URLs oder leeren POST-Daten ausgelöst.
- **Exception**: Wird bei cURL-Fehlern ausgelöst, mit detaillierten Fehlercodes und -nachrichten.

Alle Methoden, die mit dem Netzwerk interagieren, sind in den bereitgestellten Beispielen in try-catch-Blöcke eingebettet, um eine robuste Fehlerbehandlung im Produktionscode zu gewährleisten.

## Best Practices

Um die Effektivität der Bibliothek zu maximieren, beachten Sie Folgendes:

- **Verwenden Sie flü By ssige Schnittstelle**: Verketten Sie Methoden wie `open`, `set`, `Send` und `getResponse` für lesbaren Code.
- **Behandeln Sie Ausnahmen**: Umgeben Sie Netzwerkoperationen immer mit try-catch-Blöcken, um Fehler elegant zu behandeln.
- **Nutzen Sie die Cookie-Verwaltung**: Verwenden Sie `CookieManager` für die Sitzungspersistenz über mehrere Anfragen hinweg.
- **Setzen Sie angemessene Timeouts**: Konfigurieren Sie Timeouts, um zu verhindern, dass langlaufende Anfragen Ihre Anwendung blockieren.
- **Validieren Sie URLs**: Stellen Sie sicher, dass URLs vor dem Senden von Anfragen gültig sind, um Ausnahmen zu vermeiden.

## Fazit

Die EasyHTTP-Bibliothek ist ein leistungsstarkes und intuitives Werkzeug für PHP-Entwickler, das eine umfassende Sammlung von Funktionen in einer einfachen, verkettbaren Schnittstelle bietet. Ob Sie grundlegende GET-Anfragen durchführen, komplexe Cookie-Sitzungen verwalten oder Proxys und benutzerdefinierte Header konfigurieren, EasyHTTP bietet die Flexibilität und Zuverlässigkeit, die für moderne Webentwicklung erforderlich sind. Die robuste Fehlerbehandlung, detaillierte Antwortdaten und Unterstützung für erweiterte HTTP-Funktionen machen sie zur idealen Wahl sowohl für einfache Skripte als auch für komplexe Anwendungen.

Für weitere Details oder um zur Bibliothek beizutragen, besuchen Sie [https://github.com/thiswod/EasyHTTP](https://github.com/thiswod/EasyHTTP).