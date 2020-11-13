<?php

namespace WPUC\Includes;

if (!defined('ABSPATH')) {
    die('you cant access this plugin directly');
}


class Protect_Pages {
    public function __construct() {
        ob_clean();
        ob_start();
        add_shortcode('wpuc_protect_page', [$this, 'protect_pages']);
        add_action('the_content', [$this, 'content_filtering']);
    }
    public function protect_pages() {
        return;
    }
    public static function login_form() {
        $form = '<div class="container pp_container">
            <h4>Please sign in to access the page</h4>
            <div id="wpuc_alert_box_login">
            </div>
            <form action="" method="POST" id="pp_form_login">
                ' . wp_nonce_field('wpuc_form_login_action', 'wpuc_login_form_nonce', true, false) . '
                <div class="form-group vertical">
                    <input style="padding: 3px 20px;" type="text" class="form-control" required name="user_login" id="user_login" placeholder="Enter your email or username">
                </div>
                <div class="form-group vertical">
                    <input style="padding: 3px 20px;" type="password" name="user_password" required class="form-control" id="user_password" placeholder="Enter your password">
                </div>
                <button type="submit" class="btn btn-secondary">Sign In</button>
            </form>
        </div>';
        return $form;
    }
    public function content_filtering($content) {
        $pageID = get_queried_object_id();
        $home_url = home_url();
        $custom_url = get_option('wpuc_redirect_url') ? get_option('wpuc_redirect_url') : $home_url;

        if (has_shortcode($content, 'wpuc_protect_page')) {
            if (get_post_type($pageID) === 'page') {
                $permissible_users = get_post_meta($pageID, '_permissible_users', true);
                if ($permissible_users) {
                    if (!in_array(get_current_user_id(), $permissible_users)) {
                        if (is_user_logged_in()) {
                            wp_safe_redirect($custom_url);
                        } else {
                            $content = self::login_form();
                        }
                    }
                }
            }
        }
        return $content;
    }
}
