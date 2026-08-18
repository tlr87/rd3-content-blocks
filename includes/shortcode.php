<?php
/**
 * RD3 Content Blocks
 *
 * Content Block, Row and Advanced Row shortcodes.
 */


/*
 * ============================================================
 * CONTENT BLOCK
 * ============================================================
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

	if ( 'rd3_content_block' !== get_post_type( $block_id ) ) {
		return '';
	}

	$content = get_post_field( 'post_content', $block_id );

	if ( '' === trim( $content ) ) {
		return '';
	}


	/*
	 * Prevent Content Blocks containing another Content Block.
	 */
	if ( has_shortcode( $content, 'rd3_block' ) ) {
		return '';
	}


	/*
	 * Render Content Block content.
	 */
	$output = do_shortcode( $content );


	/*
	 * Add Edit link for logged-in users
	 * who can edit this Content Block.
	 */
	if (
		is_user_logged_in()
		&&
		current_user_can(
			'edit_post',
			$block_id
		)
	) {

		$edit_url = get_edit_post_link(
			$block_id,
			''
		);


		if ( $edit_url ) {

			$output .=
				'<div class="rd3-content-block-edit">';

			$output .=
				'<a href="' .
				esc_url( $edit_url ) .
				'">';

			$output .=
				'Edit Content Block';

			$output .=
				'</a>';

			$output .=
				'</div>';
		}
	}


	return $output;
}


add_shortcode(
	'rd3_block',
	'rd3_content_block_shortcode'
);


/*
 * ============================================================
 * ROW
 * ============================================================
 */
function rd3_row_shortcode( $atts ) {

	$atts = shortcode_atts(
		array(
			'id' => 0,
		),
		$atts,
		'rd3_row'
	);

	$row_id = absint( $atts['id'] );

	if ( ! $row_id ) {
		return '';
	}

	if ( 'rd3_row' !== get_post_type( $row_id ) ) {
		return '';
	}


	$layout = get_post_meta(
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


	$positions = get_post_meta(
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


	ksort(
		$positions,
		SORT_NUMERIC
	);


	$output =
		'<div class="rd3-content-row rd3-row-' .
		esc_attr( $layout ) .
		'">';


	foreach (
		$positions as $position => $block_id
	) {

		$block_id = absint( $block_id );


		if ( ! $block_id ) {
			continue;
		}


		if (
			'rd3_content_block'
			!== get_post_type( $block_id )
		) {

			continue;
		}


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


	$output .= '</div>';


	return $output;
}


add_shortcode(
	'rd3_row',
	'rd3_row_shortcode'
);


/*
 * ============================================================
 * ADVANCED ROW
 * ============================================================
 *
 * Example:
 *
 * [rd3_advanced_row brok="1" bett="1" prot="1" mana="1"]
 *
 */
function rd3_advanced_row_shortcode( $atts ) {


	/*
	 * Advanced Row uses dynamic attributes.
	 *
	 * Example:
	 * [rd3_advanced_row brok="1" bett="1" prot="1" mana="1"]
	 */

	if (
		empty( $atts )
		||
		! is_array( $atts )
	) {

		return '';
	}


	/*
	 * Sanitize attribute keys.
	 */
	$clean_atts = array();


	foreach (
		$atts as $key => $value
	) {

		$clean_atts[
			sanitize_key( $key )
		] = $value;
	}


	$atts = $clean_atts;


	/*
	 * Find the Advanced Row containing
	 * one of these IDs.
	 */
	$advanced_rows = get_posts(
		array(
			'post_type'      => 'rd3_advanced_row',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'ID',
			'order'          => 'ASC',
		)
	);


	if ( empty( $advanced_rows ) ) {
		return '';
	}


	$matched_blocks = array();

	$matched_row_id = 0;


	foreach (
		$advanced_rows as $advanced_row
	) {

		$saved_blocks = get_post_meta(
			$advanced_row->ID,
			'_rd3_advanced_row_blocks',
			true
		);


		if (
			! is_array(
				$saved_blocks
			)
		) {

			continue;
		}


		$candidate_blocks = array();


		foreach (
			$saved_blocks as $position => $block
		) {

			if (
				! is_array(
					$block
				)
			) {

				continue;
			}


			$block_id = isset(
				$block['block_id']
			)
				? absint(
					$block['block_id']
				)
				: 0;


			$shortcode_id = isset(
				$block['id']
			)
				? sanitize_key(
					$block['id']
				)
				: '';


			if (
				! $block_id
				||
				'' === $shortcode_id
			) {

				continue;
			}


			if (
				'rd3_content_block'
				!==
				get_post_type(
					$block_id
				)
			) {

				continue;
			}


			$candidate_blocks[
				$shortcode_id
			] = array(

				'position' =>
					absint(
						$position
					),

				'block_id' =>
					$block_id,
			);
		}


		/*
		 * Does this Advanced Row contain
		 * one of the shortcode IDs supplied?
		 */
		foreach (
			$atts as $attribute => $value
		) {

			if (
				isset(
					$candidate_blocks[
						$attribute
					]
				)
			) {

				$matched_blocks =
					$candidate_blocks;

				$matched_row_id =
					$advanced_row->ID;

				break 2;
			}
		}
	}


	if (
		! $matched_row_id
		||
		empty( $matched_blocks )
	) {

		return '';
	}


	/*
	 * Get the saved layout.
	 */
	$layout = get_post_meta(
		$matched_row_id,
		'_rd3_advanced_row_layout',
		true
	);


	/*
	 * Default to inline.
	 */
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
	 * Sort blocks by position.
	 */
	uasort(
		$matched_blocks,
		function( $a, $b ) {

			return $a['position']
				<=>
				$b['position'];
		}
	);


	/*
	 * Create row class.
	 */
	$row_class =
		'rd3-content-row rd3-advanced-row rd3-row-' .
		$layout;


	$output =
		'<div class="' .
		esc_attr(
			$row_class
		) .
		'">';


	/*
	 * Render selected blocks.
	 */
	foreach (
		$matched_blocks as $shortcode_id => $block
	) {


		/*
		 * Attribute not supplied = hidden.
		 */
		$show =
			isset(
				$atts[
					$shortcode_id
				]
			)
				? (string)
					$atts[
						$shortcode_id
					]
				: '0';


		/*
		 * Only "1" displays the block.
		 */
		if (
			'1' !== $show
		) {

			continue;
		}


		$block_id =
			absint(
				$block['block_id']
			);


		if ( ! $block_id ) {
			continue;
		}


		/*
		 * Render Content Block.
		 *
		 * The Content Block shortcode handles
		 * the logged-in Edit link.
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


	$output .= '</div>';


	/*
	 * Don't output an empty row.
	 */
	if (
		'<div class="' .
		esc_attr(
			$row_class
		) .
		'"></div>'
		===
		$output
	) {

		return '';
	}


	return $output;
}


add_shortcode(
	'rd3_advanced_row',
	'rd3_advanced_row_shortcode'
);