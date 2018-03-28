<?php

/**
 *
 */
class SS_Shortcode
{

    function __construct()
    {
        // register shortcode
        add_shortcode('ss_form_list', [$this, 'form_list']);
        add_shortcode('ss_form', [$this, 'form']);

        // handle admin menu
        add_action('admin_menu', [$this, 'admin_menu']);

        // handle form input
        add_action('ss_form_handle_form', [$this, 'handle_form']);

        add_action('ss_form_plugin_activated', [$this, 'install']);
    }

    /**
     * create custom table
     * @return void
     */
    public function install()
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE IF NOT EXISTS ". $wpdb->prefix ."ss_form ( `id` BIGINT(20) NOT NULL PRIMARY KEY AUTO_INCREMENT , `name` VARCHAR(200) NOT NULL , `email` VARCHAR(100) NOT NULL , `message` LONGTEXT NOT NULL )" . $charset_collate;

        if (!function_exists('dbDelta')) {
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        }

        dbDelta($sql);

    }

    /**
     * get messages from custom table
     * @param  integer $limit
     * @return array
     */
    public function get_messages($limit = 0)
    {
        global $wpdb;

        return $wpdb->get_results(sprintf("SELECT * FROM %s %s", $wpdb->prefix.'ss_form', (int) $limit ? 'LIMIT '. (int)$limit : ''), ARRAY_A);
    }

    /**
     * Register admin page
     *
     */
    function admin_menu()
    {
        // handle admin menu
        add_menu_page(
                'Form Entries', 'SS Form', 'manage_options', 'ss-form-list', [$this, 'admin_page_contact'], '', 6
        );
    }

    /**
     * Output Admin menu
     *
     */
    function admin_page_contact()
    {
        echo '<div class="wrap">';
        echo '<h3>Form Entries</h3>';
        // use wp list table
        require 'ss-form-table.php';
        $wp_table = new SS_Form_Table();
        $wp_table->prepare_items(); // prepare item
        $wp_table->display(); // display table
        echo '</div>';
    }

    /**
     * show list of messages
     * @param  array  $attr
     * @return string
     */
    public function form_list($attr=[])
    {
        $attr = shortcode_atts([
            'limit' => 0
        ], $attr);

        $messages = $this->get_messages($attr['limit']);
        ob_start();
        require (SS_DIR .'/views/form-list.php');
        return ob_get_clean();
    }

    /**
     * load html form
     * @param  array  $attr
     * @return string
     */
    public function form($attr=[])
    {
        $attr = shortcode_atts([], $attr);

        // save form
        do_action('ss_form_handle_form');
        wp_enqueue_script('jquery-validate', SS_URI.'assets/js/jquery.validate.min.js', ['jquery']);
        wp_enqueue_script('ss_form', SS_URI.'assets/js/ss-form.js', ['jquery']);
        ob_start();
        require (SS_DIR .'/views/form.php');
        return ob_get_clean();
    }

    /**
     * handle form
     * @return void
     */
    public function handle_form()
    {

        if (isset($_POST['ss_form_action_field']) && wp_verify_nonce($_POST['ss_form_action_field'], 'ss_form_action')) {
            $error = new WP_Error();

            // validate name
            if (!empty($_POST['ss_name'])) {
                $name = sanitize_text_field($_POST['ss_name']);
            } else {
                $error->add('name', 'Name is required');
            }

            // validate email
            if (!empty($_POST['ss_email'])) {
                $email = $_POST['ss_email'];
                $sanitized_email = sanitize_email($email);
                if (strlen($email) != strlen($sanitized_email)) {
                    $error->add('email', 'Email is not valid');
                } else {
                    $email = $sanitized_email;
                }
            } else {
                $error->add('email', 'Email is required');
            }

            // validate message
            if (!empty($_POST['ss_message'])) {
                $message = sanitize_textarea_field($_POST['ss_message']);
            } else {
                $error->add('message', 'message is required');
            }

            $error_messages = $error->get_error_messages();
            if (!empty($error_messages)) {
                foreach ($error_messages as $message) {
                    echo sprintf('<div class="ss-form-error-message">%s</div>', $message);
                }
            }

            global $wpdb;
            // insert to table
            $insert = $wpdb->insert($wpdb->prefix.'ss_form', [
                'name' => $name,
                'email' => $email,
                'message' => $message
            ], [
                '%s',
                '%s',
                '%s'
            ]);

            // email to admin
            if ($insert) {
                wp_mail(get_bloginfo('admin_email'), 'You have message from '.$name, $message, [
                    'Reply-To: '.$name.'<'.$email.'>'
                ]);
            }
        }
    }
}
new SS_Shortcode();
