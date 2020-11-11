<?php

namespace WPUC\Includes;

if (!defined('ABSPATH')) {
    die('you cant access this plugin directly');
}


class Protect_Pages {
    public function __construct() {
        add_action('wp_body_open', [$this, 'protect_pages']);
    }
    public static function protect_pages() {
        $pageID = get_queried_object_id();
        $home_url = home_url();
        if (get_post_type($pageID) === 'page') {
            $permissible_users = get_post_meta($pageID, '_permissible_users', true);
            if ($permissible_users) {
                if (!in_array(get_current_user_id(), $permissible_users)) {
                    wp_die("<h1>You don't have access to view this page</h1><br><a href='$home_url' >Return to Home</a>");
                }
            }
        }
    }
}
