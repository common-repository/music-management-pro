<?php


class MMP_Ajax_See_Follower
{

    public function __construct()
    {

        add_action('wp_ajax_Artist_See_Follower', array($this, 'Artist_See_Follower'));
        add_action('wp_ajax_nopriv_Artist_See_Follower', array($this, 'Artist_See_Follower'));

    }

    public function Artist_See_Follower()
    {


        global $wpdb;

        $id = esc_html($_REQUEST['id']);
        $result = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'mmp_follower wmf WHERE wmf.artist_id = ' . $id . ' LIMIT 100');

        if (empty($result)) {
            echo __('Not Found Follower', MMP_TEXT_DOMAIN);
            die;
        }

        foreach ($result as $index => $value) {

            $user = get_user_by('id', $value->user_id);
            $user_id = $user->data->ID;
            $user_name = $user->data->display_name;

            ?>

            <div class="item">
                <img src="<?php echo esc_url(get_avatar_url($user_id)); ?>"/>
                <span>
                    <span><?php echo esc_html($user_name); ?></span>
                    <?php echo __('ID', MMP_TEXT_DOMAIN) . ': ' . $user_id; ?>
                </span>
            </div>

            <?php

        }

        die;

    }

}

new MMP_Ajax_See_Follower();