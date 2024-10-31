<?php


class MMP_Ajax_Multi_Select_Artists
{

    public function __construct()
    {

        add_action('wp_ajax_Ajax_Multi_select_Artists', array($this, 'Ajax_Multi_select_Artists'));
        add_action('wp_ajax_nopriv_Ajax_Multi_select_Artists', array($this, 'Ajax_Multi_select_Artists'));

    }

    public function Ajax_Multi_select_Artists()
    {

        global $wpdb;

        MMP_Ajax_Nonce::Nonce();
        $search = esc_html($_REQUEST['search']);
        $selected = esc_html(isset($_REQUEST['id']) ? $_REQUEST['id'] : null);
        $result = [];
        $out = [];

        if (!empty($selected)) {

            $query = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'mmp_artist wmg WHERE wmg.name LIKE "%' . $search . '%" && wmg.artist_id IN (' . join(',', $selected) . ') ORDER BY wmg.artist_id DESC LIMIT 50');
            foreach ($query as $item) {

                $out[] = [
                    'value' => $item->artist_id,
                    'text'  => $item->name,
                    'image' => wp_get_attachment_image_url($item->thumb_image, 'medium')
                ];

            }

        }

        if (empty($search)) {

            update_option('mmp_check', 'SELECT * FROM ' . $wpdb->prefix . 'mmp_artist wmg ' . (!empty($selected) ? 'WHERE wmg.artist_id NOT IN (' . join(',', $selected) . ')' : '') . ' ORDER BY wmg.artist_id DESC LIMIT 50');
            $query = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'mmp_artist wmg ' . (!empty($selected) ? 'WHERE wmg.artist_id NOT IN (' . join(',', $selected) . ')' : '') . ' ORDER BY wmg.artist_id DESC LIMIT 50');
            $result = array_merge(
                $result,
                $query
            );

        } else {

            $query = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'mmp_artist wmg WHERE wmg.name LIKE "%' . $search . '%" ' . (!empty($selected) ? '&& wmg.artist_id NOT IN (' . join(',', $selected) . ')' : '') . ' ORDER BY wmg.artist_id DESC LIMIT 50');
            $result = array_merge(
                $result,
                $query
            );

        }

        foreach ($result as $item) {

            $out[] = [
                'value' => $item->artist_id,
                'text'  => $item->name,
                'image' => wp_get_attachment_image_url($item->thumb_image, 'medium')
            ];

        }

        wp_send_json($out);

        die;

    }

}

new MMP_Ajax_Multi_Select_Artists();