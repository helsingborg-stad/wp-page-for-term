<?php

/**
 * Plugin Name:       WP Page For Term
 * Plugin URI:        https://github.com/annalinneajohansson/wp-page-for-term
 * Description:       Allows you to set a static page as the replacement for any term archive.
 * Version:           1.0.0
 * Author:            Anna Johansson
 * Author URI:        https://github.com/annalinneajohansson
 * License:           MIT
 * License URI:       https://opensource.org/licenses/MIT
 * Text Domain:       wp-page-for-term
 * Domain Path:       /languages
 */

 // Protect agains direct file access
if (! defined('WPINC')) {
    die;
}

define('WP_PLUGIN_PAGE_FOR_TERM_PATH', plugin_dir_path(__FILE__));
define('WP_PLUGIN_PAGE_FOR_TERM_URL', plugins_url('', __FILE__));
define('WP_PLUGIN_PAGE_FOR_TERM_TEMPLATE_PATH', WP_PLUGIN_PAGE_FOR_TERM_PATH . 'templates/');
define('WP_PLUGIN_PAGE_FOR_TERM_TEXT_DOMAIN', 'wp-page-for-term');

load_plugin_textdomain(WP_PLUGIN_PAGE_FOR_TERM_TEXT_DOMAIN, false, WP_PLUGIN_PAGE_FOR_TERM_PATH . '/languages');

require_once WP_PLUGIN_PAGE_FOR_TERM_PATH . 'Public.php';

// Register the autoloader
require __DIR__ . '/vendor/autoload.php';

// Acf auto import and export
add_action('acf/init', function () {
    $acfExportManager = new \AcfExportManager\AcfExportManager();
    $acfExportManager->setTextdomain('wp-page-for-term');
    $acfExportManager->setExportFolder(WP_PLUGIN_PAGE_FOR_TERM_PATH . 'source/php/AcfFields/');
    $acfExportManager->autoExport(array(
        'wp-page-for-term-term-fields' => 'group_6401a49562052',
        'wp-page-for-term-page-fields' => 'group_63fe0755b42ec',
    ));
    $acfExportManager->import();
});

// Start application
new wpPageForTerm\App();
