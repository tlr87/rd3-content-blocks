
<?php
/**
 * RD3 Content Blocks
 *
 * Appearance Controls
 *
 * Handles:
 * - Content Block appearance
 * - Row appearance
 * - Row Content Block overrides
 * - Global appearance default inheritance
 *
 * Inheritance:
 *
 * Content Block:
 *   Global Default
 *       ↓
 *   Content Block Setting
 *
 * Row:
 *   Global Default
 *       ↓
 *   Row Setting
 *
 * Content Blocks inside a Row:
 *   Global Default
 *       ↓
 *   Content Block Setting
 *       ↓
 *   Row Content Block Override
 *
 * Clearing a setting removes its post meta so the next
 * available default is used.
 */


/* =========================================================
 * BORDER STYLES
 * ========================================================= */

function rd3_content_blocks_appearance_border_styles() {

    return array(
        'none'   => 'None',
        'solid'  => 'Solid',
        'dashed' => 'Dashed',
        'dotted' => 'Dotted',
        'double' => 'Double',
    );
}


/* =========================================================
 * META KEYS
 * ========================================================= */

function rd3_content_blocks_appearance_meta_keys( $type ) {

    if ( 'row' === $type ) {

        return array(
            'background'   => '_rd3_row_background',
            'text_color'   => '_rd3_row_text_color',
            'border_style' => '_rd3_row_border_style',
            'border_width' => '_rd3_row_border_width',
            'border_color' => '_rd3_row_border_color',
            'radius'       => '_rd3_row_border_radius',

            'block_background'   => '_rd3_row_block_background',
            'block_text_color'   => '_rd3_row_block_text_color',
            'block_border_style' => '_rd3_row_block_border_style',
            'block_border_width' => '_rd3_row_block_border_width',
            'block_border_color' => '_rd3_row_block_border_color',
            'block_radius'       => '_rd3_row_block_border_radius',
        );
    }

    return array(
        'background'   => '_rd3_content_block_background',
        'text_color'   => '_rd3_content_block_text_color',
        'border_style' => '_rd3_content_block_border_style',
        'border_width' => '_rd3_content_block_border_width',
        'border_color' => '_rd3_content_block_border_color',
        'radius'       => '_rd3_content_block_border_radius',
    );
}


/* =========================================================
 * GLOBAL DEFAULT
 * ========================================================= */

function rd3_content_blocks_get_global_appearance_default(
    $type,
    $setting
) {

    if (
        function_exists(
            'rd3_content_blocks_get_appearance_default'
        )
    ) {

        return rd3_content_blocks_get_appearance_default(
            $type,
            $setting
        );
    }

    return '';
}


/* =========================================================
 * GET META VALUE
 * ========================================================= */

function rd3_content_blocks_get_appearance_meta_value(
    $post_id,
    $meta_key
) {

    $value = get_post_meta(
        $post_id,
        $meta_key,
        true
    );

    if ( '' === $value || null === $value ) {
        return '';
    }

    return $value;
}


/* =========================================================
 * SAVE / DELETE VALUE
 * ========================================================= */

function rd3_content_blocks_save_appearance_value(
    $post_id,
    $meta_key,
    $value
) {

    /*
     * Empty means:
     *
     * "Use inherited/default value."
     *
     * Delete the meta entirely so CSS inheritance
     * and global defaults can take over.
     */
    if ( '' === $value || null === $value ) {

        delete_post_meta(
            $post_id,
            $meta_key
        );

        return;
    }

    update_post_meta(
        $post_id,
        $meta_key,
        $value
    );
}


/* =========================================================
 * META BOXES
 * ========================================================= */

function rd3_content_blocks_add_appearance_meta_boxes() {

    add_meta_box(
        'rd3-content-block-appearance',
        'Appearance',
        'rd3_content_blocks_render_appearance_meta_box',
        'rd3_content_block',
        'normal',
        'default'
    );

    add_meta_box(
        'rd3-row-appearance',
        'Appearance',
        'rd3_content_blocks_render_appearance_meta_box',
        'rd3_row',
        'normal',
        'default'
    );
}

