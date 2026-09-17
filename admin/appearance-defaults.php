
<?php
/**
 * RD3 Content Blocks
 *
 * Global Appearance Defaults
 *
 * Provides global appearance defaults for:
 * - Content Blocks
 * - Rows
 *
 * Inheritance:
 *
 * Global Default
 *      ↓
 * Content Block / Row setting
 *      ↓
 * Row Content Block override
 *
 * An empty global setting means:
 * "No global default — use the normal CSS fallback."
 */


/* =========================================================
 * OPTION NAME
 * ========================================================= */

if ( ! defined( 'RD3_CONTENT_BLOCKS_APPEARANCE_DEFAULTS_OPTION' ) ) {

    define(
        'RD3_CONTENT_BLOCKS_APPEARANCE_DEFAULTS_OPTION',
        'rd3_content_blocks_appearance_defaults'
    );
}


/* =========================================================
 * DEFAULT VALUES
 * ========================================================= */

function rd3_content_blocks_get_appearance_defaults() {

    $defaults = array(

        'block' => array(
            'background'   => '',
            'text_color'   => '',
            'border_style' => '',
            'border_width' => '',
            'border_color' => '',
            'radius'       => '',
        ),

        'row' => array(
            'background'   => '',
            'text_color'   => '',
            'border_style' => '',
            'border_width' => '',
            'border_color' => '',
            'radius'       => '',
        ),

    );

    $saved = get_option(
        RD3_CONTENT_BLOCKS_APPEARANCE_DEFAULTS_OPTION,
        array()
    );

    if ( ! is_array( $saved ) ) {
        return $defaults;
    }

    foreach ( array( 'block', 'row' ) as $type ) {

        if (
            empty( $saved[ $type ] ) ||
            ! is_array( $saved[ $type ] )
        ) {
            continue;
        }

        foreach (
            $defaults[ $type ] as $key => $default_value
        ) {

            if (
                array_key_exists(
                    $key,
                    $saved[ $type ]
                )
            ) {

                $defaults[ $type ][ $key ] =
                    $saved[ $type ][ $key ];
            }
        }
    }

    return $defaults;
}


/* =========================================================
 * GET ONE GLOBAL DEFAULT
 * ========================================================= */

function rd3_content_blocks_get_appearance_default(
    $type,
    $setting
) {

    $defaults =
        rd3_content_blocks_get_appearance_defaults();

    if ( ! isset( $defaults[ $type ] ) ) {
        return '';
    }

    if (
        ! array_key_exists(
            $setting,
            $defaults[ $type ]
        )
    ) {
        return '';
    }

    return $defaults[ $type ][ $setting ];
}


/* =========================================================
 * SANITIZE SETTINGS
 * ========================================================= */

