<?php

namespace wpPageForTerm;

class App
{
    protected const PAGE_FIELD_KEY     = 'field_63fe0756de689';
    protected const POSTTYPE_FIELD_KEY = 'field_641035087366a';
    protected const TERM_FIELD_KEY     = 'field_6401a495f45b5';

    public function __construct()
    {
        add_action('acf/save_post', [$this, 'updatePageForTerm'], 1, 1);
        add_filter('acf/load_field/key=' . self::POSTTYPE_FIELD_KEY, [$this, 'loadPageForTermPostTypes']);

        add_action('pre_get_posts', [$this, 'setupSecondaryQuery'], 2);
        add_action('template_redirect', [$this,'redirectTermToPageForTerm']);

        add_action('init', [$this, 'setupCustomColumns']);
    }

    public function loadPageForTermPostTypes($field)
    {
        $field['choices'] = [];

        $postTypes = get_post_types(array('public' => true), 'objects');
        if (!empty($postTypes)) {
            foreach ($postTypes as $postType) {
                $field['choices'][$postType->name] = $postType->label;
            }
        }
        return $field;
    }
   /**
    * Keeps the `page_for_term` field on the terms in sync with the `is_page_for_term` field on the page.
    *
    * @param postId The ID of the post being saved.
    */
    public function updatePageForTerm($postId)
    {
        $existingTerms = (array) get_field(self::PAGE_FIELD_KEY, $postId);
        $updatedTerms = isset($_POST['acf'][self::PAGE_FIELD_KEY]) ? (array) $_POST['acf'][self::PAGE_FIELD_KEY] : [];

        if (!empty($updatedTerms)) {
            foreach ($updatedTerms as $termId) {
                update_field(self::TERM_FIELD_KEY, $postId, "term_{$termId}");
            }
        }

        $removedTerms = array_diff($existingTerms, $updatedTerms) ?? false;
        if ($removedTerms) {
            foreach ($removedTerms as $termId) {
                delete_field(self::TERM_FIELD_KEY, "term_{$termId}");
            }
        }
    }
    /**
     * Redirects a term archive to its associated page if the `page_for_term` field is set.
     */
    public function redirectTermToPageForTerm()
    {
        if (is_tax()) {
            $term   = get_queried_object();
            $pageId = get_field(self::TERM_FIELD_KEY, $term);
            if ($pageId) {
                wp_safe_redirect(get_permalink($pageId), 301);
                exit;
            }
        }
    }

    /**
     * Adds a custom column to the edit tags page for each taxonomy
     */
    public function setupCustomColumns()
    {
        $taxonomies = get_taxonomies(array(), 'names');
        foreach ($taxonomies as $taxonomy) {
            add_filter('manage_edit-' . $taxonomy . '_columns', [$this,'addCustomColumnToEditTags']);
            add_filter('manage_' . $taxonomy . '_custom_column', [$this,'displayCustomColumnContent'], 10, 3);
        }
    }

    /**
     * Add custom column to edit-tags.php page for all publicly available taxonomies.
     *
     * @param array $columns An array of existing column names and labels.
     *
     * @return array An array of modified column names and labels.
     */
    public function addCustomColumnToEditTags($columns)
    {
        $new_columns = array();

        foreach ($columns as $key => $value) {
            $new_columns[$key] = $value;

            // Insert the new column as the column following the 'name' column
            if ($key === 'name') {
                $new_columns['pageForTerm'] = __('Page');
            }
        }

        return $new_columns;
    }
    /**
     * Display the content for the custom column on the edit-tags.php screen.
     *
     * @param string $content The existing content for the column.
     * @param string $columnName The name of the custom column.
     * @param int $termId The ID of the current term being displayed.
     *
     * @return string The modified content for the column.
     */
    public function displayCustomColumnContent($content, $columnName, $termId)
    {
        if ($columnName === 'pageForTerm') {
            $pageForTerm = get_field(self::TERM_FIELD_KEY, "term_{$termId}");
            if ($pageForTerm) {
                $content = '<a href="' . get_edit_post_link($pageForTerm) . '">';
                $content .= get_the_title($pageForTerm);
                $content .= '</a>';

                $content .= ' | <a href="' . get_permalink($pageForTerm) . '">';
                $content .= __('View');
                $content .= '</a>';
            } else {
                $content = '—';
            }
        }

        return $content;
    }


    /**
     * Additional filtering based on selected taxonomy terms.
     *
     * @note WP_Query with tax_query set defaults to post_type = 'any' instead of 'post'.
     *
     * @param WP_Query $query The current WP_Query object.
     * @return void
     */
    public function setupSecondaryQuery($query)
    {
        if (!$query->is_main_query()) {
            return;
        }

        $secondaryQuery = false;

        $isPageForTerms = (array) get_field('is_page_for_term', $query->queried_object_id);
        $postType = get_field('is_page_for_term_posttype', $query->queried_object_id);

        if ($postType && !empty($isPageForTerms)) {
            $postCount = get_theme_mod("archive_{$postType}_post_count", get_option('posts_per_page'));
            $secondaryQueryArgs =
            [
                'tax_query' => [
                    'relation' => \apply_filters('Municipio/secondaryQuery/relation', 'AND'),
                ],
                'post_type'      => $postType,
                'posts_per_page' => $postCount,
                'paged'          => ( get_query_var('paged') ) ? get_query_var('paged') : 1,
            ];

            // Preselected filters ($_REQUEST)
            $filtersSelectedByUser = \apply_filters('Municipio/secondaryQuery/selectedFilters', (array) $_REQUEST);
            if (isset($filtersSelectedByUser['paged'])) {
                unset($filtersSelectedByUser['paged']);
            }

            if (!empty($filtersSelectedByUser)) {
                $secondaryQueryArgs['tax_query']['relation'] = \apply_filters('Municipio/secondaryQuery/relation', 'AND');
                foreach ($filtersSelectedByUser as $key => $values) {
                    $terms = [];
                    /* Check if the taxonomy is valid for the selected post type. */
                    if (in_array($key, get_object_taxonomies($postType)) && is_array($values) && !empty($values)) {
                        foreach ($values as $value) {
                            $term = get_term_by('slug', $value, $key);
                            if (!$term || is_wp_error($term)) {
                                continue;
                            }
                            $terms[] = $term->term_id;
                        }
                    }
                    if (!empty($terms)) {
                        $secondaryQueryArgs['tax_query'][] = [
                            'taxonomy' => $term->taxonomy,
                            'field' => 'term_id',
                            'terms' => $terms,
                        ];
                    }
                }
            } else {
                foreach ($isPageForTerms as $termId) {
                    $term = get_term($termId);
                    if (!$term || is_wp_error($term)) {
                        continue;
                    }
                    $secondaryQueryArgs['tax_query'][] = [
                        'taxonomy' => $term->taxonomy,
                        'field' => 'term_id',
                        'terms' => $term->term_id,
                    ];
                }
            }

            $secondaryQueryArgs = apply_filters(
                'wpPageForTerm/secondaryQueryArgs',
                $secondaryQueryArgs,
                $query
            );
            $secondaryQuery = apply_filters('wpPageForTerm/secondaryQuery', new \WP_Query($secondaryQueryArgs));
        }

        $query->set('secondaryQuery', $secondaryQuery);
    }
}
