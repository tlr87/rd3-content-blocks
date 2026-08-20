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


	$block_id = absint(
		$atts['id']
	);


	if ( ! $block_id ) {
		return '';
	}


	if (
		'rd3_content_block'
		!== get_post_type(
			$block_id
		)
	) {

		return '';
	}


	/*
	 * Get Content Block title.
	 */

	$title = get_the_title(
		$block_id
	);


	/*
	 * Get Content Block content.
	 */

	$content = get_post_field(
		'post_content',
		$block_id
	);


	/*
	 * ========================================================
	 * GET LINK SETTINGS
	 * ========================================================
	 */

	$link_type = get_post_meta(
		$block_id,
		'_rd3_content_block_link_type',
		true
	);


	$link_page_id = absint(
		get_post_meta(
			$block_id,
			'_rd3_content_block_link_page_id',
			true
		)
	);


	$link_url = get_post_meta(
		$block_id,
		'_rd3_content_block_link_url',
		true
	);


	$link_text = get_post_meta(
		$block_id,
		'_rd3_content_block_link_text',
		true
	);


	$link_target = get_post_meta(
		$block_id,
		'_rd3_content_block_link_target',
		true
	);


	/*
	 * ========================================================
	 * VALIDATE LINK TYPE
	 * ========================================================
	 */

	if (
		! in_array(
			$link_type,
			array(
				'internal',
				'external',
			),
			true
		)
	) {

		/*
		 * Backwards compatibility.
		 *
		 * Existing Content Blocks that have a URL
		 * are treated as external links.
		 */

		if (
			'' !== trim(
				$link_url
			)
		) {

			$link_type = 'external';

		} else {

			$link_type = 'internal';
		}
	}


	/*
	 * ========================================================
	 * VALIDATE LINK TARGET
	 * ========================================================
	 */

	if (
		! in_array(
			$link_target,
			array(
				'_self',
				'_blank',
			),
			true
		)
	) {

		$link_target = '_self';
	}


	/*
	 * ========================================================
	 * DETERMINE FINAL LINK URL
	 * ========================================================
	 */

	$final_link_url = '';


	/*
	 * INTERNAL PAGE
	 */

	if (
		'internal'
		=== $link_type
		&&
		$link_page_id
	) {

		$final_link_url = get_permalink(
			$link_page_id
		);
	}


	/*
	 * EXTERNAL URL
	 */

	if (
		'external'
		=== $link_type
		&&
		'' !== trim(
			$link_url
		)
	) {

		$final_link_url = $link_url;
	}


	/*
	 * ========================================================
	 * DETERMINE WHETHER ANYTHING EXISTS
	 * ========================================================
	 */

	if (
		'' === trim(
			$content
		)
		&&
		'' === trim(
			$title
		)
		&&
		(
			'' === trim(
				$link_text
			)
			||
			'' === trim(
				$final_link_url
			)
		)
	) {

		return '';
	}


	/*
	 * ========================================================
	 * PREVENT NESTED CONTENT BLOCKS
	 * ========================================================
	 */

	if (
		has_shortcode(
			$content,
			'rd3_block'
		)
	) {

		return '';
	}


	/*
	 * ========================================================
	 * START CONTENT BLOCK
	 * ========================================================
	 */

	$output =
		'<div class="rd3-content-block">';


	/*
	 * ========================================================
	 * TITLE
	 * ========================================================
	 */

	if (
		'' !== trim(
			$title
		)
	) {

		$output .=
			'<h2 class="rd3-content-block-title">';

		$output .=
			esc_html(
				$title
			);

		$output .=
			'</h2>';
	}


	/*
	 * ========================================================
	 * CONTENT
	 * ========================================================
	 */

	if (
		'' !== trim(
			$content
		)
	) {

		$output .=
			'<div class="rd3-content-block-content">';

		$output .=
			do_shortcode(
				$content
			);

		$output .=
			'</div>';
	}


	/*
	 * ========================================================
	 * LINK
	 * ========================================================
	 *
	 * The link is rendered only when both:
	 *
	 * - Link Text exists
	 * - A valid final URL exists
	 *
	 */

	if (
		'' !== trim(
			$link_text
		)
		&&
		'' !== trim(
			$final_link_url
		)
	) {

		$output .=
			'<div class="rd3-content-block-link">';


		$output .=
			'<a href="' .
			esc_url(
				$final_link_url
			) .
			'" target="' .
			esc_attr(
				$link_target
			) .
			'"';


		/*
		 * Security for New Tab.
		 */

		if (
			'_blank'
			=== $link_target
		) {

			$output .=
				' rel="noopener noreferrer"';
		}


		$output .=
			'>';


		$output .=
			esc_html(
				$link_text
			);


		$output .=
			'</a>';


		$output .=
			'</div>';
	}


	/*
	 * ========================================================
	 * EDIT LINK
	 * ========================================================
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
				esc_url(
					$edit_url
				) .
				'">';


			$output .=
				'Edit Content Block';


			$output .=
				'</a>';


			$output .=
				'</div>';
		}
	}


	/*
	 * ========================================================
	 * CLOSE CONTENT BLOCK
	 * ========================================================
	 */

	$output .=
		'</div>';


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


	$row_id = absint(
		$atts['id']
	);


	if ( ! $row_id ) {
		return '';
	}


	if (
		'rd3_row'
		!== get_post_type(
			$row_id
		)
	) {

		return '';
	}


	/*
	 * Get Row layout.
	 */

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


	/*
	 * Get Row positions.
	 */

	$positions = get_post_meta(
		$row_id,
		'_rd3_row_positions',
		true
	);


	if (
		! is_array(
			$positions
		)
		||
		empty(
			$positions
		)
	) {

		return '';
	}


	ksort(
		$positions,
		SORT_NUMERIC
	);


	/*
	 * Create outer wrapper.
	 */

	$output =
		'<div class="rd3-content-row-wrapper">';


	/*
	 * Add Edit Row link.
	 */

	if (
		is_user_logged_in()
		&&
		current_user_can(
			'edit_post',
			$row_id
		)
	) {

		$edit_url = get_edit_post_link(
			$row_id,
			''
		);


		if ( $edit_url ) {

			$output .=
				'<div class="rd3-content-row-edit">';


			$output .=
				'<a href="' .
				esc_url(
					$edit_url
				) .
				'">';


			$output .=
				'Edit Row';


			$output .=
				'</a>';


			$output .=
				'</div>';
		}
	}


	/*
	 * Create actual Row.
	 */

	$output .=
		'<div class="rd3-content-row rd3-row-' .
		esc_attr(
			$layout
		) .
		'">';


	/*
	 * Render Content Blocks.
	 */

	foreach (
		$positions as $position => $block_id
	) {

		$block_id =
			absint(
				$block_id
			);


		if ( ! $block_id ) {
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


		/*
		 * Render Content Block.
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


	/*
	 * Close actual Row.
	 */

	$output .=
		'</div>';


	/*
	 * Close outer wrapper.
	 */

	$output .=
		'</div>';


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
 * [rd3_advanced_row id="2563" brok="1" bett="1" prot="1" mana="1"]
 *
 */


/**
 * Render an Advanced Row.
 *
 * The Advanced Row ID is supplied directly through the
 * shortcode "id" attribute.
 */
function rd3_advanced_row_shortcode( $atts ) {

	/*
	 * --------------------------------------------------------
	 * NORMALISE ATTRIBUTES
	 * --------------------------------------------------------
	 */

	if (
		empty( $atts )
		||
		! is_array( $atts )
	) {

		return '';
	}


	$clean_atts = array();


	foreach (
		$atts as $key => $value
	) {

		$clean_atts[
			sanitize_key(
				$key
			)
		] = $value;
	}


	$atts = $clean_atts;


	/*
	 * --------------------------------------------------------
	 * GET ADVANCED ROW ID
	 * --------------------------------------------------------
	 *
	 * Example:
	 *
	 * [rd3_advanced_row id="2563"]
	 *
	 */

	$matched_row_id =
		isset(
			$atts['id']
		)
			? absint(
				$atts['id']
			)
			: 0;


	if ( ! $matched_row_id ) {

		return '';
	}


	/*
	 * --------------------------------------------------------
	 * VALIDATE ADVANCED ROW
	 * --------------------------------------------------------
	 */

	if (
		'rd3_advanced_row'
		!==
		get_post_type(
			$matched_row_id
		)
	) {

		return '';
	}


	/*
	 * Only published Advanced Rows
	 * may be rendered.
	 */

	if (
		'publish'
		!==
		get_post_status(
			$matched_row_id
		)
	) {

		return '';
	}


	/*
	 * --------------------------------------------------------
	 * GET SAVED BLOCKS
	 * --------------------------------------------------------
	 */

	$saved_blocks =
		get_post_meta(
			$matched_row_id,
			'_rd3_advanced_row_blocks',
			true
		);


	if (
		! is_array(
			$saved_blocks
		)
	) {

		return '';
	}


	/*
	 * --------------------------------------------------------
	 * BUILD AVAILABLE BLOCKS
	 * --------------------------------------------------------
	 */

	$matched_blocks = array();


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


		$block_id =
			isset(
				$block['block_id']
			)
				? absint(
					$block['block_id']
				)
				: 0;


		$shortcode_id =
			isset(
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


		/*
		 * Make sure the referenced Content Block
		 * still exists and is the correct post type.
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


		$matched_blocks[
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


	if (
		empty(
			$matched_blocks
		)
	) {

		return '';
	}


	/*
	 * --------------------------------------------------------
	 * GET SAVED LAYOUT
	 * --------------------------------------------------------
	 */

	$layout =
		get_post_meta(
			$matched_row_id,
			'_rd3_advanced_row_layout',
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
	 * --------------------------------------------------------
	 * SORT BLOCKS BY POSITION
	 * --------------------------------------------------------
	 */

	uasort(
		$matched_blocks,
		function (
			$a,
			$b
		) {

			return
				$a['position']
				<=>
				$b['position'];
		}
	);


	/*
	 * --------------------------------------------------------
	 * CREATE ROW CLASS
	 * --------------------------------------------------------
	 */

	$row_class =
		'rd3-content-row rd3-advanced-row rd3-row-' .
		$layout;


	/*
	 * --------------------------------------------------------
	 * CREATE OUTER WRAPPER
	 * --------------------------------------------------------
	 */

	$output =
		'<div class="rd3-content-row-wrapper">';


	/*
	 * --------------------------------------------------------
	 * ADD EDIT ADVANCED ROW LINK
	 * --------------------------------------------------------
	 */

	if (
		is_user_logged_in()
		&&
		current_user_can(
			'edit_post',
			$matched_row_id
		)
	) {

		$edit_url =
			get_edit_post_link(
				$matched_row_id,
				''
			);


		if ( $edit_url ) {

			$output .=
				'<div class="rd3-content-row-edit">';


			$output .=
				'<a href="' .
				esc_url(
					$edit_url
				) .
				'">';


			$output .=
				'Edit Advanced Row';


			$output .=
				'</a>';


			$output .=
				'</div>';
		}
	}


	/*
	 * --------------------------------------------------------
	 * CREATE ACTUAL ADVANCED ROW
	 * --------------------------------------------------------
	 */

	$output .=
		'<div class="' .
		esc_attr(
			$row_class
		) .
		'">';


	/*
	 * Track whether anything was rendered.
	 */

	$has_content = false;


	/*
	 * --------------------------------------------------------
	 * RENDER SELECTED CONTENT BLOCKS
	 * --------------------------------------------------------
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
			'1'
			!==
			$show
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


		$has_content = true;


		$output .=
			'<div class="rd3-content-row-item">';


		$output .=
			$block_content;


		$output .=
			'</div>';
	}


	/*
	 * --------------------------------------------------------
	 * DON'T OUTPUT EMPTY ADVANCED ROW
	 * --------------------------------------------------------
	 */

	if ( ! $has_content ) {

		return '';
	}


	/*
	 * --------------------------------------------------------
	 * CLOSE ACTUAL ADVANCED ROW
	 * --------------------------------------------------------
	 */

	$output .=
		'</div>';


	/*
	 * --------------------------------------------------------
	 * CLOSE OUTER WRAPPER
	 * --------------------------------------------------------
	 */

	$output .=
		'</div>';


	return $output;
}


add_shortcode(
	'rd3_advanced_row',
	'rd3_advanced_row_shortcode'
);