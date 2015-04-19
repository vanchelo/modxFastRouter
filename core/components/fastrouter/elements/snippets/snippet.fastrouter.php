<?php
$key = $modx->getOption('fastrouter.paramsKey', null, 'fastrouter');
$params = $modx->getOption($key, $scriptProperties, isset($_REQUEST[$key]) ? $_REQUEST[$key] : array());

return '<pre>' . print_r($params, true) . '</pre>';
