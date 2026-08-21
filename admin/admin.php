<?php

/**
 * RD3 Content Blocks
 *
 * Admin loader, menu and Post/Page editor sidebar.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


/*
 * =========================================================
 * LOAD ADMIN COMPONENTS
 * =========================================================
 */

require_once plugin_dir_path( __FILE__ ) . 'blocks.php';
require_once plugin_dir_path( __FILE__ ) . 'rows.php';
require_once plugin_dir_path( __FILE__ ) . 'adv-rows.php';
require_once plugin_dir_path( __FILE__ ) . 'advanced-row-page-selector.php';
require_once plugin_dir_path( __FILE__ ) . 'editor.php';
require_once plugin_dir_path( __FILE__ ) . 'help.php';
require_once plugin_dir_path( __FILE__ ) . 'usage.php';
require_once plugin_dir_path( __FILE__ ) . 'sort.php';


/*
 * =========================================================
 * ADMIN MENU
 * =========================================================
 */

function rd3_content_blocks_admin_menu() {

    add_menu_page(
        'RD3 Content Blocks',
        'RD3 Content Blocks',
        'manage_options',
        'rd3-content-blocks',
        'rd3_content_blocks_admin_page',
        'dashicons-screenoptions',
        30
    );

    add_submenu_page(
        'rd3-content-blocks',
        'How to Use',
        'How to Use',
        'manage_options',
        'rd3-content-blocks-usage',
        'rd3_content_blocks_usage_page'
    );
}

add_action(
    'admin_menu',
    'rd3_content_blocks_admin_menu',
    20
);


/*
 * =========================================================
 * MAIN ADMIN PAGE
 * =========================================================
 */

function rd3_content_blocks_admin_page() {

    wp_safe_redirect(
        admin_url(
            'edit.php?post_type=rd3_content_block'
        )
    );

    exit;
}


/*
 * =========================================================
 * POST / PAGE EDITOR SIDEBAR
 * =========================================================
 */

function rd3_content_blocks_editor_sidebar() {

    add_meta_box(
        'rd3_content_blocks_editor_sidebar',
        'RD3 Content Blocks',
        'rd3_content_blocks_editor_sidebar_html',
        array(
            'post',
            'page',
        ),
        'side',
        'high'
    );
}

add_action(
    'add_meta_boxes',
    'rd3_content_blocks_editor_sidebar',
    20
);


/*
 * =========================================================
 * SIDEBAR HTML
 * =========================================================
 */

