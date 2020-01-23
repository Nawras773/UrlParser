<?php
namespace App\Utilities;

use Illuminate\Support\Str;

class UrlParser
{

	/**
	 * Parse the input string as url.
	 * @param String $input The url to parse.
	 * @return String
	 */
	public static function parse ($input)
	{
		$url = trim($input);

		// First check if the url is already valid.
		if (self::urlIsValid($url)) return $url;

		// Default scheme. We can not assume that every website uses ssl. So,
		// set the default scheme to http and override this when a scheme is found.
		$scheme = 'http://';
		
		// Parse the given url. This will split up the url into segments.
		$urlSegments = parse_url($url);
		
		// Check if there's a scheme found on the given url.
		if (array_key_exists('scheme', $urlSegments))
		{
			// Yep, found. Update the scheme to the scheme found.
			$scheme = $urlSegments['scheme'] . '://';
		}

		// Try to get the host. If the scheme is omitted, the host will be present
		// as path.
		$host = !empty($urlSegments['host'])
			? $urlSegments['host']
			: $urlSegments['path'];

		// If we don't have a host, we should not use the path, because it's already 
		// used as host.
		$path = empty($urlSegments['host'])
			? null
			: (array_key_exists('path', $urlSegments) 
				? $urlSegments['path']
				: null);

		// Glue everything together again.
		return $scheme . $host . $path;
	}

	/**
	 * Check if the url has already been validated before.
	 * @param String $url The url to check.
	 * @return Boolean
	 */
	private static function urlIsValid ($url)
	{
		return (Str::startsWith($url, 'http://') || Str::startsWith($url, 'https://'));
	}

}
