<?php

namespace WPUC\Includes;

if (!defined('ABSPATH'))
    die('you cant access this plugin directly');

class Ajax_Request {
    public function __construct() {
        /* Form submission ajax request */
        add_action('wp_ajax_wpuc_form_submit', [$this, 'wpuc_form_submit']);
        add_action('wp_ajax_nopriv_wpuc_form_submit', [$this, 'wpuc_form_submit']);
        /* Form login ajax request */
        add_action('wp_ajax_wpuc_form_login', [$this, 'wpuc_form_login']);
        add_action('wp_ajax_nopriv_wpuc_form_login', [$this, 'wpuc_form_login']);
    }
    public function wpuc_form_submit() {
        if ($_POST['action'] != 'wpuc_form_submit') {
            wp_die();
        }

        parse_str($_POST['form_data'], $parsed_data);

        if (!isset($parsed_data['pp_form_nonce']) || !wp_verify_nonce($parsed_data['pp_form_nonce'],  'pp_form_submit_action')) {
            wp_die();
        }

        if (
            (!isset($parsed_data['user_full_name']) && $parsed_data['user_full_name'] == "") ||
            (!isset($parsed_data['user_name']) && $parsed_data['user_name'] == "") ||
            (!isset($parsed_data['user_email']) && $parsed_data['user_email'] == "") ||
            (!isset($parsed_data['user_password']) && $parsed_data['user_password'] == "")
        ) {
            wp_die('empty_field');
        }

        $sanitzed_parsed_data = [
            'user_full_name' => sanitize_text_field($parsed_data['user_full_name']),
            'user_name' => sanitize_text_field($parsed_data['user_name']),
            'user_email' => sanitize_email($parsed_data['user_email']),
            'user_password' => sanitize_text_field($parsed_data['user_password']),
        ];

        self::create_user($sanitzed_parsed_data);
    }
    public static function create_user(array $sanitzed_parsed_data) {
        $userID = wp_insert_user(
            [
                'user_pass' => $sanitzed_parsed_data['user_password'],
                'user_login' => $sanitzed_parsed_data['user_name'],
                'user_email' => $sanitzed_parsed_data['user_email'],
                'display_name' => $sanitzed_parsed_data['user_full_name'],
                'rich_editing' => 'false',
                'syntax_highlighting' => 'false',
                'show_admin_bar_front' => 'false',
                'role' => 'subscriber'
            ]
        );

        if (is_int($userID)) {
            echo 'success';
        } else {
            if (array_key_exists("existing_user_login", $userID->errors)) {
                if ($userID->errors['existing_user_login'][0])
                    echo json_encode(sanitize_text_field($userID->errors['existing_user_login'][0]));
            }
            if (array_key_exists("existing_user_email", $userID->errors)) {
                if ($userID->errors['existing_user_email'][0])
                    echo json_encode(sanitize_text_field($userID->errors['existing_user_email'][0]));
            }
        }

        wp_die();
    }
    public function wpuc_form_login() {
        if ($_POST['action'] != 'wpuc_form_login') {
            wp_die();
        }

        parse_str($_POST['form_data'], $parsed_data);

        if (!isset($parsed_data['wpuc_login_form_nonce']) || !wp_verify_nonce($parsed_data['wpuc_login_form_nonce'],  'wpuc_form_login_action')) {
            wp_die();
        }

        if (
            (!isset($parsed_data['user_login']) && $parsed_data['user_login'] == "") ||
            (!isset($parsed_data['user_password']) && $parsed_data['user_password'] == "")
        ) {
            wp_die('empty_field');
        }

        $sanitzed_parsed_data = [
            'user_login' => sanitize_text_field($parsed_data['user_login']),
            'user_password' => sanitize_text_field($parsed_data['user_password']),
        ];
        self::user_login($sanitzed_parsed_data);
    }
    public static function user_login(array $sanitzed_parsed_data) {
        $cred = [
            'user_login' => $sanitzed_parsed_data['user_login'],
            'user_password' => $sanitzed_parsed_data['user_password'],
            'remember' => true,
        ];
        $res = wp_signon($cred, true);
        if (!is_wp_error($res)) {
            $user_id = $res->data->ID;
            wp_set_current_user($user_id);
            wp_set_auth_cookie($user_id, true);
            echo 'success';
        } else {
            if (array_key_exists("incorrect_password", $res->errors)) {
                if ($res->errors['incorrect_password'][0]) {
                    echo 'Incorrect Password';
                }
            }
            if (array_key_exists("invalid_username", $res->errors)) {
                if ($res->errors['invalid_username'][0]) {
                    echo 'Invalid username';
                }
            }
            if (array_key_exists("invalid_email", $res->errors)) {
                if ($res->errors['invalid_email'][0]) {
                    echo 'Invalid email';
                }
            }
        }
        wp_die();
    }
}
