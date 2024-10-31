<?php

// FIELDS
class MMP_Admin_Fields
{

    /**
     * TextBox field
     *
     * @param array $arg title
     */
    public static function Heading($arg = array())
    {

        $title = isset($arg['title']) ? $arg['title'] : __('No Title', MMP_TEXT_DOMAIN);
        ?>

        <div class="mmp_fields mmp_field_heading">
            <?php self::Title(esc_html($title)); ?>
        </div>

        <?php
    }

    /**
     * TextBox field
     *
     * @param array $arg title|class|name|required|placeholder|value
     */
    public static function TextBox($arg = array())
    {

        $title = isset($arg['title']) ? $arg['title'] : __('No Title', MMP_TEXT_DOMAIN);
        $class = isset($arg['class']) ? $arg['class'] : '';
        $name = isset($arg['name']) ? $arg['name'] : '';

        $value = isset($arg['value']) ? $arg['value'] : '';
        $required = isset($arg['required']) ? $arg['required'] : false;
        $placeholder = isset($arg['placeholder']) ? $arg['placeholder'] : __('Please Enter Your Text', MMP_TEXT_DOMAIN);

        ?>

        <div class="mmp_fields mmp_fields_save mmp_field_text_box <?php echo esc_attr($class . ($required ? ' required' : '')); ?>">
            <?php self::Title(esc_html($title)); ?>
            <input type="text" class="save" name="<?php echo esc_html($name); ?>"
                   placeholder="<?php echo esc_html($placeholder); ?>" autocomplete="off"
                   value="<?php echo esc_html($value); ?>">
        </div>

        <?php
    }

    /**
     * Number field
     *
     * @param array $arg title|class|name|required|placeholder|value|max|min
     */
    public static function Number($arg = array())
    {

        $title = isset($arg['title']) ? $arg['title'] : __('No Title', MMP_TEXT_DOMAIN);
        $class = isset($arg['class']) ? $arg['class'] : '';
        $name = isset($arg['name']) ? $arg['name'] : '';

        $value = isset($arg['value']) ? $arg['value'] : '';
        $max = isset($arg['max']) ? $arg['max'] : '';
        $min = isset($arg['min']) ? $arg['min'] : '';
        $required = isset($arg['required']) ? $arg['required'] : false;
        $placeholder = isset($arg['placeholder']) ? $arg['placeholder'] : __('Please Enter Your Text', MMP_TEXT_DOMAIN);

        ?>

        <div class="mmp_fields mmp_fields_save mmp_field_number <?php echo esc_attr($class . ($required ? ' required' : '')); ?>">
            <?php self::Title(esc_html($title)); ?>
            <input type="number" class="save" name="<?php echo esc_html($name); ?>"
                   placeholder="<?php echo esc_html($placeholder); ?>" max="<?php echo esc_html($max); ?>"
                   min="<?php echo esc_html($min); ?>" autocomplete="off" value="<?php echo esc_html($value); ?>">
        </div>

        <?php
    }

    /**
     * TextArea field
     *
     * @param array $arg title|class|name|required|placeholder|value
     */
    public static function TextArea($arg = array())
    {

        $title = isset($arg['title']) ? $arg['title'] : __('No Title', MMP_TEXT_DOMAIN);
        $class = isset($arg['class']) ? $arg['class'] : '';
        $name = isset($arg['name']) ? $arg['name'] : '';

        $value = isset($arg['value']) ? $arg['value'] : '';
        $required = isset($arg['required']) ? $arg['required'] : false;
        $placeholder = isset($arg['placeholder']) ? $arg['placeholder'] : __('Please Enter Your Text', MMP_TEXT_DOMAIN);

        ?>

        <div class="mmp_fields mmp_fields_save mmp_field_text_area <?php echo esc_attr($class . ($required ? ' required' : '')); ?>">
            <?php self::Title(esc_html($title)); ?>
            <textarea type="text" class="save" name="<?php echo esc_html($name); ?>"
                      placeholder="<?php echo esc_html($placeholder); ?>"
                      autocomplete="off"><?php echo esc_html($value); ?></textarea>
        </div>

        <?php
    }

