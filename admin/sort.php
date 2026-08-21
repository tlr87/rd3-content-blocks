<?php

/**
 * RD3 Content Blocks
 *
 * Unified Used On columns and usage sorting
 * for Content Blocks, Rows and Advanced Rows.
 */


/*
 * =========================================================
 * POST TYPE CONFIGURATION
 * =========================================================
 */

function rd3_sort_post_types() {

    return array(

        'rd3_content_block' => array(
            'usage_function' =>
                'rd3_get_content_block_usage',
        ),

        'rd3_row' => array(
            'usage_function' =>
                'rd3_get_row_usage',
        ),

        'rd3_advanced_row' => array(
            'usage_function' =>
                'rd3_get_advanced_row_usage',
        ),

    );
}


/*
 * =========================================================
 * ADD USED ON COLUMN
 * =========================================================
 */

function rd3_sort_add_used_on_column(
    $columns
) {

    /*
     * Prevent duplicate registration.
     */
    if (
        ! isset(
            $columns['rd3_used_on']
        )
    ) {

        $columns['rd3_used_on'] =
            'Used On';
    }


    return $columns;
}


add_filter(
    'manage_rd3_content_block_posts_columns',
    'rd3_sort_add_used_on_column'
);


add_filter(
    'manage_rd3_row_posts_columns',
    'rd3_sort_add_used_on_column'
);


add_filter(
    'manage_rd3_advanced_row_posts_columns',
    'rd3_sort_add_used_on_column'
);


/*
 * =========================================================
 * GET USAGE
 * =========================================================
 */

function rd3_sort_get_usage(
    $post_type,
    $post_id
) {

    $config =
        rd3_sort_post_types();


    if (
        ! isset(
            $config[ $post_type ]
        )
    ) {

        return array();
    }


    $function =
        $config[ $post_type ]['usage_function'];


    if (
        ! function_exists(
            $function
        )
    ) {

        return array();
    }


    return call_user_func(
        $function,
        $post_id
    );
}


/*
 * =========================================================
 * DISPLAY USED ON
 * =========================================================
 */

function rd3_sort_display_used_on(
    $column,
    $post_id
) {

    if (
        'rd3_used_on'
        !==
        $column
    ) {

        return;
    }


    $post_type =
        get_post_type(
            $post_id
        );


    $usage =
        rd3_sort_get_usage(
            $post_type,
            $post_id
        );


    /*
     * Cache usage count.
     */
    update_post_meta(
        $post_id,
        '_rd3_usage_count',
        count(
            $usage
        )
    );


    if (
        empty(
            $usage
        )
    ) {

        echo '<span style="color:#777;">';
        echo 'Not currently used';
        echo '</span>';

        return;
    }

    /*
     * Sort usage alphabetically by title.
     */
    usort(
        $usage,
        function (
            $a,
            $b
        ) {

            $title_a =
                isset(
                    $a['title']
                )
                    ? trim(
                        wp_strip_all_tags(
                            $a['title']
                        )
                    )
                    : '';

            $title_b =
                isset(
                    $b['title']
                )
                    ? trim(
                        wp_strip_all_tags(
                            $b['title']
                        )
                    )
                    : '';


            $result =
                strcasecmp(
                    $title_a,
                    $title_b
                );


            /*
             * If titles are identical,
             * sort by ID for a consistent order.
             */
            if (
                0 === $result
            ) {

                $id_a =
                    isset(
                        $a['post_id']
                    )
                        ? absint(
                            $a['post_id']
                        )
                        : 0;

                $id_b =
                    isset(
                        $b['post_id']
                    )
                        ? absint(
                            $b['post_id']
                        )
                        : 0;


                return $id_a <=> $id_b;
            }


            return $result;
        }
    );


    foreach (
        $usage as $item
    ) {

        if (
            ! empty(
                $item['edit_url']
            )
        ) {

            echo '<a href="';

            echo esc_url(
                $item['edit_url']
            );

            echo '">';

            echo esc_html(
                $item['title']
            );

            echo '</a>';

        } else {

            echo esc_html(
                $item['title']
            );
        }


        echo '<br>';


        if (
            ! empty(
                $item['type_label']
            )
        ) {

            echo '<small>';

            echo esc_html(
                $item['type_label']
            );


            if (
                isset(
                    $item['usage_type']
                )
                &&
                '' !==
                $item['usage_type']
            ) {

                echo ' — ';

                echo esc_html(
                    $item['usage_type']
                );
            }


            echo '</small>';

            echo '<br>';
        }


        echo '<br>';
    }
}


