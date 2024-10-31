<?php

// DASHBOARD PAGE
class MMP_Dashboard_Page
{

    public static function Output()
    {

        // Banner Pro
        if (!MMP()->is_pro) {
            MMP_Admin_Elements::Buy_Plugin_Banner();
        }

        self::Content();

    }

    private static function Content()
    {

        global $wpdb;

        ?>

        <div class="mmp_page_section mmp-dashboard-analyze">
            <div class="mmp-dashboard-analyze-item">
                <div class="mmp-dashboard-analyze-item-inner">
                    <div class="header"><?php echo __('Last Artist', MMP_TEXT_DOMAIN); ?></div>
                    <?php

                    $artist_q = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'mmp_artist wma ORDER BY wma.artist_id DESC LIMIT 3');
                    if (!empty($artist_q)) {
                        foreach ($artist_q as $item) {
                            ?>

                            <div class="mmp-dashboard-analyze-am-item">
                                <div class="image-area">
                                    <div class="image">
                                        <div class="image-inner"
                                             style="background-image:url(<?php echo esc_url(wp_get_attachment_image_url($item->thumb_image)); ?>)"></div>
                                    </div>
                                </div>
                                <div class="content-area">
                                    <div class="title"><?php echo esc_html($item->name); ?></div>
                                    <div class="meta">
                                        <div class="date">
                                            <i class="icon-time"></i>
                                            <span><?php echo esc_html($item->date); ?></span>
                                        </div>
                                        <div class="status">
                                            <i class="icon-id-card-o"></i>
                                            <span><?php echo esc_html($item->artist_id); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php
                        }
                    } else {
                        echo '<div class="empty">' . __('Empty', MMP_TEXT_DOMAIN) . '</div>';
                    }

                    ?>
                </div>
            </div>
            <div class="mmp-dashboard-analyze-item">
                <div class="mmp-dashboard-analyze-item-inner">
                    <div class="header"><?php echo __('Last Music', MMP_TEXT_DOMAIN); ?></div>
                    <?php

                    $music_q = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'mmp_music wmm WHERE wmm.status = 1 ORDER BY wmm.music_id DESC LIMIT 3');
                    if (!empty($music_q)) {
                        foreach ($music_q as $item) {

                            $cover = '';

                            if (!empty($item->thumb_image)) $cover = $item->thumb_image;
                            else $cover = get_post_thumbnail_id($item->music);

                            ?>

                            <div class="mmp-dashboard-analyze-am-item">
                                <div class="image-area">
                                    <div class="image">
                                        <div class="image-inner"
                                             style="background-image:url(<?php echo esc_url(wp_get_attachment_image_url($cover)); ?>)"></div>
                                    </div>
                                </div>
                                <div class="content-area">
                                    <div class="title"><?php echo esc_html($item->name); ?></div>
                                    <div class="meta">
                                        <div class="date">
                                            <i class="icon-time"></i>
                                            <span><?php echo esc_html($item->date); ?></span>
                                        </div>
                                        <div class="status">
                                            <i class="icon-id-card-o"></i>
                                            <span><?php echo esc_html($item->music_id); ?></span>
                                        </div>
                                        <div class="link">
                                            <i class="icon-link"></i>
                                            <input type="url" disabled
                                                   value="<?php echo esc_url(wp_get_attachment_url($item->music)); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php
                        }
                    } else {
                        echo '<div class="empty">' . __('Empty', MMP_TEXT_DOMAIN) . '</div>';
                    }

                    ?>
                </div>
            </div>
            <div class="mmp-dashboard-analyze-item">
                <div class="mmp-dashboard-analyze-item-inner">
                    <div class="header"><?php echo __('Album', MMP_TEXT_DOMAIN); ?></div>
                    <?php

                    if(MMP()->is_pro){

                        $album_q = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'mmp_album wma WHERE wma.status = 1 ORDER BY wma.album_id DESC LIMIT 3');
                        if (!empty($album_q)) {
                            foreach ($album_q as $item) {

                                ?>

                                <div class="mmp-dashboard-analyze-am-item">
                                    <div class="image-area">
                                        <div class="image">
                                            <div class="image-inner"
                                                 style="background-image:url(<?php echo esc_url(wp_get_attachment_image_url($item->thumb_image)); ?>)"></div>
                                        </div>
                                    </div>
                                    <div class="content-area">
                                        <div class="title"><?php echo esc_html($item->name); ?></div>
                                        <div class="meta">
                                            <div class="date">
                                                <i class="icon-time"></i>
                                                <span><?php echo esc_html($item->date); ?></span>
                                            </div>
                                            <div class="status">
                                                <i class="icon-id-card-o"></i>
                                                <span><?php echo esc_html($item->album_id); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php
                            }
                        } else {
                            echo '<div class="empty">' . __('Empty', MMP_TEXT_DOMAIN) . '</div>';
                        }

                    }else{
                        echo '<div class="empty">' . __('In the Pro version', MMP_TEXT_DOMAIN) . '</div>';
                    }

                    ?>
                </div>
            </div>
        </div>

        <?php
    }

}