<?php

// CREATE MENU IN WORDPRESS ADMIN
class MMP_Admin_Menu
{

    // VARIABLES
    private $main_menu = array();
    private $sub_menus = array();
    private $Icon_Svg;

    public function __construct()
    {

        // EMPTY

    }

    /**
     * Main menu
     *
     * @param $page_title string Page title
     * @param $menu_title string Menu title
     * @param $capability string manage_options
     * @param $menu_slug string
     * @param $callback callable
     */
    public function MainMenu($page_title, $menu_title, $capability, $menu_slug, $callback)
    {

        $this->main_menu['page_title'] = $page_title;
        $this->main_menu['menu_title'] = $menu_title;
        $this->main_menu['capability'] = $capability;
        $this->main_menu['menu_slug'] = $menu_slug;
        $this->main_menu['callback'] = $callback;

    }

    /**
     * Sub menu
     *
     * @param $page_title string Page title
     * @param $menu_title string Menu title
     * @param $capability string manage_options
     * @param $menu_slug string
     * @param $callback callable
     */
    public function SubMenu($page_title, $menu_title, $capability, $menu_slug, $callback)
    {

        $this->sub_menus[] =
            array(
                'page_title' => $page_title,
                'menu_title' => $menu_title,
                'capability' => $capability,
                'menu_slug'  => $menu_slug,
                'callback'   => $callback,
            );

    }

    /**
     * Create menu
     */
    public function Create()
    {

        // ADD MENU
        add_action('admin_menu', array($this, 'AddMenu'));

    }

    /**
     * Add menu
     */
    public function AddMenu()
    {

        // ADD MENU
        add_menu_page(
            $this->main_menu['page_title'],
            $this->main_menu['menu_title'],
            $this->main_menu['capability'],
            $this->main_menu['menu_slug'],
            $this->main_menu['callback'],
            $this->Icon_Svg
        );

        // ADD SUB MENU
        foreach ($this->sub_menus as $key => $value) {

            add_submenu_page(
                $this->main_menu['menu_slug'],
                $value['page_title'],
                $value['menu_title'],
                $value['capability'],
                $value['menu_slug'],
                $value['callback']
            );

        }

    }

    /**
     * Menu icon
     *
     * @param $url string Url icon Or content svg
     * @param bool $is_svg Get content svg
     */
    public function MenuIcon($url, $is_svg = false)
    {

        if ($is_svg) {

            $svg = $url;
            $out = 'data:image/svg+xml;base64,' . base64_encode($svg);
            $this->Icon_Svg = $out;

        } else {
            $this->Icon_Svg = $url;
        }

    }

}