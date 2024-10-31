<?php


class MMP_Ajax_Like
{

    public function __construct()
    {

        add_action('wp_ajax_MMP_Like', array($this, 'MMP_Like'));
        add_action('wp_ajax_nopriv_MMP_Like', array($this, 'MMP_Like'));

    }

    public function MMP_Like()
    {

        global $wpdb;

        MMP_Ajax_Nonce::Nonce();
        $id = esc_html($_REQUEST['id']);
        $user = get_current_user_id();

        $insert = $wpdb->insert($wpdb->prefix . 'mmp_like',
            [
                'user_id'     => $user,
                'object_id'   => $id,
                'object_type' => 0,
            ]
        );

        if (!empty($insert)) return true;
        else return false;

    }

}

new MMP_Ajax_Like();