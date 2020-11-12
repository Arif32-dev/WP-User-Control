<?php

/**
 * WP User Control
 * @author Arifur Rahman Arif
 * @wordpress-plugin
 * Plugin Name: WP User Control
 * Description: This plugin's purpose is to protect WordPress pages from unwanted visitors
 * Version: 1.0.0
 * Requires at least: 5.0
 * Requires PHP: 5.6
 * Author: Arifur Rahman Arif
 * Plugin URI: keendevs.com
 * Text Domain: WPUC
 * License: GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

/**
 * @plugin start here
 */

/**
 * defining all the constant to use across the plugin
 */

/* if accessed directly exit from plugin */
if (!defined('ABSPATH')) {
    die('you cant access this plugin directly');
}

if (!defined('WPUC_VERSION')) {
    define('WPUC_VERSION', '1.0.0');
}

if (!defined('WPUC_BASE_PATH')) {
    define('WPUC_BASE_PATH', plugin_dir_path(__FILE__));
}

if (!defined('WPUC_BASE_URL')) {
    define('WPUC_BASE_URL', plugin_dir_url(__FILE__));
}

if (!file_exists(WPUC_BASE_PATH . 'vendor/autoload.php')) {
    return;
}

require_once WPUC_BASE_PATH . 'vendor/autoload.php';

final class WPUC_Plugin {

    public function __construct() {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        if ($this->version_check() == 'version_low')
            return;
        $this->register_active_deactive_hooks();
        $this->plugins_check();
    }

    public function version_check() {
        if (version_compare(PHP_VERSION, '7.0.0') < 0) {
            if (is_plugin_active(plugin_basename(__FILE__))) {
                deactivate_plugins(plugin_basename(__FILE__));
                add_action('admin_notices', [$this, 'show_notice']);
            }
            return 'version_low';
        }
    }

    public function show_notice() {
        echo '<div class="notice notice-error is-dismissible"><h3><strong>Plugin </strong></h3><p> cannot be activated - requires at least  PHP 7.0.0 Plugin automatically deactivated.</p></div>';
        return;
    }

    public function plugins_check() {
        if (is_plugin_active(plugin_basename(__FILE__))) {
            $this->including_class();
            add_filter('plugin_action_links_' . plugin_basename(__FILE__), [__CLASS__, 'add_action_links']);
        }
    }

    /**
     * registering activation and deactivation Hooks
     * @return void
     */
    public function register_active_deactive_hooks() {
        register_activation_hook(__FILE__, function () {
            flush_rewrite_rules();
        });
        register_activation_hook(__FILE__, function () {
            flush_rewrite_rules();
        });
    }

    /**
     * @requiring all the classes once
     * @return void
     */
    public function including_class() {
        new WPUC\Templates\Registration_Form;
        new WPUC\Includes\Enqeue_Files;
        new WPUC\Includes\Ajax_Request;
        new WPUC\Includes\Protect_Pages;
        new WPUC\Includes\Meta_Fields;
        new WPUC\Includes\Menu_Page;
    }
    public static function add_action_links($links) {
        $mylinks = array(
            '<a href="' . admin_url('options-general.php?page=wp_user_control') . '">Settigns Page</a>',
        );
        return array_merge($links, $mylinks);
    }
}

if (!class_exists('WPUC_Plugin'))
    return;

if (!function_exists('WPUC_plugin_init')) {
    function WPUC_plugin_init() {
        return new WPUC_Plugin;
    }
}
WPUC_plugin_init();
