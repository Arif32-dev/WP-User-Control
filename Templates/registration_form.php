<?php

namespace WPUC\Templates;

if (!defined('ABSPATH')) {
    die('you cant access this plugin directly');
}

class Registration_Form {
    public function __construct() {
        add_shortcode('wp_user_control_form', [$this, 'registration_form']);
    }
    public function registration_form() {
        $form = '<div class="container pp_container">
            <div id="wpuc_alert_box">
            </div>
            <form action="" method="POST" id="pp_form">
                ' . wp_nonce_field('pp_form_submit_action', 'pp_form_nonce', true, false) . '
                <div class="form-group vertical">
                    <label for="user_full_name">Name: </label>
                    <input type="text" class="form-control" required name="user_full_name" id="user_full_name" placeholder="Enter your name">
                </div>
                <div class="form-group vertical">
                    <label for="user_name">Username: </label>
                    <input type="text" name="user_name" required class="form-control" id="user_name" placeholder="Enter your username">
                </div>
                <div class="form-group vertical">
                    <label for="user_email">Email:</label>
                    <input type="email" class="form-control" required name="user_email" id="user_email" placeholder="Enter your email">
                </div>
                <div class="form-group vertical">
                    <label for="user_password">Password</label>
                    <input type="password" name="user_password" required class="form-control" id="user_password" placeholder="Enter your password">
                </div>
                <button type="submit" class="btn btn-secondary">Sign Up</button>
            </form>
        </div>';
        return $form;
    }
}
