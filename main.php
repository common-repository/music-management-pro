<?php
/**
 * Plugin Name: Music Management Pro
 * Description: Professional panel for managing your site's music
 * Author: Idea Land
 * Version: 1.0.4
 * Text Domain: MMP_TEXT_DOMAIN
 * Author URI: http://idea-land.co
 */

/** SECURITY */
defined('ABSPATH') || exit();

// MAIN DEFINE
define('MMP_PLUGIN_DIR', __DIR__);
define('MMP_PLUGIN_PATH', trailingslashit(plugin_dir_path(__FILE__)));
define('MMP_PLUGIN_URL', trailingslashit(plugin_dir_url(__FILE__)));
define('MMP_VERSION', '1.0.0');
define('MMP_PLUGIN_NAME', 'Music Management Pro');

/** BASE INCLUDE */
include_once 'include/MMP_Database.php'; // DATABASE
include_once 'include/MMP_Register_Plugin.php'; // MMP ACTIVATE AND DEACTIVATE
include_once 'include/MMP_Start.php'; // START

function MMP()
{
    return MMP_Start::Instance();
}

$GLOBALS['MMP'] = MMP();