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

namespace Delight\Cookie;

/**
 * Modern cookie management for PHP
 *
 * Cookies are a mechanism for storing data in the client's web browser and identifying returning clients on subsequent visits
 *
 * All cookies that have successfully been set will automatically be included in the global `$_COOKIE` array with future requests
 *
 * You can set a new cookie using the static method `Cookie::setcookie(...)` which is compatible to PHP's built-in `setcookie(...)` function
 *
 * Alternatively, you can construct an instance of this class, set properties individually, and finally call `save()`
 *
 * Note that cookies must always be set before the HTTP headers are sent to the client, i.e. before the actual output
 */
final class Cookie {

	const SAME_SITE_RESTRICTION_LAX = 'Lax';
	const SAME_SITE_RESTRICTION_STRICT = 'Strict';

	/** @var string the name of the cookie which is also the key for future accesses via `$_COOKIE[...]` */
	private $name;
	/** @var mixed the value of the cookie that will be stored on the client's machine */
	private $value;
	/** @var int the Unix timestamp indicating the time that the cookie will expire, i.e. usually `time() + $seconds` */
	private $expiryTime;
	/** @var string the path on the server that the cookie will be valid for (including all sub-directories), e.g. an empty string for the current directory or `/` for the root directory */
	private $path;
	/** @var string the domain that the cookie will be valid for (including all subdomains) */
	private $domain;
	/** @var bool indicates that the cookie should be accessible through the HTTP protocol only and not through scripting languages */
	private $httpOnly;
	/** @var bool indicates that the cookie should be sent back by the client over secure HTTPS connections only */
	private $secureOnly;
	/** @var string|null indicates that the cookie should not to be sent along with cross-site requests (either `null`, `Lax` or `Strict`) */
	private $sameSiteRestriction;

	/**
	 * Prepares a new cookie
	 *
	 * @param string $name the name of the cookie which is also the key for future accesses via `$_COOKIE[...]`
	 */
	public function __construct($name) {
		$this->name = $name;
		$this->value = null;
		$this->expiryTime = 0;
		$this->path = '/';
		$this->domain = null;
		$this->httpOnly = true;
		$this->secureOnly = false;
		$this->sameSiteRestriction = self::SAME_SITE_RESTRICTION_LAX;
	}

	/**
	 * Sets the value for the cookie
	 *
	 * @param mixed $value the value of the cookie that will be stored on the client's machine
	 * @return static this instance for chaining
	 */
	public function setValue($value) {
		$this->value = $value;

		return $this;
	}

	/**
	 * Sets the expiry time for the cookie
	 *
	 * @param int $expiryTime the Unix timestamp indicating the time that the cookie will expire, i.e. usually `time() + $seconds`
	 * @return static this instance for chaining
	 */
	public function setExpiryTime($expiryTime) {
		$this->expiryTime = $expiryTime;

		return $this;
	}

	/**
	 * Sets the expiry time for the cookie based on the specified maximum age
	 *
	 * @param int $maxAge the maximum age for the cookie in seconds
	 * @return static this instance for chaining
	 */
	public function setMaxAge($maxAge) {
		$this->expiryTime = time() + $maxAge;

		return $this;
	}

	/**
	 * Sets the path for the cookie
	 *
	 * @param string $path the path on the server that the cookie will be valid for (including all sub-directories), e.g. an empty string for the current directory or `/` for the root directory
	 * @return static this instance for chaining
	 */
	public function setPath($path) {
		$this->path = $path;

		return $this;
	}

	/**
	 * Sets the domain for the cookie
	 *
	 * @param string $domain the domain that the cookie will be valid for (including all subdomains)
	 * @return static this instance for chaining
	 */
	public function setDomain($domain) {
		$this->domain = self::normalizeDomain($domain);

		return $this;
	}

	/**
	 * Sets whether the cookie should be accessible through HTTP only
	 *
	 * @param bool $httpOnly indicates that the cookie should be accessible through the HTTP protocol only and not through scripting languages
	 * @return static this instance for chaining
	 */
	public function setHttpOnly($httpOnly) {
		$this->httpOnly = $httpOnly;

		return $this;
	}

	/**
	 * Sets whether the cookie should be sent over HTTPS only
	 *
	 * @param bool $secureOnly indicates that the cookie should be sent back by the client over secure HTTPS connections only
	 * @return static this instance for chaining
	 */
	public function setSecureOnly($secureOnly) {
		$this->secureOnly = $secureOnly;

		return $this;
	}

	/**
	 * Sets the same-site restriction for the cookie
	 *
	 * @param string|null $sameSiteRestriction indicates that the cookie should not to be sent along with cross-site requests (either `null`, `Lax` or `Strict`)
	 * @return static this instance for chaining
	 */
	public function setSameSiteRestriction($sameSiteRestriction) {
		$this->sameSiteRestriction = $sameSiteRestriction;

		return $this;
	}

	/**
	 * Saves the cookie
	 *
	 * @return bool whether the cookie header has successfully been sent (and will *probably* cause the client to set the cookie)
	 */
	public function save() {
		return self::setHttpHeader((string) $this);
	}

	public function __toString() {
		return self::buildCookieHeader($this->name, $this->value, $this->expiryTime, $this->path, $this->domain, $this->secureOnly, $this->httpOnly, $this->sameSiteRestriction);
	}

