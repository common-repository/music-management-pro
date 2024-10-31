<?php

// Music PAGE
class MMP_Music
{

    /**
     * MMP_Music constructor.
     * Start class for actions
     */
    public function __construct()
    {

        add_action('mmp_music_form', array($this, 'Form'));
        add_action('mmp_save_form_music', array($this, 'Set'));
        add_action('mmp_music_delete', array($this, 'Delete'));

        if (isset($_REQUEST['page']) && (esc_html($_REQUEST['page']) === 'mmp_music')) {
            add_action('mmp_admin_header', array($this, 'Header'));
        }

    }

    /**
     * Save and edit data
     *
     * @param $data array Parameter
     */
    public function Set($data)
    {

        update_option('mmp_check', print_r($data, true));

        $result = null;
        $condition = $data['condition_data'];
        $data = $data['fields_data'];

        $arg =
            array(
                'name'        => esc_html($data['name']),
                'description' => esc_html(isset($data['description']) ? $data['description'] : ''),
                'date'        => strtoupper(date('m/d/Y h:i a')),
                'back_image'  => esc_html(isset($data['back_image']) ? $data['back_image'] : null),
                'thumb_image' => esc_html(isset($data['thumb_image']) ? $data['thumb_image'] : null),
                'music'       => esc_html(isset($data['music']) ? $data['music'] : null),
                'artist'      => esc_html(isset($data['artist']) ? $data['artist'] : null),
                'genres_id'   => esc_html(isset($data['genres_id']) ? $data['genres_id'] : null),
                'status'      => esc_html(isset($data['status']) ? $data['status'] : '1'),
            );

        update_option('mmp_check', print_r($arg, true));

        if (empty($condition)) {

            $result = MMP_Database::Insert('mmp_music', $arg);

        } else {

            MMP_Database::Update('mmp_music', $arg,
                array(
                    'music_id' => $condition['id']
                )
            );

            $result = $condition['id'];

        }

        do_action('mmp_music_save',
            [
                'fields_data'    => $data,
                'condition_data' => $result
            ]
        );

    }

    /**
     * Delete
     *
     * @param $data int Id row
     */
    public function Delete($data)
    {

        MMP_Database::Delete('mmp_music',
            array(
                'music_id' => $data['id']
            )
        );

    }

    /**
     * Form
     *
     * @param $data array Default value
     */
    public function Form($data)
    {

        if (!empty($data)) {

            $data = self::Get_By_Id($data['id'])[0];

        }

        MMP_Admin_Fields::Image(
            array(
                'title' => __('Music Cover', MMP_TEXT_DOMAIN),
                'name'  => 'thumb_image',
                'value' => (!empty($data) ? $data->thumb_image : ''),
            )
        );

        MMP_Admin_Fields::Audio(
            array(
                'title' => __('Select Audio', MMP_TEXT_DOMAIN),
                'name'  => 'music',
                'value' => (!empty($data) ? $data->music : ''),
            )
        );

        MMP_Admin_Fields::TextBox(
            array(
                'title'       => __('Name', MMP_TEXT_DOMAIN),
                'name'        => 'name',
                'required'    => true,
                'placeholder' => __('Example: music_name', MMP_TEXT_DOMAIN),
                'value'       => (!empty($data) ? $data->name : ''),
            )
        );

        MMP_Admin_Fields::DropDown(
            array(
                'title'    => __('Status', MMP_TEXT_DOMAIN),
                'name'     => 'status',
                'selected' => (!empty($data) ? $data->status : '1'),
                'option'   => array(
                    '2' => __('Album', MMP_TEXT_DOMAIN),
                    '1' => __('Enable', MMP_TEXT_DOMAIN),
                    '0' => __('Disable', MMP_TEXT_DOMAIN),
                ),
            )
        );

        $artist = [];
        if (!empty($data->artist)) {

            global $wpdb;

            $query = $wpdb->get_results('SELECT artist_id,name,thumb_image FROM ' . $wpdb->prefix . 'mmp_artist WHERE artist_id IN (' . $data->artist . ')');
            if (!empty($query)) {
                foreach ($query as $item) $artist[] = [
                    'value' => $item->artist_id,
                    'text'  => $item->name,
                    'image' => wp_get_attachment_image_url($item->thumb_image, 'medium')
                ];
            }

        }

        MMP_Admin_Fields::Multi_Select(
            array(
                'title'        => __('Select Artist', MMP_TEXT_DOMAIN),
                'name'         => 'artist',
                'ajax_action'  => 'Ajax_Multi_select_Artists',
                'multi_select' => true,
                'value'        => $artist,
            )
        );

        $genres = array();
        if (!empty($data->genres_id)) {

            $get_genres = MMP_Genres::Get_By_Id($data->genres_id)[0];
            $genres = [
                [
                    'value' => $get_genres->genres_id,
                    'text'  => $get_genres->name,
                ]
            ];

        }

        MMP_Admin_Fields::Multi_Select(
            array(
                'title'        => __('Genres', MMP_TEXT_DOMAIN),
                'name'         => 'genres_id',
                'ajax_action'  => 'Ajax_Multi_select_Genres',
                'multi_select' => false,
                'value'        => $genres,
            )
        );

        MMP_Admin_Fields::TextArea(
            array(
                'title'       => __('Description', MMP_TEXT_DOMAIN),
                'name'        => 'description',
                'required'    => true,
                'placeholder' => __('Example: Your text for music', MMP_TEXT_DOMAIN),
                'value'       => (!empty($data) ? $data->description : ''),
            )
        );

    }