/*
 * =========================================================
 * REGISTER USED ON DISPLAY
 * =========================================================
 */

add_action(
    'manage_rd3_content_block_posts_custom_column',
    'rd3_sort_display_used_on',
    10,
    2
);


add_action(
    'manage_rd3_row_posts_custom_column',
    'rd3_sort_display_used_on',
    10,
    2
);


add_action(
    'manage_rd3_advanced_row_posts_custom_column',
    'rd3_sort_display_used_on',
    10,
    2
);


/*
 * =========================================================
 * MAKE USED ON SORTABLE
 * =========================================================
 */

function rd3_sort_make_used_on_sortable(
    $columns
) {

    $columns['rd3_used_on'] =
        'rd3_used_on';

    return $columns;
}


add_filter(
    'manage_edit-rd3_content_block_sortable_columns',
    'rd3_sort_make_used_on_sortable'
);


add_filter(
    'manage_edit-rd3_row_sortable_columns',
    'rd3_sort_make_used_on_sortable'
);


add_filter(
    'manage_edit-rd3_advanced_row_sortable_columns',
    'rd3_sort_make_used_on_sortable'
);

/*
 * =========================================================
 * SORT BY USED ON TITLE
 * =========================================================
 */

function rd3_sort_used_on_orderby(
    $query
) {

    if (
        ! is_admin()
        ||
        ! $query->is_main_query()
    ) {

        return;
    }


    $post_type =
        $query->get(
            'post_type'
        );


    $config =
        rd3_sort_post_types();


    /*
     * Only handle our RD3 post types.
     */
    if (
        ! isset(
            $config[ $post_type ]
        )
    ) {

        return;
    }


    /*
     * Only handle the Used On column.
     */
    if (
        'rd3_used_on'
        !==
        $query->get(
            'orderby'
        )
    ) {

        return;
    }


    /*
     * Get all posts of this RD3 post type.
     *
     * We need to calculate the first alphabetical
     * Used On title before WordPress performs
     * the actual database sorting.
     */
    $items =
        get_posts(
            array(
                'post_type' =>
                    $post_type,

                'post_status' =>
                    array(
                        'publish',
                        'draft',
                        'private',
                    ),

                'posts_per_page' =>
                    -1,

                'fields' =>
                    'ids',

                'no_found_rows' =>
                    true,

                'suppress_filters' =>
                    true,
            )
        );


    foreach (
        $items as $item_id
    ) {

        $usage =
            rd3_sort_get_usage(
                $post_type,
                $item_id
            );


        /*
         * No usage.
         */
        if (
            empty(
                $usage
            )
        ) {

            update_post_meta(
                $item_id,
                '_rd3_used_on_sort',
                ''
            );

            continue;
        }


        /*
         * Sort the usage titles alphabetically.
         */
        usort(
            $usage,
            function (
                $a,
                $b
            ) {

                $title_a =
                    isset(
                        $a['title']
                    )
                        ? trim(
                            wp_strip_all_tags(
                                $a['title']
                            )
                        )
                        : '';


                $title_b =
                    isset(
                        $b['title']
                    )
                        ? trim(
                            wp_strip_all_tags(
                                $b['title']
                            )
                        )
                        : '';


                return strcasecmp(
                    $title_a,
                    $title_b
                );
            }
        );


        /*
         * Use the first alphabetical Used On
         * title as the database sorting value.
         */
        $sort_title =
            isset(
                $usage[0]['title']
            )
                ? trim(
                    wp_strip_all_tags(
                        $usage[0]['title']
                    )
                )
                : '';


        update_post_meta(
            $item_id,
            '_rd3_used_on_sort',
            $sort_title
        );
    }


    /*
     * Sort alphabetically by the cached
     * Used On title.
     */
    $query->set(
        'meta_key',
        '_rd3_used_on_sort'
    );


    $query->set(
        'orderby',
        'meta_value'
    );
}


add_action(
    'pre_get_posts',
    'rd3_sort_used_on_orderby'
);