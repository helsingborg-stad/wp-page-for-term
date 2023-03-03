<?php

namespace wpPageForTerm;

class App
{
    protected const PAGE_FOR_TERM_FIELD_KEY = 'page_for_term';
    protected const IS_PAGE_FOR_TERM_FIELD_KEY = 'is_page_for_term';

    public function __construct()
    {
        add_action('acf/save_post', [$this, 'updatePageForTerm'], 20);
        add_action('acf/save_post', [$this, 'updateIsPageForTerm'], 20);
    }

    /**
     * Updates the "page_for_term" ACF field for each term ID that was updated on the post.
     *
     * @param int $postId The ID of the post being saved.
     */
    public function updatePageForTerm($postId)
    {
        if (
            !isset($_POST['acf'][self::PAGE_FOR_TERM_FIELD_KEY])
            || !is_array($_POST['acf'][self::PAGE_FOR_TERM_FIELD_KEY])
        ) {
            return;
        }

        $termIds = $_POST['acf'][self::PAGE_FOR_TERM_FIELD_KEY];
        foreach ($termIds as $termId) {
            $termKey = "term_{$termId}";
            update_field(self::PAGE_FOR_TERM_FIELD_KEY, $postId, $termKey);
        }
    }

    /**
     * Updates the "is_page_for_term" ACF field for the page associated with the current term ID.
     *
     * @param int $postId The ID of the post being saved.
     */
    public function updateIsPageForTerm($postId)
    {
        if (!isset($_POST['acf'][self::IS_PAGE_FOR_TERM_FIELD_KEY])) {
            return;
        }

        $termId = (int) explode('_', $postId)[1];
        $pageId = (int) $_POST['acf'][self::IS_PAGE_FOR_TERM_FIELD_KEY];

        $terms = (array) get_field(self::PAGE_FOR_TERM_FIELD_KEY, $pageId);
        $terms[] = $termId;

        update_field(self::IS_PAGE_FOR_TERM_FIELD_KEY, $terms, $pageId);
    }
}
