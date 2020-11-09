<?php

namespace PP\Includes;

if (!defined('ABSPATH'))
    die('you cant access this plugin directly');


class Protect_Pages {
    public function __construct() {
        add_action('init', [$this, 'init_hook_callback']);
    }
    public function init_hook_callback() {
        add_shortcode('pp_protect_page', [__CLASS__, 'protect_page']);
    }
    public static function protect_page() {
        if (!is_user_logged_in())
            wp_die("<h1>You don't have access to view this page</h1>");

        $pageID = get_the_ID();

        if (get_post_type($pageID) === 'page') {
            $permissible_users = get_post_meta($pageID, '_permissible_users', true);
            if (!$permissible_users) {
                wp_die("<h1>You don't have access to view this page</h1>");
            } else {
                if (!in_array(get_current_user_id(), $permissible_users)) {
                    wp_die("<h1>You don't have access to view this page</h1>");
                }
            }
        }
    }
}
