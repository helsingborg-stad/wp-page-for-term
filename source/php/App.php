<?php

namespace wpPageForTerm;

class App
{
    public function __construct()
    {

        add_filter(
            'acf/update_value/key=field_6401a495f45b5',
            [$this, 'storeRelationshipTermToPage'],
            10,
            3
        );

        add_filter(
            'acf/update_value/key=field_63fe0756de689',
            [$this, 'storeRelationshipPageToTerm'],
            10,
            3
        );
    }

    /**
     * Updates the relationship on the post side whenever a term is updated with a post relationship.
     * It is called when the 'acf/update_value/key=field_6401a495f45b5' filter is applied.
     *
     * @param int $postId The ID of the post being added to the term.
     * @param int $termId The ID of the term being updated.
     *
     * @return int The ID of the post being added to a term.
     *
    */
    public function storeRelationshipTermToPage($postId, $termId)
    {
        update_post_meta($postId, 'is_page_for_term', $termId);
        return $postId;
    }


    /**
     * Updates the relationship on the term side whenever a post is updated with a term relationship.
     * It is called when the 'acf/update_value/key=field_63fe0756de689' filter is applied.
     *
     * @param int $termId The ID of the term being added to a post.
     * @param int $postId The ID of the post being updated.
     *
     * @return int The ID of the term being added to a post.
     *
    */
    public function storeRelationshipPageToTerm($termId, $postId)
    {
        update_term_meta($termId, 'page_for_term', $postId);
        return $termId;
    }
}
