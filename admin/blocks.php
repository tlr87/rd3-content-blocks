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
 * LINK META BOX
 * ============================================================
 */

function rd3_content_block_add_link_meta_box() {

	add_meta_box(
		'rd3_content_block_link',
		'Link',
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
 * DISPLAY LINK META BOX
 * ============================================================
 */

function rd3_content_block_link_meta_box( $post ) {

	wp_nonce_field(
		'rd3_content_block_link_save',
		'rd3_content_block_link_nonce'
	);


	/*
	 * Get saved settings.
	 */

	$link_type = get_post_meta(
		$post->ID,
		'_rd3_content_block_link_type',
		true
	);

	$link_page_id = absint(
		get_post_meta(
			$post->ID,
			'_rd3_content_block_link_page_id',
			true
		)
	);

	$link_url = get_post_meta(
		$post->ID,
		'_rd3_content_block_link_url',
		true
	);

	$link_text = get_post_meta(
		$post->ID,
		'_rd3_content_block_link_text',
		true
	);

	$link_target = get_post_meta(
		$post->ID,
		'_rd3_content_block_link_target',
		true
	);


	/*
	 * Default Link Type.
	 *
	 * Existing blocks with a URL are treated
	 * as External URL links.
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

		if ( '' !== trim( $link_url ) ) {

			$link_type = 'external';

		} else {

			$link_type = 'internal';
		}
	}


	/*
	 * Default target.
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

	<p style="margin-top:0;">
		<strong>Optional Link</strong>
	</p>

	<p
		style="
			margin-top:0;
			color:#666;
			font-size:12px;
		"
	>
		Add a link to appear below the Content Block content.
	</p>


	<?php
	/*
	 * ========================================================
	 * LINK TYPE
	 * ========================================================
	 */
	?>

	<p>
		<strong>Link Type</strong>
	</p>


	<p>

		<label>

			<input
				type="radio"
				name="rd3_content_block_link_type"
				value="internal"
				<?php checked(
					$link_type,
					'internal'
				); ?>
			>

			Internal Page

		</label>

	</p>


	<p>

		<label>

			<input
				type="radio"
				name="rd3_content_block_link_type"
				value="external"
				<?php checked(
					$link_type,
					'external'
				); ?>
			>

			External URL

		</label>

	</p>


	<?php
	/*
	 * ========================================================
	 * INTERNAL PAGE
	 * ========================================================
	 */
	?>

	<div
		id="rd3-content-block-internal-link"
		style="
			<?php
			echo 'internal' === $link_type
				? ''
				: 'display:none;';
			?>
		"
	>

		<p>

			<label
				for="rd3_content_block_link_page_id"
			>

				<strong>
					Internal Page
				</strong>

			</label>

		</p>

		<p>

			<?php

			wp_dropdown_pages(
				array(
					'name'              =>
						'rd3_content_block_link_page_id',

					'id'                =>
						'rd3_content_block_link_page_id',

					'selected'          =>
						$link_page_id,

					'show_option_none' =>
						'— Select a page —',

					'option_none_value' =>
						'0',

					'class'             =>
						'widefat',
				)
			);

			?>

		</p>

	</div>


	<?php
	/*
	 * ========================================================
	 * EXTERNAL URL
	 * ========================================================
	 */
	?>

	<div
		id="rd3-content-block-external-link"
		style="
			<?php
			echo 'external' === $link_type
				? ''
				: 'display:none;';
			?>
		"
	>

		<p>

			<label
				for="rd3_content_block_link_url"
			>

				<strong>
					External URL
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

	</div>


	<?php
	/*
	 * ========================================================
	 * LINK TEXT
	 * ========================================================
	 */
	?>

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


	<?php
	/*
	 * ========================================================
	 * LINK TARGET
	 * ========================================================
	 */
	?>

	<p>

		<label
			for="rd3_content_block_link_target"
		>

			<strong>
				Open Link
			</strong>

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

		Choose an internal page or enter an external URL.
		Only the selected link type will be used.

	</p>


	<script>

	document.addEventListener(
		'DOMContentLoaded',
		function () {

			const radios =
				document.querySelectorAll(
					'input[name="rd3_content_block_link_type"]'
				);

			const internal =
				document.getElementById(
					'rd3-content-block-internal-link'
				);

			const external =
				document.getElementById(
					'rd3-content-block-external-link'
				);


			function updateLinkType() {

				const selected =
					document.querySelector(
						'input[name="rd3_content_block_link_type"]:checked'
					);


				if ( ! selected ) {
					return;
				}


				if (
					'internal'
					=== selected.value
				) {

					internal.style.display =
						'';

					external.style.display =
						'none';

				} else {

					internal.style.display =
						'none';

					external.style.display =
						'';
				}
			}


			radios.forEach(
				function (radio) {

					radio.addEventListener(
						'change',
						updateLinkType
					);

				}
			);


			updateLinkType();

		}
	);

	</script>

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

	if (
		! isset(
			$_POST['rd3_content_block_link_nonce']
		)
	) {

		return;
	}


	if (
		! wp_verify_nonce(
			$_POST['rd3_content_block_link_nonce'],
			'rd3_content_block_link_save'
		)
	) {

		return;
	}


	if (
		defined( 'DOING_AUTOSAVE' )
		&&
		DOING_AUTOSAVE
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
		'rd3_content_block'
		!== get_post_type(
			$post_id
		)
	) {

		return;
	}


	/*
	 * ========================================================
	 * SAVE LINK TYPE
	 * ========================================================
	 */

	$link_type = isset(
		$_POST['rd3_content_block_link_type']
	)
		? sanitize_key(
			wp_unslash(
				$_POST['rd3_content_block_link_type']
			)
		)
		: 'internal';


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

		$link_type = 'internal';
	}


	update_post_meta(
		$post_id,
		'_rd3_content_block_link_type',
		$link_type
	);


	/*
	 * ========================================================
	 * SAVE INTERNAL PAGE
	 * ========================================================
	 */

	$link_page_id = isset(
		$_POST['rd3_content_block_link_page_id']
	)
		? absint(
			$_POST['rd3_content_block_link_page_id']
		)
		: 0;


	if ( $link_page_id ) {

		update_post_meta(
			$post_id,
			'_rd3_content_block_link_page_id',
			$link_page_id
		);

	} else {

		delete_post_meta(
			$post_id,
			'_rd3_content_block_link_page_id'
		);
	}


	/*
	 * ========================================================
	 * SAVE EXTERNAL URL
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