function rd3_content_blocks_editor_sidebar_html( $post ) {

    $blocks = get_posts(
        array(
            'post_type'      => 'rd3_content_block',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        )
    );

    $rows = get_posts(
        array(
            'post_type'      => 'rd3_row',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        )
    );

    $advanced_rows = get_posts(
        array(
            'post_type'      => 'rd3_advanced_row',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        )
    );

    ?>

    <div class="rd3-editor-sidebar">

        <!-- CONTENT BLOCK -->

        <div class="rd3-editor-sidebar-section">

            <label
                for="rd3-content-block-select"
                class="rd3-editor-sidebar-label"
            >
                Content Block
            </label>

            <select
                id="rd3-content-block-select"
                class="rd3-editor-sidebar-select"
            >

                <option value="">
                    Select a Content Block
                </option>

                <?php foreach ( $blocks as $block ) : ?>

                    <option
                        value="<?php echo esc_attr( $block->ID ); ?>"
                        data-shortcode="<?php echo esc_attr(
                            '[rd3_block id="' . absint( $block->ID ) . '"]'
                        ); ?>"
                        data-edit-url="<?php echo esc_url(
                            get_edit_post_link( $block->ID, '' )
                        ); ?>"
                    >
                        <?php echo esc_html( $block->post_title ); ?>
                    </option>

                <?php endforeach; ?>

            </select>

            <div class="rd3-editor-sidebar-actions">

                <button
                    type="button"
                    class="button button-primary rd3-add-to-post"
                    data-select="rd3-content-block-select"
                >
                    Add to Post
                </button>

                <button
                    type="button"
                    class="button rd3-edit-selected"
                    data-select="rd3-content-block-select"
                    disabled
                >
                    Edit
                </button>

            </div>

        </div>


        <!-- ROW -->

        <div class="rd3-editor-sidebar-section">

            <label
                for="rd3-row-select"
                class="rd3-editor-sidebar-label"
            >
                Row
            </label>

            <select
                id="rd3-row-select"
                class="rd3-editor-sidebar-select"
            >

                <option value="">
                    Select a Row
                </option>

                <?php foreach ( $rows as $row ) : ?>

                    <option
                        value="<?php echo esc_attr( $row->ID ); ?>"
                        data-shortcode="<?php echo esc_attr(
                            '[rd3_row id="' . absint( $row->ID ) . '"]'
                        ); ?>"
                        data-edit-url="<?php echo esc_url(
                            get_edit_post_link( $row->ID, '' )
                        ); ?>"
                    >
                        <?php echo esc_html( $row->post_title ); ?>
                    </option>

                <?php endforeach; ?>

            </select>

            <div class="rd3-editor-sidebar-actions">

                <button
                    type="button"
                    class="button button-primary rd3-add-to-post"
                    data-select="rd3-row-select"
                >
                    Add to Post
                </button>

                <button
                    type="button"
                    class="button rd3-edit-selected"
                    data-select="rd3-row-select"
                    disabled
                >
                    Edit
                </button>

            </div>

        </div>


        <!-- ADVANCED ROW -->

        <div class="rd3-editor-sidebar-section">

            <label
                for="rd3-advanced-row-select"
                class="rd3-editor-sidebar-label"
            >
                Advanced Row
            </label>

            <select
                id="rd3-advanced-row-select"
                class="rd3-editor-sidebar-select"
            >

                <option value="">
                    Select an Advanced Row
                </option>

<?php foreach ( $advanced_rows as $advanced_row ) : ?>

    <?php

    $advanced_row_id = absint(
        $advanced_row->ID
    );

    /*
     * Get the blocks actually saved
     * inside this Advanced Row.
     */
    $saved_blocks = get_post_meta(
        $advanced_row_id,
        '_rd3_advanced_row_blocks',
        true
    );

    /*
     * Start with the Advanced Row shortcode.
     */
    $shortcode =
        '[rd3_advanced_row'
        . ' id="' . $advanced_row_id . '"';

    /*
     * Add the actual shortcode IDs saved
     * inside this Advanced Row.
     */
    if ( is_array( $saved_blocks ) ) {

        foreach ( $saved_blocks as $block ) {

            if ( ! is_array( $block ) ) {
                continue;
            }

            $shortcode_id =
                isset( $block['id'] )
                    ? sanitize_key( $block['id'] )
                    : '';

            if ( '' === $shortcode_id ) {
                continue;
            }

            $shortcode .=
                ' ' .
                $shortcode_id .
                '="1"';
        }
    }

    $shortcode .= ']';

    ?>

    <option
        value="<?php echo esc_attr( $advanced_row_id ); ?>"
        data-shortcode="<?php echo esc_attr( $shortcode ); ?>"
        data-edit-url="<?php echo esc_url(
            get_edit_post_link(
                $advanced_row_id,
                ''
            )
        ); ?>"
    >
        <?php echo esc_html(
            $advanced_row->post_title
        ); ?>
    </option>

<?php endforeach; ?>

            </select>

            <div class="rd3-editor-sidebar-actions">

                <button
                    type="button"
                    class="button button-primary rd3-add-to-post"
                    data-select="rd3-advanced-row-select"
                >
                    Add to Post
                </button>

                <button
                    type="button"
                    class="button rd3-edit-selected"
                    data-select="rd3-advanced-row-select"
                    disabled
                >
                    Edit
                </button>

            </div>

        </div>


        <?php if (
            empty( $blocks ) &&
            empty( $rows ) &&
            empty( $advanced_rows )
        ) : ?>

            <p class="description rd3-no-items">
                No Content Blocks, Rows or Advanced Rows
                are currently published.
            </p>

        <?php endif; ?>

    </div>

    <?php
}