    /**
     * Dropdown field
     *
     * @param array $arg title|class|name|required|option|selected
     */
    public static function DropDown($arg = array())
    {

        $title = isset($arg['title']) ? $arg['title'] : __('No Title', MMP_TEXT_DOMAIN);
        $class = isset($arg['class']) ? $arg['class'] : '';
        $name = isset($arg['name']) ? $arg['name'] : '';

        $selected = isset($arg['selected']) ? $arg['selected'] : '';
        $option = isset($arg['option']) ? $arg['option'] : '';
        $required = isset($arg['required']) ? $arg['required'] : false;

        ?>

        <div class="mmp_fields mmp_fields_save mmp_field_dropdown <?php echo esc_attr($class . ($required ? ' required' : '')); ?>">
            <?php self::Title($title); ?>
            <select name="<?php echo esc_attr($name); ?>" class="save">
                <?php

                foreach ($option as $key => $val) {

                    echo '<option value="' . $key . '" ' . ($selected == $key ? 'selected' : '') . '>' . $val . '</option>';

                }

                ?>
            </select>
        </div>

        <?php
    }

    /**
     * Check box
     *
     * @param array $arg title|class|name|required|value
     */
    public static function Check_Box($arg = array())
    {

        $title = isset($arg['title']) ? $arg['title'] : __('No Title', MMP_TEXT_DOMAIN);
        $class = isset($arg['class']) ? $arg['class'] : '';
        $name = isset($arg['name']) ? $arg['name'] : '';
        $value = isset($arg['value']) ? $arg['value'] : '';
        $required = isset($arg['required']) ? $arg['required'] : false;
        ?>

        <div class="mmp_fields mmp_fields_save mmp_field_check_box <?php echo esc_attr($class . ($required ? ' required' : '')); ?>">

            <div class="mmp_check_box <?php echo esc_attr($value ? 'active' : null); ?>">
                <div class="check_area"></div>
                <?php self::Title(esc_html($title)); ?>
            </div>
            <input type="hidden" class="save" name="<?php echo esc_html($name); ?>"
                   value="<?php echo esc_html($value); ?>">

        </div>

        <?php
    }

    /**
     * Multi Select
     *
     * Default value: {value: string, image: id_image, text: string}
     *
     * @param $arg array title|class|name|required|value|ajax_action|multi_select
     */
    public static function Multi_Select($arg)
    {

        $title = isset($arg['title']) ? $arg['title'] : __('No Title', MMP_TEXT_DOMAIN);
        $class = isset($arg['class']) ? $arg['class'] : '';
        $name = isset($arg['name']) ? $arg['name'] : '';

        $value = isset($arg['value']) ? $arg['value'] : array();
        $required = isset($arg['required']) ? $arg['required'] : false;
        $ajax_action = isset($arg['ajax_action']) ? $arg['ajax_action'] : false;
        $multi_select = isset($arg['multi_select']) ? $arg['multi_select'] : true;

        $val_input = array();

        ?>

        <div class="mmp_fields mmp_fields_save mmp_field_multi_select <?php echo esc_attr($class . ($required ? ' required' : '')); ?>">
            <?php self::Title($title); ?>
            <div class="mmp_multi_select" data-ajax-action="<?php echo esc_html($ajax_action); ?>"
                 data-multi-select="<?php echo esc_html($multi_select); ?>">

                <?php
                foreach ($value as $key => $val) {

                    $val_input[] = $val['value'];

                    ?>

                    <div class="mmp_multi_select_option" data-val="<?php echo esc_html($val['value']); ?>">

                        <?php
                        if (isset($val['image'])) {
                            ?>

                            <div class="mmp_multi_select_img"
                                 style="background:url(<?php echo esc_url($val['image']); ?>)"></div>

                            <?php
                        }
                        ?>
                        <span><?php echo esc_html($val['text']); ?></span>
                        <i class="icon-x"></i>

                    </div>

                    <?php
                }
                ?>

                <input type="hidden" class="save" value="<?php echo esc_html(join(',', $val_input)) ?>"
                       name="<?php echo esc_html($name) ?>">

            </div>
        </div>

        <?php
    }

