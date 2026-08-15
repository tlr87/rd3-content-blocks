<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


/*
 * Register RD3 Content Block post type.
 */
function rd3_register_content_block_post_type() {

    register_post_type(
        'rd3_content_block',
        array(

            'labels' => array(
                'name'          => 'Content Blocks',
                'singular_name' => 'Content Block',
                'add_new'       => 'Add New',
                'add_new_item'  => 'Add New Content Block',
                'edit_item'     => 'Edit Content Block',
                'new_item'      => 'New Content Block',
                'view_item'     => 'View Content Block',
                'search_items'  => 'Search Content Blocks',
            ),

            'public'       => false,
            'show_ui'      => true,
            'show_in_menu' => 'rd3-content-blocks',

            'supports' => array(
                'title',
                'editor',
            ),

            'capability_type' => 'post',
            'map_meta_cap'    => true,
        )
    );
}

add_action(
    'init',
    'rd3_register_content_block_post_type'
);


/*
 * Add shortcode column to the native
 * WordPress Content Blocks list.
 */
function rd3_content_block_columns( $columns ) {

    $new_columns = array();

    foreach ( $columns as $key => $title ) {

        $new_columns[ $key ] = $title;

        if ( 'title' === $key ) {

            $new_columns['rd3_shortcode'] =
                'Shortcode';
        }
    }

    return $new_columns;
}

add_filter(
    'manage_rd3_content_block_posts_columns',
    'rd3_content_block_columns'
);


/*
 * Display shortcode column.
 */
function rd3_content_block_column_content(
    $column,
    $post_id
) {

    if ( 'rd3_shortcode' !== $column ) {
        return;
    }

    $shortcode =
        '[rd3_block id="' .
        $post_id .
        '"]';

    echo '<code>';

    echo esc_html(
        $shortcode
    );

    echo '</code>';

    echo ' ';

    echo '<button
        type="button"
        class="button rd3-copy-shortcode"
        data-shortcode="' .
        esc_attr(
            $shortcode
        ) .
        '"
        style="margin-left:8px;"
    >Copy</button>';
}

add_action(
    'manage_rd3_content_block_posts_custom_column',
    'rd3_content_block_column_content',
    10,
    2
);


/*
 * Copy shortcode JavaScript.
 */
function rd3_content_block_copy_script() {

    $screen =
        get_current_screen();

    if (
        ! $screen ||
        'rd3_content_block'
        !== $screen->post_type
    ) {
        return;
    }

    ?>

    <script>

    document.addEventListener(
        'DOMContentLoaded',
        function () {

            const buttons =
                document.querySelectorAll(
                    '.rd3-copy-shortcode'
                );

            buttons.forEach(
                function (button) {

                    button.addEventListener(
                        'click',
                        function () {

                            const shortcode =
                                button.getAttribute(
                                    'data-shortcode'
                                );

                            const textarea =
                                document.createElement(
                                    'textarea'
                                );

                            textarea.value =
                                shortcode;

                            textarea.style.position =
                                'fixed';

                            textarea.style.left =
                                '-9999px';

                            document.body.appendChild(
                                textarea
                            );

                            textarea.focus();

                            textarea.select();

                            try {

                                document.execCommand(
                                    'copy'
                                );

                                button.textContent =
                                    'Copied!';

                                setTimeout(
                                    function () {

                                        button.textContent =
                                            'Copy';

                                    },
                                    1500
                                );

                            } catch (error) {

                                button.textContent =
                                    'Copy failed';

                                console.error(
                                    'RD3 Content Blocks:',
                                    error
                                );
                            }

                            document.body.removeChild(
                                textarea
                            );

                        }
                    );

                }
            );

        }
    );

    </script>

    <?php
}

add_action(
    'admin_footer',
    'rd3_content_block_copy_script'
);


/*
 * Add Used On column to Content Blocks.
 */
function rd3_content_block_used_on_column(
    $columns
) {

    $columns['rd3_used_on'] =
        'Used On';

    return $columns;
}

add_filter(
    'manage_rd3_content_block_posts_columns',
    'rd3_content_block_used_on_column'
);


/*
 * Display Content Block usage.
 */
function rd3_content_block_used_on_column_content(
    $column,
    $post_id
) {

    if (
        'rd3_used_on' !== $column
    ) {
        return;
    }


    $usage =
        rd3_get_block_usage(
            $post_id
        );


    if (
        empty( $usage )
    ) {

        echo '<span style="color:#777;">';
        echo 'Not currently used';
        echo '</span>';

        return;
    }


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


        echo '<small>';

        echo esc_html(
            $item['type_label']
        );


        if (
            isset(
                $item['usage_type']
            )
        ) {

            echo ' — ';

            echo esc_html(
                $item['usage_type']
            );
        }


        if (
            'Via Row' ===
            $item['usage_type']
            &&
            ! empty(
                $item['row_title']
            )
        ) {

            echo ': ';

            echo esc_html(
                $item['row_title']
            );
        }


        echo '</small>';

        echo '<br><br>';
    }
}

add_action(
    'manage_rd3_content_block_posts_custom_column',
    'rd3_content_block_used_on_column_content',
    10,
    2
);


/*
 * Make Used On column sortable.
 */
function rd3_content_block_used_on_sortable(
    $columns
) {

    $columns['rd3_used_on'] =
        'rd3_used_on';

    return $columns;
}

add_filter(
    'manage_edit-rd3_content_block_sortable_columns',
    'rd3_content_block_used_on_sortable'
);


/*
 * Sort Content Blocks alphabetically
 * by the first Used On title.
 */
