<?php

namespace PP\Templates;

if (!defined('ABSPATH'))
    die('you cant access this plugin directly');

class Registration_Form {
    public function __construct() {
        add_action('init', [$this, 'init_hook_callback']);
    }
    public function init_hook_callback() {
        add_shortcode('pp_registration_form', [__CLASS__, 'registration_form']);
    }
    public static function registration_form() { ?>
        <div id="container pp_container">
            <h4>final Messge goes here</h4>
            <form action="" method="POST" id="pp_form">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="user_full_name">Name: </label>
                        <input type="text" class="form-control" name="user_full_name" id="user_full_name" placeholder="Enter your name">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="user_name">Username: </label>
                        <input type="text" name="user_name" class="form-control" id="user_name" placeholder="Enter your username">
                    </div>
                </div>
                <div class="form-group">
                    <label for="user_email">Email:</label>
                    <input type="email" class="form-control" name="user_email" id="user_email" placeholder="Enter your email">
                </div>
                <div class="form-group">
                    <label for="user_password">Password</label>
                    <input type="password" name="user_password" class="form-control is-invalid" id="user_password" placeholder="Enter your email">
                    <div class="invalid-feedback">
                        Please choose a username.
                    </div>
                </div>
                <button type="submit" class="btn btn-secondary">Sign Up</button>
            </form>
        </div>
<?php
    }
}
