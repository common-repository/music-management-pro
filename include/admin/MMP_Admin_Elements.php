<?php

// ELEMENTS FOR CREATE PAGES
class MMP_Admin_Elements
{

    public static function Header()
    {
        ?>

        <div class="mmp_page_header">
            <div class="title"><?php echo MMP_NAME; ?> - <?php echo get_admin_page_title(); ?></div>
            <div class="controllers">
                <?php

                // FOR ADD TOOLS AND WIDGETS IN HEADER
                do_action('mmp_admin_header');

                ?>
            </div>
        </div>

        <?php
    }

    public static function Button_Extra($text, $icon, $class = '')
    {
        ?>

        <div class="mmp_button_extra <?php echo esc_attr($class); ?>">
            <i class="<?php echo esc_attr($icon); ?>"></i>
            <span><?php echo esc_html($text); ?></span>
        </div>

        <?php
    }

    public static function Button_Icon($icon, $class = '')
    {
        ?>

        <div class="mmp_button_icon <?php echo esc_attr($class); ?>">
            <i class="<?php echo esc_attr($icon); ?>"></i>
        </div>

        <?php
    }

    public static function Search($placeholder, $class = '')
    {
        ?>

        <div class="mmp_search <?php echo esc_attr($class); ?>">
            <input type="text" placeholder="<?php echo esc_attr($placeholder); ?>">
            <i class="icon-search"></i>
        </div>

        <?php
    }

    public static function Loading()
    {
        ?>

        <div class="mmp_fix_loading"
             style="display: table; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: #fff; z-index: 9999999;">
            <div class="content" style="display: table-cell; vertical-align: middle; text-align: center;">
                <img src="<?php echo esc_url(MMP_IMG . '/logo/logo-colored.svg') ?>" alt="" style="width: 260px;"/>
                <div style="font-family: Roboto, sans-serif; font-size:14px; font-weight: 600; text-transform: uppercase; color: #3b3b3b;"><?php echo __('loading', MMP_TEXT_DOMAIN) ?>
                    ...
                </div>
            </div>
        </div>

        <?php
    }

    public static function Buy_Plugin_Banner()
    {
        ?>

        <div class="mmp_buy_plugin_banner">
            <img src="<?php echo MMP_IMG . 'Go To Pro.png' ?>" alt="">
            <a href="http://idea-land.co/music-management-pro"></a>
        </div>

        <?php
    }

    public static function number_format_short($n, $precision = 1)
    {
        if ($n < 900) {
            // 0 - 900
            $n_format = number_format($n, $precision);
            $suffix = '';
        } else if ($n < 900000) {
            // 0.9k-850k
            $n_format = number_format($n / 1000, $precision);
            $suffix = 'K';
        } else if ($n < 900000000) {
            // 0.9m-850m
            $n_format = number_format($n / 1000000, $precision);
            $suffix = 'M';
        } else if ($n < 900000000000) {
            // 0.9b-850b
            $n_format = number_format($n / 1000000000, $precision);
            $suffix = 'B';
        } else {
            // 0.9t+
            $n_format = number_format($n / 1000000000000, $precision);
            $suffix = 'T';
        }

        // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
        // Intentionally does not affect partials, eg "1.50" -> "1.50"
        if ($precision > 0) {
            $dotzero = '.' . str_repeat('0', $precision);
            $n_format = str_replace($dotzero, '', $n_format);
        }

        return $n_format . $suffix;
    }

}