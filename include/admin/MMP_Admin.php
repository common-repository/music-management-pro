<?php

// Start Admin
class MMP_Admin
{

    public function __construct()
    {

        $this->_Include();
        $this->Init();

    }

    private function Init()
    {

        add_action('mmp_admin_header', array($this, 'Enqueue'));

    }

    public function Enqueue()
    {

        wp_enqueue_style('mmp_style', MMP_CSS . 'mmp_style.css');
        wp_enqueue_style('mmp_admin_style', MMP_CSS . 'mmp_admin_style.css');
        wp_enqueue_style('mmp_base_font', MMP_CSS . 'mmp_base_font.css');
        wp_enqueue_style('mmp_font_icon', MMP_CSS . 'mmp_font_icon.css');
        wp_enqueue_style('mmp_multi_select', MMP_CSS . 'mmp_multi_select.css');

        wp_enqueue_media();

        wp_enqueue_script('jquery');
        wp_enqueue_script('mmp_object', MMP_JS . 'mmp_object.js');
        wp_enqueue_script('mmp_admin_script', MMP_JS . 'mmp_admin_script.js');
        wp_enqueue_script('mmp_multi_select', MMP_JS . 'mmp_multi_select.js');

        $localize =
            array(
                'AjaxUrl'           => admin_url('admin-ajax.php'),
                'Nonce'             => wp_create_nonce('MMP_Ajax_Action'),
                'Message'           => MMP()->message,
                '_IMAGE'            => MMP_IMG,
                // Text
                'form_save_message' => __('The data was successfully saved.', MMP_TEXT_DOMAIN),
                'delete_data'       => __('The data was successfully deleted.', MMP_TEXT_DOMAIN),
                'ok'                => __('ok', MMP_TEXT_DOMAIN),
                'save'              => __('save', MMP_TEXT_DOMAIN),
                'cancel'            => __('cancel', MMP_TEXT_DOMAIN),
                'search'            => __('search', MMP_TEXT_DOMAIN),
                'loading_text'      => __('loading', MMP_TEXT_DOMAIN),
                'error_text'        => __('Error', MMP_TEXT_DOMAIN),
                'success_text'      => __('Success', MMP_TEXT_DOMAIN),
                'save_artist'       => __('Artist information saved successfully.', MMP_TEXT_DOMAIN),
                'save_music'        => __('Music information saved successfully.', MMP_TEXT_DOMAIN),
                'save_genres'       => __('Genres information saved successfully.', MMP_TEXT_DOMAIN),
                'delete_artist'     => __('Artist successfully removed.', MMP_TEXT_DOMAIN),
                'delete_music'      => __('Music successfully removed.', MMP_TEXT_DOMAIN),
                'delete_genres'     => __('Genres successfully removed.', MMP_TEXT_DOMAIN),
                'empty_field'       => __('Please fill in the fields', MMP_TEXT_DOMAIN),
                'required_field'    => __('Please fill in the fields below:', MMP_TEXT_DOMAIN),
                'support_success'   => __('You must reload the page to see the submitted support', MMP_TEXT_DOMAIN),
                'support_error'     => __('There is a problem sending support. It may be from your internet.', MMP_TEXT_DOMAIN),
            );
        wp_localize_script('mmp_object', 'mmp_localize', $localize);

    }

    private function _Include()
    {

        include_once 'MMP_Admin_Elements.php';
        include_once 'MMP_Admin_Fields.php';

        // AJAX
        include_once 'ajax/MMP_Ajax_Nonce.php';
        include_once 'ajax/MMP_Ajax_Form_Save.php';
        include_once 'ajax/MMP_Ajax_Form_Load.php';
        include_once 'ajax/MMP_Ajax_Search.php';
        include_once 'ajax/MMP_Ajax_Search2.php';
        include_once 'ajax/MMP_Ajax_Delete.php';
        include_once 'ajax/MMP_Ajax_Multi_Select_Genres.php';
        include_once 'ajax/MMP_Ajax_Multi_Select_Artists.php';
        include_once 'ajax/MMP_Ajax_Music_Preview.php';
        include_once 'ajax/MMP_Ajax_See_Follower.php';
        include_once 'ajax/MMP_Ajax_See_Like.php';

        // PAGES
        include_once 'page/MMP_Dashboard_Page.php';

        // MENU CONTROLLER
        include_once 'MMP_Admin_Menu.php';
        include_once 'MMP_Admin_PageController.php';

    }

}

new MMP_Admin();