<?php


class MMP_Ajax_Follow
{

    public function __construct()
    {

        add_action('wp_ajax_MMP_Follow', array($this, 'MMP_Follow'));
        add_action('wp_ajax_nopriv_MMP_Follow', array($this, 'MMP_Follow'));

    }

    public function MMP_Follow()
    {

        global $wpdb;

        MMP_Ajax_Nonce::Nonce();
        $id = esc_html($_REQUEST['id']);
        $user = get_current_user_id();

        $result = $wpdb->get_results('SELECT COUNT(*) AS count FROM ' . $wpdb->prefix . 'mmp_follower wmf WHERE wmf.artist_id = ' . $id . ' AND wmf.user_id = ' . $user)[0]->count;

        if (empty($result)) {

            $wpdb->insert($wpdb->prefix . 'mmp_follower',
                [
                    'user_id'   => $user,
                    'artist_id' => $id,
                ]
            );

        } else {

            $wpdb->delete($wpdb->prefix . 'mmp_follower',
                [
                    'user_id'   => $user,
                    'artist_id' => $id,
                ]
            );

        }

        die;

    }

}

new MMP_Ajax_Follow();