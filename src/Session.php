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

use Delight\Http\ResponseHeader;

/**
 * Session management with improved cookie handling
 *
 * You can start a session using the static method `Session::start(...)` which is compatible to PHP's built-in `session_start()` function
 *
 * Note that sessions must always be started before the HTTP headers are sent to the client, i.e. before the actual output starts
 */
final class Session {

	private function __construct() { }

	/**
	 * Starts or resumes a session in a way compatible to PHP's built-in `session_start()` function
	 *
	 * @param string|null $sameSiteRestriction indicates that the cookie should not be sent along with cross-site requests (either `null`, `Lax` or `Strict`)
	 */
	public static function start($sameSiteRestriction = Cookie::SAME_SITE_RESTRICTION_LAX) {
		// run PHP's built-in equivalent
		session_start();

		// intercept the cookie header (if any) and rewrite it
		self::rewriteCookieHeader($sameSiteRestriction);
	}

	/**
	 * Returns the ID of the current session
	 *
	 * @return string the session ID or an empty string
	 */
	public static function id() {
		return session_id();
	}

	/**
	 * Re-generates the session ID in a way compatible to PHP's built-in `session_regenerate_id()` function
	 *
	 * @param bool $deleteOldSession whether to delete the old session or not
	 * @param string|null $sameSiteRestriction indicates that the cookie should not be sent along with cross-site requests (either `null`, `Lax` or `Strict`)
	 */
	public static function regenerate($deleteOldSession = false, $sameSiteRestriction = Cookie::SAME_SITE_RESTRICTION_LAX) {
		// run PHP's built-in equivalent
		session_regenerate_id($deleteOldSession);

		// intercept the cookie header (if any) and rewrite it
		self::rewriteCookieHeader($sameSiteRestriction);
	}

	/**
	 * Checks whether a value for the specified key exists in the session
	 *
	 * @param string $key the key to check
	 * @return bool whether there is a value for the specified key or not
	 */
	public static function has($key) {
		return isset($_SESSION[$key]);
	}

	/**
	 * Returns the requested value from the session or, if not found, the specified default value
	 *
	 * @param string $key the key to retrieve the value for
	 * @param mixed $defaultValue the default value to return if the requested value cannot be found
	 * @return mixed the requested value or the default value
	 */
	public static function get($key, $defaultValue = null) {
		if (isset($_SESSION[$key])) {
			return $_SESSION[$key];
		}
		else {
			return $defaultValue;
		}
	}

	/**
	 * Intercepts and rewrites the session cookie header
	 *
	 * @param string|null $sameSiteRestriction indicates that the cookie should not be sent along with cross-site requests (either `null`, `Lax` or `Strict`)
	 */
	private static function rewriteCookieHeader($sameSiteRestriction = Cookie::SAME_SITE_RESTRICTION_LAX) {
		// get and remove the original cookie header set by PHP
		$originalCookieHeader = ResponseHeader::take('Set-Cookie', session_name().'=');

		// if a cookie header has been found
		if (isset($originalCookieHeader)) {
			// parse it into a cookie instance
			$parsedCookie = Cookie::parse($originalCookieHeader);

			// if the cookie has successfully been parsed
			if (isset($parsedCookie)) {
				// apply the supplied same-site restriction
				$parsedCookie->setSameSiteRestriction($sameSiteRestriction);
				// save the cookie
				$parsedCookie->save();
			}
		}
	}

}
