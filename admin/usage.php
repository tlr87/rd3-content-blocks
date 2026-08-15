<?php

/**
 * RD3 Content Blocks
 *
 * Usage detection for Content Blocks and Rows.
 */


/*
 * Prevent direct access.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


/*
 * Get all published Pages and Posts
 * that use a specific Content Block.
 *
 * Checks for:
 *
 * [rd3_block id="123"]
 *
 * Also checks for Content Blocks
 * used through an RD3 Row.
 */
function rd3_get_block_usage(
    $block_id
) {

    $block_id =
        absint(
            $block_id
        );


    if ( ! $block_id ) {
        return array();
    }


    $usage =
        array();


    /*
     * Get published Pages and Posts.
     */
    $posts =
        get_posts(
            array(
                'post_type' =>
                    array(
                        'post',
                        'page',
                    ),

                'post_status' =>
                    'publish',

                'posts_per_page' =>
                    -1,

                'fields' =>
                    'all',
            )
        );


    /*
     * Check Pages and Posts.
     */
    foreach (
        $posts as $post
    ) {

        $content =
            $post->post_content;


        /*
         * Check for direct Content Block usage.
         */
        if (
            preg_match(
                '/\[rd3_block\b[^\]]*\bid\s*=\s*["\']?'
                . preg_quote(
                    $block_id,
                    '/'
                )
                . '["\']?[^\]]*\]/i',
                $content
            )
        ) {

            $post_type_object =
                get_post_type_object(
                    $post->post_type
                );


            $type_label =
                $post_type_object &&
                ! empty(
                    $post_type_object->labels->singular_name
                )
                    ? $post_type_object->labels->singular_name
                    : ucfirst(
                        $post->post_type
                    );


            $usage[] =
                array(
                    'title' =>
                        $post->post_title,

                    'type_label' =>
                        $type_label,

                    'usage_type' =>
                        'Direct',

                    'edit_url' =>
                        get_edit_post_link(
                            $post->ID
                        ),

                    'row_title' =>
                        '',
                );
        }


        /*
         * Find Rows used by this Page/Post.
         */
        preg_match_all(
            '/\[rd3_row\b[^\]]*\bid\s*=\s*["\']?'
            . '([0-9]+)'
            . '["\']?[^\]]*\]/i',
            $content,
            $row_matches
        );


        if (
            empty(
                $row_matches[1]
            )
        ) {
            continue;
        }


        /*
         * Check every Row.
         */
        foreach (
            $row_matches[1] as $row_id
        ) {

            $row_id =
                absint(
                    $row_id
                );


            if ( ! $row_id ) {
                continue;
            }


            /*
             * Make sure this is actually
             * an RD3 Row.
             */
            if (
                'rd3_row'
                !== get_post_type(
                    $row_id
                )
            ) {
                continue;
            }


            /*
             * Get Content Blocks assigned
             * to this Row.
             */
            $positions =
                get_post_meta(
                    $row_id,
                    '_rd3_row_positions',
                    true
                );


            if (
                ! is_array(
                    $positions
                )
            ) {
                continue;
            }


            /*
             * Check whether this Content Block
             * belongs to the Row.
             */
            if (
                ! in_array(
                    $block_id,
                    $positions,
                    true
                )
            ) {
                continue;
            }


            $row_title =
                get_the_title(
                    $row_id
                );


            $post_type_object =
                get_post_type_object(
                    $post->post_type
                );


            $type_label =
                $post_type_object &&
                ! empty(
                    $post_type_object->labels->singular_name
                )
                    ? $post_type_object->labels->singular_name
                    : ucfirst(
                        $post->post_type
                    );


            $usage[] =
                array(
                    'title' =>
                        $post->post_title,

                    'type_label' =>
                        $type_label,

                    'usage_type' =>
                        'Via Row',

                    'edit_url' =>
                        get_edit_post_link(
                            $post->ID
                        ),

                    'row_title' =>
                        $row_title,
                );
        }
    }


    return $usage;
}


/*
 * Get all published Pages and Posts
 * that use a specific Row.
 *
 * Checks for:
 *
 * [rd3_row id="123"]
 */
function rd3_get_row_usage(
    $row_id
) {

    $row_id =
        absint(
            $row_id
        );


    if ( ! $row_id ) {
        return array();
    }


    $usage =
        array();


    /*
     * Get published Pages and Posts.
     */
    $posts =
        get_posts(
            array(
                'post_type' =>
                    array(
                        'post',
                        'page',
                    ),

                'post_status' =>
                    'publish',

                'posts_per_page' =>
                    -1,

                'fields' =>
                    'all',
            )
        );


    /*
     * Check each Page and Post.
     */
    foreach (
        $posts as $post
    ) {

        $content =
            $post->post_content;


        /*
         * Check for the Row shortcode.
         */
        if (
            ! preg_match(
                '/\[rd3_row\b[^\]]*\bid\s*=\s*["\']?'
                . preg_quote(
                    $row_id,
                    '/'
                )
                . '["\']?[^\]]*\]/i',
                $content
            )
        ) {
            continue;
        }


        $post_type_object =
            get_post_type_object(
                $post->post_type
            );


        $type_label =
            $post_type_object &&
            ! empty(
                $post_type_object->labels->singular_name
            )
                ? $post_type_object->labels->singular_name
                : ucfirst(
                    $post->post_type
                );


        $usage[] =
            array(
                'title' =>
                    $post->post_title,

                'type_label' =>
                    $type_label,

                'usage_type' =>
                    'Direct',

                'edit_url' =>
                    get_edit_post_link(
                        $post->ID
                    ),

                'row_title' =>
                    '',
            );
    }


    return $usage;
}