<?php

/**
 *
 */
class SS_Shortcode
{

    function __construct()
    {
        add_shortcode('ss_form', [$this, 'form']);
        add_action('ss_form_handle_form', [$this, 'handle_form']);
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
        ob_start();
        require_once (SS_DIR .'/views/form.php');
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
        }
    }
}

new SS_Shortcode();