function rd3_content_blocks_sanitize_appearance_defaults(
    $input
) {

    $clean = array(

        'block' => array(
            'background'   => '',
            'text_color'   => '',
            'border_style' => '',
            'border_width' => '',
            'border_color' => '',
            'radius'       => '',
        ),

        'row' => array(
            'background'   => '',
            'text_color'   => '',
            'border_style' => '',
            'border_width' => '',
            'border_color' => '',
            'radius'       => '',
        ),

    );

    if ( ! is_array( $input ) ) {
        return $clean;
    }


    /* =====================================================
     * ALLOWED BORDER STYLES
     * ===================================================== */

    if (
        function_exists(
            'rd3_content_blocks_appearance_border_styles'
        )
    ) {

        $allowed_border_styles =
            rd3_content_blocks_appearance_border_styles();

    } else {

        $allowed_border_styles = array(
            'none'   => 'None',
            'solid'  => 'Solid',
            'dashed' => 'Dashed',
            'dotted' => 'Dotted',
            'double' => 'Double',
        );
    }


    /* =====================================================
     * PROCESS BLOCK + ROW
     * ===================================================== */

    foreach (
        array( 'block', 'row' ) as $type
    ) {

        if (
            ! isset( $input[ $type ] ) ||
            ! is_array( $input[ $type ] )
        ) {
            continue;
        }


        /* ---------------------------------------------
         * Background
         * --------------------------------------------- */

        if (
            isset(
                $input[ $type ]['background']
            )
        ) {

            $value =
                sanitize_hex_color(
                    wp_unslash(
                        $input[ $type ]['background']
                    )
                );

            $clean[ $type ]['background'] =
                $value ? $value : '';
        }


        /* ---------------------------------------------
         * Text colour
         * --------------------------------------------- */

        if (
            isset(
                $input[ $type ]['text_color']
            )
        ) {

            $value =
                sanitize_hex_color(
                    wp_unslash(
                        $input[ $type ]['text_color']
                    )
                );

            $clean[ $type ]['text_color'] =
                $value ? $value : '';
        }


        /* ---------------------------------------------
         * Border style
         * --------------------------------------------- */

        if (
            isset(
                $input[ $type ]['border_style']
            )
        ) {

            $value =
                sanitize_key(
                    wp_unslash(
                        $input[ $type ]['border_style']
                    )
                );

            if (
                '' === $value ||
                isset(
                    $allowed_border_styles[ $value ]
                )
            ) {

                $clean[ $type ]['border_style'] =
                    $value;
            }
        }


        /* ---------------------------------------------
         * Border width
         * --------------------------------------------- */

        if (
            isset(
                $input[ $type ]['border_width']
            )
        ) {

            $raw_value =
                trim(
                    wp_unslash(
                        $input[ $type ]['border_width']
                    )
                );

            if ( '' !== $raw_value ) {

                $value =
                    absint(
                        $raw_value
                    );

                $clean[ $type ]['border_width'] =
                    min(
                        50,
                        $value
                    );

            } else {

                $clean[ $type ]['border_width'] =
                    '';
            }
        }


        /* ---------------------------------------------
         * Border colour
         * --------------------------------------------- */

        if (
            isset(
                $input[ $type ]['border_color']
            )
        ) {

            $value =
                sanitize_hex_color(
                    wp_unslash(
                        $input[ $type ]['border_color']
                    )
                );

            $clean[ $type ]['border_color'] =
                $value ? $value : '';
        }


        /* ---------------------------------------------
         * Border radius
         * --------------------------------------------- */

        if (
            isset(
                $input[ $type ]['radius']
            )
        ) {

            $raw_value =
                trim(
                    wp_unslash(
                        $input[ $type ]['radius']
                    )
                );

            if ( '' !== $raw_value ) {

                $value =
                    absint(
                        $raw_value
                    );

                $clean[ $type ]['radius'] =
                    min(
                        100,
                        $value
                    );

            } else {

                $clean[ $type ]['radius'] =
                    '';
            }
        }
    }

    return $clean;
}


/* =========================================================
 * REGISTER SETTINGS
 * ========================================================= */

function rd3_content_blocks_register_appearance_defaults() {

    register_setting(
        'rd3_content_blocks_appearance_defaults',
        RD3_CONTENT_BLOCKS_APPEARANCE_DEFAULTS_OPTION,
        array(
            'type'              => 'array',
            'sanitize_callback' =>
                'rd3_content_blocks_sanitize_appearance_defaults',
            'default'           => array(

                'block' => array(
                    'background'   => '',
                    'text_color'   => '',
                    'border_style' => '',
                    'border_width' => '',
                    'border_color' => '',
                    'radius'       => '',
                ),

                'row' => array(
                    'background'   => '',
                    'text_color'   => '',
                    'border_style' => '',
                    'border_width' => '',
                    'border_color' => '',
                    'radius'       => '',
                ),

            ),
        )
    );
}

add_action(
    'admin_init',
    'rd3_content_blocks_register_appearance_defaults'
);


/* =========================================================
 * ADMIN ASSETS
 * ========================================================= */

