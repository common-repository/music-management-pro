<?php


class MMP_Ajax_Music_Preview
{

    public function __construct()
    {

        add_action('wp_ajax_Music_Preview', array($this, 'Music_Preview'));
        add_action('wp_ajax_nopriv_Music_Preview', array($this, 'Music_Preview'));

    }

    public function Music_Preview()
    {

        MMP_Ajax_Nonce::Nonce();
        $data = MMP()->esc_array($_REQUEST['data']);
        $music = array();

        $get_music = MMP_Music::Get_By_Id($data['id'])[0];
        $music_url = wp_get_attachment_url($get_music->music);
        $music_meta = wp_get_attachment_metadata($get_music->music);

        if (!empty($get_music->music_cover)) {
            $music['image'] = wp_get_attachment_image_url($get_music->music_cover);
        } else {
            $music['image'] = wp_get_attachment_image_url(get_post_thumbnail_id($get_music->music));
        }

        $music['title'] = $get_music->name;
        $music['time'] = $music_meta['length_formatted'];
        $music['url'] = $music_url;

        wp_send_json($music);

        die;

    }

}

new MMP_Ajax_Music_Preview();