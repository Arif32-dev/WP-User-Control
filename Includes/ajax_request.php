<?php

namespace PP\Includes;

if (!defined('ABSPATH'))
    die('you cant access this plugin directly');

class Ajax_Request {
    public function __construct() {
        /* Form submission ajax request */
        add_action('wp_ajax_pp_form_submit', [$this, 'pp_form_submit']);
        add_action('wp_ajax_nopriv_pp_form_submit', [$this, 'pp_form_submit']);
    }
    public function pp_form_submit() {
        if ($_POST['action'] != 'pp_form_submit')
            wp_die();

        parse_str($_POST['form_data'], $parsed_data);

        if (!isset($parsed_data['pp_form_nonce']) || !wp_verify_nonce($parsed_data['pp_form_nonce'],  'pp_form_submit_action'))
            wp_die();

        if (
            (!isset($parsed_data['user_full_name']) && $parsed_data['user_full_name'] == "") ||
            (!isset($parsed_data['user_name']) && $parsed_data['user_name'] == "") ||
            (!isset($parsed_data['user_email']) && $parsed_data['user_email'] == "") ||
            (!isset($parsed_data['user_password']) && $parsed_data['user_password'] == "")
        )
            wp_die('empty_field');

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
                'role' => ''
            ]
        );

        if (is_int($userID))
            echo 'success';
        else
            echo 'failed';

        wp_die();
    }
}
