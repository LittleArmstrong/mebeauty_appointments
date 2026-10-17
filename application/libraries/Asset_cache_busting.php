<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Asset cache busting library (fork, spec 0022).
 *
 * Decides the cache-busting token of an asset URL. `asset_url()` (`application/helpers/asset_helper.php`) maps the
 * URI to the file actually served (`.min.js`/`.min.css` unless debug mode) and calls this rule with that URI.
 *
 * Rules:
 * - The token is the modification time of the served file, so a rebuilt or redeployed file gets a new URL and the
 *   browser loads it on a normal reload.
 * - When the file cannot be read (missing, directory, URI with a query), the configured token is the fallback.
 *
 * @package Libraries
 */
class Asset_cache_busting
{
    /**
     * Cache-busting token of an asset URL.
     *
     * @param string $root Directory the URI is relative to (FCPATH).
     * @param string $uri Relative URI of the served file.
     * @param string $fallback Configured token ("cache_busting_token").
     *
     * @return string Returns the modification time of the served file, or the fallback.
     */
    public function token(string $root, string $uri, string $fallback): string
    {
        $path = rtrim($root, '/') . '/' . ltrim($uri, '/');

        $mtime = is_file($path) ? @filemtime($path) : false;

        return $mtime === false ? $fallback : (string) $mtime;
    }
}
