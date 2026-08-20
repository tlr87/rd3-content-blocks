<?php

/**
 * RD3 Content Blocks
 *
 * Content Block administration.
 */


/*
 * ============================================================
 * REGISTER CONTENT BLOCK POST TYPE
 * ============================================================
 */

function rd3_register_content_block_post_type() {

	register_post_type(
		'rd3_content_block',
		array(

			'labels' => array(

				'name'          => 'Content Blocks',
				'singular_name' => 'Content Block',

				'add_new'       => 'Add New',
				'add_new_item'  => 'Add New Content Block',

				'edit_item'     => 'Edit Content Block',
				'new_item'      => 'New Content Block',

				'view_item'     => 'View Content Block',

				'search_items'  => 'Search Content Blocks',

				'not_found'     => 'No Content Blocks found.',

				'menu_name'     => 'Content Blocks',

			),

			'public' => false,

			'show_ui' => true,

			'show_in_menu' => 'rd3-content-blocks',

			'show_in_admin_bar' => false,

			'show_in_nav_menus' => false,

			'supports' => array(
				'title',
				'editor',
			),

			'menu_icon' => 'dashicons-screenoptions',

			'has_archive' => false,

			'rewrite' => false,

			'query_var' => false,

		)
	);
}

add_action(
	'init',
	'rd3_register_content_block_post_type'
);


/*
 * ============================================================
 * OPTIONAL LINK META BOX
 * ============================================================
 */

function rd3_content_block_add_link_meta_box() {

	add_meta_box(
		'rd3_content_block_link',
		'Optional Link',
		'rd3_content_block_link_meta_box',
		'rd3_content_block',
		'normal',
		'default'
	);
}

add_action(
	'add_meta_boxes',
	'rd3_content_block_add_link_meta_box'
);


/*
 * ============================================================
 * DISPLAY OPTIONAL LINK META BOX
 * ============================================================
 */

function rd3_content_block_link_meta_box( $post ) {

	wp_nonce_field(
		'rd3_content_block_link_save',
		'rd3_content_block_link_nonce'
	);


	/*
	 * Get saved Link Text.
	 */

	$link_text = get_post_meta(
		$post->ID,
		'_rd3_content_block_link_text',
		true
	);


	/*
	 * Get saved Link URL.
	 */

	$link_url = get_post_meta(
		$post->ID,
		'_rd3_content_block_link_url',
		true
	);


	/*
	 * Get saved Link Target.
	 */

	$link_target = get_post_meta(
		$post->ID,
		'_rd3_content_block_link_target',
		true
	);


	/*
	 * Default to Same Window.
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


	?>

	<p
		style="
			margin-top:0;
			color:#666;
			font-size:13px;
		"
	>
		Add a link to appear below the Content Block content.
	</p>


	<p>

		<label
			for="rd3_content_block_link_text"
		>
			<strong>
				Link Text
			</strong>
		</label>

	</p>


	<p>

		<input
			type="text"
			id="rd3_content_block_link_text"
			name="rd3_content_block_link_text"
			value="<?php echo esc_attr(
				$link_text
			); ?>"
			class="widefat"
			placeholder="e.g. Get Help"
		>

	</p>


	<p>

		<label
			for="rd3_content_block_link_url"
		>
			<strong>
				Link URL
			</strong>
		</label>

	</p>


	<p>

		<input
			type="url"
			id="rd3_content_block_link_url"
			name="rd3_content_block_link_url"
			value="<?php echo esc_attr(
				$link_url
			); ?>"
			class="widefat"
			placeholder="https://example.com/"
		>

	</p>


	<p>

		<label
			for="rd3_content_block_link_target"
		>
			<strong>
				Open Link</strong>
		</label>

	</p>


	<p>

		<select
			id="rd3_content_block_link_target"
			name="rd3_content_block_link_target"
			style="width:100%;"
		>

			<option
				value="_self"
				<?php selected(
					$link_target,
					'_self'
				); ?>
			>
				Same Window
			</option>

			<option
				value="_blank"
				<?php selected(
					$link_target,
					'_blank'
				); ?>
			>
				New Tab
			</option>

		</select>

	</p>


	<p
		style="
			margin-bottom:0;
			color:#666;
			font-size:12px;
		"
	>
		Leave these fields empty if this Content Block
		does not need a link.
	</p>


	<?php
}


/*
 * ============================================================
 * SAVE LINK SETTINGS
 * ============================================================
 */

function rd3_content_block_save_link_settings(
	$post_id
) {

	/*
	 * Check nonce exists.
	 */

	if (
		! isset(
			$_POST['rd3_content_block_link_nonce']
		)
	) {

		return;
	}


	/*
	 * Verify nonce.
	 */

	if (
		! wp_verify_nonce(
			$_POST['rd3_content_block_link_nonce'],
			'rd3_content_block_link_save'
		)
	) {

		return;
	}


	/*
	 * Ignore autosaves.
	 */

	if (
		defined( 'DOING_AUTOSAVE' )
		&&
		DOING_AUTOSAVE
	) {

		return;
	}


	/*
	 * Check user permission.
	 */

	if (
		! current_user_can(
			'edit_post',
			$post_id
		)
	) {

		return;
	}


	/*
	 * Make sure this is a Content Block.
	 */

	if (
		'rd3_content_block'
		!== get_post_type(
			$post_id
		)
	) {

		return;
	}


	/*
	 * ========================================================
	 * SAVE LINK TEXT
	 * ========================================================
	 */

	$link_text = isset(
		$_POST['rd3_content_block_link_text']
	)
		? sanitize_text_field(
			wp_unslash(
				$_POST['rd3_content_block_link_text']
			)
		)
		: '';


	if ( '' !== $link_text ) {

		update_post_meta(
			$post_id,
			'_rd3_content_block_link_text',
			$link_text
		);

	} else {

		delete_post_meta(
			$post_id,
			'_rd3_content_block_link_text'
		);
	}


	/*
	 * ========================================================
	 * SAVE LINK URL
	 * ========================================================
	 */

	$link_url = isset(
		$_POST['rd3_content_block_link_url']
	)
		? esc_url_raw(
			wp_unslash(
				$_POST['rd3_content_block_link_url']
			)
		)
		: '';


	if ( '' !== $link_url ) {

		update_post_meta(
			$post_id,
			'_rd3_content_block_link_url',
			$link_url
		);

	} else {

		delete_post_meta(
			$post_id,
			'_rd3_content_block_link_url'
		);
	}


	/*
	 * ========================================================
	 * SAVE LINK TARGET
	 * ========================================================
	 */

	$link_target = isset(
		$_POST['rd3_content_block_link_target']
	)
		? sanitize_key(
			wp_unslash(
				$_POST['rd3_content_block_link_target']
			)
		)
		: '_self';


	/*
	 * Only allow supported targets.
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


	update_post_meta(
		$post_id,
		'_rd3_content_block_link_target',
		$link_target
	);
}

add_action(
	'save_post_rd3_content_block',
	'rd3_content_block_save_link_settings'
);