function rd3_content_blocks_appearance_defaults_assets(
    $hook
) {

    /*
     * This page is now registered as a submenu under:
     *
     * RD3 Content Blocks
     *
     * Therefore we check the page slug rather than the old
     * Settings API hook.
     */

    if (
        ! isset( $_GET['page'] ) ||
        'rd3-content-blocks-appearance-defaults' !==
        sanitize_key(
            wp_unslash(
                $_GET['page']
            )
        )
    ) {
        return;
    }


    /*
     * Native WordPress colour picker.
     */

    wp_enqueue_style(
        'wp-color-picker'
    );

    wp_enqueue_script(
        'wp-color-picker'
    );


    /* =====================================================
     * ADMIN CSS
     * ===================================================== */

    wp_add_inline_style(
        'wp-color-picker',
        '
        .rd3-appearance-defaults-wrap {
            max-width: 900px;
        }

        .rd3-appearance-defaults-section {
            background: #fff;
            border: 1px solid #dcdcde;
            padding: 24px;
            margin: 20px 0;
        }

        .rd3-appearance-defaults-section h2 {
            margin-top: 0;
            margin-bottom: 8px;
        }

        .rd3-appearance-defaults-description {
            color: #646970;
            margin-top: 0;
            margin-bottom: 24px;
        }

        .rd3-appearance-defaults-table {
            width: 100%;
            border-collapse: collapse;
        }

        .rd3-appearance-defaults-table th {
            width: 220px;
            text-align: left;
            vertical-align: top;
            padding: 12px 12px 12px 0;
        }

        .rd3-appearance-defaults-table td {
            padding: 12px 0;
        }

        .rd3-appearance-defaults-table tr {
            border-bottom: 1px solid #f0f0f1;
        }

        .rd3-appearance-defaults-table tr:last-child {
            border-bottom: 0;
        }

        .rd3-appearance-defaults-table
        input[type="number"] {
            width: 100px;
        }

        .rd3-appearance-defaults-table select {
            min-width: 180px;
        }

        .rd3-appearance-defaults-help {
            display: block;
            margin-top: 5px;
            color: #646970;
            font-size: 13px;
        }

        .rd3-appearance-defaults-footer {
            margin-top: 24px;
        }

        .rd3-appearance-defaults-clear {
            margin-left: 6px !important;
        }
        '
    );


    /* =====================================================
     * WORDPRESS COLOUR PICKER
     * ===================================================== */

    wp_add_inline_script(
        'wp-color-picker',
        "
        jQuery(document).ready(function($) {

            $('.rd3-appearance-defaults-color').wpColorPicker();

            $(document).on(
                'click',
                '.rd3-appearance-defaults-clear',
                function(event) {

                    event.preventDefault();

                    var target = $(this).data('target');
                    var input  = $('#' + target);

                    /*
                     * Clear the actual input.
                     */
                    input.val('');

                    /*
                     * Tell the native WordPress picker
                     * that the colour is now empty.
                     */
                    input.wpColorPicker(
                        'color',
                        ''
                    );

                    /*
                     * Ensure the visible colour swatch
                     * is also cleared.
                     */
                    input
                        .closest('.wp-picker-container')
                        .find('.wp-color-result')
                        .css(
                            'background-color',
                            ''
                        );

                    input.trigger('change');
                }
            );

        });
        "
    );
}

add_action(
    'admin_enqueue_scripts',
    'rd3_content_blocks_appearance_defaults_assets'
);


/* =========================================================
 * RENDER COLOUR FIELD
 * ========================================================= */

function rd3_content_blocks_render_appearance_defaults_color(
    $option,
    $type,
    $setting,
    $label
) {

    $value =
        isset(
            $option[ $type ][ $setting ]
        )
        ? $option[ $type ][ $setting ]
        : '';

    $field_id =
        'rd3-' .
        $type .
        '-' .
        $setting;

    ?>

    <tr>

        <th scope="row">

            <label for="<?php echo esc_attr( $field_id ); ?>">

                <?php echo esc_html( $label ); ?>

            </label>

        </th>

        <td>

            <input
                type="text"
                id="<?php echo esc_attr( $field_id ); ?>"
                name="<?php echo esc_attr(
                    RD3_CONTENT_BLOCKS_APPEARANCE_DEFAULTS_OPTION
                ); ?>[<?php echo esc_attr( $type ); ?>][<?php echo esc_attr( $setting ); ?>]"
                value="<?php echo esc_attr( $value ); ?>"
                class="rd3-appearance-defaults-color"
                data-default-color=""
            />

            <?php if ( '' !== $value ) : ?>

                <button
                    type="button"
                    class="button rd3-appearance-defaults-clear"
                    data-target="<?php echo esc_attr( $field_id ); ?>"
                >
                    Clear
                </button>

            <?php endif; ?>

            <span class="rd3-appearance-defaults-help">
                Clear to remove the global default and use the normal
                fallback.
            </span>

        </td>

    </tr>

    <?php
}


/* =========================================================
 * RENDER BORDER STYLE FIELD
 * ========================================================= */

function rd3_content_blocks_render_appearance_defaults_border_style(
    $option,
    $type
) {

    $value =
        isset(
            $option[ $type ]['border_style']
        )
        ? $option[ $type ]['border_style']
        : '';


    if (
        function_exists(
            'rd3_content_blocks_appearance_border_styles'
        )
    ) {

        $styles =
            rd3_content_blocks_appearance_border_styles();

    } else {

        $styles = array(
            'none'   => 'None',
            'solid'  => 'Solid',
            'dashed' => 'Dashed',
            'dotted' => 'Dotted',
            'double' => 'Double',
        );
    }

    ?>

    <tr>

        <th scope="row">

            <label for="rd3-<?php echo esc_attr( $type ); ?>-border-style">

                Border style

            </label>

        </th>

        <td>

            <select
                id="rd3-<?php echo esc_attr( $type ); ?>-border-style"
                name="<?php echo esc_attr(
                    RD3_CONTENT_BLOCKS_APPEARANCE_DEFAULTS_OPTION
                ); ?>[<?php echo esc_attr( $type ); ?>][border_style]"
            >

                <option value="">
                    Use normal fallback
                </option>

                <?php foreach ( $styles as $style => $label ) : ?>

                    <option
                        value="<?php echo esc_attr( $style ); ?>"
                        <?php selected(
                            $value,
                            $style
                        ); ?>
                    >
                        <?php echo esc_html( $label ); ?>
                    </option>

                <?php endforeach; ?>

            </select>

            <span class="rd3-appearance-defaults-help">
                Clear this setting to remove the global border style.
            </span>

        </td>

    </tr>

    <?php
}


