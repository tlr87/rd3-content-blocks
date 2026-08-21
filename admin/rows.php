<?php

/**
 * RD3 Rows
 *
 * Row post type and administration.
 */


/*
 * =========================================================
 * REGISTER ROW POST TYPE
 * =========================================================
 */

function rd3_register_row_post_type() {

    register_post_type(
        'rd3_row',
        array(

            'labels' => array(

                'name'          => 'Rows',
                'singular_name' => 'Row',

                'add_new'       => 'Add New',
                'add_new_item'  => 'Add New Row',

                'edit_item'     => 'Edit Row',
                'new_item'      => 'New Row',

                'view_item'     => 'View Row',

                'search_items'  => 'Search Rows',

                'not_found'     => 'No Rows found.',

                'menu_name'     => 'Rows',

            ),

            'public' =>
                false,

            'show_ui' =>
                true,

            'show_in_menu' =>
                'rd3-content-blocks',

            'show_in_admin_bar' =>
                false,

            'show_in_nav_menus' =>
                false,

            'supports' =>
                array(
                    'title',
                ),

            'menu_icon' =>
                'dashicons-layout',

            'has_archive' =>
                false,

            'rewrite' =>
                false,

            'query_var' =>
                false,

        )
    );
}

add_action(
    'init',
    'rd3_register_row_post_type'
);


/*
 * =========================================================
 * ADD ROW SETTINGS META BOX
 * =========================================================
 */

function rd3_row_add_meta_box() {

    add_meta_box(
        'rd3_row_settings',
        'Row Settings',
        'rd3_row_settings_meta_box',
        'rd3_row',
        'normal',
        'high'
    );
}

add_action(
    'add_meta_boxes',
    'rd3_row_add_meta_box'
);


/*
 * =========================================================
 * DISPLAY ROW SETTINGS
 * =========================================================
 */

function rd3_row_settings_meta_box(
    $post
) {

    wp_nonce_field(
        'rd3_row_save',
        'rd3_row_nonce'
    );


    $layout =
        get_post_meta(
            $post->ID,
            '_rd3_row_layout',
            true
        );


    if (
        ! in_array(
            $layout,
            array(
                'inline',
                'stacked',
            ),
            true
        )
    ) {

        $layout =
            'inline';
    }


    $positions =
        get_post_meta(
            $post->ID,
            '_rd3_row_positions',
            true
        );


    if (
        ! is_array(
            $positions
        )
    ) {

        $positions =
            array();
    }


    /*
     * Get published Content Blocks.
     */
    $blocks =
        get_posts(
            array(
                'post_type' =>
                    'rd3_content_block',

                'post_status' =>
                    'publish',

                'posts_per_page' =>
                    -1,

                'orderby' =>
                    'title',

                'order' =>
                    'ASC',
            )
        );

    ?>

    <p>
        <strong>
            Choose the Content Blocks that make up this Row.
        </strong>
    </p>

    <p>
        A Row can contain between 1 and 5 Content Blocks.
        On mobile devices, blocks automatically stack into
        a single column.
    </p>


    <hr>


    <h3>Layout</h3>

    <p>

        <label>

            <input
                type="radio"
                name="rd3_row_layout"
                value="inline"
                <?php checked(
                    $layout,
                    'inline'
                ); ?>
            >

            Inline

        </label>

        &nbsp;&nbsp;

        <label>

            <input
                type="radio"
                name="rd3_row_layout"
                value="stacked"
                <?php checked(
                    $layout,
                    'stacked'
                ); ?>
            >

            Stacked

        </label>

    </p>


    <hr>


    <h3>Content Blocks</h3>


    <?php for (
        $i = 1;
        $i <= 5;
        $i++
    ) : ?>

        <?php

        $selected =
            isset(
                $positions[ $i ]
            )
                ? $positions[ $i ]
                : '';

        ?>

        <p>

            <label
                for="rd3_row_position_<?php echo esc_attr( $i ); ?>"
            >

                <strong>
                    Position <?php echo esc_html( $i ); ?>
                </strong>

            </label>

            <br>

            <select
                id="rd3_row_position_<?php echo esc_attr( $i ); ?>"
                name="rd3_row_positions[<?php echo esc_attr( $i ); ?>]"
                style="width:100%;max-width:500px;"
            >

                <option value="">
                    — Remove Content Block —
                </option>


                <?php foreach (
                    $blocks as $block
                ) : ?>

                    <option
                        value="<?php echo esc_attr( $block->ID ); ?>"
                        <?php selected(
                            $selected,
                            $block->ID
                        ); ?>
                    >

                        <?php
                        echo esc_html(
                            $block->post_title
                        );
                        ?>

                    </option>

                <?php endforeach; ?>

            </select>

        </p>

    <?php endfor; ?>


    <?php if (
        empty( $blocks )
    ) : ?>

        <div
            class="notice notice-warning inline"
            style="margin:15px 0;"
        >

            <p>

                <strong>
                    No Content Blocks are available.
                </strong>

            </p>

            <p>

                Create and publish a Content Block
                before adding it to a Row.

            </p>

        </div>

    <?php endif; ?>


    <hr>


    <p>

        <strong>
            Important:
        </strong>

        Rows use existing Content Blocks.
        They do not contain Content Block content
        themselves.

    </p>

    <?php
}


