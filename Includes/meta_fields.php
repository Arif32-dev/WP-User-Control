<?php

namespace PP\Includes;

if (!defined('ABSPATH'))
    die('you cant access this plugin directly');

class Meta_Fields {
    public function __construct() {
        add_action('add_meta_boxes_page', [$this, 'register_meta_boxes']);
        add_action('save_post_page', [$this, 'save_meta_boxes'], 10, 2);
    }
    public function register_meta_boxes($post) {
        self::meta_func($post);
    }
    public static function meta_func($post) {
        add_meta_box(
            'page_protector',
            'Page Protector',
            [__CLASS__, 'protector_meta_content'],
            ['page'],
            'side',
            'high'
        );
    }
    public static function protector_meta_content($post) {
        wp_nonce_field('pp_meta_nonce_action', 'pp_page_meta');
        self::user_field($post);
    }
    public static function user_field($post) {
        $pertiable_users = get_post_meta($post->ID, '_pertiable_users', true);

?>
        <div>
            <strong>
                <label for="meta_users">Who Can Access :</label>
                <br />
            </strong>
            <br />
            <select id="multiple" name="meta_users" multiple>
                <?php self::get_users() ?>
            </select>
        </div>
        <br />
        <?php

    }
    public static function get_users() {
        $valid_users = get_users(
            [
                'role__in' => self::get_roles()
            ]
        );
        if (!$valid_users)
            return;

        foreach ($valid_users as $users) { ?>
            <option value="<?php echo esc_attr($users->data->ID) ?>"><?php echo esc_html($users->data->display_name) ?></option>
<?php
        }
    }
    public static function get_roles() {
        $return_roles = [];
        $valid_roles = wp_roles()->roles;
        if (!$valid_roles)
            return;
        foreach ($valid_roles as $roles) {
            $return_roles[] = $roles['name'];
        }
        return $return_roles;
    }
}
