<?php

namespace wpPageForTerm;

class App
{
    protected const PAGE_FOR_TERM_FIELD_KEY = 'field_6401a495f45b5';
    protected const IS_PAGE_FOR_TERM_FIELD_KEY = 'field_63fe0756de689';

    public function __construct()
    {

        add_action('acf/save_post', [$this, 'updatePageForTerm'], 20);
        add_action('acf/save_post', [$this, 'updateIsPageForTerm'], 20);

        add_filter('term_link', [$this,'replaceTermArchiveLink'], 10, 2);
        add_action('template_redirect', [$this,'redirectTermToPageForTerm']);

        add_action('init', [$this, 'setupCustomColumns']);

        add_action('pre_get_posts', [$this, 'setupSecondaryQuery'], 2);
    }


    /**
     * Updates the "page_for_term" ACF field for each term ID that was updated on the post.
     *
     * @param int $postId The ID of the post being saved.
     */
    public function updatePageForTerm($currentPostId)
    {
        $termIds = $_POST['acf'][self::IS_PAGE_FOR_TERM_FIELD_KEY] ?? [];
        if (!empty($termIds)) {
            foreach ($termIds as $termId) {
                update_field(self::PAGE_FOR_TERM_FIELD_KEY, $currentPostId, "term_{$termId}");
            }
        }
    }

    public function setupCustomColumns()
    {
        $taxonomies = get_taxonomies(array(), 'names');
        foreach ($taxonomies as $taxonomy) {
            add_filter('manage_edit-' . $taxonomy . '_columns', [$this,'addCustomColumnToEditTags']);
            add_filter('manage_' . $taxonomy . '_custom_column', [$this,'displayCustomColumnContent'], 10, 3);
        }
    }

    /**
     * Replaces the link to a term archive with the permalink of the associated page.
     *
     * @param string $termLink The original term archive link.
     * @param object $term The current term object.
     *
     * @return string The modified term archive link.
     */
    public function replaceTermArchiveLink($termLink, $term)
    {
        $pageId = get_field('page_for_term', "term_{$term->term_id}");
        if ($pageId) {
            $termLink = get_permalink($pageId);
        }
        return $termLink;
    }

    /**
     * Redirects a term archive to its associated page if the "page_for_term" field is set.
     */
    public function redirectTermToPageForTerm()
    {
        if (is_tax()) {
            $term = get_queried_object();
            $pageId = get_field('page_for_term', $term);
            if ($pageId) {
                wp_safe_redirect(get_permalink($pageId), 301);
                exit;
            }
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
            $pageForTerm = get_field('page_for_term', 'term_' . $termId);
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
     * Sets up a secondary query for the current page based on the is_page_for_term field.
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

        $isPageForTerm = get_field('is_page_for_term', $query->queried_object_id);
        $postType = get_field('page_for_term_posttype', $query->queried_object_id);

        if ($postType && is_array($isPageForTerm) && !empty($isPageForTerm)) {
            $postsPerPage = isset($_REQUEST['number']) ? $_REQUEST['number'] : get_option('posts_per_page');
            $secondaryQueryArgs =
            [
            'tax_query' => [
                'relation' => 'OR',
            ],
            'post_type' => $postType,
            'posts_per_page' => $postsPerPage,
            'paged' => ( get_query_var('paged') ) ? get_query_var('paged') : 1,
            ];

            foreach ($isPageForTerm as $termId) {
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

            $secondaryQueryArgs = apply_filters('secondaryQueryArgs', $secondaryQueryArgs);
            $secondaryQuery = apply_filters('secondaryQuery', new \WP_Query($secondaryQueryArgs));

            $query->set('secondaryQuery', $secondaryQuery);
        }
    }
}
