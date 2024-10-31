<?php

namespace ElementorBaseWidget;

class MMP_Register_Widget
{

    /**
     * Instance
     *
     * @var null
     */
    private static $_instance = null;

    /**
     * MMP_Register_Widget constructor.
     *
     * Register plugin action hooks and filters
     */
    public function __construct()
    {

        // Register widget scripts
        add_action('elementor/frontend/after_register_scripts', [$this, 'widget_scripts']);

        // Register widgets
        add_action('elementor/widgets/widgets_registered', [$this, 'register_widgets']);

    }

    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @return MMP_Register_Widget|null
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * widget_scripts
     *
     * Load required plugin core files.
     */
    public function widget_scripts()
    {

        wp_enqueue_style('mmp_front_widget', MMP_CSS . 'mmp_front_widget.css');
        wp_enqueue_style('mmp_base_font', MMP_CSS . 'mmp_base_font.css');
        wp_enqueue_style('mmp_font_icon', MMP_CSS . 'mmp_font_icon.css');

        wp_enqueue_script('jquery');
        wp_enqueue_script('mmp_player', MMP_JS . 'mmp_player_beta.js', [], '1.0.0');
        wp_enqueue_script('mmp_front_script', MMP_JS . 'mmp_front_script.js', [], '1.0.0');

        $localize = [
            'AjaxUrl'  => admin_url('admin-ajax.php'),
            'Nonce'    => wp_create_nonce('MMP_Ajax_Action'),
            'follow'   => __('Follow', MMP_TEXT_DOMAIN),
            'unfollow' => __('Unfollow', MMP_TEXT_DOMAIN),
        ];
        wp_localize_script('mmp_front_script', 'mmp_localize', $localize);

    }

    /**
     * Include Widgets files
     *
     * Load widgets files
     */
    private function include_widgets_files()
    {
        require_once(__DIR__ . '/MMP_Base_Elementor_Widget.php');
    }

    /**
     * Register Widgets
     *
     * Register new Elementor widgets
     */
    public function register_widgets()
    {

        // Its is now safe to include Widgets files
        $this->include_widgets_files();

        // Register Widgets
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\MMP_Base_Elementor_Widget());

    }

}

new MMP_Register_Widget();