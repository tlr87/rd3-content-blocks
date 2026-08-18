<?php

/**
 * RD3 Advanced Row Editor
 *
 * Detects existing [rd3_advanced_row] shortcodes
 * in the current Post/Page editor and provides
 * simple on/off controls for their block attributes.
 */


/*
 * ============================================================
 * ADD EDITOR META BOX
 * ============================================================
 */

function rd3_advanced_row_editor_meta_box() {

	/*
	 * Only Posts and Pages.
	 */
	$screen = get_current_screen();

	if ( ! $screen ) {
		return;
	}

	if (
		! in_array(
			$screen->post_type,
			array(
				'post',
				'page',
			),
			true
		)
	) {

		return;
	}


	/*
	 * Get the current post.
	 */
	$post = get_post();

	if ( ! $post ) {
		return;
	}


	/*
	 * Detect an Advanced Row shortcode
	 * in the current post content.
	 */
	if (
		! preg_match(
			'/\[rd3_advanced_row\b[^\]]*\]/i',
			$post->post_content
		)
	) {

		/*
		 * No Advanced Row shortcode.
		 *
		 * Do not register the meta box at all.
		 */
		return;
	}


	/*
	 * Add the meta box only when
	 * an Advanced Row shortcode exists.
	 */
	add_meta_box(
		'rd3_advanced_row_editor',
		'Advanced Row',
		'rd3_advanced_row_editor_meta_box_callback',
		array(
			'post',
			'page',
		),
        'normal',
        'low'
	);
}

add_action(
	'add_meta_boxes',
	'rd3_advanced_row_editor_meta_box'
);


/*
 * ============================================================
 * DISPLAY EDITOR
 * ============================================================
 */

function rd3_advanced_row_editor_meta_box_callback( $post ) {

	$content = $post->post_content;


	/*
	 * Find Advanced Row shortcodes.
	 */
	$pattern =
		'/\[rd3_advanced_row\b([^\]]*)\]/i';


	preg_match_all(
		$pattern,
		$content,
		$matches
	);


	/*
	 * No shortcode.
	 */
	if (
		empty(
			$matches[0]
		)
	) {

		return;
	}


	?>

<p>
	<strong>Advanced Row</strong>
</p>

<p>
	Add an Advanced Row shortcode to this page to enable the controls below.
</p>

<p>
	After adding or changing the shortcode, click
	<strong>Publish</strong> or <strong>Save Draft</strong>
	to update the page.
</p>

<p>
	Use the checkboxes below to turn individual Content Blocks
	on or off for this page.
</p>

	<?php


	/*
	 * Process every Advanced Row shortcode.
	 */
	foreach (
		$matches[0] as $index => $shortcode
	) {

		$attributes_string =
			isset(
				$matches[1][ $index ]
			)
				? $matches[1][ $index ]
				: '';


		/*
		 * Parse shortcode attributes.
		 */
		$attributes =
			shortcode_parse_atts(
				$attributes_string
			);


		if (
			! is_array(
				$attributes
			)
		) {

			continue;
		}


		/*
		 * Find the Advanced Row.
		 */
		$advanced_row =
			rd3_advanced_row_editor_find_row(
				$attributes
			);


		if (
			! $advanced_row
		) {

			continue;
		}


		/*
		 * Get configured blocks.
		 */
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
			||
			empty(
				$blocks
			)
		) {

			continue;
		}


		?>

		<div
			class="rd3-advanced-row-editor-group"
			style="
				margin:0 0 15px;
				padding:0 0 15px;
				border-bottom:1px solid #ddd;
			"
		>

			<p
				style="
					margin:0 0 10px;
				"
			>

				<strong>
					<?php
					echo esc_html(
						$advanced_row->post_title
					);
					?>
				</strong>

			</p>


			<?php

			/*
			 * Display each configured block.
			 */
			foreach (
				$blocks as $position => $block
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
				 * Get Content Block title.
				 */
				$block_title =
					get_the_title(
						$block_id
					);


				/*
				 * Missing shortcode attribute = OFF.
				 */
				$value =
					isset(
						$attributes[
							$shortcode_id
						]
					)
						? (string)
							$attributes[
								$shortcode_id
							]
						: '0';


				$checked =
					'1' === $value;

				?>

				<p
					style="
						margin:0 0 8px;
					"
				>

					<label>

						<input
							type="checkbox"
							class="rd3-advanced-row-toggle"
							data-shortcode-index="<?php echo esc_attr( $index ); ?>"
							data-shortcode-id="<?php echo esc_attr( $shortcode_id ); ?>"
							<?php checked( $checked ); ?>
						>

						<?php
						echo esc_html(
							$block_title
						);
						?>

					</label>

				</p>

				<?php
			}
			?>

		</div>

		<?php
	}
}


