
<?php
/**
 * RD3 Content Blocks
 *
 * Appearance Settings
 *
 * Handles visual appearance settings for:
 * - Content Blocks
 * - Rows
 *
 * Row settings also allow a Row to override the appearance
 * of all Content Blocks contained within that Row.
 *
 * Settings:
 * - Background colour
 * - Text colour
 * - Border style
 * - Border width
 * - Border colour
 * - Border radius
 *
 * Appearance settings are stored as post meta and rendered
 * by the shortcode system as CSS custom properties.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/*
|--------------------------------------------------------------------------
| Appearance Configuration
|--------------------------------------------------------------------------
*/

/**
 * Get allowed border styles.
 *
 * @return array
 */
function rd3_content_blocks_appearance_border_styles() {

	return array(
		'none'   => 'None',
		'solid'  => 'Solid',
		'dashed' => 'Dashed',
		'dotted' => 'Dotted',
		'double' => 'Double',
	);
}


/**
 * Get appearance meta keys for a content block or row.
 *
 * @param string $type block|row
 * @return array
 */
function rd3_content_blocks_appearance_meta_keys( $type = 'block' ) {

	if ( 'row' === $type ) {

		return array(
			'background'       => '_rd3_row_background',
			'text_color'       => '_rd3_row_text_color',
			'border_style'     => '_rd3_row_border_style',
			'border_width'     => '_rd3_row_border_width',
			'border_color'     => '_rd3_row_border_color',
			'radius'           => '_rd3_row_border_radius',

			/*
			 * Content Block override settings.
			 */
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


/*
|--------------------------------------------------------------------------
| Meta Boxes
|--------------------------------------------------------------------------
*/

/**
 * Add Appearance meta box to Content Blocks.
 */
function rd3_content_blocks_add_block_appearance_meta_box() {

	add_meta_box(
		'rd3_content_block_appearance',
		'Appearance',
		'rd3_content_block_appearance_meta_box',
		'rd3_content_block',
		'normal',
		'default'
	);
}

add_action(
	'add_meta_boxes_rd3_content_block',
	'rd3_content_blocks_add_block_appearance_meta_box'
);


/**
 * Add Appearance meta box to Rows.
 */
function rd3_content_blocks_add_row_appearance_meta_box() {

	add_meta_box(
		'rd3_row_appearance',
		'Appearance',
		'rd3_row_appearance_meta_box',
		'rd3_row',
		'normal',
		'default'
	);
}

add_action(
	'add_meta_boxes_rd3_row',
	'rd3_content_blocks_add_row_appearance_meta_box'
);


/*
|--------------------------------------------------------------------------
| Meta Box Output
|--------------------------------------------------------------------------
*/

/**
 * Render Content Block Appearance meta box.
 *
 * @param WP_Post $post
 */
function rd3_content_block_appearance_meta_box( $post ) {

	wp_nonce_field(
		'rd3_content_blocks_save_appearance',
		'rd3_content_blocks_appearance_nonce'
	);

	rd3_content_blocks_render_appearance_fields(
		$post->ID,
		'block'
	);
}


/**
 * Render Row Appearance meta box.
 *
 * @param WP_Post $post
 */
function rd3_row_appearance_meta_box( $post ) {

	wp_nonce_field(
		'rd3_content_blocks_save_appearance',
		'rd3_content_blocks_appearance_nonce'
	);

	rd3_content_blocks_render_appearance_fields(
		$post->ID,
		'row'
	);
}


/**
 * Render shared appearance fields.
 *
 * @param int    $post_id
 * @param string $type
 */
function rd3_content_blocks_render_appearance_fields(
	$post_id,
	$type = 'block'
) {

	$keys = rd3_content_blocks_appearance_meta_keys( $type );

	$background = get_post_meta(
		$post_id,
		$keys['background'],
		true
	);

	$text_color = get_post_meta(
		$post_id,
		$keys['text_color'],
		true
	);

	$border_style = get_post_meta(
		$post_id,
		$keys['border_style'],
		true
	);

	$border_width = get_post_meta(
		$post_id,
		$keys['border_width'],
		true
	);

	$border_color = get_post_meta(
		$post_id,
		$keys['border_color'],
		true
	);

	$radius = get_post_meta(
		$post_id,
		$keys['radius'],
		true
	);

	$border_styles =
		rd3_content_blocks_appearance_border_styles();

	?>

	<div class="rd3-appearance-settings">

		<p class="description">
			Use these settings to customise the appearance of this
			<?php echo ( 'row' === $type ) ? 'row' : 'content block'; ?>.
			Leave colour fields empty to keep the existing appearance.
		</p>

		<table class="form-table rd3-appearance-table">

			<tr>
				<th scope="row">
					<label
						for="rd3-appearance-background-<?php echo esc_attr( $post_id ); ?>"
					>
						Background colour
					</label>
				</th>

				<td>

					<div class="rd3-appearance-color-row">

						<input
							type="text"
							id="rd3-appearance-background-<?php echo esc_attr( $post_id ); ?>"
							name="rd3_appearance_background"
							value="<?php echo esc_attr( $background ); ?>"
							class="rd3-appearance-color"
							data-default-color=""
							placeholder="#FFFFFF"
						/>

						<button
							type="button"
							class="button rd3-appearance-clear"
							data-target="rd3-appearance-background-<?php echo esc_attr( $post_id ); ?>"
						>
							Clear
						</button>

					</div>

					<p class="description">
						Sets the background colour of the
						<?php echo ( 'row' === $type ) ? 'row' : 'content block'; ?>.
					</p>

				</td>
			</tr>


			<tr>
				<th scope="row">
					<label
						for="rd3-appearance-text-<?php echo esc_attr( $post_id ); ?>"
					>
						Text colour
					</label>
				</th>

				<td>

					<div class="rd3-appearance-color-row">

						<input
							type="text"
							id="rd3-appearance-text-<?php echo esc_attr( $post_id ); ?>"
							name="rd3_appearance_text_color"
							value="<?php echo esc_attr( $text_color ); ?>"
							class="rd3-appearance-color"
							data-default-color=""
							placeholder="#333333"
						/>

						<button
							type="button"
							class="button rd3-appearance-clear"
							data-target="rd3-appearance-text-<?php echo esc_attr( $post_id ); ?>"
						>
							Clear
						</button>

					</div>

					<p class="description">
						Sets the text colour inside the
						<?php echo ( 'row' === $type ) ? 'row' : 'content block'; ?>.
					</p>

				</td>
			</tr>


			<tr>
				<th scope="row">
					<label
						for="rd3-appearance-border-style-<?php echo esc_attr( $post_id ); ?>"
					>
						Border
					</label>
				</th>

				<td>

					<select
						id="rd3-appearance-border-style-<?php echo esc_attr( $post_id ); ?>"
						name="rd3_appearance_border_style"
					>

						<?php foreach ( $border_styles as $value => $label ) : ?>

							<option
								value="<?php echo esc_attr( $value ); ?>"
								<?php selected(
									$border_style ? $border_style : 'none',
									$value
								); ?>
							>
								<?php echo esc_html( $label ); ?>
							</option>

						<?php endforeach; ?>

					</select>

					<p class="description">
						Choose the border style. Select None for no border.
					</p>

				</td>
			</tr>


			<tr>
				<th scope="row">
					<label
						for="rd3-appearance-border-width-<?php echo esc_attr( $post_id ); ?>"
					>
						Border thickness
					</label>
				</th>

				<td>

					<div class="rd3-appearance-number">

						<input
							type="number"
							id="rd3-appearance-border-width-<?php echo esc_attr( $post_id ); ?>"
							name="rd3_appearance_border_width"
							value="<?php echo esc_attr( $border_width ); ?>"
							min="0"
							max="50"
							step="1"
						/>

						<span>px</span>

					</div>

					<p class="description">
						0px means no visible border.
					</p>

				</td>
			</tr>


			<tr>
				<th scope="row">
					<label
						for="rd3-appearance-border-color-<?php echo esc_attr( $post_id ); ?>"
					>
						Border colour
					</label>
				</th>

				<td>

					<div class="rd3-appearance-color-row">

						<input
							type="text"
							id="rd3-appearance-border-color-<?php echo esc_attr( $post_id ); ?>"
							name="rd3_appearance_border_color"
							value="<?php echo esc_attr( $border_color ); ?>"
							class="rd3-appearance-color"
							data-default-color=""
							placeholder="#CCCCCC"
						/>

						<button
							type="button"
							class="button rd3-appearance-clear"
							data-target="rd3-appearance-border-color-<?php echo esc_attr( $post_id ); ?>"
						>
							Clear
						</button>

					</div>

				</td>
			</tr>


			<tr>
				<th scope="row">
					<label
						for="rd3-appearance-radius-<?php echo esc_attr( $post_id ); ?>"
					>
						Border radius
					</label>
				</th>

				<td>

					<div class="rd3-appearance-number">

						<input
							type="number"
							id="rd3-appearance-radius-<?php echo esc_attr( $post_id ); ?>"
							name="rd3_appearance_border_radius"
							value="<?php echo esc_attr( $radius ); ?>"
							min="0"
							max="100"
							step="1"
						/>

						<span>px</span>

					</div>

					<p class="description">
						0px means square corners.
					</p>

				</td>
			</tr>

		</table>


		<?php if ( 'row' === $type ) : ?>

			<?php
			/*
			 * Row Content Block Overrides
			 */

			$block_background = get_post_meta(
				$post_id,
				$keys['block_background'],
				true
			);

			$block_text_color = get_post_meta(
				$post_id,
				$keys['block_text_color'],
				true
			);

			$block_border_style = get_post_meta(
				$post_id,
				$keys['block_border_style'],
				true
			);

			$block_border_width = get_post_meta(
				$post_id,
				$keys['block_border_width'],
				true
			);

			$block_border_color = get_post_meta(
				$post_id,
				$keys['block_border_color'],
				true
			);

			$block_radius = get_post_meta(
				$post_id,
				$keys['block_radius'],
				true
			);
			?>

			<hr />

			<h3>
				Content Block Overrides
			</h3>

			<p class="description">
				These settings override the matching appearance settings
				of every Content Block inside this Row.
				Leave a setting empty to use each Content Block's own setting.
			</p>

			<table class="form-table rd3-appearance-table">

				<tr>
					<th scope="row">
						<label
							for="rd3-row-block-background-<?php echo esc_attr( $post_id ); ?>"
						>
							Background colour
						</label>
					</th>

					<td>

						<div class="rd3-appearance-color-row">

							<input
								type="text"
								id="rd3-row-block-background-<?php echo esc_attr( $post_id ); ?>"
								name="rd3_row_block_background"
								value="<?php echo esc_attr( $block_background ); ?>"
								class="rd3-appearance-color"
								data-default-color=""
								placeholder="#FFFFFF"
							/>

							<button
								type="button"
								class="button rd3-appearance-clear"
								data-target="rd3-row-block-background-<?php echo esc_attr( $post_id ); ?>"
							>
								Clear
							</button>

						</div>

						<p class="description">
							Overrides the background colour of all Content Blocks in this Row.
						</p>

					</td>
				</tr>


				<tr>
					<th scope="row">
						<label
							for="rd3-row-block-text-<?php echo esc_attr( $post_id ); ?>"
						>
							Text colour
						</label>
					</th>

					<td>

						<div class="rd3-appearance-color-row">

							<input
								type="text"
								id="rd3-row-block-text-<?php echo esc_attr( $post_id ); ?>"
								name="rd3_row_block_text_color"
								value="<?php echo esc_attr( $block_text_color ); ?>"
								class="rd3-appearance-color"
								data-default-color=""
								placeholder="#333333"
							/>

							<button
								type="button"
								class="button rd3-appearance-clear"
								data-target="rd3-row-block-text-<?php echo esc_attr( $post_id ); ?>"
							>
								Clear
							</button>

						</div>

						<p class="description">
							Overrides the text colour of all Content Blocks in this Row.
						</p>

					</td>
				</tr>


				<tr>
					<th scope="row">
						<label
							for="rd3-row-block-border-style-<?php echo esc_attr( $post_id ); ?>"
						>
							Border
						</label>
					</th>

					<td>

						<select
							id="rd3-row-block-border-style-<?php echo esc_attr( $post_id ); ?>"
							name="rd3_row_block_border_style"
						>

							<option value="">
								Use Content Block setting
							</option>

							<?php foreach ( $border_styles as $value => $label ) : ?>

								<option
									value="<?php echo esc_attr( $value ); ?>"
									<?php selected(
										$block_border_style,
										$value
									); ?>
								>
									<?php echo esc_html( $label ); ?>
								</option>

							<?php endforeach; ?>

						</select>

						<p class="description">
							Overrides the border style of all Content Blocks in this Row.
						</p>

					</td>
				</tr>


				<tr>
					<th scope="row">
						<label
							for="rd3-row-block-border-width-<?php echo esc_attr( $post_id ); ?>"
						>
							Border thickness
						</label>
					</th>

					<td>

						<div class="rd3-appearance-number">

							<input
								type="number"
								id="rd3-row-block-border-width-<?php echo esc_attr( $post_id ); ?>"
								name="rd3_row_block_border_width"
								value="<?php echo esc_attr( $block_border_width ); ?>"
								min="0"
								max="50"
								step="1"
								placeholder="Use Content Block setting"
							/>

							<span>px</span>

						</div>

						<p class="description">
							Leave empty to use each Content Block's border thickness.
						</p>

					</td>
				</tr>


				<tr>
					<th scope="row">
						<label
							for="rd3-row-block-border-color-<?php echo esc_attr( $post_id ); ?>"
						>
							Border colour
						</label>
					</th>

					<td>

						<div class="rd3-appearance-color-row">

							<input
								type="text"
								id="rd3-row-block-border-color-<?php echo esc_attr( $post_id ); ?>"
								name="rd3_row_block_border_color"
								value="<?php echo esc_attr( $block_border_color ); ?>"
								class="rd3-appearance-color"
								data-default-color=""
								placeholder="#CCCCCC"
							/>

							<button
								type="button"
								class="button rd3-appearance-clear"
								data-target="rd3-row-block-border-color-<?php echo esc_attr( $post_id ); ?>"
							>
								Clear
							</button>

						</div>

						<p class="description">
							Overrides the border colour of all Content Blocks in this Row.
						</p>

					</td>
				</tr>


				<tr>
					<th scope="row">
						<label
							for="rd3-row-block-radius-<?php echo esc_attr( $post_id ); ?>"
						>
							Border radius
						</label>
					</th>

					<td>

						<div class="rd3-appearance-number">

							<input
								type="number"
								id="rd3-row-block-radius-<?php echo esc_attr( $post_id ); ?>"
								name="rd3_row_block_border_radius"
								value="<?php echo esc_attr( $block_radius ); ?>"
								min="0"
								max="100"
								step="1"
								placeholder="Use Content Block setting"
							/>

							<span>px</span>

						</div>

						<p class="description">
							Leave empty to use each Content Block's border radius.
						</p>

					</td>
				</tr>

			</table>

		<?php endif; ?>

	</div>

	<?php
}


/*
|--------------------------------------------------------------------------
| Saving
|--------------------------------------------------------------------------
*/

/**
 * Save appearance settings.
 *
 * Handles both Content Blocks and Rows.
 *
 * @param int     $post_id
 * @param WP_Post $post
 * @param bool    $update
 */
function rd3_content_blocks_save_appearance(
	$post_id,
	$post,
	$update
) {

	/*
	 * Only our post types.
	 */
	if (
		! $post ||
		! in_array(
			$post->post_type,
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
	 * Ignore autosaves.
	 */
	if ( wp_is_post_autosave( $post_id ) ) {
		return;
	}


	/*
	 * Ignore revisions.
	 */
	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}


	/*
	 * Permission check.
	 */
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}


	/*
	 * Nonce check.
	 */
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
					$_POST['rd3_content_blocks_appearance_nonce']
				)
			),
			'rd3_content_blocks_save_appearance'
		)
	) {
		return;
	}


	/*
	 * Determine which appearance settings to use.
	 */
	$type = (
		'rd3_row' === $post->post_type
	)
		? 'row'
		: 'block';


	$keys = rd3_content_blocks_appearance_meta_keys(
		$type
	);


	/*
	|--------------------------------------------------------------------------
	| Background
	|--------------------------------------------------------------------------
	*/

	$background = '';

	if ( isset( $_POST['rd3_appearance_background'] ) ) {

		$background = sanitize_hex_color(
			wp_unslash(
				$_POST['rd3_appearance_background']
			)
		);
	}

	rd3_content_blocks_save_appearance_value(
		$post_id,
		$keys['background'],
		$background
	);


	/*
	|--------------------------------------------------------------------------
	| Text Colour
	|--------------------------------------------------------------------------
	*/

	$text_color = '';

	if ( isset( $_POST['rd3_appearance_text_color'] ) ) {

		$text_color = sanitize_hex_color(
			wp_unslash(
				$_POST['rd3_appearance_text_color']
			)
		);
	}

	rd3_content_blocks_save_appearance_value(
		$post_id,
		$keys['text_color'],
		$text_color
	);


	/*
	|--------------------------------------------------------------------------
	| Border Style
	|--------------------------------------------------------------------------
	*/

	$border_style = 'none';

	if ( isset( $_POST['rd3_appearance_border_style'] ) ) {

		$border_style = sanitize_key(
			wp_unslash(
				$_POST['rd3_appearance_border_style']
			)
		);
	}

	$allowed_styles =
		rd3_content_blocks_appearance_border_styles();

	if (
		! array_key_exists(
			$border_style,
			$allowed_styles
		)
	) {
		$border_style = 'none';
	}

	update_post_meta(
		$post_id,
		$keys['border_style'],
		$border_style
	);


	/*
	|--------------------------------------------------------------------------
	| Border Width
	|--------------------------------------------------------------------------
	*/

	$border_width = 0;

	if ( isset( $_POST['rd3_appearance_border_width'] ) ) {

		$border_width = absint(
			wp_unslash(
				$_POST['rd3_appearance_border_width']
			)
		);
	}

	$border_width = min(
		$border_width,
		50
	);

	update_post_meta(
		$post_id,
		$keys['border_width'],
		$border_width
	);


	/*
	|--------------------------------------------------------------------------
	| Border Colour
	|--------------------------------------------------------------------------
	*/

	$border_color = '';

	if ( isset( $_POST['rd3_appearance_border_color'] ) ) {

		$border_color = sanitize_hex_color(
			wp_unslash(
				$_POST['rd3_appearance_border_color']
			)
		);
	}

	rd3_content_blocks_save_appearance_value(
		$post_id,
		$keys['border_color'],
		$border_color
	);


	/*
	|--------------------------------------------------------------------------
	| Border Radius
	|--------------------------------------------------------------------------
	*/

	$radius = 0;

	if ( isset( $_POST['rd3_appearance_border_radius'] ) ) {

		$radius = absint(
			wp_unslash(
				$_POST['rd3_appearance_border_radius']
			)
		);
	}

	$radius = min(
		$radius,
		100
	);

	update_post_meta(
		$post_id,
		$keys['radius'],
		$radius
	);


	/*
	|--------------------------------------------------------------------------
	| Row Content Block Overrides
	|--------------------------------------------------------------------------
	*/

	if ( 'row' === $type ) {

		/*
		 * Background.
		 */
		$block_background = '';

		if ( isset( $_POST['rd3_row_block_background'] ) ) {

			$block_background = sanitize_hex_color(
				wp_unslash(
					$_POST['rd3_row_block_background']
				)
			);
		}

		rd3_content_blocks_save_appearance_value(
			$post_id,
			$keys['block_background'],
			$block_background
		);


		/*
		 * Text colour.
		 */
		$block_text_color = '';

		if ( isset( $_POST['rd3_row_block_text_color'] ) ) {

			$block_text_color = sanitize_hex_color(
				wp_unslash(
					$_POST['rd3_row_block_text_color']
				)
			);
		}

		rd3_content_blocks_save_appearance_value(
			$post_id,
			$keys['block_text_color'],
			$block_text_color
		);


		/*
		 * Border style.
		 *
		 * Empty means "use Content Block setting".
		 */
		$block_border_style = '';

		if ( isset( $_POST['rd3_row_block_border_style'] ) ) {

			$block_border_style = sanitize_key(
				wp_unslash(
					$_POST['rd3_row_block_border_style']
				)
			);
		}

		if (
			'' !== $block_border_style &&
			! array_key_exists(
				$block_border_style,
				$allowed_styles
			)
		) {
			$block_border_style = '';
		}

		rd3_content_blocks_save_appearance_value(
			$post_id,
			$keys['block_border_style'],
			$block_border_style
		);


		/*
		 * Border width.
		 *
		 * Empty means "use Content Block setting".
		 */
		$block_border_width = '';

		if (
			isset(
				$_POST['rd3_row_block_border_width']
			)
		) {

			$raw_width =
				trim(
					wp_unslash(
						$_POST['rd3_row_block_border_width']
					)
				);

			if ( '' !== $raw_width ) {

				$block_border_width = absint(
					$raw_width
				);

				$block_border_width = min(
					$block_border_width,
					50
				);

			}
		}

		rd3_content_blocks_save_appearance_value(
			$post_id,
			$keys['block_border_width'],
			$block_border_width
		);


		/*
		 * Border colour.
		 */
		$block_border_color = '';

		if (
			isset(
				$_POST['rd3_row_block_border_color']
			)
		) {

			$block_border_color = sanitize_hex_color(
				wp_unslash(
					$_POST['rd3_row_block_border_color']
				)
			);
		}

		rd3_content_blocks_save_appearance_value(
			$post_id,
			$keys['block_border_color'],
			$block_border_color
		);


		/*
		 * Border radius.
		 *
		 * Empty means "use Content Block setting".
		 */
		$block_radius = '';

		if (
			isset(
				$_POST['rd3_row_block_border_radius']
			)
		) {

			$raw_radius =
				trim(
					wp_unslash(
						$_POST['rd3_row_block_border_radius']
					)
				);

			if ( '' !== $raw_radius ) {

				$block_radius = absint(
					$raw_radius
				);

				$block_radius = min(
					$block_radius,
					100
				);

			}
		}

		rd3_content_blocks_save_appearance_value(
			$post_id,
			$keys['block_radius'],
			$block_radius
		);
	}
}

