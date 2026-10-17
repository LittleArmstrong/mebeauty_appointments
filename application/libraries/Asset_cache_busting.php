<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Asset cache busting library (fork, spec 0022).
 *
 * Decides the served file and the cache-busting token of an asset URL for `asset_url()`
 * (`application/helpers/asset_helper.php`).
 *
 * Rules:
 * - Outside debug mode the served file of `.js`/`.css` is `.min.js`/`.min.css` (upstream rule).
 * - The token is the modification time of the served file, so a rebuilt or redeployed file gets a new URL and the
 *   browser loads it on a normal reload.
 * - When the file cannot be read (missing, directory, URI with a query), the configured token is the fallback.
 *
 * @package Libraries
 */
class Asset_cache_busting
{
    /**
     * URI of the file actually served: outside debug mode `.js`/`.css` map to `.min.js`/`.min.css` (upstream rule
     * of `asset_url()`, moved here unchanged so the token is taken from that file).
     *
     * @param string $uri Relative URI as passed to asset_url().
     * @param bool $debug Debug mode ("debug" config).
     *
     * @return string Returns the URI of the served file.
     */
    public function served_uri(string $uri, bool $debug): string
    {
        if (str_contains(basename($uri), '.js') && !str_contains(basename($uri), '.min.js') && !$debug) {
            $uri = str_replace('.js', '.min.js', $uri);
        }

        if (str_contains(basename($uri), '.css') && !str_contains(basename($uri), '.min.css') && !$debug) {
            $uri = str_replace('.css', '.min.css', $uri);
        }

        return $uri;
    }

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
