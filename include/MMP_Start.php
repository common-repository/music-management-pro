<?php

// START
final class MMP_Start
{

    // VERSION PLUGIN
    public $version = MMP_VERSION;

    // NEW VERSION
    public $new_version = null;

    // ENABLE PRO PLUGIN - MMP ADDITIONAL
    public $is_pro = false;

    // MESSAGE
    public $message = [];

    // PLUGIN INSTANCE
    private static $_instance = null;

    /**
     * Instance
     *
     * @return MMP_Start|null
     */
    public static function Instance()
    {

        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;

    }

    /**
     * MMP_Start constructor.
     */
    public function __construct()
    {

        $this->_Define();

        // PRO FILTER
        $this->is_pro = apply_filters('mmp_pro', false);

        $this->_Include();

    }

    /**
     * Set define
     */
    private function _Define()
    {

        define('MMP_NAME', 'Music Management Pro');
        define('MMP_TEXT_DOMAIN', 'mmp_text_domain');
        define('MMP_CSS', trailingslashit(MMP_PLUGIN_URL . 'assets/css'));
        define('MMP_IMG', trailingslashit(MMP_PLUGIN_URL . 'assets/img'));
        define('MMP_JS', trailingslashit(MMP_PLUGIN_URL . 'assets/js'));

    }

    /**
     * Include
     */
    private function _Include()
    {

        // Objects
        include_once 'admin/page/MMP_Music.php';
        include_once 'admin/page/MMP_Artist.php';
        include_once 'admin/page/MMP_Genres.php';
        include_once 'admin/ajax/MMP_Ajax_Nonce.php';
        include_once 'admin/ajax/MMP_Ajax_Like.php';
        include_once 'admin/ajax/MMP_Ajax_Remove_Like.php';
        include_once 'admin/ajax/MMP_Ajax_Follow.php';
        include_once 'admin/ajax/MMP_Ajax_Play.php';

        // Admin
        if (is_admin()) {
            include_once MMP_PLUGIN_PATH . 'include/admin/MMP_Admin.php';
        }

        // Elementor widgets
        include_once 'widgets/MMP_Register_Widget.php';

    }

    /**
     * Create Message(Alert)
     *
     * @param string $title
     * @param string $description
     * @param array $icon type | content
     */
    public function Message($title, $description, $icon)
    {

        $this->message[] = [
            'title'       => $title,
            'description' => $description,
            'icon'        => [
                'type'    => $icon['type'],
                'content' => $icon['content']
            ]
        ];

    }

    /**
     * esc_array
     *
     * @param $array
     * @return array
     */
    public function esc_array($array)
    {

        $a = [];

        if (!empty($array)) {
            foreach ($array as $index => $value) {
                $a[esc_html($index)] = esc_html($value);
            }
        }

        return $a;

    }

}