/*
 * =========================================================
 * SAVE ROW SETTINGS
 * =========================================================
 */

function rd3_row_save_settings(
    $post_id
) {

    /*
     * Check nonce.
     */
    if (
        ! isset(
            $_POST['rd3_row_nonce']
        )
    ) {

        return;
    }


    if (
        ! wp_verify_nonce(
            sanitize_text_field(
                wp_unslash(
                    $_POST['rd3_row_nonce']
                )
            ),
            'rd3_row_save'
        )
    ) {

        return;
    }


    /*
     * Check autosave.
     */
    if (
        defined(
            'DOING_AUTOSAVE'
        )
        &&
        DOING_AUTOSAVE
    ) {

        return;
    }


    /*
     * Check permissions.
     */
    if (
        ! current_user_can(
            'edit_post',
            $post_id
        )
    ) {

        return;
    }


    /*
     * Confirm post type.
     */
    if (
        'rd3_row'
        !==
        get_post_type(
            $post_id
        )
    ) {

        return;
    }


    /*
     * Save layout.
     */
    $layout =
        isset(
            $_POST['rd3_row_layout']
        )
            ? sanitize_key(
                wp_unslash(
                    $_POST['rd3_row_layout']
                )
            )
            : 'inline';


    if (
        ! in_array(
            $layout,
            array(
                'inline',
                'stacked',
            ),
            true
        )
    ) {

        $layout =
            'inline';
    }


    update_post_meta(
        $post_id,
        '_rd3_row_layout',
        $layout
    );


    /*
     * Save positions.
     */
    $positions =
        array();


    if (
        isset(
            $_POST['rd3_row_positions']
        )
        &&
        is_array(
            $_POST['rd3_row_positions']
        )
    ) {

        foreach (
            $_POST['rd3_row_positions']
            as $position => $block_id
        ) {

            $position =
                absint(
                    $position
                );


            $block_id =
                absint(
                    $block_id
                );


            if (
                $position < 1
                ||
                $position > 5
            ) {

                continue;
            }


            if (
                ! $block_id
            ) {

                continue;
            }


            /*
             * Make sure the selected post
             * really is a Content Block.
             */
            if (
                'rd3_content_block'
                !==
                get_post_type(
                    $block_id
                )
            ) {

                continue;
            }


            $positions[ $position ] =
                $block_id;
        }
    }


    update_post_meta(
        $post_id,
        '_rd3_row_positions',
        $positions
    );
}

add_action(
    'save_post_rd3_row',
    'rd3_row_save_settings'
);


/*
 * =========================================================
 * SHORTCODE COLUMN
 * =========================================================
 */

function rd3_row_shortcode_column(
    $columns
) {

    $columns['rd3_shortcode'] =
        'Shortcode';

    return $columns;
}

add_filter(
    'manage_rd3_row_posts_columns',
    'rd3_row_shortcode_column'
);


/*
 * Display Row shortcode.
 */
