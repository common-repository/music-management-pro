<?php

// GENRES
class MMP_Genres
{

    /**
     * MMP_Genres constructor.
     * Start class for actions
     */
    public function __construct()
    {

        add_action('mmp_save_form_genres', array($this, 'Set'));
        add_action('mmp_genres_form', array($this, 'Form'));
        add_action('mmp_genres_delete', array($this, 'Delete'));

        if (isset($_REQUEST['page']) && (esc_html($_REQUEST['page']) === 'mmp_genres')) {
            add_action('mmp_admin_header', array($this, 'Header'));
        }

    }

    /**
     * Save data
     * Example: new MMP_Genres()
     *
     * @param $data array Parameter
     */
    public function Set($data)
    {

        $result = null;
        $condition = $data['condition_data'];
        $data = $data['fields_data'];

        $arg =
            array(
                'name'        => $data['name'],
                'thumb_image' => $data['thumb_image'],
            );

        if (empty($condition)) {

            $result = MMP_Database::Insert('mmp_genres', $arg);

        } else {

            MMP_Database::Update('mmp_genres', $arg,
                array(
                    'genres_id' => $condition['id']
                )
            );

            $result = $condition['id'];

        }

    }

    /**
     * Delete genres
     *
     * @param $data int Id row
     */
    public function Delete($data)
    {

        MMP_Database::Delete('mmp_genres',
            array(
                'genres_id' => $data['id']
            )
        );

        MMP_Database::Delete('mmp_genres_relation',
            array(
                'genres_id' => $data['id']
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
                'title' => __('Thumbnail Image', MMP_TEXT_DOMAIN),
                'name'  => 'thumb_image',
                'value' => (!empty($data) ? $data->thumb_image : ''),
            )
        );

        MMP_Admin_Fields::TextBox(
            array(
                'title'       => __('Name Genres', MMP_TEXT_DOMAIN),
                'name'        => 'name',
                'required'    => true,
                'placeholder' => __('Example: Pop', MMP_TEXT_DOMAIN),
                'value'       => (!empty($data) ? $data->name : ''),
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

        return MMP_Database::Get('mmp_genres', null,
            array(
                'condition' => array(
                    'genres_id' => $id
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

        return MMP_Database::Get('mmp_genres', [
            'per_page' => '999999',
            'page'     => $page,
            'order_by' => 'genres_id',
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

        MMP_Admin_Elements::Search(__('Search', MMP_TEXT_DOMAIN), 'genres');
        MMP_Admin_Elements::Button_Extra(__('Add Genres', MMP_TEXT_DOMAIN), 'icon-plus', 'add_genres');

    }

    /**
     * Print html list item
     *
     * @param $object object Result database Self::Get()
     */
    public static function List_Output($object)
    {

        if (empty($object)) {
            ?>
            <tr>
                <td colspan="4" style="text-align: center;"><?php echo __('Empty', MMP_TEXT_DOMAIN) ?></td>
            </tr>
            <?php
        }

        foreach ($object as $key => $value) {
            ?>

            <tr data-id="<?php echo esc_attr($value->genres_id); ?>">
                <td><?php echo esc_html($value->genres_id); ?></td>
                <td class="mmp-open-editor-genres"><?php echo esc_html($value->name); ?></td>
                <td>
                    <?php
                    MMP_Admin_Elements::Button_Icon('icon-more-horizontal', 'mmp_more genres');
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
        do_action('mmp_genres_analyze');

        ?>

        <div class="mmp_page_section">
            <table class="mmp_table mmp_artist_list">

                <colgroup>
                    <col span="1" style="width: 2%;">
                    <col span="1" style="width: 97%;">
                    <!--                    <col span="1" style="width: 78%;">-->
                    <col span="1" style="width: 1%;">
                </colgroup>

                <thead>
                <tr>
                    <th><?php echo __('ID', MMP_TEXT_DOMAIN) ?></th>
                    <th><?php echo __('Name', MMP_TEXT_DOMAIN) ?></th>
                    <!--                    <th>--><?php //echo __('Date', MMP_TEXT_DOMAIN)
                    ?><!--</th>-->
                    <th><?php echo __('More', MMP_TEXT_DOMAIN) ?></th>
                </tr>
                </thead>
                <tbody>
                <?php

                self::List_Output(self::Get());

                ?>
                </tbody>
            </table>
        </div>

        <?php
    }

}

new MMP_Genres();