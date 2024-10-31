<?php


class MMP_Ajax_Search2
{

    public function __construct()
    {

        add_action('wp_ajax_MMP_Search2', array($this, 'MMP_Search2'));
        add_action('wp_ajax_nopriv_MMP_Search2', array($this, 'MMP_Search2'));

    }

    public function MMP_Search2()
    {

        global $wpdb;

        MMP_Ajax_Nonce::Nonce();
        $search = esc_html(isset($_REQUEST['search']) ? $_REQUEST['search'] : null);
        $table = esc_html(isset($_REQUEST['table']) ? $_REQUEST['table'] : null);
        $column = esc_html(isset($_REQUEST['column']) ? $_REQUEST['column'] : null);
        $order_by = esc_html(isset($_REQUEST['order_by']) ? $_REQUEST['order_by'] : null);
        $object = esc_html(isset($_REQUEST['object']) ? $_REQUEST['object'] : null);
        $limit = esc_html(isset($_REQUEST['limit']) ? $_REQUEST['limit'] : null);

        $query = 'SELECT * FROM ' . $wpdb->prefix . $table . ' srh ' . (!empty($search) ? 'WHERE srh.' . $column . ' LIKE "%' . $search . '%"' : '') . ' ORDER BY srh.' . $order_by . ' DESC LIMIT ' . $limit;
        $query = $wpdb->get_results($query);

        ob_start();
        $object::List_Output($query);
        $out = ob_get_contents();
        ob_end_clean();

        echo self::sanitize_output($out);
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

new MMP_Ajax_Search2();