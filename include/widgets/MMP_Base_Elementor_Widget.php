<?php

namespace ElementorBaseWidget\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class MMP_Base_Elementor_Widget extends Widget_Base
{

    /**
     * @inheritDoc
     */
    public function get_name()
    {
        return 'music_player';
    }

    public function get_title()
    {
        return __('Music Player', MMP_TEXT_DOMAIN);
    }

    public function get_icon()
    {
        return 'fa fa-music';
    }

    public function get_categories()
    {
        return ['general'];
    }

    protected function _register_controls()
    {

        global $wpdb;

        /**
         * TODO Start Basic
         */

        $this->start_controls_section(
            'basic_section',
            [
                'label' => __('Basic', MMP_TEXT_DOMAIN),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $option = [];
        foreach ($wpdb->get_results('SELECT music_id AS id,name FROM ' . $wpdb->prefix . 'mmp_music') as $item) {
            $option[$item->id] = $item->name;
        }

        $this->add_control(
            'id',
            [
                'label'    => __('Show Elements', MMP_TEXT_DOMAIN),
                'type'     => \Elementor\Controls_Manager::SELECT2,
                'multiple' => false,
                'options'  => $option,
            ]
        );

        if (!MMP()->is_pro) {
            $this->add_control(
                'important_note',
                [
                    'type'            => \Elementor\Controls_Manager::RAW_HTML,
                    'raw'             => '<a href="http://idea-land.co/music-management-pro"><img src="' . MMP_IMG . 'Go To Pro - mini.png' . '"/></a>',
                    'content_classes' => 'your-class',
                ]
            );
        }

        $this->end_controls_section();

        /**
         * TODO End Basic
         */

        /**
         * TODO Start Player
         */

        $this->start_controls_section(
            'player_section',
            [
                'label' => __('Player', MMP_TEXT_DOMAIN),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'player_cover_blur',
            [
                'label'      => __('Blur Second Cover', MMP_TEXT_DOMAIN),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ]
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 25,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .mmp-detail-content .mmp-cover-shadow' => 'filter: blur({{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->add_control(
            'player_cover_opacity',
            [
                'label'      => __('Opacity Second Cover', MMP_TEXT_DOMAIN),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1,
                        'step' => 0.01,
                    ]
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 1,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .mmp-detail-content .mmp-cover-shadow' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'player_background',
            [
                'label'     => __('Background Color', MMP_TEXT_DOMAIN),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#fcfcfc',
                'scheme'    => [
                    'type'  => \Elementor\Scheme_Color::get_type(),
                    'value' => \Elementor\Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mmp-detail-content,
                    {{WRAPPER}} .mmp-detail-content .mmp-cover:before' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'player_text_color',
            [
                'label'     => __('Text Color', MMP_TEXT_DOMAIN),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#000',
                'scheme'    => [
                    'type'  => \Elementor\Scheme_Color::get_type(),
                    'value' => \Elementor\Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mmp-detail-content .mmp-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'player_meta_color',
            [
                'label'     => __('Meta Color', MMP_TEXT_DOMAIN),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#C7C7C7',
                'scheme'    => [
                    'type'  => \Elementor\Scheme_Color::get_type(),
                    'value' => \Elementor\Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mmp-detail-content .mmp-genres,
                    {{WRAPPER}} .mmp-detail-content .mmp-meta' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'player_progress_bg_color',
            [
                'label'     => __('Progress Background Color', MMP_TEXT_DOMAIN),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#f3f3f3',
                'scheme'    => [
                    'type'  => \Elementor\Scheme_Color::get_type(),
                    'value' => \Elementor\Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mmp-detail-content .mmp-progress-content' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'player_progress_value_bg_color',
            [
                'label'     => __('Progress Value Background Color', MMP_TEXT_DOMAIN),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#006D9A',
                'scheme'    => [
                    'type'  => \Elementor\Scheme_Color::get_type(),
                    'value' => \Elementor\Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mmp-detail-content .mmp-progressed,
                    {{WRAPPER}} .mmp-detail-content .mmp-progress-hover:after' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'player_progress_buffer_bg_color',
            [
                'label'     => __('Progress Buffered Background Color', MMP_TEXT_DOMAIN),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#DBDBDB',
                'scheme'    => [
                    'type'  => \Elementor\Scheme_Color::get_type(),
                    'value' => \Elementor\Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mmp-detail-content .mmp-preload' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'player_shadow_color',
            [
                'label'     => __('Shadow Color', MMP_TEXT_DOMAIN),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => 'rgba(0, 0, 0, 0.1)',
                'scheme'    => [
                    'type'  => \Elementor\Scheme_Color::get_type(),
                    'value' => \Elementor\Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mmp-detail-content' => 'box-shadow: 0 40px 82px -22px {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * TODO End Player
         */

        /**
         * TODO Start Controller
         */

        $this->start_controls_section(
            'controller_section',
            [
                'label' => __('Controller', MMP_TEXT_DOMAIN),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ctrl_space',
            [
                'label'      => __('Empty space up to the player', MMP_TEXT_DOMAIN),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 50,
                        'step' => 1,
                    ]
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .mmp-controller-content' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ctrl_play_button_color',
            [
                'label'     => __('Play Button Background Color', MMP_TEXT_DOMAIN),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#fff',
                'scheme'    => [
                    'type'  => \Elementor\Scheme_Color::get_type(),
                    'value' => \Elementor\Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mmp-controller-content .mmp-button#play' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'ctrl_play_button_shadow_color',
            [
                'label'     => __('Play Button Shadow Color', MMP_TEXT_DOMAIN),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => 'rgba(0, 0, 0, 0.05)',
                'scheme'    => [
                    'type'  => \Elementor\Scheme_Color::get_type(),
                    'value' => \Elementor\Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mmp-controller-content .mmp-button#play' => 'box-shadow: 0 0 0 6px {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'ctrl_icon_color',
            [
                'label'     => __('Icon Color', MMP_TEXT_DOMAIN),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#000',
                'scheme'    => [
                    'type'  => \Elementor\Scheme_Color::get_type(),
                    'value' => \Elementor\Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mmp-controller-content i' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'ctrl_hover_icon_color',
            [
                'label'     => __('Icon Color (Hover/Active)', MMP_TEXT_DOMAIN),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#006D9A',
                'scheme'    => [
                    'type'  => \Elementor\Scheme_Color::get_type(),
                    'value' => \Elementor\Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mmp-controller-content .mmp-button:hover,
                    {{WRAPPER}} .mmp-controller-content .mmp-button:hover i,
                    {{WRAPPER}} .mmp-controller-content .mmp-button.active:not(#play) i,
                    {{WRAPPER}} .mmp-controller-content .mmp-button.loop i,
                    {{WRAPPER}} .mmp-controller-content .mmp-button.this i,
                    {{WRAPPER}} .mmp-controller-content .mmp-button.loop,
                    {{WRAPPER}} .mmp-controller-content .mmp-button.this' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * TODO End Controller
         */

    }

    protected function render()
    {

        global $wpdb;

        $settings = $this->get_settings_for_display();

        // Check id is empty
        if (empty($settings['id'])) {

            echo self::Error(__('Please select a music', MMP_TEXT_DOMAIN));
            return false;

        }

        $music = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'mmp_music wmm WHERE wmm.music_id = ' . $settings['id']);

        if (empty($music)) {

            echo self::Error(__('invalid music', MMP_TEXT_DOMAIN));
            return false;

        } else $music = $music[0];

        $_cover = null;
        $_title = $music->name;
        $_genres = self::Genres($music->genres_id);
        $_artist = null;
        $_music = $music->music;
        $_music_meta = wp_get_attachment_metadata($_music);
        $_music_duration = $_music_meta['length_formatted'];
        $_music_url = wp_get_attachment_url($_music);
        $_player_id = uniqid('a');
        $_music_id = uniqid('m');

        if (!empty($music->thumb_image)) {
            $_cover = wp_get_attachment_image_url($music->thumb_image);
        } else {
            $_cover = wp_get_attachment_image_url(get_post_thumbnail_id($music->music));
        }

        $audio = [
            $_player_id => [
                'item'         => [
                    [
                        'id'            => $_music_id,
                        'real_id'       => $music->music_id,
                        'name'          => $_title,
                        'genres'        => $_genres,
                        'artist'        => $_artist,
                        'startTime'     => 0,
                        'duration'      => $_music_meta['length'],
                        'endTime'       => 0,
                        'playTime'      => 0,
                        'startBuffered' => 0,
                        'endBuffered'   => 0,
                        'cover'         => $_cover,
                        'play_status'   => false,
                        'src'           => [
                            '64'  => $_music_url,
                            '128' => $_music_url,
                            '256' => $_music_url,
                            '320' => $_music_url,
                        ],
                    ]
                ],
                'loop'         => -1,
                'volume'       => 1,
                'shuffle'      => false,
                'currentAudio' => $_music_id
            ]
        ];

        ?>

        <div class="mmp_player single" id="<?php echo esc_attr($_player_id); ?>" data-current-audio="<?php echo $_music_id; ?>">
            <div class="mmp-flx">
                <div class="mmp-detail-content">
                    <div class="mmp-cover">
                        <div class="mmp-cover-main" style="background: url(<?php echo esc_url($_cover); ?>);"></div>
                        <div class="mmp-cover-shadow" style="background: url(<?php echo esc_url($_cover); ?>);"></div>
                    </div>
                    <div class="mmp-title"><?php echo esc_html($_title); ?></div>
                    <div class="mmp-genres">
                        <?php echo esc_html($_genres); ?>
                    </div>
                    <div class="mmp-progress">
                        <div class="mmp-progress-content">
                            <div class="mmp-progressed"></div>
                            <div class="mmp-preload"></div>
                            <div class="mmp-progress-hover"></div>
                        </div>
                    </div>
                    <div class="mmp-meta">
                        <div class="mmp-start-time">00:00</div>
                        <div class="mmp-end-time"><?php echo esc_html($_music_duration); ?></div>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="mmp-controller-content">
                    <div class="mmp-button" id="shuffle"><i class="icon-shuffle"></i></div>
                    <div class="mmp-button" id="prev"><i class="icon-chevrons-left"></i></div>
                    <div class="mmp-button" id="play"><i class="icon-play"></i></div>
                    <div class="mmp-button" id="next"><i class="icon-chevrons-right"></i></div>
                    <div class="mmp-button" id="loop"><i class="icon-repeat"></i></div>
                </div>
            </div>
        </div>

        <?php
        self::Set_MMP($audio);

    }

    /**
     * Get Musics
     *
     * @param $id
     * @return string
     */
    static function Genres($id)
    {

        global $wpdb;

        $out = '';
        $query = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'mmp_genres wmg WHERE wmg.genres_id=' . $id);
        foreach ($query as $item) $out .= '<a href="#">' . $item->name . '</a>';

        return $out;

    }

    /**
     * Set Value MMP
     *
     * @param $arg
     */
    private static function Set_MMP($arg)
    {

        foreach ($arg as $index => $item) {
            ?>
            <script>MMP.currentAudio.<?php echo $index; ?> = JSON.parse(<?php echo json_encode(json_encode($item)); ?>);</script>
            <?php
        }

    }

    /**
     * Show Error
     *
     * @param $string
     * @return string
     */
    private static function Error($string)
    {

        return '<div class="mmp_error">' . $string . '</div>';

    }

}