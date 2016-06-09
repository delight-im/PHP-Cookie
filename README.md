# PHP-Cookie

Modern cookie management for PHP

## Requirements

 * PHP 5.3.0+

## Installation

 * Install via [Composer](https://getcomposer.org/) (recommended)

   `$ composer require delight-im/cookie`

   Include the Composer autoloader:

   `require __DIR__.'/vendor/autoload.php';`

 * or

 * Install manually

   * Copy the contents of the [`src`](src) directory to a subfolder of your project
   * Include the files in your code via `require` or `require_once`

## Usage

### Static method

This library provides a static method that is compatible to PHP's built-in `setcookie(...)` function but includes support for more recent features such as the [`SameSite`](https://tools.ietf.org/html/draft-west-first-party-cookies-07) attribute:

```php
\Delight\Cookie\Cookie::setcookie('SID', '31d4d96e407aad42');
// or
\Delight\Cookie\Cookie::setcookie('SID', '31d4d96e407aad42', time() + 3600, '/~rasmus/', 'example.com', true, true, 'Lax');
```

### Builder pattern

Instances of the `Cookie` class let you build a cookie conveniently by setting individual properties. This class uses reasonable defaults that may differ from defaults of the `setcookie` function.

```php
$cookie = new \Delight\Cookie\Cookie('SID');
$cookie->setValue('31d4d96e407aad42');
$cookie->setMaxAge(60 * 60 * 24);
// $cookie->setExpiryTime(time() + 60 * 60 * 24);
$cookie->setPath('/~rasmus/');
$cookie->setDomain('example.com');
$cookie->setHttpOnly(true);
$cookie->setSecureOnly(true);
$cookie->setSameSiteRestriction('Strict');
// echo $cookie;
$cookie->save();
```

The method calls can also be chained:

```php
(new \Delight\Cookie\Cookie('SID'))->setValue('31d4d96e407aad42')->setMaxAge(60 * 60 * 24)->setSameSiteRestriction('Strict')->save();
```

### Managing sessions

Using the `Session` class, you can start and resume sessions in a way that is compatible to PHP's built-in `session_start()` function. But additionally, you have access to the improved cookie handling.

Calling `Session::start(...)` with `null` as in

```php
\Delight\Cookie\Session::start(null);
```

is equivalent to `session_start()`. So there's no advantage here, yet. But calling

```php
\Delight\Cookie\Session::start();
// or
\Delight\Cookie\Session::start('Lax');
```

additionally sets the same-site restriction to `Lax` and

```php
\Delight\Cookie\Session::start('Strict');
```

sets the same-site restriction to `Strict`.

All three calls respect the settings from PHP's `session_set_cookie_params(...)` function and the configuration options `session.name`, `session.cookie_lifetime`, `session.cookie_path`, `session.cookie_domain`, `session.cookie_secure`, `session.cookie_httponly` and `session.use_cookies`.

### Parsing cookies

```php
$cookieHeader = 'Set-Cookie: test=php.net; expires=Thu, 09-Jun-2016 16:30:32 GMT; Max-Age=3600; path=/~rasmus/; secure';
$cookieInstance = \Delight\Cookie\Cookie::parse($cookieHeader);
```

## Specifications

 * [RFC 2109](https://tools.ietf.org/html/rfc2109)
 * [RFC 6265](https://tools.ietf.org/html/rfc6265)
 * [Same-site Cookies](https://tools.ietf.org/html/draft-west-first-party-cookies-07)

## Contributing

All contributions are welcome! If you wish to contribute, please create an issue first so that your feature, problem or question can be discussed.

## License

```
Copyright (c) delight.im <info@delight.im>

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

  http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
```
