# Documentación de la Biblioteca EasyHTTP

## Language Navigation
[中文文档](README_ZH.markdown) | [Documentación en Español](README_ES.markdown) | [日本語ドキュメント](README_JA.markdown) | [한국어 문서](README_KO.markdown) | [Документация на русском](README_RU.markdown) | [Dokumentation auf Deutsch](README_DE.markdown)

## Resumen

La biblioteca **EasyHTTP** es una biblioteca PHP versátil y fácil de usar diseñada para simplificar las operaciones de solicitudes HTTP. Construida con un enfoque en la simplicidad y la funcionalidad, ofrece un conjunto robusto de características para manejar solicitudes HTTP, incluyendo GET, POST, PUT, DELETE y más, al tiempo que proporciona capacidades avanzadas como gestión de cookies, configuración de proxy, control de verificación SSL y soporte para encabezados personalizados. La biblioteca aprovecha la extensión cURL de PHP para garantizar una comunicación HTTP confiable y eficiente, lo que la convierte en una excelente opción para desarrolladores que buscan una solución ligera pero potente para interacciones web.

Esta documentación proporciona una guía completa de la biblioteca EasyHTTP, destacando su facilidad de uso, su rico conjunto de funciones y ejemplos prácticos para ayudar a los desarrolladores a integrarla rápidamente en sus proyectos.

## Características Principales

La biblioteca EasyHTTP está diseñada para ser intuitiva y rica en funciones, ofreciendo las siguientes capacidades:

- **Métodos HTTP Flexibles**: Admite una amplia gama de métodos HTTP (GET, POST, HEAD, PUT, OPTIONS, DELETE, TRACE, CONNECT) con una configuración sencilla.
- **Gestión de Cookies**: Proporciona una clase dedicada `CookieManager` para manejar cookies de manera fluida, incluyendo la configuración, obtención y eliminación de cookies.
- **Encabezados Personalizables**: Permite configuraciones de encabezados basadas en cadenas y arreglos, con encabezados predeterminados automáticos para mayor comodidad.
- **Soporte de Proxy**: Configura ajustes de proxy con autenticación opcional para un enrutamiento de red seguro y flexible.
- **Control de Verificación SSL**: Habilita o deshabilita la verificación SSL para pares y hosts, adaptándose a diversos requisitos de seguridad.
- **Manejo de Redirecciones**: Admite habilitar o deshabilitar el seguimiento automático de redirecciones, con acceso a las ubicaciones de redirección.
- **Configuración de Tiempo de Espera**: Permite establecer períodos de tiempo de espera personalizados para gestionar eficazmente la duración de las solicitudes.
- **Personalización del Agente de Usuario**: Simplifica la configuración de cadenas de agente de usuario personalizadas para solicitudes.
- **Manejo Integral de Respuestas**: Devuelve datos de respuesta detallados, incluyendo códigos de estado, encabezados, cuerpo y cookies, en un formato estructurado.
- **Manejo de Errores**: Implementa un manejo robusto de excepciones para garantizar una operación confiable y mensajes de error claros.
- **Interfaz Fluida**: Ofrece un diseño de métodos encadenables para un código intuitivo y legible.

## Instalación

Para usar la biblioteca EasyHTTP, asegúrese de tener PHP instalado con la extensión cURL habilitada. Siga estos pasos:

1. Coloque el directorio `EasyHTTP` que contiene los archivos de la biblioteca (`HttpRequestClass.php`, `HttpRequestParameter.php`, `HttpResponseData.php`, `CookieManager.php`) en el directorio de su proyecto.
2. Incluya el archivo `autoload.php` en su proyecto para cargar automáticamente las clases requeridas:

```php
require_once __DIR__ . '/autoload.php';
```

3. Use el espacio de nombres `EasyHTTP` para acceder a las clases de la biblioteca:

```php
use EasyHTTP\HttpRequestClass;
```

## Ejemplos de Uso

Los siguientes ejemplos demuestran la facilidad de uso y la versatilidad de la biblioteca, cubriendo casos de uso comunes y funciones avanzadas.

### 1. Solicitud GET Básica

Realice una solicitud GET simple para recuperar datos de una URL:

```php
use EasyHTTP\HttpRequestClass;

try {
    $response = (new HttpRequestClass('http://example.com'))
        ->Send()
        ->getResponse();
    echo $response->body; // Imprimir el cuerpo de la respuesta
} catch (Exception $e) {
    echo $e->getMessage();
}
```

Este ejemplo muestra la sintaxis directa de la biblioteca para iniciar una solicitud GET y recuperar el cuerpo de la respuesta.

### 2. Patrón Alternativo de Solicitud GET

La biblioteca admite un patrón alternativo para configurar solicitudes:

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

El método `open` permite establecer la URL y el método dinámicamente, mejorando la flexibilidad.

### 3. Solicitud GET con Agente de Usuario Personalizado

Personalice el agente de usuario para una solicitud GET:

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

Esta función es útil para imitar comportamientos específicos del navegador o cumplir con los requisitos de la API.

### 4. Solicitud POST con Datos de Formulario

Envíe una solicitud POST con datos de formulario:

```php
try {
    $response = (new HttpRequestClass('https://postman-echo.com/post', 1)) // 1 = POST
        ->Send([
            'username' => 'john_doe',
            'password' => 'secure123'
        ])
        ->getResponse();
    echo "Respuesta POST: " . $response->body;
} catch (Exception $e) {
    echo $e->getMessage();
}
```

La biblioteca simplifica el envío de datos POST, admitiendo formatos de arreglos y cadenas.

### 5. Gestión de Cookies

Gestione cookies para sesiones persistentes:

