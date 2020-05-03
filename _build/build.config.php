<?php

define('DS', DIRECTORY_SEPARATOR);

/**
 * Define package
 */
define('PKG_NAME', 'FastRouter');
define('PKG_NAME_LOWER', strtolower(PKG_NAME));

define('PKG_VERSION', '1.0.4');
define('PKG_RELEASE', 'pl');
define('PKG_AUTO_INSTALL', true);
define('PKG_NAMESPACE_PATH', '{core_path}components/' . PKG_NAME_LOWER . '/');

/**
 * Define paths
 */
if (isset($_SERVER['MODX_BASE_PATH'])) {
    define('MODX_BASE_PATH', $_SERVER['MODX_BASE_PATH']);
} elseif (file_exists(realpath(dirname(__DIR__)) . DS . 'core')) {
    define('MODX_BASE_PATH', realpath(dirname(__DIR__)) . DS);
} else {
    define('MODX_BASE_PATH', dirname(dirname(dirname(__DIR__))) . '/');
}

define('MODX_CORE_PATH', MODX_BASE_PATH . 'core/');
define('MODX_MANAGER_PATH', MODX_BASE_PATH . 'manager/');
define('MODX_CONNECTORS_PATH', MODX_BASE_PATH . 'connectors/');

/**
 * Define URLs
 */
define('MODX_BASE_URL', '/');
define('MODX_CORE_URL', MODX_BASE_URL . 'core/');
define('MODX_MANAGER_URL', MODX_BASE_URL . 'manager/');
define('MODX_CONNECTORS_URL', MODX_BASE_URL . 'connectors/');

/**
 * Define build options
 */
define('BUILD_ACTION_UPDATE', false);
define('BUILD_SETTING_UPDATE', false);
define('BUILD_CHUNK_UPDATE', false);

define('BUILD_SNIPPET_UPDATE', true);
define('BUILD_PLUGIN_UPDATE', true);

define('BUILD_CHUNK_STATIC', false);
define('BUILD_SNIPPET_STATIC', false);
define('BUILD_PLUGIN_STATIC', false);

$BUILD_RESOLVERS = [
    'chunks',
];
