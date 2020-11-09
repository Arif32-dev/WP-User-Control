<?php

namespace PP\Includes;

/* if accessed directly exit from plugin */

if (!defined('ABSPATH'))
    die('you cant access this plugin directly');

class Enqeue_Files {
    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'frontend_scripts']);
        add_action('admin_enqueue_scripts', [$this, 'backend_scripts']);
    }
    public function frontend_scripts() {
        wp_enqueue_style('pp-bootstrap-css', '//stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
        wp_enqueue_script('pp-boostrap-js', '//stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js');
        wp_enqueue_script('jquery');
        wp_enqueue_script('pp_frontend_scirpt', PP_BASE_URL . 'Assets/Scripts/Frontend/front-end.js', 'jquery', PP_VERSION, true);
        wp_localize_script('pp_frontend_scirpt', 'file_url', [
            'admin_ajax' => admin_url('admin-ajax.php'),
        ]);
    }
    public function backend_scripts() {
        /* slim-select css */
        wp_enqueue_style('pp-select-css', '//cdnjs.cloudflare.com/ajax/libs/slim-select/1.26.0/slimselect.min.css');
        /* slim-select js */
        wp_enqueue_script('pp-select-js', '//cdnjs.cloudflare.com/ajax/libs/slim-select/1.26.0/slimselect.min.js');
        wp_enqueue_script('pp_backend_scirpt', PP_BASE_URL . 'Assets/Scripts/Backend/back-end.js', 'jquery', PP_VERSION, true);
    }
}
