<?php

/*
 * PHP-Cookie (https://github.com/delight-im/PHP-Cookie)
 * Copyright (c) delight.im (https://www.delight.im/)
 * Licensed under the MIT License (https://opensource.org/licenses/MIT)
 */

// enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 'stdout');

header('Content-type: text/plain; charset=utf-8');

require __DIR__.'/../vendor/autoload.php';

/* BEGIN TEST COOKIES */

// start output buffering
ob_start();

testCookie(null);
testCookie(false);
testCookie('');
testCookie(0);
testCookie('hello');
testCookie('hello', false);
testCookie('hello', true);
testCookie('hello', null);
testCookie('hello', '');
testCookie('hello', 0);
testCookie('hello', 1);
testCookie('hello', 'world');
testCookie('hello', 123);
testCookie(123, 'world');
testCookie('greeting', '¡Buenos días!');
testCookie('¡Buenos días!', 'greeting');
testCookie('%a|b}c_$d!f"g-h(i)j$', 'value value value');
testCookie('%a|b}c_$d!f"g-h(i)j$', '%a|b}c_$d!f"g-h(i)j$');
testCookie('hello', 'world', '!');
testCookie('hello', 'world', '');
testCookie('hello', 'world', false);
testCookie('hello', 'world', null);
testCookie('hello', 'world', true);
testCookie('hello', 'world', 0);
testCookie('hello', 'world', '');
testCookie('hello', 'world', -1);
testCookie('hello', 'world', 234234);
testCookie('hello', 'world', time() + 60 * 60 * 24);
testCookie('hello', 'world', time() + 60 * 60 * 24 * 30);
testCookie('hello', 'world', time() + 86400, null);
testCookie('hello', 'world', time() + 86400, false);
testCookie('hello', 'world', time() + 86400, true);
testCookie('hello', 'world', time() + 86400, 0);
testCookie('hello', 'world', time() + 86400, '');
testCookie('hello', 'world', time() + 86400, '/');
testCookie('hello', 'world', time() + 86400, '/foo');
testCookie('hello', 'world', time() + 86400, '/foo/');
testCookie('hello', 'world', time() + 86400, '/buenos/días/');
testCookie('hello', 'world', time() + 86400, '/buenos días/');
testCookie('hello', 'world', time() + 86400, '/foo/', null);
testCookie('hello', 'world', time() + 86400, '/foo/', false);
testCookie('hello', 'world', time() + 86400, '/foo/', true);
testCookie('hello', 'world', time() + 86400, '/foo/', 0);
testCookie('hello', 'world', time() + 86400, '/foo/', '');
testCookie('hello', 'world', time() + 86400, '/foo/', 'example.com');
testCookie('hello', 'world', time() + 86400, '/foo/', '.example.com');
testCookie('hello', 'world', time() + 86400, '/foo/', 'www.example.com');
testCookie('hello', 'world', time() + 86400, '/foo/', 'días.example.com');
testCookie('hello', 'world', time() + 86400, '/foo/', 'localhost');
testCookie('hello', 'world', time() + 86400, '/foo/', '127.0.0.1');
testCookie('hello', 'world', time() + 86400, '/foo/', 'example.com', null);
testCookie('hello', 'world', time() + 86400, '/foo/', 'example.com', false);
testCookie('hello', 'world', time() + 86400, '/foo/', 'example.com', true);
testCookie('hello', 'world', time() + 86400, '/foo/', 'example.com', 0);
testCookie('hello', 'world', time() + 86400, '/foo/', 'example.com', '');
testCookie('hello', 'world', time() + 86400, '/foo/', 'example.com', 'hello');
testCookie('hello', 'world', time() + 86400, '/foo/', 'example.com', 7);
testCookie('hello', 'world', time() + 86400, '/foo/', 'example.com', -7);
testCookie('hello', 'world', time() + 86400, '/foo/', 'example.com', false, null);
testCookie('hello', 'world', time() + 86400, '/foo/', 'example.com', false, false);
testCookie('hello', 'world', time() + 86400, '/foo/', 'example.com', false, true);
testCookie('hello', 'world', time() + 86400, '/foo/', 'example.com', false, 0);
testCookie('hello', 'world', time() + 86400, '/foo/', 'example.com', false, '');
testCookie('hello', 'world', time() + 86400, '/foo/', 'example.com', false, 'hello');
testCookie('hello', 'world', time() + 86400, '/foo/', 'example.com', false, 5);
testCookie('hello', 'world', time() + 86400, '/foo/', 'example.com', false, -5);
testCookie('hello', 'world', time() + 86400, '/foo/', 'example.com', true, null);
testCookie('hello', 'world', time() + 86400, '/foo/', 'example.com', true, false);
testCookie('hello', 'world', time() + 86400, '/foo/', 'example.com', true, true);
testCookie('hello', 'world', time() + 86400, '/foo/', 'example.com', true, 0);
testCookie('hello', 'world', time() + 86400, '/foo/', 'example.com', true, '');
testCookie('hello', 'world', time() + 86400, '/foo/', 'example.com', true, 'hello');
testCookie('hello', 'world', time() + 86400, '/foo/', 'example.com', true, 5);
testCookie('hello', 'world', time() + 86400, '/foo/', 'example.com', true, -5);
testCookie('TestCookie', 'php.net');
testCookie('TestCookie', 'php.net', time() + 3600);
testCookie('TestCookie', 'php.net', time() + 3600, '/~rasmus/', 'example.com', 1);
testCookie('TestCookie', '', time() - 3600);
testCookie('TestCookie', '', time() - 3600, '/~rasmus/', 'example.com', 1);
testCookie('cookie[three]', 'cookiethree');
testCookie('cookie[two]', 'cookietwo');
testCookie('cookie[one]', 'cookieone');
$cookieSID = new \Delight\Cookie\Cookie('SID');
testEqual($cookieSID->setValue('31d4d96e407aad42')->setDomain('localhost')->setSameSiteRestriction('Strict'), 'Set-Cookie: SID=31d4d96e407aad42; path=/; httponly; SameSite=Strict');
$cookieKey = new \Delight\Cookie\Cookie('key');
testEqual($cookieKey->setValue('value')->setDomain('localhost'), 'Set-Cookie: key=value; path=/; httponly; SameSite=Lax');
$cookieKey = new \Delight\Cookie\Cookie('key');
testEqual($cookieKey->setValue('value')->setDomain('.localhost'), 'Set-Cookie: key=value; path=/; httponly; SameSite=Lax');
$cookieKey = new \Delight\Cookie\Cookie('key');
testEqual($cookieKey->setValue('value')->setDomain('127.0.0.1'), 'Set-Cookie: key=value; path=/; httponly; SameSite=Lax');
$cookieKey = new \Delight\Cookie\Cookie('key');
testEqual($cookieKey->setValue('value')->setDomain('.local'), 'Set-Cookie: key=value; path=/; httponly; SameSite=Lax');
$cookieKey = new \Delight\Cookie\Cookie('key');
testEqual($cookieKey->setValue('value')->setDomain('example.com'), 'Set-Cookie: key=value; path=/; domain=.example.com; httponly; SameSite=Lax');
$cookieKey = new \Delight\Cookie\Cookie('key');
testEqual($cookieKey->setValue('value')->setDomain('.example.com'), 'Set-Cookie: key=value; path=/; domain=.example.com; httponly; SameSite=Lax');
$cookieKey = new \Delight\Cookie\Cookie('key');
testEqual($cookieKey->setValue('value')->setDomain('www.example.com'), 'Set-Cookie: key=value; path=/; domain=.example.com; httponly; SameSite=Lax');
$cookieKey = new \Delight\Cookie\Cookie('key');
testEqual($cookieKey->setValue('value')->setDomain('.www.example.com'), 'Set-Cookie: key=value; path=/; domain=.example.com; httponly; SameSite=Lax');
$cookieKey = new \Delight\Cookie\Cookie('key');
testEqual($cookieKey->setValue('value')->setDomain('www.example.com', true), 'Set-Cookie: key=value; path=/; domain=.www.example.com; httponly; SameSite=Lax');
$cookieKey = new \Delight\Cookie\Cookie('key');
testEqual($cookieKey->setValue('value')->setDomain('.www.example.com', true), 'Set-Cookie: key=value; path=/; domain=.www.example.com; httponly; SameSite=Lax');
$cookieKey = new \Delight\Cookie\Cookie('key');
testEqual($cookieKey->setValue('value')->setDomain('blog.example.com'), 'Set-Cookie: key=value; path=/; domain=.blog.example.com; httponly; SameSite=Lax');
$cookieKey = new \Delight\Cookie\Cookie('key');
testEqual($cookieKey->setValue('value')->setDomain('.blog.example.com'), 'Set-Cookie: key=value; path=/; domain=.blog.example.com; httponly; SameSite=Lax');