/*
 * =========================================================
 * SIDEBAR STYLES
 * =========================================================
 */

function rd3_content_blocks_editor_sidebar_styles( $hook ) {

    if (
        'post.php' !== $hook &&
        'post-new.php' !== $hook
    ) {
        return;
    }

    $screen = get_current_screen();

    if ( ! $screen ) {
        return;
    }

    if (
        ! in_array(
            $screen->post_type,
            array(
                'post',
                'page',
            ),
            true
        )
    ) {
        return;
    }

    ?>

    <style>

        .rd3-editor-sidebar {
            margin: -6px -12px -12px;
        }

        .rd3-editor-sidebar-section {
            padding: 12px;
            border-bottom: 1px solid #dcdcde;
        }

        .rd3-editor-sidebar-section:last-child {
            border-bottom: 0;
        }

        .rd3-editor-sidebar-label {
            display: block;
            margin: 0 0 6px;
            font-size: 13px;
            font-weight: 600;
            color: #1d2327;
        }

        .rd3-editor-sidebar-select {
            display: block;
            width: 100%;
            max-width: 100%;
            min-height: 34px;
            margin: 0;
        }

        .rd3-editor-sidebar-actions {
            display: flex;
            gap: 6px;
            margin-top: 8px;
        }

        .rd3-editor-sidebar-actions .button {
            flex: 1;
            text-align: center;
        }

        .rd3-editor-sidebar-actions
        .rd3-edit-selected:disabled {
            opacity: .5;
            cursor: default;
        }

        .rd3-no-items {
            padding: 12px;
            margin: 0;
        }

    </style>

    <?php
}

add_action(
    'admin_enqueue_scripts',
    'rd3_content_blocks_editor_sidebar_styles'
);


/*
 * =========================================================
 * LOAD RD3 EDITOR JAVASCRIPT
 * =========================================================
 *
 * IMPORTANT:
 *
 * admin.php is here:
 *
 * /rd3-content-blocks/admin/admin.php
 *
 * JavaScript is here:
 *
 * /rd3-content-blocks/admin/rd3-editor-sidebar.js
 *
 * They are in the SAME directory.
 */

function rd3_content_blocks_enqueue_editor_script( $hook ) {

    /*
     * Only Post/Page editor screens.
     */

    if (
        'post.php' !== $hook &&
        'post-new.php' !== $hook
    ) {
        return;
    }


    /*
     * Get current screen.
     */

    $screen = get_current_screen();

    if ( ! $screen ) {
        return;
    }


    /*
     * Only Posts and Pages.
     */

    if (
        ! in_array(
            $screen->post_type,
            array(
                'post',
                'page',
            ),
            true
        )
    ) {
        return;
    }


    /*
     * -----------------------------------------------------
     * CORRECT JS PATH
     * -----------------------------------------------------
     */

    $js_file =
        plugin_dir_path( __FILE__ )
        . 'rd3-editor-sidebar.js';


    $js_url =
        plugin_dir_url( __FILE__ )
        . 'rd3-editor-sidebar.js';


    /*
     * Debug logging.
     */

    error_log(
        'RD3: JS file = ' . $js_file
    );

    error_log(
        'RD3: JS URL = ' . $js_url
    );


    /*
     * Make sure the file exists.
     */

    if ( ! file_exists( $js_file ) ) {

        error_log(
            'RD3 ERROR: JS file does not exist: '
            . $js_file
        );

        return;
    }


    /*
     * Load JavaScript.
     *
     * "editor" makes sure the WordPress Classic
     * Editor/TinyMCE dependencies are available.
     */

    wp_enqueue_script(
        'rd3-editor-sidebar',
        $js_url,
        array(
            'jquery',
            'editor',
        ),
        filemtime( $js_file ),
        true
    );

}

add_action(
    'admin_enqueue_scripts',
    'rd3_content_blocks_enqueue_editor_script',
    30
);