    /**
     * Get row by id
     *
     * @param $id int|string Row id
     * @return array|object|null Result
     */
    public static function Get_By_Id($id)
    {

        return MMP_Database::Get('mmp_music', null,
            array(
                'condition' => array(
                    'music_id' => $id
                )
            )
        );

    }

    /**
     * Get table data
     *
     * @param int $page
     *
     * @return array|object|null
     */
    public static function Get($page = 1)
    {

        return MMP_Database::Get('mmp_music', [
            'per_page' => '12',
            'page'     => $page,
            'order_by' => 'music_id',
            'order'    => 'DESC'
        ]);

    }

    /**
     * Manage output
     */
    public static function Output()
    {

        self::Content();

    }

    /**
     * Header
     */
    public function Header()
    {

        MMP_Admin_Elements::Search(__('Search', MMP_TEXT_DOMAIN), 'music');
        MMP_Admin_Elements::Button_Extra(__('Add Music', MMP_TEXT_DOMAIN), 'icon-plus', 'add_music');

    }

    /**
     * Print html list item
     *
     * @param $object object Result database Self::Get()
     */
    public static function List_Output($object)
    {

        global $wpdb;

        if (empty($object)) {
            ?>
            <tr>
                <td colspan="4" style="text-align: center;"><?php echo __('Empty', MMP_TEXT_DOMAIN) ?></td>
            </tr>
            <?php
        }

        foreach ($object as $key => $value) {

            $genres = array();
            $cover = '';

            if (!empty($value->thumb_image)) {
                $cover = $value->thumb_image;
            } else {
                $cover = get_post_thumbnail_id($value->music);
            }

            if (!empty($value->genres_id)) {
                $genres[] = MMP_Genres::Get_By_Id($value->genres_id)[0]->name;
            }

            ?>

            <tr data-id="<?php echo esc_attr($value->music_id); ?>">

                <td>
                    <div class="mmp_image"
                         style="background:url('<?php echo esc_url(wp_get_attachment_image_src($cover, 'full')[0]); ?>')"></div>
                </td>
                <td>
                    <div class="mmp_title mmp-open-editor-music"><?php echo esc_html($value->name); ?></div>
                    <div>
                        <?php

                        echo __('Artist: ', MMP_TEXT_DOMAIN);

                        if (!empty($value->artist)) {

                            $query = $wpdb->get_results('SELECT name FROM ' . $wpdb->prefix . 'mmp_artist WHERE artist_id IN (' . $value->artist . ')');
                            $art = [];

                            foreach ($query as $item) $art[] = $item->name;
                            echo join(', ', $art);

                        } else echo 'Null';

                        ?>
                    </div>
                    <div class="mmp_music_type">
                        <?php echo empty($genres) ? __('No Genres', MMP_TEXT_DOMAIN) : join(', ', $genres); ?>
                    </div>
                </td>
                <td>

                    <div class="mmp_box">
                        <div class="mmp_status <?php echo esc_attr($value->status === '1' ? 'active' : ($value->status === '2' ? 'suspend' : 'disable')); ?>">
                            <?php echo esc_html($value->status === '1' ? __('enable', MMP_TEXT_DOMAIN) : ($value->status === '2' ? __('Album', MMP_TEXT_DOMAIN) : __('disable', MMP_TEXT_DOMAIN))); ?>
                        </div>
                        <div class="mmp_date"><?php echo esc_html($value->date); ?></div>
                    </div>

                    <div class="statistic-option">
                        <div class="number"><?php echo MMP_Admin_Elements::number_format_short($value->play); ?></div>
                        <span><?php echo __('Play', MMP_TEXT_DOMAIN); ?></span>
                    </div>

                    <?php do_action('mmp_music_list_out', esc_attr($value->music_id)); ?>

                </td>
                <td>
                    <?php
                    MMP_Admin_Elements::Button_Icon('icon-more-horizontal', 'mmp_more music');
                    ?>
                </td>
            </tr>

            <?php
        }

    }

    /**
     * Content
     */
    private static function Content()
    {

        // ADD SECTION FOR ANALYZE
        do_action('mmp_artist_analyze');

        ?>

        <div class="mmp_page_section">
            <table class="mmp_table mmp_artist_list">

                <colgroup>
                    <col span="1" style="width: 1%;">
                    <col span="1" style="width: 20%;">
                    <col span="1" style="width: 78%;">
                    <col span="1" style="width: 1%;">
                </colgroup>

                <thead>
                <tr>
                    <th><?php echo __('Image', MMP_TEXT_DOMAIN) ?></th>
                    <th><?php echo __('Name', MMP_TEXT_DOMAIN) ?></th>
                    <th><?php echo __('Status/Preview', MMP_TEXT_DOMAIN) ?></th>
                    <th><?php echo __('More', MMP_TEXT_DOMAIN) ?></th>
                </tr>
                </thead>
                <tbody>
                <?php

                $artist = self::Get();
                $pagination = MMP_Database::Pagination();
                self::List_Output($artist);

                ?>
                </tbody>
            </table>
        </div>

        <?php

        echo $pagination;

    }

}

new MMP_Music();