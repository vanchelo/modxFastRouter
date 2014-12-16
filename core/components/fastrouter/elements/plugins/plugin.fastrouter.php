<?php
require_once MODX_CORE_PATH . 'components/fastrouter/fastrouter.class.php';

$router = new FastRouter($modx);

if ($modx->event->name == 'OnPageNotFound') {
    $router->dispatch();
} else if ($modx->event->name == 'OnChunkSave' && $chunk->name == 'fastrouter') {
    $router->clearCache();
}