function rd3_content_block_used_on_orderby(
    $query
) {

    if (
        ! is_admin() ||
        ! $query->is_main_query()
    ) {
        return;
    }


    if (
        'rd3_content_block' !==
        $query->get( 'post_type' )
    ) {
        return;
    }


    if (
        'rd3_used_on' !==
        $query->get( 'orderby' )
    ) {
        return;
    }


    /*
     * Get all Content Blocks.
     */
    $blocks =
        get_posts(
            array(
                'post_type'      => 'rd3_content_block',
                'post_status'    => 'any',
                'posts_per_page' => -1,
                'fields'         => 'ids',
                'no_found_rows'  => true,
            )
        );


    foreach (
        $blocks as $block_id
    ) {

        $usage =
            rd3_get_block_usage(
                $block_id
            );


        $titles =
            array();


        /*
         * Collect every page/post title.
         */
        foreach (
            $usage as $item
        ) {

            if (
                empty(
                    $item['title']
                )
            ) {
                continue;
            }


            $titles[] =
                wp_strip_all_tags(
                    $item['title']
                );
        }


        /*
         * Find the alphabetically first
         * page/post title.
         */
        if (
            ! empty( $titles )
        ) {

            natcasesort(
                $titles
            );


            $titles =
                array_values(
                    $titles
                );


            $sort_title =
                $titles[0];

        } else {

            /*
             * Unused blocks go to the end.
             */
            $sort_title =
                'ZZZZZZZZZZ';
        }


        /*
         * Store the alphabetical
         * sorting value.
         */
        update_post_meta(
            $block_id,
            '_rd3_used_on_sort',
            $sort_title
        );
    }


    /*
     * Sort by the stored title.
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
    'rd3_content_block_used_on_orderby'
);


/*
 * Add RD3 Content Block error panel
 * inside the Publish box.
 */
function rd3_content_block_error_panel() {

    global $post;

    if (
        ! $post ||
        'rd3_content_block'
        !== $post->post_type
    ) {
        return;
    }

    ?>

    <div
        id="rd3-content-block-error"
        class="postbox"
        style="
            display:none;
            margin:12px 0 0 0;
        "
    >

        <h2
            style="
                margin:0;
                padding:10px 12px;
                font-size:14px;
            "
        >

            RD3 Content Block Error

        </h2>


        <div
            style="
                padding:0 12px 12px 12px;
            "
        >

            <p>

                <strong>
                    This Content Block cannot contain
                    [rd3_block] or [rd3_row] shortcodes.
                </strong>

            </p>


            <p>

                Please remove the
                <code>[rd3_block]</code>
                or
                <code>[rd3_row]</code>
                shortcode before saving this block.

            </p>

        </div>

    </div>

    <?php
}

add_action(
    'post_submitbox_misc_actions',
    'rd3_content_block_error_panel'
);


/*
 * Prevent an RD3 Content Block from being
 * saved if it contains an RD3 Block or Row.
 */
function rd3_content_block_save_validation_script() {

    global $post;

    if (
        ! $post ||
        'rd3_content_block'
        !== $post->post_type
    ) {
        return;
    }

    ?>

    <script>

    document.addEventListener(
        'DOMContentLoaded',
        function () {

            const form =
                document.getElementById(
                    'post'
                );

            const errorPanel =
                document.getElementById(
                    'rd3-content-block-error'
                );

            if (
                ! form ||
                ! errorPanel
            ) {
                return;
            }


            /*
             * Get current editor content.
             */
            function rd3GetContent() {

                let content = '';


                /*
                 * TinyMCE / Classic Editor.
                 */
                if (
                    typeof tinymce !== 'undefined' &&
                    tinymce.get(
                        'content'
                    )
                ) {

                    content =
                        tinymce.get(
                            'content'
                        ).getContent();

                } else {

                    const textarea =
                        document.getElementById(
                            'content'
                        );

                    if ( textarea ) {

                        content =
                            textarea.value;
                    }
                }


                return content;
            }


            /*
             * Check for restricted shortcodes.
             */
            function rd3HasRestrictedShortcode() {

                const content =
                    rd3GetContent();


                return (
                    /\[rd3_block\b[^\]]*\]/i.test(
                        content
                    )
                    ||
                    /\[rd3_row\b[^\]]*\]/i.test(
                        content
                    )
                );
            }


            /*
             * Show error panel.
             */
            function rd3ShowError() {

                errorPanel.style.display =
                    'block';

            }


            /*
             * Hide error panel.
             */
            function rd3HideError() {

                errorPanel.style.display =
                    'none';

            }


            /*
             * Check the form before submission.
             */
            form.addEventListener(
                'submit',
                function (event) {

                    /*
                     * Allow autosave.
                     */
                    if (
                        event.submitter &&
                        (
                            'autosave' ===
                            event.submitter.name
                            ||
                            'autosave' ===
                            event.submitter.id
                        )
                    ) {

                        return;
                    }


                    if (
                        rd3HasRestrictedShortcode()
                    ) {

                        event.preventDefault();

                        event.stopPropagation();

                        rd3ShowError();

                        return false;
                    }


                    rd3HideError();

                },
                true
            );


            /*
             * Check the Update / Publish button.
             */
            const updateButton =
                document.getElementById(
                    'publish'
                );


            if ( updateButton ) {

                updateButton.addEventListener(
                    'click',
                    function (event) {

                        if (
                            rd3HasRestrictedShortcode()
                        ) {

                            event.preventDefault();

                            event.stopPropagation();

                            rd3ShowError();

                            return false;
                        }

                    },
                    true
                );

            }

        }
    );

    </script>

    <?php
}

add_action(
    'admin_footer',
    'rd3_content_block_save_validation_script'
);