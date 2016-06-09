<?php

/*
 * Copyright (c) delight.im <info@delight.im>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

// enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 'stdout');

// enable assertions
ini_set('assert.active', 1);
ini_set('zend.assertions', 1);
ini_set('assert.exception', 1);

header('Content-type: text/plain; charset=utf-8');

require __DIR__.'/../vendor/autoload.php';

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
testEqual((new \Delight\Cookie\Cookie('SID'))->setValue('31d4d96e407aad42')->setSameSiteRestriction('Strict'), 'Set-Cookie: SID=31d4d96e407aad42; path=/; httponly; SameSite=Strict');

echo 'ALL TESTS PASSED'."\n";

// release the output buffer
ob_end_flush();

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

	if (!assert($actualValue === $expectedValue)) {
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

	return Delight\Http\ResponseHeader::take('Set-Cookie');
}
