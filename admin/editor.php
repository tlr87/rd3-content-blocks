<?php

/**
 * RD3 Content Blocks
 *
 * Classic Editor integration for
 * Content Blocks and Rows.
 */


/*
 * Pass RD3 Content Blocks to the Classic Editor.
 */
function rd3_content_blocks_editor_data() {

    global $post;


    /*
     * Do not load RD3 Block data when editing
     * an RD3 Content Block itself.
     */
    if (
        $post &&
        'rd3_content_block' === $post->post_type
    ) {

        return;
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


    $editor_blocks =
        array();


    foreach (
        $blocks as $block
    ) {

        $editor_blocks[] =
            array(
                'text' =>
                    $block->post_title,

                'value' =>
                    (string)
                    $block->ID,
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
 * Pass RD3 Rows to the Classic Editor.
 */
function rd3_rows_editor_data() {

    global $post;


    /*
     * Do not load Row data when editing
     * an RD3 Row itself.
     */
    if (
        $post &&
        'rd3_row' === $post->post_type
    ) {

        return;
    }


    /*
     * Get published Rows.
     */
    $rows =
        get_posts(
            array(
                'post_type' =>
                    'rd3_row',

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


    $editor_rows =
        array();


    foreach (
        $rows as $row
    ) {

        $editor_rows[] =
            array(
                'text' =>
                    $row->post_title,

                'value' =>
                    (string)
                    $row->ID,
            );
    }


    ?>

    <script>

        window.rd3Rows =
            <?php
            echo wp_json_encode(
                $editor_rows
            );
            ?>;

    </script>

    <?php
}

add_action(
    'admin_footer',
    'rd3_rows_editor_data'
);


/*
 * Add RD3 buttons to the Classic Editor.
 */
function rd3_editor_buttons(
    $buttons
) {

    global $post;


    /*
     * Do not add RD3 buttons when editing
     * an RD3 Content Block or RD3 Row.
     */
    if (
        $post &&
        (
            'rd3_content_block' === $post->post_type ||
            'rd3_row' === $post->post_type
        )
    ) {

        return $buttons;
    }


    /*
     * Insert RD3 Content Block.
     */
    $buttons[] =
        'rd3_content_block';


    /*
     * Insert RD3 Row.
     */
    $buttons[] =
        'rd3_row';


    return $buttons;
}

add_filter(
    'mce_buttons',
    'rd3_editor_buttons'
);


/*
 * Register RD3 TinyMCE plugin.
 */
function rd3_content_block_tinymce_plugin(
    $plugins
) {

    $plugins[
        'rd3_content_block'
    ] =
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