function rd3_row_shortcode_column_content(
    $column,
    $post_id
) {

    if (
        'rd3_shortcode'
        !==
        $column
    ) {

        return;
    }


    $shortcode =
        '[rd3_row id="' .
        absint(
            $post_id
        ) .
        '"]';

    ?>

    <code>
        <?php
        echo esc_html(
            $shortcode
        );
        ?>
    </code>

    <button
        type="button"
        class="button rd3-copy-row-shortcode"
        data-shortcode="<?php echo esc_attr( $shortcode ); ?>"
        style="margin-left:5px;"
    >
        Copy
    </button>

    <?php
}

add_action(
    'manage_rd3_row_posts_custom_column',
    'rd3_row_shortcode_column_content',
    10,
    2
);


/*
 * =========================================================
 * COPY ROW SHORTCODE
 * =========================================================
 */

function rd3_row_copy_shortcode_script() {
    ?>

    <script>

    document.addEventListener(
        'click',
        function( event ) {

            if (
                ! event.target.classList.contains(
                    'rd3-copy-row-shortcode'
                )
            ) {

                return;
            }


            var button =
                event.target;


            var shortcode =
                button.getAttribute(
                    'data-shortcode'
                );


            if (
                ! shortcode
            ) {

                return;
            }


            if (
                navigator.clipboard &&
                navigator.clipboard.writeText
            ) {

                navigator.clipboard.writeText(
                    shortcode
                ).then(
                    function() {

                        rd3_row_copy_success(
                            button
                        );

                    }
                ).catch(
                    function() {

                        rd3_row_copy_fallback(
                            button,
                            shortcode
                        );

                    }
                );

                return;
            }


            rd3_row_copy_fallback(
                button,
                shortcode
            );
        }
    );


    function rd3_row_copy_success(
        button
    ) {

        var original =
            button.textContent;


        button.textContent =
            'Copied';


        setTimeout(
            function() {

                button.textContent =
                    original;

            },
            1500
        );
    }


    function rd3_row_copy_fallback(
        button,
        shortcode
    ) {

        var textarea =
            document.createElement(
                'textarea'
            );


        textarea.value =
            shortcode;


        textarea.style.position =
            'fixed';


        textarea.style.opacity =
            '0';


        document.body.appendChild(
            textarea
        );


        textarea.focus();

        textarea.select();


        var successful =
            document.execCommand(
                'copy'
            );


        textarea.remove();


        if (
            successful
        ) {

            rd3_row_copy_success(
                button
            );

        } else {

            alert(
                'Unable to copy the Row shortcode.'
            );
        }
    }

    </script>

    <?php
}

add_action(
    'admin_footer',
    'rd3_row_copy_shortcode_script'
);


/*
 * =========================================================
 * ROW CONTENT VALIDATION
 * =========================================================
 *
 * Prevent an RD3 Row from containing another RD3 Block
 * or RD3 Row shortcode.
 */

function rd3_row_save_validation_script() {

    global $post;


    if (
        ! $post
        ||
        'rd3_row'
        !==
        $post->post_type
    ) {

        return;
    }

    ?>

    <script>

    document.addEventListener(
        'DOMContentLoaded',
        function() {

            var form =
                document.getElementById(
                    'post'
                );


            if (
                ! form
            ) {

                return;
            }


            form.addEventListener(
                'submit',
                function( event ) {

                    var editor =
                        typeof tinymce !== 'undefined'
                            ? tinymce.get( 'content' )
                            : null;


                    var content =
                        '';


                    if (
                        editor
                    ) {

                        content =
                            editor.getContent();

                    } else {

                        var textarea =
                            document.getElementById(
                                'content'
                            );


                        if (
                            textarea
                        ) {

                            content =
                                textarea.value;
                        }
                    }


                    if (
                        content.indexOf(
                            '[rd3_block'
                        )
                        !==
                        -1
                        ||
                        content.indexOf(
                            '[rd3_row'
                        )
                        !==
                        -1
                    ) {

                        alert(
                            'Rows cannot contain [rd3_block] or [rd3_row] shortcodes.'
                        );


                        event.preventDefault();

                        return false;
                    }

                }
            );

        }
    );

    </script>

    <?php
}

add_action(
    'admin_footer',
    'rd3_row_save_validation_script'
);