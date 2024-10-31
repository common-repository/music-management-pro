<?php

// Save Form Data
class MMP_Ajax_Delete
{

    public function __construct()
    {

        add_action('wp_ajax_Delete_Data', array($this, 'Delete_Data'));
        add_action('wp_ajax_nopriv_Delete_Data', array($this, 'Delete_Data'));

    }

    public function Delete_Data()
    {

        MMP_Ajax_Nonce::Nonce();
        $prefix = esc_html($_REQUEST['prefix']);
        $data = MMP()->esc_array($_REQUEST['data']);

        // SAVE
        do_action('mmp_' . esc_html($prefix) . '_delete', $data);

        die;

    }

}

new MMP_Ajax_Delete();