add_action(
    'add_meta_boxes',
    'rd3_content_blocks_add_appearance_meta_boxes'
);


/* =========================================================
 * COLOUR FIELD
 * ========================================================= */

function rd3_content_blocks_render_appearance_colour(
    $label,
    $name,
    $value,
    $description = ''
) {
    ?>

    <tr>

        <th scope="row">

            <label for="<?php echo esc_attr( $name ); ?>">

                <?php echo esc_html( $label ); ?>

            </label>

        </th>

        <td>

            <input
                type="text"
                id="<?php echo esc_attr( $name ); ?>"
                name="<?php echo esc_attr( $name ); ?>"
                value="<?php echo esc_attr( $value ); ?>"
                class="rd3-appearance-color"
                data-default-color=""
            />

            <?php if ( '' !== $value ) : ?>

                <button
                    type="button"
                    class="button rd3-appearance-clear-color"
                    data-target="<?php echo esc_attr( $name ); ?>"
                >
                    Clear
                </button>

            <?php endif; ?>

            <?php if ( $description ) : ?>

                <p class="description">
                    <?php echo esc_html( $description ); ?>
                </p>

            <?php else : ?>

                <p class="description">
                    Clear to use the default.
                </p>

            <?php endif; ?>

        </td>

    </tr>

    <?php
}


/* =========================================================
 * BORDER STYLE
 * ========================================================= */

function rd3_content_blocks_render_appearance_border_style(
    $name,
    $value,
    $allow_default = true
) {

    $styles =
        rd3_content_blocks_appearance_border_styles();
    ?>

    <tr>

        <th scope="row">

            <label for="<?php echo esc_attr( $name ); ?>">

                Border style

            </label>

        </th>

        <td>

            <select
                id="<?php echo esc_attr( $name ); ?>"
                name="<?php echo esc_attr( $name ); ?>"
            >

                <?php if ( $allow_default ) : ?>

                    <option
                        value=""
                        <?php selected( $value, '' ); ?>
                    >
                        Use default
                    </option>

                <?php endif; ?>

                <?php foreach ( $styles as $style => $label ) : ?>

                    <option
                        value="<?php echo esc_attr( $style ); ?>"
                        <?php selected( $value, $style ); ?>
                    >
                        <?php echo esc_html( $label ); ?>
                    </option>

                <?php endforeach; ?>

            </select>

        </td>

    </tr>

    <?php
}


/* =========================================================
 * NUMBER FIELD
 * ========================================================= */

function rd3_content_blocks_render_appearance_number(
    $label,
    $name,
    $value,
    $min,
    $max,
    $unit = 'px',
    $allow_default = true
) {
    ?>

    <tr>

        <th scope="row">

            <label for="<?php echo esc_attr( $name ); ?>">

                <?php echo esc_html( $label ); ?>

            </label>

        </th>

        <td>

            <input
                type="number"
                id="<?php echo esc_attr( $name ); ?>"
                name="<?php echo esc_attr( $name ); ?>"
                value="<?php echo esc_attr( $value ); ?>"
                min="<?php echo esc_attr( $min ); ?>"
                max="<?php echo esc_attr( $max ); ?>"
                step="1"
                style="width:100px;"
            />

            <?php if ( $allow_default && '' === $value ) : ?>

                <span class="description">
                    Use default
                </span>

            <?php else : ?>

                <span class="description">
                    <?php echo esc_html( $unit ); ?>
                </span>

            <?php endif; ?>

        </td>

    </tr>

    <?php
}


/* =========================================================
 * STANDARD APPEARANCE FIELDS
 * ========================================================= */

