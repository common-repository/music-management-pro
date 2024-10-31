<?php

// Load Form Ajax
class MMP_Ajax_Form_Load
{

    public function __construct()
    {

        add_action('wp_ajax_Load_Form', array($this, 'Load_Form'));
        add_action('wp_ajax_nopriv_Load_Form', array($this, 'Load_Form'));

    }

    /**
     * Load form
     */
    public function Load_Form()
    {

        MMP_Ajax_Nonce::Nonce();
        $prefix = esc_html($_REQUEST['prefix']);
        $data = MMP()->esc_array(isset($_REQUEST['data']) ? $_REQUEST['data'] : null);

        do_action('mmp_' . $prefix . '_form', $data);

        die;

    }

}

new MMP_Ajax_Form_Load();