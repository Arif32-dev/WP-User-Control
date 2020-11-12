<?php

namespace WPUC\Includes;

if (!defined('ABSPATH')) {
    die('you cant access this plugin directly');
}

class Menu_Page {
    public function __construct() {
        add_action('admin_menu', [$this, 'admin_func']);
        add_action('admin_init', [$this, 'add_settings']);
    }
    public function admin_func() {
        add_submenu_page(
            'options-general.php',
            'WP User Control',
            'WP User Control',
            'manage_optionss',
            'wp_user_control',
            [__CLASS__, 'user_control_page']
        );
    }
    public static function user_control_page() { ?>


        <div class="wrap">
            <form action="options.php" method="POST">
                <?php settings_fields('wp_user_control') ?>
                <?php do_settings_sections('wp_user_control') ?>
                <?php submit_button('Save Settings'); ?>
            </form>
        </div>


    <?php
    }
    public function add_settings() {
        register_setting(
            'wp_user_control',
            'wpuc_redirect_url'
        );
        add_settings_section(
            'wpuc_settings_section_id',
            'Usage of this plugin',
            null,
            'wp_user_control'
        );
        add_settings_field(
            'wpuc_settings_field_id',
            "",
            [__CLASS__, 'page_elements'],
            'wp_user_control',
            'wpuc_settings_section_id'
        );
    }
    public static function page_elements() {
        $url = get_option('wpuc_redirect_url');

    ?>

        <tr>
            <b>
                <p>To use this plugin there are 1 shortcode available for this plugin.</p>
            </b>
            <b>
                <i>
                    Sortcode: [wp_user_control_form]
                </i>
            </b>
            <br>
            <p>
                With this sortcode your website will have custom registration form in the front-end. Where people can register their account as a subscriber
            </p>
            <br>
            <br>
            <br>


            <b>
                <i>
                    With this pluign you can protect your pages from unwanted visitor. Just simply check users from a page you want to give access and rest of the user or mass people can't access that page.

                    Admin will have ability to give permission to specific users for specific pages. No need to use private pages anymore.
                </i>
            </b>
        </tr>

        <tr>
            <td>
                <strong>
                    <label for="wpuc_redirect_url">Redirect url :</label>
                </strong>
            </td>
            <td>
                <input style='width: 300px;' type="text" name="wpuc_redirect_url" placeholder="Url to redirect user to your desired page" value="<?php echo esc_html($url) ?>" />
            </td>
        </tr>
<?php
    }
}