function rd3_content_blocks_render_standard_appearance_fields(
    $post_id,
    $type
) {

    $keys =
        rd3_content_blocks_appearance_meta_keys(
            $type
        );

    $background =
        rd3_content_blocks_get_appearance_meta_value(
            $post_id,
            $keys['background']
        );

    $text_color =
        rd3_content_blocks_get_appearance_meta_value(
            $post_id,
            $keys['text_color']
        );

    $border_style =
        rd3_content_blocks_get_appearance_meta_value(
            $post_id,
            $keys['border_style']
        );

    $border_width =
        rd3_content_blocks_get_appearance_meta_value(
            $post_id,
            $keys['border_width']
        );

    $border_color =
        rd3_content_blocks_get_appearance_meta_value(
            $post_id,
            $keys['border_color']
        );

    $radius =
        rd3_content_blocks_get_appearance_meta_value(
            $post_id,
            $keys['radius']
        );
    ?>

    <table class="form-table">

        <?php

        rd3_content_blocks_render_appearance_colour(
            'Background colour',
            'rd3_appearance_background',
            $background
        );

        rd3_content_blocks_render_appearance_colour(
            'Text colour',
            'rd3_appearance_text_color',
            $text_color
        );

        rd3_content_blocks_render_appearance_border_style(
            'rd3_appearance_border_style',
            $border_style,
            true
        );

        rd3_content_blocks_render_appearance_number(
            'Border thickness',
            'rd3_appearance_border_width',
            $border_width,
            0,
            50,
            'px',
            true
        );

        rd3_content_blocks_render_appearance_colour(
            'Border colour',
            'rd3_appearance_border_color',
            $border_color
        );

        rd3_content_blocks_render_appearance_number(
            'Border radius',
            'rd3_appearance_radius',
            $radius,
            0,
            100,
            'px',
            true
        );

        ?>

    </table>

    <?php
}


/* =========================================================
 * ROW CONTENT BLOCK OVERRIDES
 * ========================================================= */

function rd3_content_blocks_render_row_block_overrides(
    $post_id
) {

    $keys =
        rd3_content_blocks_appearance_meta_keys(
            'row'
        );

    $background =
        rd3_content_blocks_get_appearance_meta_value(
            $post_id,
            $keys['block_background']
        );

    $text_color =
        rd3_content_blocks_get_appearance_meta_value(
            $post_id,
            $keys['block_text_color']
        );

    $border_style =
        rd3_content_blocks_get_appearance_meta_value(
            $post_id,
            $keys['block_border_style']
        );

    $border_width =
        rd3_content_blocks_get_appearance_meta_value(
            $post_id,
            $keys['block_border_width']
        );

    $border_color =
        rd3_content_blocks_get_appearance_meta_value(
            $post_id,
            $keys['block_border_color']
        );

    $radius =
        rd3_content_blocks_get_appearance_meta_value(
            $post_id,
            $keys['block_radius']
        );
    ?>

    <hr>

    <h3>
        Content Block Overrides
    </h3>

    <p>
        These settings override the appearance of Content Blocks
        inside this Row. Clear a setting to use the Content Block's
        own setting or the global default.
    </p>

    <table class="form-table">

        <?php

        rd3_content_blocks_render_appearance_colour(
            'Background colour',
            'rd3_row_block_background',
            $background,
            'Clear to use the Content Block setting or global default.'
        );

        rd3_content_blocks_render_appearance_colour(
            'Text colour',
            'rd3_row_block_text_color',
            $text_color,
            'Clear to use the Content Block setting or global default.'
        );

        rd3_content_blocks_render_appearance_border_style(
            'rd3_row_block_border_style',
            $border_style,
            true
        );

        rd3_content_blocks_render_appearance_number(
            'Border thickness',
            'rd3_row_block_border_width',
            $border_width,
            0,
            50,
            'px',
            true
        );

        rd3_content_blocks_render_appearance_colour(
            'Border colour',
            'rd3_row_block_border_color',
            $border_color,
            'Clear to use the Content Block setting or global default.'
        );

        rd3_content_blocks_render_appearance_number(
            'Border radius',
            'rd3_row_block_radius',
            $radius,
            0,
            100,
            'px',
            true
        );

        ?>

    </table>

    <?php
}


/* =========================================================
 * META BOX RENDER
 * ========================================================= */