add_action(
	'save_post',
	'rd3_content_blocks_save_appearance',
	10,
	3
);


/**
 * Save an appearance value or remove it when empty.
 *
 * @param int    $post_id
 * @param string $meta_key
 * @param string $value
 */
function rd3_content_blocks_save_appearance_value(
	$post_id,
	$meta_key,
	$value
) {

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


/*
|--------------------------------------------------------------------------
| Front-End Appearance Helper
|--------------------------------------------------------------------------
*/

/**
 * Build inline CSS custom properties for a block or row.
 *
 * The returned value is suitable for placing directly inside
 * a style="" attribute.
 *
 * @param int    $post_id
 * @param string $type block|row
 * @return string
 */
function rd3_content_blocks_get_appearance_style(
	$post_id,
	$type = 'block'
) {

	$keys = rd3_content_blocks_appearance_meta_keys(
		$type
	);

	$styles = array();


	/*
	|--------------------------------------------------------------------------
	| Prefix
	|--------------------------------------------------------------------------
	*/

	if ( 'row' === $type ) {

		$prefix = 'rd3-row';

	} else {

		$prefix = 'rd3-block';
	}


	/*
	|--------------------------------------------------------------------------
	| Background
	|--------------------------------------------------------------------------
	*/

	$background = get_post_meta(
		$post_id,
		$keys['background'],
		true
	);

	$background = sanitize_hex_color(
		$background
	);

	if ( $background ) {

		$styles[] =
			'--' .
			$prefix .
			'-background:' .
			$background;
	}


	/*
	|--------------------------------------------------------------------------
	| Text Colour
	|--------------------------------------------------------------------------
	*/

	$text_color = get_post_meta(
		$post_id,
		$keys['text_color'],
		true
	);

	$text_color = sanitize_hex_color(
		$text_color
	);

	if ( $text_color ) {

		$styles[] =
			'--' .
			$prefix .
			'-text:' .
			$text_color;
	}


	/*
	|--------------------------------------------------------------------------
	| Border Style
	|--------------------------------------------------------------------------
	*/

	$border_style = get_post_meta(
		$post_id,
		$keys['border_style'],
		true
	);

	$allowed_styles =
		rd3_content_blocks_appearance_border_styles();

	if (
		$border_style &&
		array_key_exists(
			$border_style,
			$allowed_styles
		)
	) {

		$styles[] =
			'--' .
			$prefix .
			'-border-style:' .
			$border_style;
	}


	/*
	|--------------------------------------------------------------------------
	| Border Width
	|--------------------------------------------------------------------------
	*/

	$border_width = get_post_meta(
		$post_id,
		$keys['border_width'],
		true
	);

	if (
		'' !== $border_width &&
		is_numeric( $border_width )
	) {

		$border_width = max(
			0,
			min(
				50,
				(float) $border_width
			)
		);

		$styles[] =
			'--' .
			$prefix .
			'-border-width:' .
			$border_width .
			'px';
	}


	/*
	|--------------------------------------------------------------------------
	| Border Colour
	|--------------------------------------------------------------------------
	*/

	$border_color = get_post_meta(
		$post_id,
		$keys['border_color'],
		true
	);

	$border_color = sanitize_hex_color(
		$border_color
	);

	if ( $border_color ) {

		$styles[] =
			'--' .
			$prefix .
			'-border-color:' .
			$border_color;
	}


	/*
	|--------------------------------------------------------------------------
	| Border Radius
	|--------------------------------------------------------------------------
	*/

	$radius = get_post_meta(
		$post_id,
		$keys['radius'],
		true
	);

	if (
		'' !== $radius &&
		is_numeric( $radius )
	) {

		$radius = max(
			0,
			min(
				100,
				(float) $radius
			)
		);

		$styles[] =
			'--' .
			$prefix .
			'-radius:' .
			$radius .
			'px';
	}


	/*
	|--------------------------------------------------------------------------
	| Row Content Block Overrides
	|--------------------------------------------------------------------------
	*/

	if ( 'row' === $type ) {

		/*
		 * Background.
		 */
		$block_background = get_post_meta(
			$post_id,
			$keys['block_background'],
			true
		);

		$block_background = sanitize_hex_color(
			$block_background
		);

		if ( $block_background ) {

			$styles[] =
				'--rd3-row-block-background:' .
				$block_background;
		}


		/*
		 * Text colour.
		 */
		$block_text_color = get_post_meta(
			$post_id,
			$keys['block_text_color'],
			true
		);

		$block_text_color = sanitize_hex_color(
			$block_text_color
		);

		if ( $block_text_color ) {

			$styles[] =
				'--rd3-row-block-text:' .
				$block_text_color;
		}


		/*
		 * Border style.
		 */
		$block_border_style = get_post_meta(
			$post_id,
			$keys['block_border_style'],
			true
		);

		if (
			$block_border_style &&
			array_key_exists(
				$block_border_style,
				$allowed_styles
			)
		) {

			$styles[] =
				'--rd3-row-block-border-style:' .
				$block_border_style;
		}


		/*
		 * Border width.
		 */
		$block_border_width = get_post_meta(
			$post_id,
			$keys['block_border_width'],
			true
		);

		if (
			'' !== $block_border_width &&
			is_numeric( $block_border_width )
		) {

			$block_border_width = max(
				0,
				min(
					50,
					(float) $block_border_width
				)
			);

			$styles[] =
				'--rd3-row-block-border-width:' .
				$block_border_width .
				'px';
		}


		/*
		 * Border colour.
		 */
		$block_border_color = get_post_meta(
			$post_id,
			$keys['block_border_color'],
			true
		);

		$block_border_color = sanitize_hex_color(
			$block_border_color
		);

		if ( $block_border_color ) {

			$styles[] =
				'--rd3-row-block-border-color:' .
				$block_border_color;
		}


		/*
		 * Border radius.
		 */
		$block_radius = get_post_meta(
			$post_id,
			$keys['block_radius'],
			true
		);

		if (
			'' !== $block_radius &&
			is_numeric( $block_radius )
		) {

			$block_radius = max(
				0,
				min(
					100,
					(float) $block_radius
				)
			);

			$styles[] =
				'--rd3-row-block-radius:' .
				$block_radius .
				'px';
		}
	}


	/*
	|--------------------------------------------------------------------------
	| Return
	|--------------------------------------------------------------------------
	*/

	if ( empty( $styles ) ) {
		return '';
	}


	return ' style="' .
		esc_attr(
			implode( ';', $styles ) . ';'
		) .
		'"';
}


/*
|--------------------------------------------------------------------------
| Admin Assets
|--------------------------------------------------------------------------
*/

/**
 * Load appearance controls only on the relevant edit screens.
 *
 * @param string $hook
 */
function rd3_content_blocks_appearance_admin_assets( $hook ) {

	if (
		'post.php' !== $hook &&
		'post-new.php' !== $hook
	) {
		return;
	}


	$screen = get_current_screen();

	if ( ! $screen ) {
		return;
	}


	if (
		! in_array(
			$screen->post_type,
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
	 * WordPress native colour picker.
	 */
	wp_enqueue_style(
		'wp-color-picker'
	);

	wp_enqueue_script(
		'wp-color-picker'
	);


	/*
	|--------------------------------------------------------------------------
	| Appearance Admin CSS
	|--------------------------------------------------------------------------
	*/

	$admin_css = <<<'CSS'

.rd3-appearance-settings {
	padding: 4px 0 8px;
}

.rd3-appearance-settings .description {
	margin-top: 4px;
}

.rd3-appearance-table {
	margin-top: 12px;
}

.rd3-appearance-table th {
	width: 180px;
	padding-left: 0;
}

.rd3-appearance-table td {
	padding-left: 10px;
}

.rd3-appearance-color-row {
	display: flex;
	align-items: center;
	gap: 8px;
}

.rd3-appearance-color {
	max-width: 120px;
}

.rd3-appearance-number {
	display: flex;
	align-items: center;
	gap: 8px;
}

.rd3-appearance-number input {
	width: 90px;
}

.rd3-appearance-number span {
	color: #646970;
}

CSS;

	wp_add_inline_style(
		'wp-color-picker',
		$admin_css
	);


	/*
	|--------------------------------------------------------------------------
	| Appearance Admin JavaScript
	|--------------------------------------------------------------------------
	*/

	$admin_js = <<<'JS'

jQuery(function($) {

	$('.rd3-appearance-color').wpColorPicker();

	$('.rd3-appearance-clear').on('click', function(event) {

		event.preventDefault();

		var target = $(this).data('target');
		var input  = $('#' + target);

		if (!input.length) {
			return;
		}

		input.val('');

		try {
			input.wpColorPicker('color', '');
		} catch (error) {
			// Leave the input cleared even if the picker API changes.
		}

		input.trigger('change');
	});

});

JS;

	wp_add_inline_script(
		'wp-color-picker',
		$admin_js
	);
}

add_action(
	'admin_enqueue_scripts',
	'rd3_content_blocks_appearance_admin_assets'
);
