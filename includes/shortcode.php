<?php

/**
 * RD3 Content Blocks
 *
 * Content Block and Row shortcodes.
 */


/*
 * Render a Content Block.
 */
function rd3_content_block_shortcode( $atts ) {

    $atts = shortcode_atts(
        array(
            'id' => 0,
        ),
        $atts,
        'rd3_block'
    );


    $block_id = absint(
        $atts['id']
    );


    if ( ! $block_id ) {
        return '';
    }


    if (
        'rd3_content_block'
        !== get_post_type( $block_id )
    ) {
        return '';
    }


    $content =
        get_post_field(
            'post_content',
            $block_id
        );


    if ( '' === trim( $content ) ) {
        return '';
    }


    /*
     * Prevent a Content Block from
     * containing another Content Block.
     */
    if (
        has_shortcode(
            $content,
            'rd3_block'
        )
    ) {
        return '';
    }


    return do_shortcode(
        $content
    );
}

add_shortcode(
    'rd3_block',
    'rd3_content_block_shortcode'
);


/*
 * Render a Row.
 */
function rd3_row_shortcode( $atts ) {

    $atts = shortcode_atts(
        array(
            'id' => 0,
        ),
        $atts,
        'rd3_row'
    );


    $row_id = absint(
        $atts['id']
    );


    if ( ! $row_id ) {
        return '';
    }


    if (
        'rd3_row'
        !== get_post_type( $row_id )
    ) {
        return '';
    }


    /*
     * Get Row layout.
     */
    $layout =
        get_post_meta(
            $row_id,
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
        $layout = 'inline';
    }


    /*
     * Get selected Content Blocks.
     */
    $positions =
        get_post_meta(
            $row_id,
            '_rd3_row_positions',
            true
        );


    if (
        ! is_array( $positions )
        ||
        empty( $positions )
    ) {
        return '';
    }


    /*
     * Sort positions numerically.
     */
    ksort(
        $positions,
        SORT_NUMERIC
    );


    /*
     * Start Row.
     */
    $output =
        '<div class="rd3-content-row rd3-row-' .
        esc_attr( $layout ) .
        '">';


    foreach (
        $positions as $position => $block_id
    ) {

        $block_id =
            absint( $block_id );


        if ( ! $block_id ) {
            continue;
        }


        /*
         * Make sure this is actually
         * a Content Block.
         */
        if (
            'rd3_content_block'
            !== get_post_type( $block_id )
        ) {
            continue;
        }


        /*
         * Render the Content Block.
         */
        $block_content =
            do_shortcode(
                '[rd3_block id="' .
                $block_id .
                '"]'
            );


        if (
            '' === trim(
                $block_content
            )
        ) {
            continue;
        }


        $output .=
            '<div class="rd3-content-row-item">';


        $output .=
            $block_content;


        $output .=
            '</div>';
    }


    $output .=
        '</div>';


    return $output;
}

add_shortcode(
    'rd3_row',
    'rd3_row_shortcode'
);