    /**
     * Image background
     *
     * @param $arg array title|class|name|required|value
     */
    public static function Image_Background($arg)
    {

        $title = isset($arg['title']) ? $arg['title'] : __('No Title', MMP_TEXT_DOMAIN);
        $class = isset($arg['class']) ? $arg['class'] : '';
        $name = isset($arg['name']) ? $arg['name'] : '';

        $value = isset($arg['value']) ? $arg['value'] : '';
        $required = isset($arg['required']) ? $arg['required'] : false;

        ?>

        <div class="mmp_fields mmp_fields_save mmp_field_image_background <?php echo esc_attr($class . ($required ? ' required' : '')); ?>">
            <div class="image"
                 style="<?php echo(empty($value) ? '' : 'background:url(' . esc_url(wp_get_attachment_image_url($value, 'full')) . ');') ?>">
                <?php
                self::Title($title);
                MMP_Admin_Elements::Button_Icon('icon-camera', 'mmp_select_image');
                if (!empty($value))
                    MMP_Admin_Elements::Button_Icon('icon-x', 'mmp_remove_image');
                ?>
            </div>
            <input type="hidden" class="save" value="<?php echo esc_html($value) ?>"
                   name="<?php echo esc_html($name) ?>">
        </div>

        <?php
    }

    /**
     * Image
     *
     * @param $arg array title|class|name|required|value
     */
    public static function Image($arg)
    {

        $title = isset($arg['title']) ? $arg['title'] : __('No Title', MMP_TEXT_DOMAIN);
        $class = isset($arg['class']) ? $arg['class'] : '';
        $name = isset($arg['name']) ? $arg['name'] : '';

        $value = isset($arg['value']) ? $arg['value'] : '';
        $required = isset($arg['required']) ? $arg['required'] : false;

        ?>

        <div class="mmp_fields mmp_fields_save mmp_field_image <?php echo esc_attr($class . ($required ? ' required' : '')); ?>">
            <div class="image"
                 style="<?php echo(empty($value) ? '' : 'background:url(' . esc_url(wp_get_attachment_image_url($value, 'full')) . ');') ?>">
            </div>
            <div class="mmp_field_image_content">
                <?php self::Title(esc_html($title)); ?>
                <div>
                    <?php
                    MMP_Admin_Elements::Button_Icon('icon-camera', 'mmp_select_image');
                    if (!empty($value))
                        MMP_Admin_Elements::Button_Icon('icon-x', 'mmp_remove_image');
                    ?>
                </div>
            </div>
            <input type="hidden" class="save" value="<?php echo esc_html($value) ?>"
                   name="<?php echo esc_html($name) ?>">
        </div>

        <?php
    }

    /**
     * Audio
     *
     * Default value: {value: string, image: id_image, text: string}
     *
     * @param $arg array title|class|name|required|value
     */
    public static function Audio($arg)
    {

        $title = isset($arg['title']) ? $arg['title'] : __('No Title', MMP_TEXT_DOMAIN);
        $class = isset($arg['class']) ? $arg['class'] : '';
        $name = isset($arg['name']) ? $arg['name'] : '';

        $value = isset($arg['value']) ? $arg['value'] : array();
        $required = isset($arg['required']) ? $arg['required'] : false;

        $audio_url = wp_get_attachment_url($value);
        $audio_thumbnail = get_post_thumbnail_id($value);
        $audio_meta = wp_get_attachment_metadata($value);

        $audio_name = empty($value) ? __('Click on the box to select music', MMP_TEXT_DOMAIN) : (isset($audio_meta['title']) ? $audio_meta['title'] : null) . '.' . $audio_meta['fileformat'];
        $audio_time = $audio_meta['length_formatted'];

        ?>

        <div class="mmp_fields mmp_fields_save mmp_field_audio <?php echo esc_attr($class . ($required ? ' required' : '')); ?>">
            <div class="mmp_audio">

                <div class="image"
                    <?php echo(empty($value) ? null : 'style="background: url(' . wp_get_attachment_image_url($audio_thumbnail) . ');"'); ?>></div>
                <div class="content">
                    <?php self::Title($title); ?>
                    <div class="audio_name"><?php echo esc_html($audio_name); ?></div>
                    <div class="audio_meta">
                        <div class="audio_time"><?php echo esc_html($audio_time); ?></div>
                        <input type="text" disabled class="audio_link" value="<?php echo esc_url($audio_url); ?>">
                    </div>
                </div>
                <input type="hidden" class="save" value="<?php echo esc_html($value) ?>"
                       name="<?php echo esc_html($name) ?>">

            </div>
        </div>

        <?php
    }

    /**
     * Title fields
     *
     * @param $text string
     */
    private static function Title($text)
    {
        ?>
        <div class="title"><?php echo esc_html($text); ?></div>
        <?php
    }

}