<?php

// Get around direct access blockers.
if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/../../../');
}

define('WP_PLUGIN_PAGE_FOR_TERM_PATH', __DIR__ . '/../../../');
define('WP_PLUGIN_PAGE_FOR_TERM_URL', 'https://example.com/wp-content/plugins/' . 'modularity-wp-page-for-term');
define('WP_PLUGIN_PAGE_FOR_TERM_TEMPLATE_PATH', WP_PLUGIN_PAGE_FOR_TERM_PATH . 'templates/');


// Register the autoloader
$loader = require __DIR__ . '/../../../vendor/autoload.php';
$loader->addPsr4('wpPageForTerm\\Test\\', __DIR__ . '/../php/');

require_once __DIR__ . '/PluginTestCase.php';
