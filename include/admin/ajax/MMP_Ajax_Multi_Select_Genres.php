<?php


class MMP_Ajax_Multi_Select_Genres
{

    public function __construct()
    {

        add_action('wp_ajax_Ajax_Multi_select_Genres', array($this, 'Ajax_Multi_select_Genres'));
        add_action('wp_ajax_nopriv_Ajax_Multi_select_Genres', array($this, 'Ajax_Multi_select_Genres'));

    }

    public function Ajax_Multi_select_Genres()
    {

        global $wpdb;

        MMP_Ajax_Nonce::Nonce();
        $search = esc_html($_REQUEST['search']);
        $selected = esc_html(isset($_REQUEST['id']) ? $_REQUEST['id'] : null);
        $result = [];
        $out = [];

        if (!empty($selected)) {

            $query = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'mmp_genres wmg WHERE wmg.name LIKE "%' . $search . '%" && wmg.genres_id IN (' . join(',', $selected) . ') ORDER BY wmg.genres_id DESC LIMIT 50');
            foreach ($query as $item) {

                $out[] = [
                    'value' => $item->genres_id,
                    'text'  => $item->name
                ];

            }

        }

        if (empty($search)) {

            $query = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'mmp_genres wmg ' . (!empty($selected) ? 'WHERE wmg.genres_id NOT IN (' . join(',', $selected) . ')' : '') . ' ORDER BY wmg.genres_id DESC LIMIT 50');
            $result = array_merge(
                $result,
                $query
            );

        } else {

            $query = $wpdb->get_results('SELECT wmg.genres_id,wmg.name FROM ' . $wpdb->prefix . 'mmp_genres wmg WHERE wmg.name LIKE "%' . $search . '%" ' . (!empty($selected) ? '&& wmg.genres_id NOT IN (' . join(',', $selected) . ')' : '') . ' ORDER BY wmg.genres_id DESC LIMIT 50');
            $result = array_merge(
                $result,
                $query
            );

        }

        foreach ($result as $val) {

            $out[] = [
                'value' => $val->genres_id,
                'text'  => $val->name
            ];

        }

        wp_send_json($out);

        die;

    }

}

new MMP_Ajax_Multi_Select_Genres();