function rd3_content_blocks_render_appearance_meta_box(
    $post
) {

    wp_nonce_field(
        'rd3_content_blocks_save_appearance',
        'rd3_content_blocks_appearance_nonce'
    );

    ?>

    <div class="rd3-content-blocks-appearance">

        <?php

        $type =
            'rd3_row' === $post->post_type
            ? 'row'
            : 'block';

        rd3_content_blocks_render_standard_appearance_fields(
            $post->ID,
            $type
        );

        if ( 'row' === $type ) {

            rd3_content_blocks_render_row_block_overrides(
                $post->ID
            );
        }

        ?>

    </div>

    <?php
}


/* =========================================================
 * SAVE APPEARANCE
 * ========================================================= */

function rd3_content_blocks_save_appearance(
    $post_id
) {

    $post_type = get_post_type(
        $post_id
    );

    if (
        'rd3_content_block' !== $post_type &&
        'rd3_row' !== $post_type
    ) {
        return;
    }


    if (
        defined( 'DOING_AUTOSAVE' ) &&
        DOING_AUTOSAVE
    ) {
        return;
    }


    if (
        wp_is_post_revision(
            $post_id
        )
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
        ! isset(
            $_POST['rd3_content_blocks_appearance_nonce']
        )
    ) {
        return;
    }


    if (
        ! wp_verify_nonce(
            sanitize_text_field(
                wp_unslash(
                    $_POST[
                        'rd3_content_blocks_appearance_nonce'
                    ]
                )
            ),
            'rd3_content_blocks_save_appearance'
        )
    ) {
        return;
    }


    $type =
        'rd3_row' === $post_type
        ? 'row'
        : 'block';

    $keys =
        rd3_content_blocks_appearance_meta_keys(
            $type
        );


    /* =====================================================
     * BACKGROUND
     * ===================================================== */

    $background = '';

    if (
        isset(
            $_POST['rd3_appearance_background']
        )
    ) {

        $background =
            sanitize_hex_color(
                wp_unslash(
                    $_POST[
                        'rd3_appearance_background'
                    ]
                )
            );

        if ( ! $background ) {
            $background = '';
        }
    }

    rd3_content_blocks_save_appearance_value(
        $post_id,
        $keys['background'],
        $background
    );


    /* =====================================================
     * TEXT COLOUR
     * ===================================================== */

    $text_color = '';

    if (
        isset(
            $_POST['rd3_appearance_text_color']
        )
    ) {

        $text_color =
            sanitize_hex_color(
                wp_unslash(
                    $_POST[
                        'rd3_appearance_text_color'
                    ]
                )
            );

        if ( ! $text_color ) {
            $text_color = '';
        }
    }

    rd3_content_blocks_save_appearance_value(
        $post_id,
        $keys['text_color'],
        $text_color
    );


    /* =====================================================
     * BORDER STYLE
     * ===================================================== */

    $border_style = '';

    if (
        isset(
            $_POST['rd3_appearance_border_style']
        )
    ) {

        $border_style =
            sanitize_key(
                wp_unslash(
                    $_POST[
                        'rd3_appearance_border_style'
                    ]
                )
            );

        $allowed_styles =
            rd3_content_blocks_appearance_border_styles();

        if (
            ! isset(
                $allowed_styles[
                    $border_style
                ]
            )
        ) {
            $border_style = '';
        }
    }

    rd3_content_blocks_save_appearance_value(
        $post_id,
        $keys['border_style'],
        $border_style
    );


    /* =====================================================
     * BORDER WIDTH
     * ===================================================== */

    $border_width = '';

    if (
        isset(
            $_POST['rd3_appearance_border_width']
        )
    ) {

        $raw_width =
            trim(
                wp_unslash(
                    $_POST[
                        'rd3_appearance_border_width'
                    ]
                )
            );

        if ( '' !== $raw_width ) {

            $border_width =
                absint(
                    $raw_width
                );

            $border_width =
                min(
                    50,
                    $border_width
                );
        }
    }

    rd3_content_blocks_save_appearance_value(
        $post_id,
        $keys['border_width'],
        $border_width
    );


    /* =====================================================
     * BORDER COLOUR
     * ===================================================== */

    $border_color = '';

    if (
        isset(
            $_POST['rd3_appearance_border_color']
        )
    ) {

        $border_color =
            sanitize_hex_color(
                wp_unslash(
                    $_POST[
                        'rd3_appearance_border_color'
                    ]
                )
            );

        if ( ! $border_color ) {
            $border_color = '';
        }
    }

    rd3_content_blocks_save_appearance_value(
        $post_id,
        $keys['border_color'],
        $border_color
    );


    /* =====================================================
     * BORDER RADIUS
     * ===================================================== */

    $radius = '';

    if (
        isset(
            $_POST['rd3_appearance_radius']
        )
    ) {

        $raw_radius =
            trim(
                wp_unslash(
                    $_POST[
                        'rd3_appearance_radius'
                    ]
                )
            );

        if ( '' !== $raw_radius ) {

            $radius =
                absint(
                    $raw_radius
                );

            $radius =
                min(
                    100,
                    $radius
                );
        }
    }

    rd3_content_blocks_save_appearance_value(
        $post_id,
        $keys['radius'],
        $radius
    );


    /* =====================================================
     * ROW CONTENT BLOCK OVERRIDES
     * ===================================================== */

    if ( 'row' !== $type ) {
        return;
    }


    /* Background override. */

    $value = '';

    if (
        isset(
            $_POST['rd3_row_block_background']
        )
    ) {

        $value =
            sanitize_hex_color(
                wp_unslash(
                    $_POST[
                        'rd3_row_block_background'
                    ]
                )
            );

        if ( ! $value ) {
            $value = '';
        }
    }

    rd3_content_blocks_save_appearance_value(
        $post_id,
        $keys['block_background'],
        $value
    );


    /* Text colour override. */

    $value = '';

    if (
        isset(
            $_POST['rd3_row_block_text_color']
        )
    ) {

        $value =
            sanitize_hex_color(
                wp_unslash(
                    $_POST[
                        'rd3_row_block_text_color'
                    ]
                )
            );

        if ( ! $value ) {
            $value = '';
        }
    }

    rd3_content_blocks_save_appearance_value(
        $post_id,
        $keys['block_text_color'],
        $value
    );


    /* Border style override. */

    $value = '';

    if (
        isset(
            $_POST['rd3_row_block_border_style']
        )
    ) {

        $value =
            sanitize_key(
                wp_unslash(
                    $_POST[
                        'rd3_row_block_border_style'
                    ]
                )
            );

        $allowed_styles =
            rd3_content_blocks_appearance_border_styles();

        if (
            ! isset(
                $allowed_styles[
                    $value
                ]
            )
        ) {
            $value = '';
        }
    }

    rd3_content_blocks_save_appearance_value(
        $post_id,
        $keys['block_border_style'],
        $value
    );


    /* Border width override. */

    $value = '';

    if (
        isset(
            $_POST['rd3_row_block_border_width']
        )
    ) {

        $raw_value =
            trim(
                wp_unslash(
                    $_POST[
                        'rd3_row_block_border_width'
                    ]
                )
            );

        if ( '' !== $raw_value ) {

            $value =
                absint(
                    $raw_value
                );

            $value =
                min(
                    50,
                    $value
                );
        }
    }

    rd3_content_blocks_save_appearance_value(
        $post_id,
        $keys['block_border_width'],
        $value
    );


    /* Border colour override. */

    $value = '';

    if (
        isset(
            $_POST['rd3_row_block_border_color']
        )
    ) {

        $value =
            sanitize_hex_color(
                wp_unslash(
                    $_POST[
                        'rd3_row_block_border_color'
                    ]
                )
            );

        if ( ! $value ) {
            $value = '';
        }
    }

    rd3_content_blocks_save_appearance_value(
        $post_id,
        $keys['block_border_color'],
        $value
    );


    /* Radius override. */

    $value = '';

    if (
        isset(
            $_POST['rd3_row_block_radius']
        )
    ) {

        $raw_value =
            trim(
                wp_unslash(
                    $_POST[
                        'rd3_row_block_radius'
                    ]
                )
            );

        if ( '' !== $raw_value ) {

            $value =
                absint(
                    $raw_value
                );

            $value =
                min(
                    100,
                    $value
                );
        }
    }

    rd3_content_blocks_save_appearance_value(
        $post_id,
        $keys['block_radius'],
        $value
    );
}

