<?php

/**
 *
 */
class SS_Shortcode
{

    function __construct()
    {
        add_shortcode('ss_form', [$this, 'form']);
    }

    public function form($attr=[])
    {
        $attr = shortcode_atts([], $attr);

        ob_start();
        require_once (SS_DIR .'/views/form.php');
        return ob_get_clean();
    }
}

new SS_Shortcode();
