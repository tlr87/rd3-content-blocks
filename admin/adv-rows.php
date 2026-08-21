<?php

/**
 * RD3 Advanced Rows
 *
 * Advanced Row post type and administration.
 */


/*
 * ============================================================
 * REGISTER POST TYPE
 * ============================================================
 */

function rd3_register_advanced_row_post_type() {

    register_post_type(
        'rd3_advanced_row',
        array(

            'labels' => array(

                'name'          => 'Advanced Rows',
                'singular_name' => 'Advanced Row',

                'add_new'       => 'Add New',
                'add_new_item'  => 'Add New Advanced Row',

                'edit_item'     => 'Edit Advanced Row',
                'new_item'      => 'New Advanced Row',

                'view_item'     => 'View Advanced Row',

                'search_items'  => 'Search Advanced Rows',

                'not_found'     => 'No Advanced Rows found.',

                'menu_name'     => 'Advanced Rows',

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
    'rd3_register_advanced_row_post_type'
);


/*
 * ============================================================
 * META BOX
 * ============================================================
 */

function rd3_advanced_row_add_meta_box() {

    add_meta_box(
        'rd3_advanced_row_settings',
        'Advanced Row Settings',
        'rd3_advanced_row_settings_meta_box',
        'rd3_advanced_row',
        'normal',
        'high'
    );
}

add_action(
    'add_meta_boxes',
    'rd3_advanced_row_add_meta_box'
);


/*
 * ============================================================
 * DISPLAY SETTINGS
 * ============================================================
 */

function rd3_advanced_row_settings_meta_box(
    $post
) {

    wp_nonce_field(
        'rd3_advanced_row_save',
        'rd3_advanced_row_nonce'
    );


    /*
     * Blocks.
     */

    $blocks =
        get_post_meta(
            $post->ID,
            '_rd3_advanced_row_blocks',
            true
        );


    if (
        ! is_array(
            $blocks
        )
    ) {

        $blocks =
            array();
    }


    /*
     * Layout.
     */

    $layout =
        get_post_meta(
            $post->ID,
            '_rd3_advanced_row_layout',
            true
        );


    if (
        ! in_array(
            $layout,
            array(
                'inline',
                'stacked',
                'aligned',
            ),
            true
        )
    ) {

        $layout =
            'inline';
    }


    /*
     * Available Content Blocks.
     */

    $available_blocks =
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


    /*
     * Default shortcode IDs.
     */

    $defaults =
        array(
            1 => 'brok',
            2 => 'bett',
            3 => 'prot',
            4 => 'mana',
            5 => '',
        );


    /*
     * Fill missing settings.
     */

    for (
        $i = 1;
        $i <= 5;
        $i++
    ) {

        if (
            ! isset(
                $blocks[ $i ]
            )
            ||
            ! is_array(
                $blocks[ $i ]
            )
        ) {

            $blocks[ $i ] =
                array(
                    'block_id' => '',
                    'id'       => $defaults[ $i ],
                );
        }


        if (
            ! isset(
                $blocks[ $i ]['block_id']
            )
        ) {

            $blocks[ $i ]['block_id'] =
                '';
        }


        if (
            ! isset(
                $blocks[ $i ]['id']
            )
            ||
            ''
            ===
            $blocks[ $i ]['id']
        ) {

            $blocks[ $i ]['id'] =
                $defaults[ $i ];
        }
    }


    /*
     * Current-page behaviour.
     */

    $hide_current =
        get_post_meta(
            $post->ID,
            '_rd3_advanced_row_hide_current',
            true
        );


    if (
        ''
        ===
        $hide_current
    ) {

        $hide_current =
            1;
    }

    ?>

    <p>

        <strong>
            Configure the Content Blocks available to this Advanced Row.
        </strong>

    </p>


    <p>

        Select an existing Content Block for each position
        and give it a shortcode ID of up to 4 characters.

    </p>


    <hr>


    <h3>
        Layout
    </h3>


    <p>

        Select how the Content Blocks should be displayed.

    </p>


    <p>

        <label
            style="margin-right:20px;"
        >

            <input
                type="radio"
                name="rd3_advanced_row_layout"
                value="inline"
                <?php checked(
                    $layout,
                    'inline'
                ); ?>
            >

            <strong>
                Inline
            </strong>

        </label>


        <label
            style="margin-right:20px;"
        >

            <input
                type="radio"
                name="rd3_advanced_row_layout"
                value="stacked"
                <?php checked(
                    $layout,
                    'stacked'
                ); ?>
            >

            <strong>
                Stacked
            </strong>

        </label>


        <label>

            <input
                type="radio"
                name="rd3_advanced_row_layout"
                value="aligned"
                <?php checked(
                    $layout,
                    'aligned'
                ); ?>
            >

            <strong>
                Aligned Cards
            </strong>

        </label>

    </p>


    <hr>


    <h3>
        Content Blocks
    </h3>


    <table
        class="widefat striped"
        style="max-width:900px;"
    >

        <thead>

            <tr>

                <th style="width:65%;">
                    Content Block
                </th>

                <th style="width:35%;">
                    Shortcode ID
                </th>

            </tr>

        </thead>


        <tbody>

            <?php for (
                $i = 1;
                $i <= 5;
                $i++
            ) : ?>

                <tr>

                    <td
                        style="
                            border-left:1px solid #000;
                            padding:12px;
                            vertical-align:middle;
                        "
                    >

                        <strong
                            style="
                                display:block;
                                margin-bottom:6px;
                            "
                        >

                            Position
                            <?php echo esc_html( $i ); ?>

                        </strong>


                        <select
                            id="rd3_advanced_row_block_<?php echo esc_attr( $i ); ?>"
                            name="rd3_advanced_row_blocks[<?php echo esc_attr( $i ); ?>][block_id]"
                            style="
                                width:100%;
                                max-width:500px;
                            "
                        >

                            <option value="">
                                — Remove Content Block —
                            </option>


                            <?php foreach (
                                $available_blocks as $available_block
                            ) : ?>

                                <option
                                    value="<?php echo esc_attr( $available_block->ID ); ?>"
                                    <?php selected(
                                        $blocks[ $i ]['block_id'],
                                        $available_block->ID
                                    ); ?>
                                >

                                    <?php
                                    echo esc_html(
                                        $available_block->post_title
                                    );
                                    ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </td>


                    <td
                        style="
                            border-right:1px solid #000;
                            padding:12px;
                            vertical-align:bottom;
                            white-space:nowrap;
                        "
                    >

                        <span
                            style="
                                font-size:18px;
                                font-weight:bold;
                                margin-right:8px;
                                vertical-align:middle;
                            "
                            aria-hidden="true"
                        >
                            =
                        </span>


                        <input
                            type="text"
                            name="rd3_advanced_row_blocks[<?php echo esc_attr( $i ); ?>][id]"
                            value="<?php echo esc_attr(
                                $blocks[ $i ]['id']
                            ); ?>"
                            maxlength="4"
                            class="small-text"
                            style="vertical-align:middle;"
                        >

                    </td>

                </tr>

            <?php endfor; ?>

        </tbody>

    </table>


    <?php if (
        empty(
            $available_blocks
        )
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

                Create and publish a Content Block before
                adding it to an Advanced Row.

            </p>

        </div>

    <?php endif; ?>


    <hr>


    <h3>
        Conditional Display
    </h3>


    <p>

        <label>

            <input
                type="checkbox"
                name="rd3_advanced_row_hide_current"
                value="1"
                <?php checked(
                    $hide_current,
                    1
                ); ?>
            >

            Hide the block that links to the current page

        </label>

    </p>


    <hr>


    <h3>
        Shortcode
    </h3>


    <p>

        Use the block IDs to control which blocks are displayed.

    </p>


    <p>

        <strong>
            IDs can have a maximum of 4 characters.
        </strong>

    </p>


    <p>

        <code>
            [rd3_advanced_row id="<?php echo esc_attr( $post->ID ); ?>" brok="1" bett="1" prot="1" mana="1"]
        </code>

    </p>


    <p>

        Use <strong>1</strong> to show a block and
        <strong>0</strong> to hide it.

    </p>


    <p>

        The Advanced Row uses existing Content Blocks.
        It does not contain Content Block content itself.

    </p>

    <?php
}


/*
 * ============================================================
 * SAVE SETTINGS
 * ============================================================
 */

function rd3_advanced_row_save_settings(
    $post_id
) {

    if (
        ! isset(
            $_POST['rd3_advanced_row_nonce']
        )
    ) {

        return;
    }


    if (
        ! wp_verify_nonce(
            sanitize_text_field(
                wp_unslash(
                    $_POST['rd3_advanced_row_nonce']
                )
            ),
            'rd3_advanced_row_save'
        )
    ) {

        return;
    }


    if (
        defined(
            'DOING_AUTOSAVE'
        )
        &&
        DOING_AUTOSAVE
    ) {

        return;
    }


    if (
        ! current_user_can(
            'edit_post',
            $post_id
        )
    ) {

        return;
    }


    if (
        'rd3_advanced_row'
        !==
        get_post_type(
            $post_id
        )
    ) {

        return;
    }


    /*
     * Layout.
     */

    $layout =
        isset(
            $_POST['rd3_advanced_row_layout']
        )
            ? sanitize_key(
                wp_unslash(
                    $_POST['rd3_advanced_row_layout']
                )
            )
            : 'inline';


    if (
        ! in_array(
            $layout,
            array(
                'inline',
                'stacked',
                'aligned',
            ),
            true
        )
    ) {

        $layout =
            'inline';
    }


    update_post_meta(
        $post_id,
        '_rd3_advanced_row_layout',
        $layout
    );


    /*
     * Blocks.
     */

    $blocks =
        array();


    if (
        isset(
            $_POST['rd3_advanced_row_blocks']
        )
        &&
        is_array(
            $_POST['rd3_advanced_row_blocks']
        )
    ) {

        foreach (
            $_POST['rd3_advanced_row_blocks']
            as $position => $block
        ) {

            $position =
                absint(
                    $position
                );


            if (
                $position < 1
                ||
                $position > 5
            ) {

                continue;
            }


            $block_id =
                isset(
                    $block['block_id']
                )
                    ? absint(
                        $block['block_id']
                    )
                    : 0;


            if (
                $block_id
                &&
                'rd3_content_block'
                !==
                get_post_type(
                    $block_id
                )
            ) {

                $block_id =
                    0;
            }


            $id =
                isset(
                    $block['id']
                )
                    ? sanitize_key(
                        wp_unslash(
                            $block['id']
                        )
                    )
                    : '';


            $id =
                substr(
                    $id,
                    0,
                    4
                );


            if (
                ! $block_id
                ||
                ''
                ===
                $id
            ) {

                continue;
            }


            $blocks[
                $position
            ] =
                array(

                    'block_id' =>
                        $block_id,

                    'id' =>
                        $id,

                );
        }
    }


    update_post_meta(
        $post_id,
        '_rd3_advanced_row_blocks',
        $blocks
    );


    /*
     * Current-page behaviour.
     */

    $hide_current =
        isset(
            $_POST['rd3_advanced_row_hide_current']
        )
            ? 1
            : 0;


    update_post_meta(
        $post_id,
        '_rd3_advanced_row_hide_current',
        $hide_current
    );
}

add_action(
    'save_post_rd3_advanced_row',
    'rd3_advanced_row_save_settings'
);


/*
 * ============================================================
 * SHORTCODE COLUMN
 * ============================================================
 */

function rd3_advanced_row_shortcode_column(
    $columns
) {

    $columns['rd3_shortcode'] =
        'Shortcode';

    return $columns;
}

add_filter(
    'manage_rd3_advanced_row_posts_columns',
    'rd3_advanced_row_shortcode_column'
);


/*
 * ============================================================
 * DISPLAY SHORTCODE
 * ============================================================
 */

function rd3_advanced_row_shortcode_column_content(
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


    $blocks =
        get_post_meta(
            $post_id,
            '_rd3_advanced_row_blocks',
            true
        );


    if (
        ! is_array(
            $blocks
        )
    ) {

        return;
    }


    $attributes =
        array();


    for (
        $i = 1;
        $i <= 5;
        $i++
    ) {

        if (
            empty(
                $blocks[ $i ]['id']
            )
        ) {

            continue;
        }


        $id =
            sanitize_key(
                $blocks[ $i ]['id']
            );


        if (
            ''
            ===
            $id
        ) {

            continue;
        }


        $attributes[] =
            $id .
            '="1"';
    }


    if (
        empty(
            $attributes
        )
    ) {

        echo '<span style="color:#777;">';
        echo 'Configure block IDs';
        echo '</span>';

        return;
    }


    $shortcode =
        '[rd3_advanced_row id="' .
        absint(
            $post_id
        ) .
        '" ' .
        implode(
            ' ',
            $attributes
        ) .
        ']';

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
        class="button rd3-copy-advanced-row-shortcode"
        data-shortcode="<?php echo esc_attr( $shortcode ); ?>"
        style="margin-left:5px;"
    >
        Copy
    </button>

    <?php
}

add_action(
    'manage_rd3_advanced_row_posts_custom_column',
    'rd3_advanced_row_shortcode_column_content',
    10,
    2
);


/*
 * ============================================================
 * COPY SHORTCODE
 * ============================================================
 */

function rd3_advanced_row_copy_shortcode_script() {
    ?>

    <script>

    document.addEventListener(
        'click',
        function( event ) {

            if (
                ! event.target.classList.contains(
                    'rd3-copy-advanced-row-shortcode'
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
                navigator.clipboard
                &&
                navigator.clipboard.writeText
            ) {

                navigator.clipboard.writeText(
                    shortcode
                ).then(
                    function() {

                        rd3_advanced_row_copy_success(
                            button
                        );

                    }
                ).catch(
                    function() {

                        rd3_advanced_row_copy_fallback(
                            button,
                            shortcode
                        );

                    }
                );

                return;
            }


            rd3_advanced_row_copy_fallback(
                button,
                shortcode
            );
        }
    );


    function rd3_advanced_row_copy_success(
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


    function rd3_advanced_row_copy_fallback(
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

            rd3_advanced_row_copy_success(
                button
            );

        } else {

            alert(
                'Unable to copy the Advanced Row shortcode.'
            );
        }
    }

    </script>

    <?php
}

add_action(
    'admin_footer',
    'rd3_advanced_row_copy_shortcode_script'
);