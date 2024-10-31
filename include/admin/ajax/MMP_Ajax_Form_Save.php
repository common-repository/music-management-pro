<?php

// Save Form Data
class MMP_Ajax_Form_Save
{

    public function __construct()
    {

        add_action('wp_ajax_Save_Form', array($this, 'Save_Form'));
        add_action('wp_ajax_nopriv_Save_Form', array($this, 'Save_Form'));

    }

    public function Save_Form()
    {

        MMP_Ajax_Nonce::Nonce();
        $data =
            array(
                'fields_data'    => stripslashes_deep(MMP()->esc_array($_REQUEST['fieldsData'])),
                'condition_data' => MMP()->esc_array(isset($_REQUEST['data']) ? stripslashes_deep($_REQUEST['data']) : null)
            );

        // SAVE
        do_action('mmp_save_form_' . esc_html($_REQUEST['prefix']), $data);

        die;

    }

}

new MMP_Ajax_Form_Save();