<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/*
 * RD3 Content Block shortcode.
 *
 * Usage:
 * [rd3_block id="123"]
 */
function rd3_content_block_shortcode( $atts ) {

    $atts = shortcode_atts(
        array(
            'id' => 0,
        ),
        $atts,
        'rd3_block'
    );

    $block_id = absint( $atts['id'] );

    if ( ! $block_id ) {
        return '';
    }

    $block = get_post( $block_id );

    if ( ! $block ) {
        return '';
    }

    if ( 'rd3_content_block' !== $block->post_type ) {
        return '';
    }

    if ( 'publish' !== $block->post_status ) {
        return '';
    }

    return apply_filters(
        'the_content',
        $block->post_content
    );
}

add_shortcode(
    'rd3_block',
    'rd3_content_block_shortcode'
);