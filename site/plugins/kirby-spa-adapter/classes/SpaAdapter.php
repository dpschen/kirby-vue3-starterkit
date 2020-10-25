<?php

namespace KirbyExtended;

use InvalidArgumentException;
use Kirby\Cms\Url;
use Kirby\Exception\Exception;

class SpaAdapter {
    /**
     * Relative path to assets dir
     *
     * @var string
     */
    public static string $assetsDir;

    /**
     * API location for content
     *
     * @var string
     */
    public static string $apiLocation;

    /**
     * Global `site` data for the index template
     *
     * @var array
     */
    public static array $site;

    /**
     * Get and cache `$assetsDir`
     *
     * @return string
     */
    public static function useAssetsDir(): string {
        return static::$assetsDir ??= Url::path(env('VITE_ASSETS_DIR'), true);
    }

    /**
     * Get and cache `$apiLocation`
     *
     * @return string
     */
    public static function useApiLocation(): string {
        return static::$apiLocation ??= Url::path(env('CONTENT_API_SLUG'), true);
    }

    /**
     * Get and cache `$site`
     *
     * @return array
     */
    public static function useSite(): array {
        return static::$site ??= require kirby()->root('config') . '/spa-site.php';
    }

    /**
     * Returns the filename for a build asset, e.g. `style.d4814c7a.css`
     *
     * @param string $pattern A pattern to be matched by `glob`
     * @return string
     * @throws Exception
     */
    public static function pathToAsset (string $pattern): string {
        $match = glob(kirby()->root() . static::useAssetsDir() . '/' . $pattern);
        if (empty($match)) {
            throw new Exception('No production assets found. You have to bundle the app first. Run `npm run build`.');
        }

        return static::useAssetsDir() . '/' . basename($match[0]);
    }

    /**
     * Preloads the JSON-encoded page data for a given page
     *
     * @param string $name Page id
     * @return string
     * @throws InvalidArgumentException
     */
    public static function jsonPreloadLink (string $name): string {
        return '<link rel="preload" href="' . static::useApiLocation() . '/' . $name . '.json" as="fetch" crossorigin>';
    }

    /**
     * Preloads the view module for a given page, e.g. `Home.e701bdef.js`
     *
     * @param string $name Page template name or other module name
     * @return string|void
     * @throws InvalidArgumentException
     */
    public static function modulePreloadLink (string $name) {
        $match = glob(kirby()->root() . static::useAssetsDir() . '/' . ucfirst($name) . '.*.js');
        if (!empty($match)) {
            return '<link rel="modulepreload" href="' . static::useAssetsDir() . '/' . basename($match[0]) . '">';
        }
    }
}