add_action(
    'save_post',
    'rd3_content_blocks_save_appearance'
);


/* =========================================================
 * RESOLVE APPEARANCE VALUE
 * ========================================================= */

function rd3_content_blocks_resolve_appearance_value(
    $post_id,
    $meta_key,
    $type,
    $setting
) {

    $value =
        rd3_content_blocks_get_appearance_meta_value(
            $post_id,
            $meta_key
        );

    if ( '' !== $value ) {
        return $value;
    }

    return
        rd3_content_blocks_get_global_appearance_default(
            $type,
            $setting
        );
}


/* =========================================================
 * FRONT-END CSS VARIABLES
 * ========================================================= */

function rd3_content_blocks_get_appearance_style(
    $post_id,
    $type
) {

    $keys =
        rd3_content_blocks_appearance_meta_keys(
            $type
        );

    $styles = array();

    $prefix =
        'row' === $type
        ? 'row'
        : 'block';


    /* =====================================================
     * BACKGROUND
     * ===================================================== */

    $background =
        rd3_content_blocks_resolve_appearance_value(
            $post_id,
            $keys['background'],
            $type,
            'background'
        );

    if ( '' !== $background ) {

        $styles[] =
            '--rd3-' .
            $prefix .
            '-background:' .
            $background;
    }


    /* =====================================================
     * TEXT
     * ===================================================== */

    $text_color =
        rd3_content_blocks_resolve_appearance_value(
            $post_id,
            $keys['text_color'],
            $type,
            'text_color'
        );

    if ( '' !== $text_color ) {

        $styles[] =
            '--rd3-' .
            $prefix .
            '-text:' .
            $text_color;
    }


    /* =====================================================
     * BORDER STYLE
     * ===================================================== */

    $border_style =
        rd3_content_blocks_resolve_appearance_value(
            $post_id,
            $keys['border_style'],
            $type,
            'border_style'
        );

    if ( '' !== $border_style ) {

        $styles[] =
            '--rd3-' .
            $prefix .
            '-border-style:' .
            $border_style;
    }


    /* =====================================================
     * BORDER WIDTH
     * ===================================================== */

    $border_width =
        rd3_content_blocks_resolve_appearance_value(
            $post_id,
            $keys['border_width'],
            $type,
            'border_width'
        );

    if ( '' !== $border_width ) {

        $styles[] =
            '--rd3-' .
            $prefix .
            '-border-width:' .
            absint( $border_width ) .
            'px';
    }


    /* =====================================================
     * BORDER COLOUR
     * ===================================================== */

    $border_color =
        rd3_content_blocks_resolve_appearance_value(
            $post_id,
            $keys['border_color'],
            $type,
            'border_color'
        );

    if ( '' !== $border_color ) {

        $styles[] =
            '--rd3-' .
            $prefix .
            '-border-color:' .
            $border_color;
    }


    /* =====================================================
     * RADIUS
     * ===================================================== */

    $radius =
        rd3_content_blocks_resolve_appearance_value(
            $post_id,
            $keys['radius'],
            $type,
            'radius'
        );

    if ( '' !== $radius ) {

        $styles[] =
            '--rd3-' .
            $prefix .
            '-radius:' .
            absint( $radius ) .
            'px';
    }


    /* =====================================================
     * ROW CONTENT BLOCK OVERRIDES
     * ===================================================== */

    if ( 'row' === $type ) {

        /*
         * IMPORTANT:
         *
         * Only output an override variable when an
         * actual Row override exists.
         *
         * If it is empty, the Content Block variable
         * underneath remains active.
         */


        $override_background =
            rd3_content_blocks_get_appearance_meta_value(
                $post_id,
                $keys['block_background']
            );

        if ( '' !== $override_background ) {

            $styles[] =
                '--rd3-row-block-background:' .
                $override_background;
        }


        $override_text =
            rd3_content_blocks_get_appearance_meta_value(
                $post_id,
                $keys['block_text_color']
            );

        if ( '' !== $override_text ) {

            $styles[] =
                '--rd3-row-block-text:' .
                $override_text;
        }


        $override_border_style =
            rd3_content_blocks_get_appearance_meta_value(
                $post_id,
                $keys['block_border_style']
            );

        if ( '' !== $override_border_style ) {

            $styles[] =
                '--rd3-row-block-border-style:' .
                $override_border_style;
        }


        $override_border_width =
            rd3_content_blocks_get_appearance_meta_value(
                $post_id,
                $keys['block_border_width']
            );

        if ( '' !== $override_border_width ) {

            $styles[] =
                '--rd3-row-block-border-width:' .
                absint(
                    $override_border_width
                ) .
                'px';
        }


        $override_border_color =
            rd3_content_blocks_get_appearance_meta_value(
                $post_id,
                $keys['block_border_color']
            );

        if ( '' !== $override_border_color ) {

            $styles[] =
                '--rd3-row-block-border-color:' .
                $override_border_color;
        }


        $override_radius =
            rd3_content_blocks_get_appearance_meta_value(
                $post_id,
                $keys['block_radius']
            );

        if ( '' !== $override_radius ) {

            $styles[] =
                '--rd3-row-block-radius:' .
                absint(
                    $override_radius
                ) .
                'px';
        }
    }


    if ( empty( $styles ) ) {
        return '';
    }


    return ' style="' .
        esc_attr(
            implode( ';', $styles )
        ) .
        ';"';
}


