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
        // wp_die('<h1>No access</h1>');
    }
}