setcookie('hello', 'world', time() + 86400, '/foo/', 'example.com', true, true);
$cookie = \Delight\Cookie\Cookie::parse(\Delight\Http\ResponseHeader::take('Set-Cookie'));

$cookieHello = new \Delight\Cookie\Cookie('hello');
testEqual($cookie, $cookieHello->setValue('world')->setMaxAge(86400)->setPath('/foo/')->setDomain('example.com')->setHttpOnly(true)->setSecureOnly(true));

($cookie->getName() === 'hello') or fail(__LINE__);
($cookie->getValue() === 'world') or fail(__LINE__);
($cookie->getExpiryTime() === time() + 86400) or fail(__LINE__);
($cookie->getMaxAge() === 86400) or fail(__LINE__);
($cookie->getPath() === '/foo/') or fail(__LINE__);
($cookie->getDomain() === '.example.com') or fail(__LINE__);
($cookie->isHttpOnly() === true) or fail(__LINE__);
($cookie->isSecureOnly() === true) or fail(__LINE__);
($cookie->getSameSiteRestriction() === \Delight\Cookie\Cookie::SAME_SITE_RESTRICTION_LAX) or fail(__LINE__);

/* END TEST COOKIES */

/* BEGIN TEST SESSION */

// enable assertions
ini_set('assert.active', 1);
ini_set('zend.assertions', 1);
ini_set('assert.exception', 1);

