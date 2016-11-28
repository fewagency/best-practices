<?php

/*
Valet driver for Wordpress Multisite

Usage: Drop this file into your ~/.valet/Drivers/ directory
*/

class WordPressMultisiteValetDriver extends WordPressValetDriver
{
    /**
     * @var string The public web directory, if deeper under the root directory
     */
    protected $public_dir = '';

    /**
     * @var bool true if site is detected to be multisite
     */
    protected $multisite = false;

    /**
     * Determine if the driver serves the request.
     *
     * @param  string $sitePath
     * @param  string $siteName
     * @param  string $uri
     * @return bool
     */
    public function serves($sitePath, $siteName, $uri)
    {
        foreach (['', 'public'] as $public_directory) {
            $this->public_dir = $public_directory;
            $wp_config_path = $this->realSitePath($sitePath) . "/wp-config.php";
            if (file_exists($wp_config_path)) {
                $wp_config_content = file_get_contents($wp_config_path);
                // Look for define('MULTISITE', true in wp-config
                if (preg_match("/^define\(\s*('|\")MULTISITE\1\s*,\s*true\s*\)/mi", $wp_config_content)) {
                    $this->multisite = true;
                } else {
                    $env_content = file_get_contents($sitePath . "/.env");
                    if (preg_match("/^WP_MULTISITE=true$/mi", $env_content)) {
                        $this->multisite = true;
                    }
                }

                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the incoming request is for a static file.
     *
     * @param  string $sitePath
     * @param  string $siteName
     * @param  string $uri
     * @return string|false
     */
    public function isStaticFile($sitePath, $siteName, $uri)
    {
        $uri = $this->rewriteMultisite($sitePath, $uri);
        $sitePath = $this->realSitePath($sitePath);

        if ($this->isActualFile($staticFilePath = $sitePath . $uri)) {
            return $staticFilePath;
        }

        return false;
    }

    /**
     * Get the fully resolved path to the application's front controller.
     *
     * @param  string $sitePath
     * @param  string $siteName
     * @param  string $uri
     * @return string
     */
    public function frontControllerPath($sitePath, $siteName, $uri)
    {
        $uri = $this->rewriteMultisite($sitePath, $uri);
        $sitePath = $this->realSitePath($sitePath);

        return parent::frontControllerPath($sitePath, $siteName, $uri);
    }

    /**
     * Translate the site path to the actual public directory
     *
     * @param $sitePath
     * @return string
     */
    protected function realSitePath($sitePath)
    {
        if ($this->public_dir) {
            $sitePath .= "/" . $this->public_dir;
        }

        return $sitePath;
    }

    /**
     * Imitate the rewrite rules for a multisite .htaccess
     *
     * @param $sitePath
     * @param $uri
     * @return string
     */
    protected function rewriteMultisite($sitePath, $uri)
    {
        if ($this->multisite) {
            if (preg_match('/^(.*)?(\/wp-(content|admin|includes).*)/', $uri, $matches)) {
                //RewriteRule ^([_0-9a-zA-Z-]+/)?(wp-(content|admin|includes).*) $2 [L]
                $uri = $matches[2];
            } elseif (preg_match('/^(.*)?(\/.*\.php)$/', $uri, $matches)) {
                //RewriteRule ^([_0-9a-zA-Z-]+/)?(.*\.php)$ $2 [L]
                $uri = $matches[2];
            }
        }

        return $uri;
    }
}
