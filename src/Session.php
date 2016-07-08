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
		// run PHP's built-in `session_start` function
		session_start();

		// get and remove the original cookie header set by `session_start` (if any)
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
