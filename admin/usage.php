<?php

/**
 * RD3 Content Blocks
 *
 * Usage tracking for Content Blocks,
 * Rows and Advanced Rows.
 */


/*
 * ============================================================
 * GET ROW USAGE
 * ============================================================
 *
 * Finds published Pages and Posts that directly use:
 *
 * [rd3_row id="123"]
 *
 */

function rd3_get_row_usage(
	$row_id
) {

	$row_id =
		absint(
			$row_id
		);


	if ( ! $row_id ) {

		return array();
	}


	$usage =
		array();


	$posts =
		get_posts(
			array(
				'post_type' =>
					array(
						'post',
						'page',
					),

				'post_status' =>
					'publish',

				'posts_per_page' =>
					-1,

				'fields' =>
					'all',

				'no_found_rows' =>
					true,
			)
		);


	foreach (
		$posts as $post
	) {

		$content =
			$post->post_content;


		if (
			! preg_match(
				'/\[rd3_row\b[^\]]*\bid\s*=\s*["\']?'
				. preg_quote(
					$row_id,
					'/'
				)
				. '["\']?[^\]]*\]/i',
				$content
			)
		) {

			continue;
		}


		$post_type_object =
			get_post_type_object(
				$post->post_type
			);


		$type_label =
			$post_type_object
			&&
			! empty(
				$post_type_object->labels->singular_name
			)
				? $post_type_object->labels->singular_name
				: ucfirst(
					$post->post_type
				);


		$usage[] =
			array(

				'title' =>
					$post->post_title,

				'type_label' =>
					$type_label,

				'usage_type' =>
					'Direct',

				'edit_url' =>
					get_edit_post_link(
						$post->ID
					),

				'row_title' =>
					'',

				'post_id' =>
					$post->ID,
			);
	}


	return $usage;
}


/*
 * ============================================================
 * GET ADVANCED ROW USAGE
 * ============================================================
 *
 * Finds published Pages and Posts that directly use:
 *
 * [rd3_advanced_row id="123"]
 *
 */

function rd3_get_advanced_row_usage(
	$row_id
) {

	$row_id =
		absint(
			$row_id
		);


	if ( ! $row_id ) {

		return array();
	}


	$usage =
		array();


	$posts =
		get_posts(
			array(
				'post_type' =>
					array(
						'post',
						'page',
					),

				'post_status' =>
					'publish',

				'posts_per_page' =>
					-1,

				'fields' =>
					'all',

				'no_found_rows' =>
					true,
			)
		);


	foreach (
		$posts as $post
	) {

		$content =
			$post->post_content;


		if (
			! preg_match(
				'/\[rd3_advanced_row\b[^\]]*\bid\s*=\s*["\']?'
				. preg_quote(
					$row_id,
					'/'
				)
				. '["\']?[^\]]*\]/i',
				$content
			)
		) {

			continue;
		}


		$post_type_object =
			get_post_type_object(
				$post->post_type
			);


		$type_label =
			$post_type_object
			&&
			! empty(
				$post_type_object->labels->singular_name
			)
				? $post_type_object->labels->singular_name
				: ucfirst(
					$post->post_type
				);


		$usage[] =
			array(

				'title' =>
					$post->post_title,

				'type_label' =>
					$type_label,

				'usage_type' =>
					'Direct',

				'edit_url' =>
					get_edit_post_link(
						$post->ID
					),

				'row_title' =>
					'',

				'post_id' =>
					$post->ID,
			);
	}


	return $usage;
}


/*
 * ============================================================
 * GET CONTENT BLOCK USAGE
 * ============================================================
 *
 * Finds:
 *
 * 1. Direct Content Block usage in published Pages and Posts.
 *
 * 2. Content Blocks contained in Rows.
 *
 */

