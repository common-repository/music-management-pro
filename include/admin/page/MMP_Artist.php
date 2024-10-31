<?php

// ARTIST PAGE
class MMP_Artist
{

    /**
     * MMP_Artist constructor.
     * Start class for actions
     */
    public function __construct()
    {

        add_action('mmp_save_form_artists', array($this, 'Set'));
        add_action('mmp_artists_form', array($this, 'Form'));
        add_action('mmp_artists_delete', array($this, 'Delete'));

        if (isset($_REQUEST['page']) && (esc_html($_REQUEST['page']) === 'mmp_artists')) {
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

        global $wpdb;

        $result = null;
        $condition = $data['condition_data'];
        $data = $data['fields_data'];

        $arg =
            array(
                'name'        => esc_html($data['name']),
                'date'        => strtoupper(date('m/d/Y h:i a')),
                'thumb_image' => esc_html(isset($data['thumb_image']) ? $data['thumb_image'] : null),
                'back_image'  => esc_html(isset($data['back_image']) ? $data['back_image'] : null),
                'gender'      => esc_html($data['gender']),
                'country'     => esc_html(isset($data['country']) ? $data['country'] : null),
                'date_birth'  => esc_html(isset($data['date_birth']) ? $data['date_birth'] : null),
                'year_active' => esc_html(isset($data['year_active']) ? $data['year_active'] : null),
                'status'      => esc_html(isset($data['status']) ? $data['status'] : '1'),
            );

        if (empty($condition)) {

            $result = MMP_Database::Insert('mmp_artist', $arg);

        } else {

            MMP_Database::Delete('mmp_genres_relation',
                array(
                    'artist_id' => $condition['id']
                )
            );

            MMP_Database::Update('mmp_artist', $arg,
                array(
                    'artist_id' => $condition['id']
                )
            );

            $result = $condition['id'];

        }

        if (!empty($data['genres'])) {

            $arr = [];
            $genres = explode(',', $data['genres']);
            foreach ($genres as $key => $value) $arr[] = '(' . $result . ',' . $value . ')';

            $wpdb->query('INSERT INTO ' . $wpdb->prefix . 'mmp_genres_relation (artist_id, genres_id) VALUES ' . join(',', $arr));

        }

        do_action('mmp_artist_save',
            [
                'fields_data'    => $data,
                'condition_data' => $result
            ]
        );

    }

    /**
     * Delete artist
     *
     * @param $data int Id row
     */
    public function Delete($data)
    {

        MMP_Database::Delete('mmp_genres_relation',
            array(
                'artist_id' => $data['id']
            )
        );

        MMP_Database::Delete('mmp_artist',
            array(
                'artist_id' => $data['id']
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

        global $wpdb;

        if (!empty($data)) {

            $data = self::Get_By_Id($data['id'])[0];

        }

        MMP_Admin_Fields::Image_Background(
            array(
                'title' => __('Background Image', MMP_TEXT_DOMAIN),
                'name'  => 'back_image',
                'class' => 'full_width',
                'value' => (!empty($data) ? $data->back_image : ''),
            )
        );

        MMP_Admin_Fields::Image(
            array(
                'title' => __('Thumbnail Image', MMP_TEXT_DOMAIN),
                'name'  => 'thumb_image',
                'value' => (!empty($data) ? $data->thumb_image : ''),
            )
        );

        MMP_Admin_Fields::TextBox(
            array(
                'title'       => __('Full Name Artist', MMP_TEXT_DOMAIN),
                'name'        => 'name',
                'required'    => true,
                'placeholder' => __('Example: Taylor Swift', MMP_TEXT_DOMAIN),
                'value'       => (!empty($data) ? $data->name : ''),
            )
        );

        MMP_Admin_Fields::DropDown(
            array(
                'title'    => __('Gender', MMP_TEXT_DOMAIN),
                'name'     => 'gender',
                'selected' => (!empty($data) ? $data->gender : 'male'),
                'option'   => array(
                    'female' => __('Female', MMP_TEXT_DOMAIN),
                    'male'   => __('Male', MMP_TEXT_DOMAIN),
                ),
            )
        );

        MMP_Admin_Fields::TextBox(
            array(
                'title'       => __('Date Of Birth', MMP_TEXT_DOMAIN),
                'name'        => 'date_birth',
                'placeholder' => __('Example: 1/1/2020', MMP_TEXT_DOMAIN),
                'value'       => (!empty($data) ? $data->date_birth : ''),
            )
        );

        MMP_Admin_Fields::Number(
            array(
                'title'       => __('Years active', MMP_TEXT_DOMAIN),
                'name'        => 'year_active',
                'min'         => '0',
                'placeholder' => __('Example: 2006', MMP_TEXT_DOMAIN),
                'value'       => (!empty($data) ? $data->year_active : ''),
            )
        );

        MMP_Admin_Fields::TextBox(
            array(
                'title'       => __('Country', MMP_TEXT_DOMAIN),
                'name'        => 'country',
                'placeholder' => __('Example: Usa', MMP_TEXT_DOMAIN),
                'value'       => (!empty($data) ? $data->country : ''),
            )
        );

        $genres = array();
        if (!empty($data)) {

            $query = $wpdb->get_results('SELECT wmg.genres_id,wmg.name FROM ' . $wpdb->prefix . 'mmp_genres_relation wmgr INNER JOIN ' . $wpdb->prefix . 'mmp_genres wmg ON wmg.genres_id = wmgr.genres_id WHERE wmgr.artist_id = ' . $data->artist_id);
            if (!empty($query)) {
                foreach ($query as $item) {

                    $genres[] = array(
                        'value' => $item->genres_id,
                        'text'  => $item->name
                    );

                }
            }

        }

        MMP_Admin_Fields::Multi_Select(
            array(
                'title'       => __('Genres', MMP_TEXT_DOMAIN),
                'name'        => 'genres',
                'ajax_action' => 'Ajax_Multi_select_Genres',
                'value'       => $genres,
            )
        );

        MMP_Admin_Fields::DropDown(
            array(
                'title'    => __('Status', MMP_TEXT_DOMAIN),
                'name'     => 'status',
                'selected' => (!empty($data) ? $data->status : '1'),
                'option'   => array(
                    '1' => __('Enable', MMP_TEXT_DOMAIN),
                    '0' => __('Disable', MMP_TEXT_DOMAIN),
                ),
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

        return MMP_Database::Get('mmp_artist', null,
            array(
                'condition' => array(
                    'artist_id' => $id
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

        return MMP_Database::Get('mmp_artist', [
            'per_page' => '12',
            'page'     => $page,
            'order_by' => 'artist_id',
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

        MMP_Admin_Elements::Search(__('Search', MMP_TEXT_DOMAIN), 'artist');
        MMP_Admin_Elements::Button_Extra(__('Add Artist', MMP_TEXT_DOMAIN), 'icon-plus', 'add_artist');

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

            $query = $wpdb->get_results('SELECT wmg.genres_id,wmg.name FROM ' . $wpdb->prefix . 'mmp_genres_relation wmgr INNER JOIN ' . $wpdb->prefix . 'mmp_genres wmg ON wmg.genres_id = wmgr.genres_id WHERE wmgr.artist_id = ' . $value->artist_id);
            foreach ($query as $item) $genres[] = '<span>' . $item->name . '</span>';

            ?>

            <tr data-id="<?php echo esc_attr($value->artist_id); ?>">

                <td>
                    <div class="mmp_image"
                         style="background:url('<?php echo esc_url(wp_get_attachment_image_src($value->thumb_image, 'full')[0]); ?>')"></div>
                </td>
                <td>
                    <div class="mmp_title mmp-open-editor-artist"><?php echo esc_html($value->name); ?></div>
                    <div class="mmp_music_type">
                        <?php echo empty($genres) ? __('No Genres', MMP_TEXT_DOMAIN) : join(', ', $genres); ?>
                    </div>
                </td>
                <td>

                    <div class="mmp_box">
                        <div class="mmp_status <?php echo esc_attr($value->status === '1' ? 'active' : 'disable'); ?>">
                            <?php echo esc_html($value->status === '1' ? __('enable', MMP_TEXT_DOMAIN) : __('disable', MMP_TEXT_DOMAIN)); ?>
                        </div>
                        <div class="mmp_date"><?php echo esc_html($value->date); ?></div>
                    </div>

                    <?php do_action('mmp_artist_list_out', esc_attr($value->artist_id)); ?>

                </td>
                <td>
                    <?php
                    MMP_Admin_Elements::Button_Icon('icon-more-horizontal', 'mmp_more artist');
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
                    <th><?php echo __('Status/modified', MMP_TEXT_DOMAIN) ?></th>
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

new MMP_Artist();