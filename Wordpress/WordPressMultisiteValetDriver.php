<?php

class WordPressMultisiteValetDriver extends WordPressValetDriver
{
    /**
     * @var string The public web directory, if deeper under the root directory
     */
    protected $public_dir = '';

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
        if (file_exists($sitePath . '/wp-config.php')) {
            return true;
        }
        if (file_exists($sitePath . '/public/wp-config.php')) {
            $this->public_dir = '/public';

            return true;
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
            $sitePath .= $this->public_dir;
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
        if (file_exists($sitePath . '/multisite-directories')) {
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