/*
 * ============================================================
 * FIND ADVANCED ROW
 * ============================================================
 */

function rd3_advanced_row_editor_find_row(
	$attributes
) {

	if (
		empty(
			$attributes
		)
	) {

		return false;
	}


	$advanced_rows =
		get_posts(
			array(
				'post_type'      => 'rd3_advanced_row',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => 'ID',
				'order'          => 'ASC',
			)
		);


	if (
		empty(
			$advanced_rows
		)
	) {

		return false;
	}


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


			$shortcode_id =
				isset(
					$block['id']
				)
					? sanitize_key(
						$block['id']
					)
					: '';


			if (
				''
				!==
				$shortcode_id
				&&
				array_key_exists(
					$shortcode_id,
					$attributes
				)
			) {

				return $advanced_row;
			}
		}
	}


	return false;
}


/*
 * ============================================================
 * EDITOR JAVASCRIPT
 * ============================================================
 */

function rd3_advanced_row_editor_script() {

	$screen = get_current_screen();

	if ( ! $screen ) {
		return;
	}


	if (
		! in_array(
			$screen->post_type,
			array(
				'post',
				'page',
			),
			true
		)
	) {

		return;
	}

	?>

	<script>

	document.addEventListener(
		'DOMContentLoaded',
		function() {

			var editor =
				document.getElementById(
					'content'
				);


			var box =
				document.getElementById(
					'rd3_advanced_row_editor'
				);


			if (
				! editor
				||
				! box
			) {

				return;
			}


			var toggles =
				box.querySelectorAll(
					'.rd3-advanced-row-toggle'
				);


			toggles.forEach(
				function( toggle ) {

					toggle.addEventListener(
						'change',
						function() {

							var shortcodeIndex =
								toggle.getAttribute(
									'data-shortcode-index'
								);


							var shortcodeId =
								toggle.getAttribute(
									'data-shortcode-id'
								);


							if (
								! shortcodeId
								||
								shortcodeIndex === null
							) {

								return;
							}


							var value =
								toggle.checked
									? '1'
									: '0';


							var content =
								editor.value;


							/*
							 * Find all Advanced Row
							 * shortcodes.
							 */
							var pattern =
								/\[rd3_advanced_row\b[^\]]*\]/gi;


							var currentIndex = 0;


							content =
								content.replace(
									pattern,
									function( shortcode ) {

										/*
										 * Only modify the
										 * selected shortcode.
										 */
										if (
											String(
												currentIndex
											)
											!==
											String(
												shortcodeIndex
											)
										) {

											currentIndex++;

											return shortcode;
										}


										currentIndex++;


										/*
										 * Existing attribute.
										 */
										var attributePattern =
											new RegExp(
												'([\\s])' +
												escapeRegExp(
													shortcodeId
												) +
												'=([\'"])' +
												'[01]' +
												'\\2',
												'i'
											);


										if (
											attributePattern.test(
												shortcode
											)
										) {

											return shortcode.replace(
												attributePattern,
												function(
													match,
													space,
													quote
												) {

													return (
														space +
														shortcodeId +
														'=' +
														quote +
														value +
														quote
													);

												}
											);

										}


										/*
										 * Attribute does not exist.
										 *
										 * Only add it when
										 * switching ON.
										 */
										if (
											'1' === value
										) {

											return shortcode.replace(
												']',
												' ' +
												shortcodeId +
												'="1"]'
											);

										}


										return shortcode;

									}
								);


							editor.value =
								content;


							/*
							 * Tell WordPress that the
							 * editor content changed.
							 */
							editor.dispatchEvent(
								new Event(
									'input',
									{
										bubbles: true
									}
								)
							);

						}
					);

				}
			);


			/*
			 * Escape text for RegExp.
			 */
			function escapeRegExp(
				string
			) {

				return string.replace(
					/[.*+?^${}()|[\]\\]/g,
					'\\$&'
				);

			}

		}
	);

	</script>

	<?php
}


add_action(
	'admin_footer-post.php',
	'rd3_advanced_row_editor_script'
);

add_action(
	'admin_footer-post-new.php',
	'rd3_advanced_row_editor_script'
);

add_action(
	'admin_footer-page.php',
	'rd3_advanced_row_editor_script'
);

add_action(
	'admin_footer-page-new.php',
	'rd3_advanced_row_editor_script'
);