/* =========================================================
 * ADMIN ASSETS
 * ========================================================= */

function rd3_content_blocks_appearance_admin_assets(
    $hook
) {

    if (
        ! in_array(
            $hook,
            array(
                'post.php',
                'post-new.php',
            ),
            true
        )
    ) {
        return;
    }


    /*
     * Get the current post type.
     */
    $post_type = '';


    if ( isset( $_GET['post'] ) ) {

        $post_id =
            absint(
                $_GET['post']
            );

        if ( $post_id ) {

            $post_type =
                get_post_type(
                    $post_id
                );
        }
    }


    if ( ! $post_type && isset( $_GET['post_type'] ) ) {

        $post_type =
            sanitize_key(
                wp_unslash(
                    $_GET['post_type']
                )
            );
    }


    if (
        ! in_array(
            $post_type,
            array(
                'rd3_content_block',
                'rd3_row',
            ),
            true
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


    /*
     * Admin styling.
     */
    wp_add_inline_style(
        'wp-color-picker',
        '
        .rd3-content-blocks-appearance {
            max-width: 900px;
        }

        .rd3-content-blocks-appearance hr {
            margin: 30px 0;
        }

        .rd3-content-blocks-appearance h3 {
            margin-bottom: 8px;
        }

        .rd3-content-blocks-appearance
        .form-table th {
            width: 220px;
        }

        .rd3-content-blocks-appearance
        .description {
            margin-top: 6px;
        }

        .rd3-appearance-clear-color {
            margin-left: 6px !important;
        }
        '
    );


    /*
     * Initialise WordPress colour pickers.
     */
    wp_add_inline_script(
        'wp-color-picker',
        "
        jQuery(document).ready(function($) {

            $('.rd3-appearance-color').wpColorPicker();

            $(document).on(
                'click',
                '.rd3-appearance-clear-color',
                function(event) {

                    event.preventDefault();

                    var target = $(this).data('target');
                    var input  = $('#' + target);

                    input.val('');

                    input.wpColorPicker(
                        'color',
                        ''
                    );

                    input
                        .closest('.wp-picker-container')
                        .find('.wp-color-result')
                        .css('background-color', '');

                    input.trigger('change');

                }
            );

        });
        "
    );
}

add_action(
    'admin_enqueue_scripts',
    'rd3_content_blocks_appearance_admin_assets'
);

