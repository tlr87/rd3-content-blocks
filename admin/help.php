<?php

/**
 * RD3 Content Blocks
 *
 * How to Use administration page.
 */


/*
 * Prevent direct access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/*
 * ============================================================
 * HOW TO USE
 * ============================================================
 */

function rd3_content_blocks_usage_page() {
	?>

	<div class="wrap">

		<h1>
			RD3 Content Blocks
		</h1>

		<h2>
			How to Use
		</h2>


		<h3>
			Content Blocks
		</h3>

		<p>
			Content Blocks allow you to create reusable pieces of
			content that can be placed on multiple Pages or Posts.
		</p>


		<h3>
			Rows
		</h3>

		<p>
			Rows combine existing Content Blocks into a single layout.
			They can be displayed inline or stacked.
		</p>


		<h3>
			Advanced Rows
		</h3>

		<p>
			Advanced Rows allow different Content Blocks to be
			enabled or disabled using shortcode attributes.
		</p>

		<p>
			For example:
		</p>

		<p>
			<code>
				[rd3_advanced_row brok="1" bett="1" prot="1" mana="1"]
			</code>
		</p>

		<p>
			Use <strong>1</strong> to display a Content Block and
			<strong>0</strong> to hide it.
		</p>


		<h3>
			Advanced Row Editor
		</h3>

		<p>
			When an Advanced Row shortcode is added to a Post or Page,
			the editor will detect it and display the Advanced Row
			controls.
		</p>

		<p>
			Use the checkboxes to turn the available Content Blocks
			on or off without manually editing the shortcode.
		</p>

		<p>
			<strong>
				After adding or changing an Advanced Row shortcode,
				click Publish, Update or Save Draft to save the page.
			</strong>
		</p>


		<h3>
			Important
		</h3>

		<p>
			RD3 Content Blocks uses existing Content Blocks.
			Advanced Rows do not contain the Content Block content
			themselves.
		</p>

	</div>

	<?php
}