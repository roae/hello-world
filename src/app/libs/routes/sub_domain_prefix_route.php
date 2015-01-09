<?php

/*
 *    This class allows automatic adding of "prefix" to a route using a sub-domain as the "prefix route".
 *    It will not interfere with any existing prefix routes.
 *
 *    Basically, if a sub-domain matches one of the defined "prefix routes", then currently defined
 *    routes that do not contain a prefix will be modified to use the sub-domain as the prefix.
 *    Routes with a pre-existing prefix will not be modified.
 */

class SubDomainPrefixRoute extends CakeRoute {

	function parse($url) {
		//    Get an instance of the Router
		$router = & Router::getInstance();

		//    Get an array of all defined prefixes
		$prefixes = $router->prefixes();

		// Only proceed if prefixes have been defined...
		if ((is_array($prefixes)) && (count($prefixes, COUNT_RECURSIVE) > 0)) {
			//    Get the sub-domain from the current URL, and convert to lower-case
			$url_parts = explode('.', env('HTTP_HOST'));

			$subdomain = strtolower(trim($url_parts[0]));

			//    Assuming all routing prefixes are lower-case, check for a match between a prefix and the current URL sub-domain
			if (in_array($subdomain, $prefixes)) {
				//    Iterate each defined route...
				foreach ($router->routes as $route) {
					//    Check to make sure we are not trying to modify our own route definition...
					if (!($route instanceof SubDomainPrefixRoute)) {
						//    Check, just in case...
						if (isset($route->defaults)) {
							//    If a prefix has not been defined for this particular route, then we can add a prefix using the current URL sub-domain...
							if (!isset($route->defaults['prefix'])) {
								$route->defaults['prefix'] = $subdomain;
								$route->defaults[$subdomain] = true;
							}
						}
					}
				}
			}
		}

		return false;		// We do not want this route to be matched, so we return false in order that Cake can continue checking routes for a match
	}

}

?>