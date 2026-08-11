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