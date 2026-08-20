<?php

/**
 * RD3 Content Blocks
 *
 * Usage tracking for Content Blocks.
 *
 * Rows are handled separately in rows.php.
 */


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


/**
 * Get all usage locations for a Content Block.
 *
 * @param int $block_id Content Block ID.
 *
 * @return array
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
 * This function is used by rows.php.
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


	/*
	 * --------------------------------------------------------
	 * GET PUBLISHED PAGES AND POSTS
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


	/*
	 * --------------------------------------------------------
	 * CHECK EACH PAGE / POST
	 * --------------------------------------------------------
	 */

	foreach (
		$posts as $post
	) {

		$content =
			$post->post_content;


		/*
		 * Look for:
		 *
		 * [rd3_row id="123"]
		 *
		 */

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
	 *
	 * Look for:
	 *
	 * [rd3_block id="123"]
	 *
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


		/*
		 * Check for direct Content Block shortcode.
		 */

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
	 *
	 * A Content Block can also be used by a Row.
	 *
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
				$unique_usage[
					$key
				]
			)
		) {

			continue;
		}


		$unique_usage[
			$key
		] =
			$item;
	}


	return array_values(
		$unique_usage
	);
}


/*
 * ============================================================
 * CONTENT BLOCK — USED ON COLUMN
 * ============================================================
 */

function rd3_content_block_used_on_column(
	$columns
) {

	$columns['rd3_used_on'] =
		'Used On';


	return $columns;
}


add_filter(
	'manage_rd3_content_block_posts_columns',
	'rd3_content_block_used_on_column'
);


/*
 * ============================================================
 * CONTENT BLOCK — DISPLAY USED ON
 * ============================================================
 */

function rd3_content_block_used_on_column_content(
	$column,
	$post_id
) {

	if (
		'rd3_used_on'
		!== $column
	) {

		return;
	}


	$usage =
		rd3_get_content_block_usage(
			$post_id
		);


	/*
	 * Update cached usage count.
	 *
	 * This is safe here because we are only updating
	 * the current Content Block.
	 */

	update_post_meta(
		$post_id,
		'_rd3_usage_count',
		count(
			$usage
		)
	);


	if (
		empty(
			$usage
		)
	) {

		echo '<span style="color:#777;">';

		echo 'Not currently used';

		echo '</span>';

		return;
	}


	/*
	 * Sort displayed usage alphabetically
	 * by page / row title.
	 */

	usort(
		$usage,
		function(
			$a,
			$b
		) {

			return strcasecmp(
				$a['title'],
				$b['title']
			);
		}
	);


	foreach (
		$usage as $item
	) {

		/*
		 * Title.
		 */

		if (
			! empty(
				$item['edit_url']
			)
		) {

			echo '<a href="';

			echo esc_url(
				$item['edit_url']
			);

			echo '">';

			echo esc_html(
				$item['title']
			);

			echo '</a>';

		} else {

			echo esc_html(
				$item['title']
			);
		}


		echo '<br>';


		/*
		 * Usage type.
		 */

		echo '<small>';

		echo esc_html(
			$item['type_label']
		);


		if (
			isset(
				$item['usage_type']
			)
			&&
			'' !==
			$item['usage_type']
		) {

			echo ' — ';

			echo esc_html(
				$item['usage_type']
			);
		}


		echo '</small>';

		echo '<br><br>';
	}
}


add_action(
	'manage_rd3_content_block_posts_custom_column',
	'rd3_content_block_used_on_column_content',
	10,
	2
);


/*
 * ============================================================
 * CONTENT BLOCK — SORTABLE USED ON COLUMN
 * ============================================================
 */

function rd3_content_block_used_on_sortable(
	$columns
) {

	$columns['rd3_used_on'] =
		'rd3_used_on';


	return $columns;
}


add_filter(
	'manage_edit-rd3_content_block_sortable_columns',
	'rd3_content_block_used_on_sortable'
);


/*
 * ============================================================
 * CONTENT BLOCK — UPDATE USAGE COUNTS
 * ============================================================
 *
 * IMPORTANT:
 *
 * Do NOT run this from pre_get_posts.
 *
 * Calling get_posts() from pre_get_posts creates recursive
 * queries and can exhaust PHP memory.
 *
 */


/**
 * Update all Content Block usage counts.
 *
 * This runs only once when the Content Blocks admin list
 * is displayed.
 */
function rd3_update_content_block_usage_counts() {

	static $running =
		false;


	if ( $running ) {
		return;
	}


	if ( ! is_admin() ) {
		return;
	}


	global $pagenow;


	if (
		'edit.php'
		!==
		$pagenow
	) {

		return;
	}


	if (
		! isset(
			$_GET['post_type']
		)
	) {

		return;
	}


	$post_type =
		sanitize_key(
			wp_unslash(
				$_GET['post_type']
			)
		);


	if (
		'rd3_content_block'
		!==
		$post_type
	) {

		return;
	}


	/*
	 * Prevent recursion.
	 */

	$running =
		true;


	/*
	 * Get all Content Blocks.
	 */

	$blocks =
		get_posts(
			array(
				'post_type' =>
					'rd3_content_block',

				'post_status' =>
					array(
						'publish',
						'draft',
						'private',
						'pending',
					),

				'posts_per_page' =>
					-1,

				'fields' =>
					'ids',

				'no_found_rows' =>
					true,
			)
		);


	foreach (
		$blocks as $block_id
	) {

		$usage =
			rd3_get_content_block_usage(
				$block_id
			);


		update_post_meta(
			$block_id,
			'_rd3_usage_count',
			count(
				$usage
			)
		);
	}


	/*
	 * Allow future executions.
	 */

	$running =
		false;
}


/*
 * Run after WordPress has prepared
 * the main admin query.
 *
 * This avoids recursive pre_get_posts calls.
 */

add_action(
	'wp_loaded',
	'rd3_update_content_block_usage_counts',
	20
);


/*
 * ============================================================
 * CONTENT BLOCK — SORT BY USAGE
 * ============================================================
 */

function rd3_content_block_used_on_orderby(
	$query
) {

	if (
		! is_admin()
		||
		! $query->is_main_query()
	) {

		return;
	}


	if (
		'rd3_content_block'
		!==
		$query->get(
			'post_type'
		)
	) {

		return;
	}


	if (
		'rd3_used_on'
		!==
		$query->get(
			'orderby'
		)
	) {

		return;
	}


	/*
	 * Sort using the cached numeric usage count.
	 */

	$query->set(
		'meta_key',
		'_rd3_usage_count'
	);


	$query->set(
		'orderby',
		'meta_value_num'
	);
}