assert(isset($_SESSION) === false);
assert(\Delight\Cookie\Session::id() === '');

\Delight\Cookie\Session::start();

assert(isset($_SESSION) === true);
assert(\Delight\Cookie\Session::id() !== '');

$oldSessionId = \Delight\Cookie\Session::id();
\Delight\Cookie\Session::regenerate();
assert(\Delight\Cookie\Session::id() !== $oldSessionId);
assert(\Delight\Cookie\Session::id() !== null);

session_unset();

assert(isset($_SESSION['key1']) === false);
assert(\Delight\Cookie\Session::has('key1') === false);
assert(\Delight\Cookie\Session::get('key1') === null);
assert(\Delight\Cookie\Session::get('key1', 5) === 5);
assert(\Delight\Cookie\Session::get('key1', 'monkey') === 'monkey');

\Delight\Cookie\Session::set('key1', 'value1');

assert(isset($_SESSION['key1']) === true);
assert(\Delight\Cookie\Session::has('key1') === true);
assert(\Delight\Cookie\Session::get('key1') === 'value1');
assert(\Delight\Cookie\Session::get('key1', 5) === 'value1');
assert(\Delight\Cookie\Session::get('key1', 'monkey') === 'value1');

assert(\Delight\Cookie\Session::take('key1') === 'value1');
assert(\Delight\Cookie\Session::take('key1') === null);
assert(\Delight\Cookie\Session::take('key1', 'value2') === 'value2');
assert(isset($_SESSION['key1']) === false);
assert(\Delight\Cookie\Session::has('key1') === false);

\Delight\Cookie\Session::set('key2', 'value3');

assert(isset($_SESSION['key2']) === true);
assert(\Delight\Cookie\Session::has('key2') === true);
assert(\Delight\Cookie\Session::get('key2', 'value4') === 'value3');
\Delight\Cookie\Session::delete('key2');
assert(\Delight\Cookie\Session::get('key2', 'value4') === 'value4');
assert(\Delight\Cookie\Session::get('key2') === null);
assert(\Delight\Cookie\Session::has('key2') === false);

session_destroy();

/* END TEST SESSION */

echo 'ALL TESTS PASSED'."\n";

function testCookie($name, $value = null, $expire = 0, $path = null, $domain = null, $secure = false, $httpOnly = false) {
	$actualValue = \Delight\Cookie\Cookie::buildCookieHeader($name, $value, $expire, $path, $domain, $secure, $httpOnly);
	if (is_null($actualValue)) {
		$expectedValue = @simulateSetCookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
	}
	else {
		$expectedValue = simulateSetCookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
	}

	testEqual($actualValue, $expectedValue);
}

function testEqual($actualValue, $expectedValue) {
	$actualValue = (string) $actualValue;
	$expectedValue = (string) $expectedValue;

	echo '[';
	echo $expectedValue;
	echo ']';
	echo "\n";

	if (strcasecmp($actualValue, $expectedValue) !== 0) {
		echo 'FAILED: ';
		echo '[';
		echo $actualValue;
		echo ']';
		echo ' !== ';
		echo '[';
		echo $expectedValue;
		echo ']';
		echo "\n";

		exit;
	}
}

function simulateSetCookie($name, $value = null, $expire = 0, $path = null, $domain = null, $secure = false, $httpOnly = false) {
	setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);

	return \Delight\Http\ResponseHeader::take('Set-Cookie');
}

function fail($lineNumber) {
	exit('Error in line '.$lineNumber);
}
