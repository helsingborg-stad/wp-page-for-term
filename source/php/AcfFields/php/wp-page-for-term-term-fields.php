<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_6401a49562052',
    'title' => __('Page for term', 'wp-page-for-term'),
    'fields' => array(
        0 => array(
            'key' => 'field_6401a495f45b5',
            'label' => __('Page for term', 'wp-page-for-term'),
            'name' => 'page_for_term',
            'aria-label' => '',
            'type' => 'post_object',
            'instructions' => __('Redirect this term to the selected page.', 'wp-page-for-term'),
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'post_type' => array(
                0 => 'page',
            ),
            'taxonomy' => '',
            'return_format' => 'id',
            'multiple' => 0,
            'save_custom' => 0,
            'save_post_status' => 'publish',
            'acfe_bidirectional' => array(
                'acfe_bidirectional_enabled' => '0',
            ),
            'acfe_settings' => array(
                '6410391ba13df' => array(
                    'acfe_settings_location' => '',
                    'acfe_settings_settings' => array(
                        '64103920a13e0' => array(
                            'acfe_settings_setting_type' => 'hide_field',
                            'acfe_settings_setting_operator' => 'true',
                        ),
                    ),
                ),
            ),
            'acfe_validate' => '',
            'allow_null' => 1,
            'acfe_permissions' => array(
                0 => 'super_admin',
            ),
            'ui' => 1,
            'save_post_type' => '',
        ),
    ),
    'location' => array(
        0 => array(
            0 => array(
                'param' => 'taxonomy',
                'operator' => '==',
                'value' => 'all',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'left',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => false,
    'description' => '',
    'show_in_rest' => 0,
    'acfe_display_title' => '',
    'acfe_autosync' => '',
    'acfe_permissions' => '',
    'acfe_form' => 0,
    'acfe_meta' => '',
    'acfe_note' => '',
));
}