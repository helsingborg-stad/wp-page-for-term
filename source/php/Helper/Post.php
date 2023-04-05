<?php

namespace wpPageForTerm\Helper;

class Post
{
    /**
     * If the post has a value for the ACF field "is_page_for_term", return the value of that field.
     * Otherwise, return false.
     *
     * @param int postId The post ID of the page you want to check. If you don't pass this, it will use
     * the current page.
     *
     * @return An array of term objects.
     */
    public static function isPageForTerm(int $postId = 0)
    {
        if (!$postId) {
            $postId = get_queried_object_id();
        }
        if (!function_exists('get_field')) {
            return new WP_Error('acf_not_installed', __('Advanced Custom Fields is required and is not installed.'));
        }

        $terms = (array) get_field('is_page_for_term', $postId);
        if (empty($terms)) {
            return false;
        }
        return $terms;
    }
}
