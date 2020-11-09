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
        wp_nonce_field('pp_meta_nonce_action', 'pp_page_meta_nonce');
        self::user_field($post);
    }
    public static function user_field($post) {
        $permissible_users = get_post_meta($post->ID, '_permissible_users', true);
?>
        <div>
            <strong>
                <label for="meta_users">Who Can Access :</label>
                <br />
            </strong>
            <br />
            <select id="multiple" name="meta_users[]" multiple>
                <?php self::get_users($permissible_users) ?>
            </select>
        </div>
        <br />
        <?php

    }

    public static function get_users($permissible_users) {
        $valid_users = get_users(
            [
                'role__in' => self::get_roles()
            ]
        );
        if (!$valid_users)
            return;

        foreach ($valid_users as $users) { ?>
            <option <?php self::echo_select($permissible_users, $users->data->ID) ?> value="<?php echo esc_attr($users->data->ID) ?>"><?php echo esc_html($users->data->display_name) ?></option>
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

    public function save_meta_boxes(int $post_id, object $post_object) {

        if (!isset($_POST['pp_page_meta_nonce']) || !wp_verify_nonce($_POST['pp_page_meta_nonce'],  'pp_meta_nonce_action'))
            return $post_id;

        /* Does current user have capabitlity to edit post */
        $post_type = get_post_type_object($post_object->post_type);
        if (!current_user_can($post_type->cap->edit_post, $post_id))
            return $post_id;

        /* Get the posted data and check it for uses. */
        $new_meta_value = (isset($_POST['meta_users']) ? $_POST['meta_users'] : "");

        /* Get the meta key. */
        $meta_key = '_permissible_users';

        /* Get the meta value of the custom field key. */
        $meta_value = get_post_meta($post_id, $meta_key, true);

        /* If a new meta value was added and there was no previous value, add it. */
        if ($new_meta_value && "" == $meta_value)
            add_post_meta($post_id, $meta_key, $new_meta_value);

        /* If the new meta value does not match the old value, update it. */
        elseif ($new_meta_value && $new_meta_value != $meta_value)
            update_post_meta($post_id, $meta_key, $new_meta_value);

        /* If there is no new meta value but an old value exists, delete it. */
        elseif ("" == $new_meta_value && $meta_value)
            delete_post_meta($post_id, $meta_key, $meta_value);
    }

    public static function echo_select($permissible_users, int $user_id) {
        if (!is_array($permissible_users))
            return;

        if (in_array($user_id, $permissible_users))
            echo 'selected';
        else
            echo '';
    }
}
