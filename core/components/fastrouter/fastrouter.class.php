<?php

require_once __DIR__ . '/vendor/nikic/fast-route/src/bootstrap.php';

class FastRouter
{
	protected $cacheFile;

	/**
	 * @var modX
	 */
	protected $modx;
	/**
	 * @var FastRoute\Dispatcher\GroupCountBased
	 */
	protected $dispatcher;

	function __construct(modX $modx)
	{
		$this->modx = $modx;
		$this->cacheFile = $modx->getOption(xPDO::OPT_CACHE_PATH) . 'fastrouter.cache.php';
	}

	protected function getMethod()
	{
		return $_SERVER['REQUEST_METHOD'];
	}

	protected function getUri()
	{
		$alias = $this->modx->getOption('request_alias', null, 'q');

		$uri = isset($_REQUEST[$alias]) ? (string) $_REQUEST[$alias] : '';

		return '/' . ltrim($uri, '/');
	}

	/**
	 * @return FastRoute\Dispatcher|FastRoute\Dispatcher\GroupCountBased
	 */
	protected function getDispatcher()
	{
		if ( ! isset($this->dispatcher))
		{
			$this->dispatcher = FastRoute\cachedDispatcher(function (FastRoute\RouteCollector $r) {
				$this->getRoutes($r);
			}, array('cacheFile' => $this->cacheFile));
		}

		return $this->dispatcher;
	}

	protected function getRoutes(FastRoute\RouteCollector $r)
	{
		$routes = json_decode($this->modx->getChunk('fastrouter'), true);

		if ( ! $routes)
		{
			throw new \InvalidArgumentException('Invalid routes');
		}

		foreach ($routes as $route)
		{
			if (count($route) == 3)
			{
				$r->addRoute($route[0], $route[1], $route[2]);
			}
		}
	}

	public function dispatch()
	{
		$dispatcher = $this->getDispatcher();

		$routeInfo = $dispatcher->dispatch($this->getMethod(), $this->getUri());

		switch ($routeInfo[0])
		{
			case FastRoute\Dispatcher::NOT_FOUND:
			case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
				return $this->error();
				break;

			case FastRoute\Dispatcher::FOUND:
				return $this->handle($routeInfo);
				break;
		}
	}

	protected function handle(array $routeInfo)
	{
		if ((int) $routeInfo[1] == $routeInfo[1])
		{
			$_REQUEST = $_REQUEST + $routeInfo[2];
			$this->modx->sendForward($routeInfo[1]);
		}

		return null;
	}

	protected function error()
	{
		$options = array(
			'response_code'   => $this->modx->getOption('error_page_header', null, 'HTTP/1.1 404 Not Found'),
			'error_type'      => '404',
			'error_header'    => $this->modx->getOption('error_page_header', null, 'HTTP/1.1 404 Not Found'),
			'error_pagetitle' => $this->modx->getOption('error_page_pagetitle', null, 'Error 404: Page not found'),
			'error_message'   => $this->modx->getOption('error_page_message', null, '<h1>Page not found</h1><p>The page you requested was not found.</p>')
		);

		$this->modx->sendForward($this->modx->getOption('error_page', $options, '404'), $options);

		return null;
	}

	public function clearCache()
	{
		if (file_exists($this->cacheFile))
		{
			unlink($this->cacheFile);
		}
	}
}
