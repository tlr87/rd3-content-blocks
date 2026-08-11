<?php

/**
 * RD3 Content Blocks
 *
 * Usage / Help administration.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


/*
 * Register Usage / Help submenu.
 *
 * The actual submenu registration is handled
 * by admin.php, so this file only provides
 * the page callback.
 */
function rd3_content_blocks_usage_page() {

    ?>

    <div class="wrap">

        <h1>How to Use RD3 Content Blocks</h1>

        <p>
            <strong>
                RD3 Content Blocks let you create reusable
                pieces of content that can be inserted into
                multiple WordPress Pages and Posts.
            </strong>
        </p>

        <p>
            Create a Content Block once, then insert it
            wherever you need it. If you update the block,
            the changes will appear wherever that block is used.
        </p>


        <hr>


        <h2>1. Create a Content Block</h2>

        <p>
            Go to
            <strong>
                RD3 Content Blocks → Content Blocks
            </strong>
            and select <strong>Add New</strong>.
        </p>

        <p>
            Give the Content Block a title and add your
            content using the normal WordPress Classic Editor.
        </p>


        <h2>2. What Can a Content Block Contain?</h2>

        <p>
            Content Blocks can contain normal WordPress
            content including:
        </p>

        <ul>
            <li>Text</li>
            <li>Images</li>
            <li>HTML</li>
            <li>Other WordPress shortcodes</li>
        </ul>


        <h2>3. Insert a Content Block</h2>

        <p>
            When editing a normal WordPress Page or Post,
            use the <strong>Insert RD3 Block</strong> button
            in the Classic Editor.
        </p>

        <p>
            Select the Content Block you want to insert.
        </p>

        <p>
            RD3 will insert the appropriate shortcode.
        </p>

        <p>
            Example:
        </p>

        <p>
            <code>[rd3_block id="2256"]</code>
        </p>


        <h2>4. Content Block Shortcodes</h2>

        <p>
            Every Content Block has its own shortcode.
            The shortcode is displayed in the Content Blocks
            management screen.
        </p>

        <p>
            You can use the <strong>Copy</strong> button
            beside the shortcode to copy it to the clipboard.
        </p>


        <h2>5. Content Block Rules</h2>

        <div
            class="notice notice-warning inline"
            style="margin:15px 0;"
        >

            <p>
                <strong>
                    Content Blocks cannot contain
                    <code>[rd3_block]</code> or
                    <code>[rd3_row]</code> shortcodes.
                </strong>
            </p>

            <p>
                A Content Block is a reusable piece of
                content. It cannot contain another RD3
                Content Block or an RD3 Row.
            </p>

            <p>
                If either shortcode is detected, the Content
                Block will not be allowed to be updated or
                published.
            </p>

        </div>


        <h2>6. Rows</h2>

        <p>
            RD3 Rows allow multiple existing Content Blocks
            to be combined into a single reusable layout.
        </p>

        <p>
            A Row can contain between
            <strong>1 and 5 Content Blocks</strong>.
        </p>

        <p>
            Go to:
        </p>

        <p>
            <strong>
                RD3 Content Blocks → Rows
            </strong>
        </p>

        <p>
            Create a Row and select the Content Blocks that
            should appear in each position.
        </p>


        <h2>7. Row Layout</h2>

        <p>
            Rows currently support two layouts:
        </p>

        <ul>

            <li>
                <strong>Inline</strong> —
                Content Blocks are displayed beside
                each other where space allows.
            </li>

            <li>
                <strong>Stacked</strong> —
                Content Blocks are displayed one
                underneath another.
            </li>

        </ul>

        <p>
            On smaller screens, Row content is designed
            to stack into a single column.
        </p>


        <h2>8. Insert a Row</h2>

        <p>
            When editing a normal WordPress Page or Post,
            use the <strong>Insert RD3 Row</strong> button
            in the Classic Editor.
        </p>

        <p>
            Select the Row you want to insert.
        </p>

        <p>
            RD3 will insert the Row shortcode.
        </p>

        <p>
            Example:
        </p>

        <p>
            <code>[rd3_row id="2257"]</code>
        </p>


        <h2>9. Row Shortcodes</h2>

        <p>
            Every Row has its own shortcode displayed in
            the Rows management screen.
        </p>

        <p>
            Use the <strong>Copy</strong> button beside the
            shortcode to copy it to the clipboard.
        </p>


        <h2>10. Row Rules</h2>

        <div
            class="notice notice-warning inline"
            style="margin:15px 0;"
        >

            <p>
                <strong>
                    Rows use existing Content Blocks.
                </strong>
            </p>

            <p>
                Rows do not contain Content Block content
                themselves.
            </p>

            <p>
                Select published Content Blocks from the
                Row Settings panel.
            </p>

            <p>
                Rows should not contain
                <code>[rd3_block]</code> or
                <code>[rd3_row]</code> shortcodes in
                their editor content.
            </p>

        </div>


        <h2>11. Using RD3 Blocks and Rows</h2>

        <p>
            The intended structure is:
        </p>

        <div
            style="
                background:#f6f7f7;
                border:1px solid #ccd0d4;
                padding:15px;
                margin:15px 0;
            "
        >

            <p>
                <strong>Page / Post</strong>
            </p>

            <p>
                &nbsp;&nbsp;&nbsp;↓
            </p>

            <p>
                <strong>RD3 Row</strong>
            </p>

            <p>
                &nbsp;&nbsp;&nbsp;↓
            </p>

            <p>
                <strong>Content Block 1</strong>
                &nbsp;&nbsp;+
                <strong>Content Block 2</strong>
                &nbsp;&nbsp;+
                <strong>Content Block 3</strong>
            </p>

        </div>


        <h2>12. Reusable Content</h2>

        <p>
            The main advantage of RD3 Content Blocks is
            that the same content can be reused in multiple
            places.
        </p>

        <p>
            For example, you could create a Content Block
            containing:
        </p>

        <ul>
            <li>A contact section</li>
            <li>A call to action</li>
            <li>A service description</li>
            <li>A business information section</li>
            <li>A footer-style information section</li>
        </ul>

        <p>
            That same Content Block can then be inserted
            into multiple Pages or Posts.
        </p>


        <h2>13. Updating a Content Block</h2>

        <p>
            When a Content Block is updated, the updated
            content is used wherever that Content Block
            shortcode appears.
        </p>

        <p>
            This means you do not need to manually update
            every Page or Post that uses the block.
        </p>


        <h2>14. Updating a Row</h2>

        <p>
            A Row controls which Content Blocks appear
            together and how they are arranged.
        </p>

        <p>
            If you change the Content Blocks assigned to a
            Row, the change will apply wherever that Row
            shortcode is used.
        </p>


        <h2>15. Recommended Structure</h2>

        <p>
            For larger pages, use Rows to control the layout
            and Content Blocks to control the reusable content.
        </p>

        <p>
            For example:
        </p>

        <ul>

            <li>
                <strong>Row:</strong>
                Service Introduction
            </li>

            <li>
                <strong>Content Block:</strong>
                Heading
            </li>

            <li>
                <strong>Content Block:</strong>
                Description
            </li>

            <li>
                <strong>Content Block:</strong>
                Call to Action
            </li>

        </ul>


        <hr>


        <h2>Quick Reference</h2>

        <table
            class="widefat striped"
            style="max-width:900px;"
        >

            <thead>

                <tr>

                    <th>
                        Item
                    </th>

                    <th>
                        Purpose
                    </th>

                    <th>
                        Example
                    </th>

                </tr>

            </thead>

            <tbody>

                <tr>

                    <td>
                        Content Block
                    </td>

                    <td>
                        Reusable content
                    </td>

                    <td>
                        <code>[rd3_block id="2256"]</code>
                    </td>

                </tr>

                <tr>

                    <td>
                        Row
                    </td>

                    <td>
                        Combines Content Blocks
                    </td>

                    <td>
                        <code>[rd3_row id="2257"]</code>
                    </td>

                </tr>

                <tr>

                    <td>
                        Insert RD3 Block
                    </td>

                    <td>
                        Inserts a Content Block into
                        the Classic Editor
                    </td>

                    <td>
                        Content Block selector
                    </td>

                </tr>

                <tr>

                    <td>
                        Insert RD3 Row
                    </td>

                    <td>
                        Inserts a Row into
                        the Classic Editor
                    </td>

                    <td>
                        Row selector
                    </td>

                </tr>

            </tbody>

        </table>


        <hr>


        <p>
            <strong>
                RD3 Content Blocks
            </strong>
            are designed to keep reusable content
            separate from page content and layout.
        </p>

    </div>

    <?php
}