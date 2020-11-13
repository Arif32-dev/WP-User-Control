<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

unregister_setting('wp_user_control', 'wpuc_redirect_url');
delete_option('wpuc_redirect_url');
