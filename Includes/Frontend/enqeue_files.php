<?php

namespace PP\Includes\Frontend;

/* if accessed directly exit from plugin */

if (!defined('ABSPATH'))
    die('you cant access this plugin directly');

class Enqeue_Files {
    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scirpts_styles']);
    }
    public function enqueue_scirpts_styles() {
        wp_enqueue_style('pp-bootstrap-css', '//stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
        wp_enqueue_script('pp-boostrap-js', '//stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js');
        wp_enqueue_script('jquery');
        wp_enqueue_script('oe_admin_scirpt', PP_BASE_URL . 'Assets/Scripts/Frontend/front-end.js', 'jquery', PP_VERSION, true);
    }
}
