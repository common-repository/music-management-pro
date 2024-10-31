<?php

// Check nonce
class MMP_Ajax_Nonce
{

    const NONCE = 'MMP_Ajax_Action';

    /**
     * Check nonce
     */
    public static function Nonce()
    {

        $nonce = esc_html($_POST['nonce']);

        if (!wp_verify_nonce($nonce, self::NONCE)) {
            print_r('Error');
            die();
        }

    }

}