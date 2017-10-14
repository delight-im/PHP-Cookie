# Migration

## From `v2.x.x` to `v3.x.x`

 * For the domain scope, `www` subdomains are not automatically widened to the bare domain anymore. If you want to include the bare domain and all subdomains in addition to the `www` subdomain, you must now explicitly specifiy the bare domain instead of the `www` subdomain as the scope.
 * The second parameter of the `Cookie#setDomain` method, which was named `$keepWww`, has been removed.

## From `v1.x.x` to `v2.x.x`

 * The license has been changed from the [Apache License 2.0](http://www.apache.org/licenses/LICENSE-2.0) to the [MIT License](https://opensource.org/licenses/MIT).
