<?php

/*
 * PHP-Cookie (https://github.com/delight-im/PHP-Cookie)
 * Copyright (c) delight.im (https://www.delight.im/)
 * Licensed under the MIT License (https://opensource.org/licenses/MIT)
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
 * Note that cookies must always be set before the HTTP headers are sent to the client, i.e. before the actual output starts
 */
final class Cookie {

	const SAME_SITE_RESTRICTION_LAX = 'Lax';
	const SAME_SITE_RESTRICTION_STRICT = 'Strict';

	/** @var string the name of the cookie which is also the key for future accesses via `$_COOKIE[...]` */
	private $name;
	/** @var mixed|null the value of the cookie that will be stored on the client's machine */
	private $value;
	/** @var int the Unix timestamp indicating the time that the cookie will expire at, i.e. usually `time() + $seconds` */
	private $expiryTime;
	/** @var string the path on the server that the cookie will be valid for (including all sub-directories), e.g. an empty string for the current directory or `/` for the root directory */
	private $path;
	/** @var string|null the domain that the cookie will be valid for (including all subdomains) */
	private $domain;
	/** @var bool indicates that the cookie should be accessible through the HTTP protocol only and not through scripting languages */
	private $httpOnly;
	/** @var bool indicates that the cookie should be sent back by the client over secure HTTPS connections only */
	private $secureOnly;
	/** @var string|null indicates that the cookie should not be sent along with cross-site requests (either `null`, `Lax` or `Strict`) */
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
		$this->domain = self::normalizeDomain($_SERVER['HTTP_HOST']);
		$this->httpOnly = true;
		$this->secureOnly = false;
		$this->sameSiteRestriction = self::SAME_SITE_RESTRICTION_LAX;
	}

	/**
	 * Returns the name of the cookie
	 *
	 * @return string the name of the cookie which is also the key for future accesses via `$_COOKIE[...]`
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Returns the value of the cookie
	 *
	 * @return mixed|null the value of the cookie that will be stored on the client's machine
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * Sets the value for the cookie
	 *
	 * @param mixed|null $value the value of the cookie that will be stored on the client's machine
	 * @return static this instance for chaining
	 */
	public function setValue($value) {
		$this->value = $value;

		return $this;
	}

	/**
	 * Returns the expiry time of the cookie
	 *
	 * @return int the Unix timestamp indicating the time that the cookie will expire at, i.e. usually `time() + $seconds`
	 */
	public function getExpiryTime() {
		return $this->expiryTime;
	}

	/**
	 * Sets the expiry time for the cookie
	 *
	 * @param int $expiryTime the Unix timestamp indicating the time that the cookie will expire at, i.e. usually `time() + $seconds`
	 * @return static this instance for chaining
	 */
	public function setExpiryTime($expiryTime) {
		$this->expiryTime = $expiryTime;

		return $this;
	}

	/**
	 * Returns the maximum age of the cookie (i.e. the remaining lifetime)
	 *
	 * @return int the maximum age of the cookie in seconds
	 */
	public function getMaxAge() {
		return $this->expiryTime - time();
	}

	/**
	 * Sets the expiry time for the cookie based on the specified maximum age (i.e. the remaining lifetime)
	 *
	 * @param int $maxAge the maximum age for the cookie in seconds
	 * @return static this instance for chaining
	 */
	public function setMaxAge($maxAge) {
		$this->expiryTime = time() + $maxAge;

		return $this;
	}

	/**
	 * Returns the path of the cookie
	 *
	 * @return string the path on the server that the cookie will be valid for (including all sub-directories), e.g. an empty string for the current directory or `/` for the root directory
	 */
	public function getPath() {
		return $this->path;
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
	 * Returns the domain of the cookie
	 *
	 * @return string|null the domain that the cookie will be valid for (including all subdomains)
	 */
	public function getDomain() {
		return $this->domain;
	}

	/**
	 * Sets the domain for the cookie
	 *
	 * @param string $domain the domain that the cookie will be valid for (including all subdomains)
	 * @param bool $keepWww whether a leading `www` subdomain must be preserved or not
	 * @return static this instance for chaining
	 */
	public function setDomain($domain, $keepWww = false) {
		$this->domain = self::normalizeDomain($domain, $keepWww);

		return $this;
	}

	/**
	 * Returns whether the cookie should be accessible through HTTP only
	 *
	 * @return bool whether the cookie should be accessible through the HTTP protocol only and not through scripting languages
	 */
	public function isHttpOnly() {
		return $this->httpOnly;
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
	 * Returns whether the cookie should be sent over HTTPS only
	 *
	 * @return bool whether the cookie should be sent back by the client over secure HTTPS connections only
	 */
	public function isSecureOnly() {
		return $this->secureOnly;
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
	 * Returns the same-site restriction of the cookie
	 *
	 * @return string|null whether the cookie should not be sent along with cross-site requests (either `null`, `Lax` or `Strict`)
	 */
	public function getSameSiteRestriction() {
		return $this->sameSiteRestriction;
	}

	/**
	 * Sets the same-site restriction for the cookie
	 *
	 * @param string|null $sameSiteRestriction indicates that the cookie should not be sent along with cross-site requests (either `null`, `Lax` or `Strict`)
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
		return self::addHttpHeader((string) $this);
	}

	/**
	 * Deletes the cookie
	 *
	 * @return bool whether the cookie header has successfully been sent (and will *probably* cause the client to delete the cookie)
	 */
	public function delete() {
		// create a temporary copy of this cookie so that it isn't corrupted
		$copiedCookie = clone $this;
		// set the copied cookie's value to an empty string which internally sets the required options for a deletion
		$copiedCookie->setValue('');

		// save the copied "deletion" cookie
		return $copiedCookie->save();
	}

	public function __toString() {
		return self::buildCookieHeader($this->name, $this->value, $this->expiryTime, $this->path, $this->domain, $this->secureOnly, $this->httpOnly, $this->sameSiteRestriction);
	}

	/**
	 * Sets a new cookie in a way compatible to PHP's `setcookie(...)` function
	 *
	 * @param string $name the name of the cookie which is also the key for future accesses via `$_COOKIE[...]`
	 * @param mixed|null $value the value of the cookie that will be stored on the client's machine
	 * @param int $expiryTime the Unix timestamp indicating the time that the cookie will expire at, i.e. usually `time() + $seconds`
	 * @param string|null $path the path on the server that the cookie will be valid for (including all sub-directories), e.g. an empty string for the current directory or `/` for the root directory
	 * @param string|null $domain the domain that the cookie will be valid for (including all subdomains)
	 * @param bool $secureOnly indicates that the cookie should be sent back by the client over secure HTTPS connections only
	 * @param bool $httpOnly indicates that the cookie should be accessible through the HTTP protocol only and not through scripting languages
	 * @param string|null $sameSiteRestriction indicates that the cookie should not be sent along with cross-site requests (either `null`, `Lax` or `Strict`)
	 * @return bool whether the cookie header has successfully been sent (and will *probably* cause the client to set the cookie)
	 */
	public static function setcookie($name, $value = null, $expiryTime = 0, $path = null, $domain = null, $secureOnly = false, $httpOnly = false, $sameSiteRestriction = null) {
		$cookieHeader = self::buildCookieHeader($name, $value, $expiryTime, $path, $domain, $secureOnly, $httpOnly, $sameSiteRestriction);

		return self::addHttpHeader($cookieHeader);
	}

	/**
	 * Builds the HTTP header that can be used to set a cookie with the specified options
	 *
	 * @param string $name the name of the cookie which is also the key for future accesses via `$_COOKIE[...]`
	 * @param mixed|null $value the value of the cookie that will be stored on the client's machine
	 * @param int $expiryTime the Unix timestamp indicating the time that the cookie will expire at, i.e. usually `time() + $seconds`
	 * @param string|null $path the path on the server that the cookie will be valid for (including all sub-directories), e.g. an empty string for the current directory or `/` for the root directory
	 * @param string|null $domain the domain that the cookie will be valid for (including all subdomains)
	 * @param bool $secureOnly indicates that the cookie should be sent back by the client over secure HTTPS connections only
	 * @param bool $httpOnly indicates that the cookie should be accessible through the HTTP protocol only and not through scripting languages
	 * @param string|null $sameSiteRestriction indicates that the cookie should not be sent along with cross-site requests (either `null`, `Lax` or `Strict`)
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

		if (version_compare(phpversion(), '5.5.0', '>=')) {
			if (!is_null($maxAgeStr)) {
				$headerStr .= '; Max-Age='.$maxAgeStr;
			}
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

	/**
	 * Parses the given cookie header and returns an equivalent cookie instance
	 *
	 * @param string $cookieHeader the cookie header to parse
	 * @return \Delight\Cookie\Cookie|null the cookie instance or `null`
	 */
	public static function parse($cookieHeader) {
		if (empty($cookieHeader)) {
			return null;
		}

		if (preg_match('/^Set-Cookie: (.*?)=(.*?)(?:; (.*?))?$/i', $cookieHeader, $matches)) {
			if (count($matches) >= 4) {
				$attributes = explode('; ', $matches[3]);

				$cookie = new self($matches[1]);
				$cookie->setPath(null);
				$cookie->setHttpOnly(false);
				$cookie->setValue($matches[2]);

				foreach ($attributes as $attribute) {
					if (strcasecmp($attribute, 'HttpOnly') === 0) {
						$cookie->setHttpOnly(true);
					}
					elseif (strcasecmp($attribute, 'Secure') === 0) {
						$cookie->setSecureOnly(true);
					}
					elseif (stripos($attribute, 'Expires=') === 0) {
						$cookie->setExpiryTime((int) strtotime(substr($attribute, 8)));
					}
					elseif (stripos($attribute, 'Domain=') === 0) {
						$cookie->setDomain(substr($attribute, 7), true);
					}
					elseif (stripos($attribute, 'Path=') === 0) {
						$cookie->setPath(substr($attribute, 5));
					}
				}

				return $cookie;
			}
			else {
				return null;
			}
		}
		else {
			return null;
		}
	}

	private static function isNameValid($name) {
		$name = (string) $name;

		if ($name !== '' || version_compare(phpversion(), '7.0.0', '<')) {
			if (!preg_match('/[=,; \\t\\r\\n\\013\\014]/', $name)) {
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

	private static function normalizeDomain($domain, $keepWww = false) {
		// make sure the domain is actually a string
		$domain = (string) $domain;

		// if the cookie should be valid for the current host only
		if ($domain === '') {
			// no need for further normalization
			return null;
		}

		// if the provided domain is actually an IP address
		if (filter_var($domain, FILTER_VALIDATE_IP) !== false) {
			// let the cookie be valid for the current host
			return null;
		}

		// for local hostnames (which either have no dot at all or a leading dot only)
		if (strpos($domain, '.') === false || strrpos($domain, '.') === 0) {
			// let the cookie be valid for the current host while ensuring maximum compatibility
			return null;
		}

		// unless the domain already starts with a dot
		if ($domain[0] !== '.') {
			// prepend a dot for maximum compatibility (e.g. with RFC 2109)
			$domain = '.' . $domain;
		}

		// if a leading `www` subdomain may be dropped
		if (!$keepWww) {
			// if the domain name actually starts with a `www` subdomain
			if (substr($domain, 0, 5) === '.www.') {
				// strip that subdomain
				$domain = substr($domain, 4);
			}
		}

		// return the normalized domain
		return $domain;
	}

	private static function addHttpHeader($header) {
		if (!headers_sent()) {
			if (!empty($header)) {
				header($header, false);

				return true;
			}
		}

		return false;
	}

}
