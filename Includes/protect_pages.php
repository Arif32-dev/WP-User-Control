<?php

namespace WPUC\Includes;

if (!defined('ABSPATH')) {
    die('you cant access this plugin directly');
}


class Protect_Pages {
    public function __construct() {
        ob_clean();
        ob_start();
        add_action('wp_head', [$this, 'protect_pages']);
    }
    public function protect_pages() {
        $pageID = get_queried_object_id();
        $home_url = home_url();
        $login_url = wp_login_url();
        $custom_url = get_option('wpuc_redirect_url') ? get_option('wpuc_redirect_url') : $home_url;
        if (get_post_type($pageID) === 'page') {
            $permissible_users = get_post_meta($pageID, '_permissible_users', true);
            if ($permissible_users) {
                if (!in_array(get_current_user_id(), $permissible_users)) {
                    if (is_user_logged_in()) {
                        wp_safe_redirect($custom_url);
                    } else {
                        wp_safe_redirect($login_url);
                    }
                }
            }
        }
    }
}