```php
try {
    $http = new HttpRequestClass('http://example.com');
    $http->set_Cookie_str('session_id=abc123; user_pref=dark_mode');
    $response = $http->Send()->getResponse();
    echo $response->cookieManager->getCookieString(); // Imprimir cookies
} catch (Exception $e) {
    echo $e->getMessage();
}
```

La clase `CookieManager` maneja el análisis, almacenamiento y recuperación de cookies, manteniendo automáticamente las cookies en múltiples solicitudes.

### 6. Gestión Avanzada de Cookies

Para un control más detallado, use `CookieManager` directamente:

```php
try {
    $http = new HttpRequestClass();
    $cookieManager = $http->CookieManager();
    $cookieManager->setCookie('language', 'es-ES')
                  ->setCookie('theme', 'dark');
    $response = $http->open('http://example.com')
                     ->Send()
                     ->getResponse();
    echo "Cookies Actuales: " . $cookieManager->getCookieString();
} catch (Exception $e) {
    echo $e->getMessage();
}
```

Esto permite operaciones de cookies de grano fino, como establecer o eliminar cookies específicas.

### 7. Manejo de Redirecciones

Controle el comportamiento de redirección y acceda a la información de redirección:

```php
try {
    $http = new HttpRequestClass('http://example.com/redirect');
    $http->set()->followLocation = false; // Deshabilitar redirección automática
    $response = $http->Send()->getResponse();
    if ($response->statusCode >= 300 && $response->statusCode < 400) {
        $location = $response->responseHeadersArray['Location'] ?? '';
        echo "Redirigir a: " . $location;
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
```

Esta función es útil para depuración o manejo manual de redirecciones.

### 8. Configuración de Proxy

Configure un proxy para solicitudes:

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

La biblioteca admite proxies autenticados, lo que la hace adecuada para entornos seguros.

### 9. Control de Verificación SSL

Deshabilite la verificación SSL para escenarios específicos:

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

Esta flexibilidad es ideal para pruebas o entornos con certificados autofirmados.

### 10. Encabezados Personalizados

Establezca encabezados personalizados para solicitudes:

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

La biblioteca admite encabezados basados en arreglos y cadenas, con una fusión inteligente de valores predeterminados.

### 11. Configuración de Tiempo de Espera

Establezca un tiempo de espera personalizado para solicitudes:

```php
try {
    $http = new HttpRequestClass('http://example.com');
    $http->set()->timeout = 5; // Establecer tiempo de espera a 5 segundos
    $response = $http->Send()->getResponse();
    echo $response->body;
} catch (Exception $e) {
    echo "Error de Tiempo de Espera: " . $e->getMessage();
}
```

Esto asegura que las solicitudes no se queden colgadas indefinidamente, mejorando la confiabilidad de la aplicación.

## Estructura de Clases

La biblioteca EasyHTTP consta de cuatro clases principales, cada una con un rol específico:

1. **HttpRequestClass**: La clase principal para iniciar y configurar solicitudes HTTP. Proporciona métodos para establecer URLs, métodos, encabezados, proxies y más, con una interfaz fluida para operaciones encadenadas.
2. **HttpRequestParameter**: Gestiona los parámetros de solicitud, como URL, método, encabezados, cookies y tiempos de espera, permitiendo un control detallado sobre la configuración de solicitudes.
3. **HttpResponseData**: Almacena los datos de respuesta, incluyendo el cuerpo, el código de estado, los encabezados y las cookies, en un formato estructurado para un acceso fácil.
4. **CookieManager**: Maneja las operaciones de cookies, incluyendo la configuración, obtención y eliminación de cookies, con soporte para formatos de cadenas y pares clave-valor.

## Manejo de Errores

La biblioteca utiliza el manejo de excepciones de PHP para gestionar errores de manera elegante. Las excepciones comunes incluyen:

- **InvalidArgumentException**: Lanzada por URLs inválidas o datos POST vacíos.
- **Exception**: Lanzada por errores de cURL, con códigos de error y mensajes detallados.

Todos los métodos que interactúan con la red están envueltos en bloques try-catch en los ejemplos proporcionados, asegurando un manejo robusto de errores en el código de producción.

## Mejores Prácticas

Para maximizar la efectividad de la biblioteca, considere lo siguiente:

- **Usar la Interfaz Fluida**: Encadene métodos como `open`, `set`, `Send` y `getResponse` para un código legible.
- **Manejar Excepciones**: Siempre envuelva las operaciones de red en bloques try-catch para manejar errores de manera elegante.
- **Aprovechar la Gestión de Cookies**: Use `CookieManager` para la persistencia de sesiones en múltiples solicitudes.
- **Establecer Tiempos de Espera Apropiados**: Configure tiempos de espera para evitar que las solicitudes de larga duración bloqueen su aplicación.
- **Validar URLs**: Asegúrese de que las URLs sean válidas antes de enviar solicitudes para evitar excepciones.

## Conclusión

La biblioteca EasyHTTP es una herramienta poderosa e intuitiva para desarrolladores PHP, que ofrece un conjunto completo de funciones envueltas en una interfaz simple y encadenable. Ya sea que esté realizando solicitudes GET básicas, gestionando sesiones de cookies complejas o configurando proxies y encabezados personalizados, EasyHTTP proporciona la flexibilidad y confiabilidad necesarias para el desarrollo web moderno. Su robusto manejo de errores, datos de respuesta detallados y soporte para funciones HTTP avanzadas la convierten en una opción ideal tanto para scripts simples como para aplicaciones complejas.

Para más detalles o para contribuir a la biblioteca, visite [https://github.com/thiswod/EasyHTTP](https://github.com/thiswod/EasyHTTP).