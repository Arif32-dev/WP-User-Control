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
        $custom_url = get_option('wpuc_redirect_url') ? get_option('wpuc_redirect_url') : $home_url;
        if (get_post_type($pageID) === 'page') {
            $permissible_users = get_post_meta($pageID, '_permissible_users', true);
            if ($permissible_users) {
                if (!in_array(get_current_user_id(), $permissible_users)) {
                    if (is_user_logged_in()) {
                        wp_safe_redirect($custom_url);
                    } else {
                        echo self::login_form($home_url);
                        self::form_script();
                        wp_die();
                    }
                }
            }
        }
    }
    public static function login_form($url) {
        $form = '<div id="container pp_container">
            <div id="wpuc_alert_box">
            </div>
            <form action="" method="POST" id="pp_form">
                ' . wp_nonce_field('wpuc_form_login_action', 'wpuc_login_form_nonce', true, false) . '
                <div class="form-group vertical">
                    <label for="user_login">Email:</label>
                    <input style="padding: 3px 20px;" type="text" class="form-control" required name="user_login" id="user_login" placeholder="Enter your email or username">
                </div>
                <div class="form-group vertical">
                    <label for="user_password">Password: </label>
                    <input style="padding: 3px 20px;" type="password" name="user_password" required class="form-control" id="user_password" placeholder="Enter your password">
                </div>
                <button type="submit" class="btn btn-secondary">Sign In</button>
            </form>
            <h5><a href="' . $url . '">Back to Home</a></h5>
        </div>';
        return $form;
    }
    public static function form_script() { ?>

        <script>
            jQuery(document).ready(function($) {
                let alert_box = $('#wpuc_alert_box');
                let form = $('#pp_form');
                form.on('submit', function(e) {
                    e.preventDefault();
                    let formData = $(e.currentTarget).serialize();
                    $.ajax({
                        url: '<?php echo admin_url('admin-ajax.php') ?>',
                        data: {
                            action: 'wpuc_form_login',
                            form_data: formData
                        },
                        type: 'post',
                        success: res => {
                            console.log(res);
                            if (res == 'success') {
                                alert_box.removeClass('wpuc_fail').addClass('wpuc_success').html(`
                                    Logged In Successfully,
                                    `).hide().slideDown(500, function() {
                                    location.reload();
                                });
                            } else {
                                alert_box.removeClass('wpuc_success').addClass('wpuc_fail').html(`
                                    ${res.replace(/"/g, "")}
                            `).hide().slideDown();
                            }
                        },
                        error: err => {
                            alert_box.removeClass('wpuc_success').addClass('wpuc_fail').html(`
                                    Something Went Wrong.
                            `).hide().slideDown();
                        }
                    })
                })
            })
        </script>
<?php
    }
}