/* =========================================================
 * RENDER NUMBER FIELD
 * ========================================================= */

function rd3_content_blocks_render_appearance_defaults_number(
    $option,
    $type,
    $setting,
    $label,
    $max,
    $help
) {

    $value =
        isset(
            $option[ $type ][ $setting ]
        )
        ? $option[ $type ][ $setting ]
        : '';

    ?>

    <tr>

        <th scope="row">

            <label for="rd3-<?php echo esc_attr(
                $type . '-' . $setting
            ); ?>">

                <?php echo esc_html( $label ); ?>

            </label>

        </th>

        <td>

            <input
                type="number"
                id="rd3-<?php echo esc_attr(
                    $type . '-' . $setting
                ); ?>"
                name="<?php echo esc_attr(
                    RD3_CONTENT_BLOCKS_APPEARANCE_DEFAULTS_OPTION
                ); ?>[<?php echo esc_attr( $type ); ?>][<?php echo esc_attr( $setting ); ?>]"
                value="<?php echo esc_attr( $value ); ?>"
                min="0"
                max="<?php echo esc_attr( $max ); ?>"
                step="1"
            />

            <span class="rd3-appearance-defaults-help">
                <?php echo esc_html( $help ); ?>
            </span>

        </td>

    </tr>

    <?php
}


/* =========================================================
 * RENDER APPEARANCE SECTION
 * ========================================================= */

function rd3_content_blocks_render_appearance_defaults_section(
    $option,
    $type,
    $title,
    $description
) {

    ?>

    <div class="rd3-appearance-defaults-section">

        <h3>
            <?php echo esc_html( $title ); ?>
        </h3>

        <p class="rd3-appearance-defaults-description">
            <?php echo esc_html( $description ); ?>
        </p>

        <table class="rd3-appearance-defaults-table">

            <tbody>

                <?php

                rd3_content_blocks_render_appearance_defaults_color(
                    $option,
                    $type,
                    'background',
                    'Background colour'
                );

                rd3_content_blocks_render_appearance_defaults_color(
                    $option,
                    $type,
                    'text_color',
                    'Text colour'
                );

                rd3_content_blocks_render_appearance_defaults_border_style(
                    $option,
                    $type
                );

                rd3_content_blocks_render_appearance_defaults_number(
                    $option,
                    $type,
                    'border_width',
                    'Border thickness',
                    50,
                    'Enter a value from 0–50 pixels. Clear to remove the global default.'
                );

                rd3_content_blocks_render_appearance_defaults_color(
                    $option,
                    $type,
                    'border_color',
                    'Border colour'
                );

                rd3_content_blocks_render_appearance_defaults_number(
                    $option,
                    $type,
                    'radius',
                    'Border radius',
                    100,
                    'Enter a value from 0–100 pixels. Clear to remove the global default.'
                );

                ?>

            </tbody>

        </table>

    </div>

    <?php
}


/* =========================================================
 * SETTINGS PAGE
 * ========================================================= */

function rd3_content_blocks_render_appearance_defaults_page() {

    if (
        ! current_user_can(
            'manage_options'
        )
    ) {
        return;
    }


    $option =
        rd3_content_blocks_get_appearance_defaults();

    ?>

    <div class="wrap rd3-appearance-defaults-wrap">

        <h1>
            RD3 Content Blocks — Appearance Defaults
        </h1>

        <p>
            Set the global appearance defaults used by RD3 Content Blocks
            and Rows when an individual item does not have its own
            appearance setting.
        </p>

        <p>
            Individual settings take priority over these defaults.
            Row Content Block overrides take priority over the individual
            Content Block settings.
        </p>


        <form
            method="post"
            action="options.php"
        >

            <?php

            settings_fields(
                'rd3_content_blocks_appearance_defaults'
            );

            ?>


            <?php

            rd3_content_blocks_render_appearance_defaults_section(
                $option,
                'block',
                'Content Block Defaults',
                'These settings provide the global appearance defaults for Content Blocks.'
            );

            ?>


            <?php

            rd3_content_blocks_render_appearance_defaults_section(
                $option,
                'row',
                'Row Defaults',
                'These settings provide the global appearance defaults for Rows.'
            );

            ?>


            <div class="rd3-appearance-defaults-footer">

                <?php

                submit_button(
                    'Save Appearance Defaults'
                );

                ?>

            </div>

        </form>

    </div>

    <?php
}