	/**
	 * Sets a new cookie in a way compatible to PHP's `setcookie(...)` function
	 *
	 * @param string $name the name of the cookie which is also the key for future accesses via `$_COOKIE[...]`
	 * @param mixed|null $value the value of the cookie that will be stored on the client's machine
	 * @param int $expiryTime the Unix timestamp indicating the time that the cookie will expire, i.e. usually `time() + $seconds`
	 * @param string|null $path the path on the server that the cookie will be valid for (including all sub-directories), e.g. an empty string for the current directory or `/` for the root directory
	 * @param string|null $domain the domain that the cookie will be valid for (including all subdomains)
	 * @param bool $secureOnly indicates that the cookie should be sent back by the client over secure HTTPS connections only
	 * @param bool $httpOnly indicates that the cookie should be accessible through the HTTP protocol only and not through scripting languages
	 * @param string|null $sameSiteRestriction indicates that the cookie should not to be sent along with cross-site requests (either `null`, `Lax` or `Strict`)
	 * @return bool whether the cookie header has successfully been sent (and will *probably* cause the client to set the cookie)
	 */
	public static function setcookie($name, $value = null, $expiryTime = 0, $path = null, $domain = null, $secureOnly = false, $httpOnly = false, $sameSiteRestriction = null) {
		$cookieHeader = self::buildCookieHeader($name, $value, $expiryTime, $path, $domain, $secureOnly, $httpOnly, $sameSiteRestriction);

		return self::setHttpHeader($cookieHeader);
	}

	/**
	 * Builds the HTTP header that can be used to set a cookie with the specified options
	 *
	 * @param string $name the name of the cookie which is also the key for future accesses via `$_COOKIE[...]`
	 * @param mixed|null $value the value of the cookie that will be stored on the client's machine
	 * @param int $expiryTime the Unix timestamp indicating the time that the cookie will expire, i.e. usually `time() + $seconds`
	 * @param string|null $path the path on the server that the cookie will be valid for (including all sub-directories), e.g. an empty string for the current directory or `/` for the root directory
	 * @param string|null $domain the domain that the cookie will be valid for (including all subdomains)
	 * @param bool $secureOnly indicates that the cookie should be sent back by the client over secure HTTPS connections only
	 * @param bool $httpOnly indicates that the cookie should be accessible through the HTTP protocol only and not through scripting languages
	 * @param string|null $sameSiteRestriction indicates that the cookie should not to be sent along with cross-site requests (either `null`, `Lax` or `Strict`)
	 * @return string the HTTP header
	 */
	public static function buildCookieHeader($name, $value = null, $expiryTime = 0, $path = null, $domain = null, $secureOnly = false, $httpOnly = false, $sameSiteRestriction = null) {
		if (self::isNameValid($name)) {
			$name = (string) $name;
		}
		else {
			return null;
		}

		if (self::isExpiryTimeValid($expiryTime)) {
			$expiryTime = (int) $expiryTime;
		}
		else {
			return null;
		}

		$forceShowExpiry = false;

		if (is_null($value) || $value === false || $value === '') {
			$value = 'deleted';
			$expiryTime = 0;
			$forceShowExpiry = true;
		}

		$maxAgeStr = self::formatMaxAge($expiryTime, $forceShowExpiry);
		$expiryTimeStr = self::formatExpiryTime($expiryTime, $forceShowExpiry);

		$headerStr = 'Set-Cookie: '.$name.'='.urlencode($value);

		if (!is_null($expiryTimeStr)) {
			$headerStr .= '; expires='.$expiryTimeStr;
		}

		if (!is_null($maxAgeStr)) {
			$headerStr .= '; Max-Age='.$maxAgeStr;
		}

		if (!empty($path) || $path === 0) {
			$headerStr .= '; path='.$path;
		}

		if (!empty($domain) || $domain === 0) {
			$headerStr .= '; domain='.$domain;
		}

		if ($secureOnly) {
			$headerStr .= '; secure';
		}

		if ($httpOnly) {
			$headerStr .= '; httponly';
		}

		if ($sameSiteRestriction === self::SAME_SITE_RESTRICTION_LAX) {
			$headerStr .= '; SameSite=Lax';
		}
		elseif ($sameSiteRestriction === self::SAME_SITE_RESTRICTION_STRICT) {
			$headerStr .= '; SameSite=Strict';
		}

		return $headerStr;
	}

	private static function isNameValid($name) {
		if (is_string($name) || is_null($name) || is_int($name) || is_float($name) || is_bool($name)) {
			if (!preg_match('/[=,; \\t\\r\\n\\013\\014]/', (string) $name)) {
				return true;
			}
		}

		return false;
	}

	private static function isExpiryTimeValid($expiryTime) {
		return is_numeric($expiryTime) || is_null($expiryTime) || is_bool($expiryTime);
	}

	private static function calculateMaxAge($expiryTime) {
		if ($expiryTime === 0) {
			return 0;
		}
		else {
			return $expiryTime - time();
		}
	}

	private static function formatExpiryTime($expiryTime, $forceShow = false) {
		if ($expiryTime > 0 || $forceShow) {
			if ($forceShow) {
				$expiryTime = 1;
			}

			return gmdate('D, d-M-Y H:i:s T', $expiryTime);
		}
		else {
			return null;
		}
	}

	private static function formatMaxAge($expiryTime, $forceShow = false) {
		if ($expiryTime > 0 || $forceShow) {
			return (string) self::calculateMaxAge($expiryTime);
		}
		else {
			return null;
		}
	}

	private static function normalizeDomain($domain) {
		if (isset($domain[0]) && $domain[0] !== '.') {
			$domain = '.'.$domain;
		}

		return $domain;
	}

	private static function setHttpHeader($header) {
		if (!headers_sent()) {
			if (!empty($header)) {
				header($header);

				return true;
			}
		}

		return false;
	}

}
