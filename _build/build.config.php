<?php

define('DS', DIRECTORY_SEPARATOR);

/* define package */
define('PKG_NAME', 'FastRouter');
define('PKG_NAME_LOWER', strtolower(PKG_NAME));

define('PKG_VERSION', '1.0.2');
define('PKG_RELEASE', 'pl');
define('PKG_AUTO_INSTALL', true);
define('PKG_NAMESPACE_PATH', '{core_path}components/' . PKG_NAME_LOWER . '/');

/* define paths */
if (isset($_SERVER['MODX_BASE_PATH'])) {
    define('MODX_BASE_PATH', $_SERVER['MODX_BASE_PATH']);
} elseif (file_exists(realpath(dirname(dirname(__FILE__))) . DS . 'core')) {
    define('MODX_BASE_PATH', realpath(dirname(dirname(__FILE__))) . DS);
} else {
    define('MODX_BASE_PATH', dirname(dirname(dirname(dirname(__FILE__)))) . '/');
}

define('MODX_CORE_PATH', MODX_BASE_PATH . 'core/');
define('MODX_MANAGER_PATH', MODX_BASE_PATH . 'manager/');
define('MODX_CONNECTORS_PATH', MODX_BASE_PATH . 'connectors/');

/* define urls */
define('MODX_BASE_URL', '/');
define('MODX_CORE_URL', MODX_BASE_URL . 'core/');
define('MODX_MANAGER_URL', MODX_BASE_URL . 'manager/');
define('MODX_CONNECTORS_URL', MODX_BASE_URL . 'connectors/');

/* define build options */
define('BUILD_ACTION_UPDATE', false);
define('BUILD_SETTING_UPDATE', false);
define('BUILD_CHUNK_UPDATE', false);

define('BUILD_SNIPPET_UPDATE', true);
define('BUILD_PLUGIN_UPDATE', true);

define('BUILD_CHUNK_STATIC', false);
define('BUILD_SNIPPET_STATIC', false);
define('BUILD_PLUGIN_STATIC', false);

$BUILD_RESOLVERS = array(
    'chunks',
);
