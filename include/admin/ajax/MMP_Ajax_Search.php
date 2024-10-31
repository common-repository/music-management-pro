<?php


class MMP_Ajax_Search
{

    public function __construct()
    {

        add_action('wp_ajax_Search', array($this, 'Search'));
        add_action('wp_ajax_nopriv_Search', array($this, 'Search'));

    }

    public function Search()
    {

        MMP_Ajax_Nonce::Nonce();
        $data = MMP()->esc_array($_REQUEST['data']);


        if (empty($data['search'])) {

            $result = ('MMP_' . esc_html($data['prefix']))::Get($data['page']);

            ob_start();
            ('MMP_' . esc_html($data['prefix']))::List_Output($result);
            $output = ob_get_contents();
            ob_end_clean();

            echo self::sanitize_output($output);
            die;

        }

        $order_by = 'id';
        if ($data['prefix'] == 'EX_Playlist') $order_by = 'playlist_id';
        if ($data['prefix'] == 'EX_Album') $order_by = 'album_id';

        $result = MMP_Database::Get('MMP_' . esc_html($data['from']),
            array(
                'per_page' => esc_html($data['per_page']),
                'page'     => esc_html($data['page']),
                'order_by' => $order_by
            ),
            array(
                'operator'  => 'LIKE',
                'condition' => array(
                    esc_html($data['by']) => '%' . esc_html($data['search']) . '%'
                )
            )
        );

        ob_start();
        ('MMP_' . esc_html($data['prefix']))::List_Output($result);
        $output = ob_get_contents();
        ob_end_clean();

        echo self::sanitize_output($output);
        die;

    }

    private static function sanitize_output($buffer)
    {

        $search = array(
            '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
            '/[^\S ]+\</s',     // strip whitespaces before tags, except space
            '/(\s)+/s',         // shorten multiple whitespace sequences
            '/<!--(.|\s)*?-->/' // Remove HTML comments
        );

        $replace = array(
            '>',
            '<',
            '\\1',
            ''
        );

        $buffer = preg_replace($search, $replace, $buffer);

        return $buffer;
    }

}

new MMP_Ajax_Search();