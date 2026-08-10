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
 * RD3 Content Blocks admin menu.
 */
function rd3_content_blocks_admin_menu() {

    /*
     * Main menu.
     */
    add_menu_page(
        'Content Blocks',
        'RD3 Content Blocks',
        'manage_options',
        'rd3-content-blocks',
        '__return_null',
        'dashicons-layout',
        30
    );


    /*
     * Add New.
     */
    add_submenu_page(
        'rd3-content-blocks',
        'Add New Content Block',
        'Add New',
        'manage_options',
        'post-new.php?post_type=rd3_content_block'
    );


    /*
     * How to Use.
     */
    add_submenu_page(
        'rd3-content-blocks',
        'How to Use',
        'How to Use',
        'manage_options',
        'rd3-content-blocks-how-to-use',
        'rd3_content_blocks_how_to_use_page'
    );
}

add_action(
    'admin_menu',
    'rd3_content_blocks_admin_menu'
);


/*
 * How to Use page.
 */
function rd3_content_blocks_how_to_use_page() {

    ?>

    <div class="wrap">

        <h1>How to Use RD3 Content Blocks</h1>

        <p>
            <strong>
                RD3 Content Blocks let you create reusable pieces
                of content that can be inserted into multiple
                WordPress Pages and Posts.
            </strong>
        </p>

        <p>
            Create a block once, then insert it wherever you need it.
            If you update the block later, the changes will appear
            wherever that block is used.
        </p>

        <hr>

        <h2>1. Create a Content Block</h2>

        <p>
            Go to
            <strong>
                RD3 Content Blocks → Add New
            </strong>
            and give your block a name.
        </p>

        <p>
            Add your reusable content using the normal
            WordPress Classic Editor.
        </p>


        <h2>2. Add Your Content</h2>

        <p>
            Content Blocks can contain normal text, images,
            HTML and other WordPress shortcodes.
        </p>


        <h2>3. Insert a Content Block</h2>

        <p>
            When editing a normal WordPress Page or Post,
            use the
            <strong>Insert RD3 Block</strong>
            button in the Classic Editor.
        </p>


        <h2>4. Using the Shortcode</h2>

        <p>
            Each Content Block has its own shortcode.
            You can copy it from the Content Blocks list.
        </p>

        <p>
            Example:
        </p>

        <p>
            <code>[rd3_block id="2256"]</code>
        </p>

        <p>
            Replace <code>2256</code> with the ID of the
            Content Block you want to use.
        </p>


        <h2>5. Important Rule</h2>

        <div
            class="notice notice-warning inline"
            style="margin:15px 0;"
        >

            <p>
                <strong>
                    Content Blocks cannot contain another
                    Content Block.
                </strong>
            </p>

            <p>
                Do not place an
                <code>[rd3_block]</code>
                shortcode inside a Content Block.
            </p>

            <p>
                If you try to save a Content Block containing
                an <code>[rd3_block]</code> shortcode, the save
                will be stopped and an error will be displayed.
            </p>

            <p>
                Other WordPress shortcodes can still be used normally.
            </p>

        </div>


        <h2>6. Managing Content Blocks</h2>

        <p>
            Go to
            <strong>
                RD3 Content Blocks → Content Blocks
            </strong>
            to view and manage your reusable blocks.
        </p>

        <p>
            From there you can edit, delete and copy the
            shortcode for each Content Block.
        </p>

    </div>

    <?php
}


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
    echo esc_html( $shortcode );
    echo '</code>';

    echo ' ';

    echo '<button
        type="button"
        class="button rd3-copy-shortcode"
        data-shortcode="' .
        esc_attr( $shortcode ) .
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

    $screen = get_current_screen();

    if (
        ! $screen ||
        'rd3_content_block' !== $screen->post_type
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
 * Pass RD3 Content Blocks to the Classic Editor.
 */
function rd3_content_blocks_editor_data() {

    global $post;

    /*
     * Do not load RD3 block data when editing
     * an RD3 Content Block itself.
     */
    if (
        $post &&
        'rd3_content_block' === $post->post_type
    ) {
        return;
    }


    $blocks = get_posts(
        array(
            'post_type'      => 'rd3_content_block',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        )
    );


    $editor_blocks = array();

    foreach ( $blocks as $block ) {

        $editor_blocks[] = array(
            'text'  => $block->post_title,
            'value' => (string) $block->ID,
        );
    }

    ?>

    <script>

        window.rd3ContentBlocks =
            <?php
            echo wp_json_encode(
                $editor_blocks
            );
            ?>;

    </script>

    <?php
}

add_action(
    'admin_footer',
    'rd3_content_blocks_editor_data'
);


/*
 * Add RD3 Block button to Classic Editor.
 */
function rd3_content_block_editor_button( $buttons ) {

    /*
     * Do not add the button when editing
     * an RD3 Content Block.
     */
    global $post;

    if (
        $post &&
        'rd3_content_block' === $post->post_type
    ) {
        return $buttons;
    }

    $buttons[] =
        'rd3_content_block';

    return $buttons;
}

add_filter(
    'mce_buttons',
    'rd3_content_block_editor_button'
);


/*
 * Register RD3 Block TinyMCE plugin.
 */
function rd3_content_block_tinymce_plugin( $plugins ) {

    $plugins['rd3_content_block'] =
        plugin_dir_url(
            dirname( __FILE__ )
        ) .
        'assets/editor.js';

    return $plugins;
}

add_filter(
    'mce_external_plugins',
    'rd3_content_block_tinymce_plugin'
);


/*
 * Add RD3 Content Block error panel
 * inside the Publish box.
 */
function rd3_content_block_error_panel() {

    global $post;

    if (
        ! $post ||
        'rd3_content_block' !== $post->post_type
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
                    This content block cannot contain
                    another RD3 Content Block shortcode.
                </strong>
            </p>

            <p>
                Please remove the
                <code>[rd3_block]</code>
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
 * Prevent an RD3 Content Block from being saved
 * if it contains another RD3 Content Block shortcode.
 */
function rd3_content_block_save_validation_script() {

    global $post;

    if (
        ! $post ||
        'rd3_content_block' !== $post->post_type
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


            function rd3HasNestedBlock() {

                let content = '';


                /*
                 * Classic Editor / TinyMCE.
                 */
                if (
                    typeof tinymce !== 'undefined' &&
                    tinymce.get('content')
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


                return /\[rd3_block\b[^\]]*\]/i.test(
                    content
                );
            }


            function rd3ShowError() {

                errorPanel.style.display =
                    'block';

                errorPanel.scrollIntoView(
                    {
                        behavior: 'smooth',
                        block: 'nearest'
                    }
                );
            }


            function rd3HideError() {

                errorPanel.style.display =
                    'none';
            }


            form.addEventListener(
                'submit',
                function (event) {

                    /*
                     * Ignore autosave.
                     */
                    if (
                        event.submitter &&
                        (
                            'autosave' ===
                            event.submitter.name ||
                            'autosave' ===
                            event.submitter.id
                        )
                    ) {
                        return;
                    }


                    if (
                        rd3HasNestedBlock()
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
             * Check Update / Publish button.
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
                            rd3HasNestedBlock()
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