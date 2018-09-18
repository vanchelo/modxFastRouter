<?php

require_once __DIR__ . '/vendor/nikic/fast-route/src/bootstrap.php';

/**
 * Class FastRouter
 */
class FastRouter
{
    const FAST_ROUTER = 'fastrouter';
    const EVENT = 'OnPageNotFound';
    const EVENT_ON_CHUNK_SAVE = 'OnChunkSave';

    /**
     * Path to routes cache file
     *
     * @var string
     */
    protected $cacheFile;
    /**
     * @var modX
     */
    protected $modx;
    /**
     * @var FastRoute\Dispatcher\GroupCountBased
     */
    protected $dispatcher;
    /**
     * @var string
     */
    protected $paramsKey;

    /**
     * FastRouter constructor
     *
     * @param modX $modx
     */
    public function __construct(modX $modx)
    {
        $this->modx = $modx;
        $this->cacheFile = $modx->getOption(xPDO::OPT_CACHE_PATH) . static::FAST_ROUTER . '.cache.php';
        $this->paramsKey = $modx->getOption(static::FAST_ROUTER . '.paramsKey', null, static::FAST_ROUTER);

        $this->registerDispatcher();
    }

    /**
     * Get request method
     *
     * @return string
     */
    protected function getMethod()
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    /**
     * Get request URI
     *
     * @return string
     */
    protected function getUri()
    {
        $alias = $this->modx->getOption('request_alias', null, 'q');

        $uri = isset($_REQUEST[$alias]) && is_scalar($_REQUEST[$alias]) ? (string) $_REQUEST[$alias] : '';

        return '/' . ltrim($uri, '/');
    }

    /**
     * Get routes dispatcher
     *
     * @return FastRoute\Dispatcher|FastRoute\Dispatcher\GroupCountBased
     */
    protected function getDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * Register routes
     *
     * @param FastRoute\RouteCollector $router
     */
    protected function getRoutes(FastRoute\RouteCollector $router)
    {
        $routes = json_decode($this->getRoutesChunk(), true);

        if (!$routes) {
            throw new InvalidArgumentException('Routes is invalid.');
        }

        foreach ($routes as $r) {
            if (isset($r[0], $r[1], $r[2])) {
                $router->addRoute($r[0], $r[1], $r[2]);
            }
        }
    }

    /**
     * Get routes chunk content
     *
     * @return string
     */
    protected function getRoutesChunk()
    {
        return $this->modx->getChunk($this->chunkName());
    }

    /**
     * Get routes chunk name
     *
     * @return string
     */
    public function chunkName()
    {
        return $this->modx->getOption(static::FAST_ROUTER . '.chunkName', null, static::FAST_ROUTER);
    }

    /**
     * Dispatch request
     *
     * @return null
     */
    public function dispatch()
    {
        $dispatcher = $this->getDispatcher();

        $params = $dispatcher->dispatch($this->getMethod(), $this->getUri());

        switch ($params[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                return $this->error();

            case FastRoute\Dispatcher::FOUND:
                return $this->handle($params[1], $params[2]);
        }

        return null;
    }

    /**
     * Handle route
     *
     * @param integer|string $routeHandler
     * @param array $data
     *
     * @return null
     */
    protected function handle($routeHandler, array $data)
    {
        // Send forward to resource
        if (is_numeric($routeHandler)) {
            $_REQUEST += [$this->paramsKey => $data];
            $this->modx->sendForward($routeHandler);

            return null;
        }

        // TODO: Refactor. Remove exit. What is the best way to do this?
        // Call snippet
        echo $this->modx->runSnippet($routeHandler, [
            $this->paramsKey => $data,
        ]);
        exit();
    }

    /**
     * Send error page
     *
     * @return null
     */
    protected function error()
    {
        $options = [
            'response_code' => $this->modx->getOption('error_page_header', null, 'HTTP/1.1 404 Not Found'),
            'error_type' => '404',
            'error_header' => $this->modx->getOption('error_page_header', null, 'HTTP/1.1 404 Not Found'),
            'error_pagetitle' => $this->modx->getOption('error_page_pagetitle', null, 'Error 404: Page not found'),
            'error_message' => $this->modx->getOption(
                'error_page_message',
                null,
                '<h1>Page not found</h1><p>The page you requested was not found.</p>'
            ),
        ];

        $this->modx->sendForward($this->modx->getOption('error_page', $options, '404'), $options);

        return null;
    }

    /**
     * Remove routes cache
     */
    public function clearCache()
    {
        if (file_exists($this->cacheFile)) {
            unlink($this->cacheFile);
        }
    }

    /**
     * Register router dispatcher
     */
    protected function registerDispatcher()
    {
        $this->dispatcher = FastRoute\cachedDispatcher(function (FastRoute\RouteCollector $router) {
            $this->getRoutes($router);
        }, [
            'cacheFile' => $this->cacheFile,
        ]);
    }

    /**
     * @return bool
     */
    public function needDispatch()
    {
        $event = $this->modx->event;

        return static::EVENT === $event->name && !isset($event->params['stop']);
    }

    /**
     * Check if chunk with routes updated
     *
     * @param string $chunkName
     * @return bool
     */
    public function isRoutesChunkUpdated($chunkName)
    {
        $event = $this->modx->event;

        return static::EVENT_ON_CHUNK_SAVE === $event->name && $chunkName === $this->chunkName();
    }
}