function rd3_get_content_block_usage(
	$block_id
) {

	$block_id =
		absint(
			$block_id
		);


	if ( ! $block_id ) {

		return array();
	}


	$usage =
		array();


	/*
	 * --------------------------------------------------------
	 * DIRECT PAGE / POST USAGE
	 * --------------------------------------------------------
	 */

	$posts =
		get_posts(
			array(
				'post_type' =>
					array(
						'post',
						'page',
					),

				'post_status' =>
					'publish',

				'posts_per_page' =>
					-1,

				'fields' =>
					'all',

				'no_found_rows' =>
					true,
			)
		);


	foreach (
		$posts as $post
	) {

		$content =
			$post->post_content;


		if (
			! preg_match(
				'/\[rd3_block\b[^\]]*\bid\s*=\s*["\']?'
				. preg_quote(
					$block_id,
					'/'
				)
				. '["\']?[^\]]*\]/i',
				$content
			)
		) {

			continue;
		}


		$post_type_object =
			get_post_type_object(
				$post->post_type
			);


		$type_label =
			$post_type_object
			&&
			! empty(
				$post_type_object->labels->singular_name
			)
				? $post_type_object->labels->singular_name
				: ucfirst(
					$post->post_type
				);


		$usage[] =
			array(

				'title' =>
					$post->post_title,

				'type_label' =>
					$type_label,

				'usage_type' =>
					'Direct',

				'edit_url' =>
					get_edit_post_link(
						$post->ID
					),

				'row_title' =>
					'',

				'post_id' =>
					$post->ID,
			);
		}


	/*
	 * --------------------------------------------------------
	 * ROW USAGE
	 * --------------------------------------------------------
	 */

	$rows =
		get_posts(
			array(
				'post_type' =>
					'rd3_row',

				'post_status' =>
					array(
						'publish',
						'draft',
						'private',
					),

				'posts_per_page' =>
					-1,

				'fields' =>
					'all',

				'no_found_rows' =>
					true,
			)
		);


	foreach (
		$rows as $row
	) {

		$positions =
			get_post_meta(
				$row->ID,
				'_rd3_row_positions',
				true
			);


		if (
			! is_array(
				$positions
			)
		) {

			continue;
		}


		$found =
			false;


		foreach (
			$positions as $position_block_id
		) {

			if (
				absint(
					$position_block_id
				)
				===
				$block_id
			) {

				$found =
					true;

				break;
			}
		}


		if ( ! $found ) {

			continue;
		}


		$usage[] =
			array(

				'title' =>
					$row->post_title,

				'type_label' =>
					'Row',

				'usage_type' =>
					'Contains Block',

				'edit_url' =>
					get_edit_post_link(
						$row->ID
					),

				'row_title' =>
					$row->post_title,

				'post_id' =>
					$row->ID,
			);
	}


	/*
	 * --------------------------------------------------------
	 * ADVANCED ROW USAGE
	 * --------------------------------------------------------
	 */

	$advanced_rows =
		get_posts(
			array(
				'post_type' =>
					'rd3_advanced_row',

				'post_status' =>
					array(
						'publish',
						'draft',
						'private',
					),

				'posts_per_page' =>
					-1,

				'fields' =>
					'all',

				'no_found_rows' =>
					true,
			)
		);


	foreach (
		$advanced_rows as $advanced_row
	) {

		$blocks =
			get_post_meta(
				$advanced_row->ID,
				'_rd3_advanced_row_blocks',
				true
			);


		if (
			! is_array(
				$blocks
			)
		) {

			continue;
		}


		$found =
			false;


		foreach (
			$blocks as $block
		) {

			if (
				! is_array(
					$block
				)
			) {

				continue;
			}


			if (
				absint(
					isset(
						$block['block_id']
					)
						? $block['block_id']
						: 0
				)
				===
				$block_id
			) {

				$found =
					true;

				break;
			}
		}


		if ( ! $found ) {

			continue;
		}


		$usage[] =
			array(

				'title' =>
					$advanced_row->post_title,

				'type_label' =>
					'Advanced Row',

				'usage_type' =>
					'Contains Block',

				'edit_url' =>
					get_edit_post_link(
						$advanced_row->ID
					),

				'row_title' =>
					$advanced_row->post_title,

				'post_id' =>
					$advanced_row->ID,
			);
	}


	/*
	 * --------------------------------------------------------
	 * REMOVE DUPLICATES
	 * --------------------------------------------------------
	 */

	$unique_usage =
		array();


	foreach (
		$usage as $item
	) {

		$key =
			absint(
				isset(
					$item['post_id']
				)
					? $item['post_id']
					: 0
			)
			. '|'
			. $item['type_label']
			. '|'
			. $item['usage_type'];


		if (
			isset(
				$unique_usage[ $key ]
			)
		) {

			continue;
		}


		$unique_usage[ $key ] =
			$item;
	}


	return array_values(
		$unique_usage
	);
}