<?php


class MMP_Ajax_Play
{

    public function __construct()
    {

        add_action('wp_ajax_MMP_Play', array($this, 'MMP_Play'));
        add_action('wp_ajax_nopriv_MMP_Play', array($this, 'MMP_Play'));

    }

    public function MMP_Play()
    {

        global $wpdb;

        MMP_Ajax_Nonce::Nonce();
        $id = esc_html($_REQUEST['id']);

        $wpdb->query('UPDATE ' . $wpdb->prefix . 'mmp_music wmm SET wmm.play = wmm.play + 1 WHERE wmm.music_id = ' . $id);

        die;

    }

}

new MMP_Ajax_Play();