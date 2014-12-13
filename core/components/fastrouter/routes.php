<?php
/** @var FastRoute\RouteCollector $r */

$r->addRoute('GET', '/fastrouter/{name}/{id:[0-9]+}', '2');
$r->addRoute('GET', '/fastrouter/{id:[0-9]+}', '3');
$r->addRoute('GET', '/fastrouter/{name}', '3');
