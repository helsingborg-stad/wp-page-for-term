<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_63fe0755b42ec',
    'title' => __('Is page for term', 'wp-page-for-term'),
    'fields' => array(
        0 => array(
            'key' => 'field_63fe0756de689',
            'label' => __('Term', 'wp-page-for-term'),
            'name' => 'is_page_for_term',
            'aria-label' => '',
            'type' => 'acfe_taxonomy_terms',
            'instructions' => __('This page replaces the archive for the selected terms.', 'wp-page-for-term'),
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'taxonomy' => '',
            'allow_terms' => '',
            'allow_level' => '',
            'field_type' => 'select',
            'default_value' => array(
            ),
            'return_format' => 'id',
            'ui' => 1,
            'allow_null' => 1,
            'placeholder' => '',
            'multiple' => 1,
            'ajax' => 1,
            'save_terms' => 0,
            'load_terms' => 0,
            'choices' => array(
            ),
            'search_placeholder' => '',
            'layout' => '',
            'toggle' => 0,
            'allow_custom' => 0,
            'other_choice' => 0,
        ),
        1 => array(
            'key' => 'field_641035087366a',
            'label' => __('Post type', 'wp-page-for-term'),
            'name' => 'is_page_for_term_posttype',
            'aria-label' => '',
            'type' => 'select',
            'instructions' => __('Post type to display in the term archive on this page. Note that if the term is available to multiple post types, some content will be excluded if the term archive is replaced this way.', 'wp-page-for-term'),
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_63fe0756de689',
                        'operator' => '!=empty',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'choices' => array(
                'post' => __('Inlägg', 'wp-page-for-term'),
                'page' => __('Sidor', 'wp-page-for-term'),
                'attachment' => __('Media', 'wp-page-for-term'),
                'event' => __('Evenemang', 'wp-page-for-term'),
                'local-events' => __('Lokalt evenemang', 'wp-page-for-term'),
                'place' => __('Platser', 'wp-page-for-term'),
                'guide' => __('Guider', 'wp-page-for-term'),
                'project' => __('Initiativ', 'wp-page-for-term'),
                'challenge' => __('Utmaningar', 'wp-page-for-term'),
                'platform' => __('Plattformar', 'wp-page-for-term'),
            ),
            'default_value' => 'post',
            'return_format' => 'value',
            'multiple' => 0,
            'allow_custom' => 0,
            'search_placeholder' => '',
            'allow_null' => 0,
            'ui' => 1,
            'ajax' => 0,
            'placeholder' => '',
        ),
    ),
    'location' => array(
        0 => array(
            0 => array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'page',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'left',
    'instruction_placement' => 'field',
    'hide_on_screen' => '',
    'active' => true,
    'description' => '',
    'show_in_rest' => 0,
    'acfe_display_title' => '',
    'acfe_autosync' => '',
    'acfe_form' => 0,
    'acfe_meta' => '',
    'acfe_note' => '',
));
}