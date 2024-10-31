<?php


class MMP_Ajax_Remove_Like
{

    public function __construct()
    {

        add_action('wp_ajax_MMP_Remove_Like', array($this, 'MMP_Remove_Like'));
        add_action('wp_ajax_nopriv_MMP_Remove_Like', array($this, 'MMP_Remove_Like'));

    }

    public function MMP_Remove_Like()
    {

        global $wpdb;

        MMP_Ajax_Nonce::Nonce();
        $id = esc_html($_REQUEST['id']);
        $user = get_current_user_id();

        $wpdb->delete($wpdb->prefix . 'mmp_like',
            [
                'user_id'   => $user,
                'object_id' => $id
            ]
        );

        die;

    }

}

new MMP_Ajax_Remove_Like();