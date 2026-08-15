RD3 Content Blocks
==================

Reusable Content Blocks and Rows for WordPress.

RD3 Content Blocks provides a simple way to create reusable pieces of content that can be inserted into WordPress Pages and Posts using shortcodes.

Rows allow multiple Content Blocks to be combined into reusable layouts.

The plugin is designed to keep **content, layout, and page structure separate** while remaining lightweight and easy to manage from the WordPress administration area.

* * *

Current Version
---------------

**v0.2.0**

### v0.2.0 — Rows and Content Blocks Integration

This release introduces RD3 Rows and expands the Content Blocks system with reusable layouts, administration tools, usage tracking, sorting, and nesting protection.

* * *

Features
--------

### Content Blocks

Content Blocks are reusable pieces of WordPress content.

They can contain:

*   Text
*   Images
*   HTML
*   WordPress shortcodes
*   Other normal WordPress content

Each Content Block receives its own shortcode.

    [rd3_block id="2256"]

The shortcode can be copied directly from the Content Blocks administration screen.

### Rows

Rows combine existing Content Blocks into a reusable layout.

A Row can contain between **1 and 5 Content Blocks**.

    Row
    ├── Content Block 1
    ├── Content Block 2
    ├── Content Block 3
    └── Content Block 4

Rows support:

*   Inline layout
*   Stacked layout

    [rd3_row id="2257"]

* * *

WordPress Administration
------------------------

RD3 Content Blocks adds its own administration section:

    RD3 Content Blocks
    ├── Content Blocks
    ├── Add New
    ├── Rows
    ├── Add New Row
    └── How to Use

The Content Blocks and Rows are implemented as WordPress custom post types.

### Content Block post type

    rd3_content_block

### Row post type

    rd3_row

* * *

Content Block Administration
----------------------------

The Content Blocks administration screen provides information to help manage reusable content.

Each Content Block includes:

*   Title
*   Edit controls
*   Shortcode
*   Copy shortcode button
*   Publication status
*   Used On information

### Shortcode

Each Content Block displays its shortcode directly in the administration list.

    [rd3_block id="2401"]

A **Copy** button allows the shortcode to be copied without manually selecting it.

* * *

Used On
-------

The Content Blocks administration screen includes a **Used On** column.

This shows where a Content Block is currently being used.

    Services
    Page — Via Row: IT Services

or:

    SEO Test Page
    Page — Direct

A Content Block can therefore be used:

*   Directly on a Page or Post
*   Inside an RD3 Row
*   In multiple locations

When a Content Block is used through a Row, the administration screen identifies the Row relationship.

    Services
    Page — Via Row: IT Services

### Used On Sorting

The **Used On** column is sortable.

Clicking the column heading sorts Content Blocks alphabetically according to where they are used.

The column supports:

    A → Z

and:

    Z → A

The WordPress sorting arrow indicates the current sort direction.

For Content Blocks used in multiple locations, the first alphabetical usage title determines the Content Block's position in the list.

Content Blocks that are not currently used are placed at the end when sorting alphabetically.

* * *

Classic Editor Integration
--------------------------

RD3 Content Blocks integrates with the WordPress Classic Editor.

When editing a normal Page or Post, the editor provides:

    Insert RD3 Block
    Insert RD3 Row

### Insert RD3 Block

Allows a published Content Block to be selected and inserted.

    [rd3_block id="2256"]

### Insert RD3 Row

Allows a published Row to be selected and inserted.

    [rd3_row id="2257"]

RD3 does not provide these insertion buttons when editing a Content Block or Row itself.

This prevents unnecessary nesting and keeps the structure simple.

* * *

Content Block Protection
------------------------

Content Blocks are designed to contain reusable content only.

A Content Block cannot contain:

    [rd3_block]

or:

    [rd3_row]

Attempting to update or publish a Content Block containing either shortcode is prevented.

An error message is displayed inside the WordPress Publish box explaining the problem.

This prevents recursive or unnecessary Content Block and Row nesting.

* * *

Content Block Structure
-----------------------

    Content Block
    └── Content

A Content Block must not contain another RD3 Block or RD3 Row.

The following are prohibited:

    [rd3_block]
    [rd3_row]

* * *

Row Protection
--------------

Rows are designed to use existing Content Blocks rather than containing Content Block content directly.

Rows support up to five Content Blocks.

A Row references existing Content Blocks rather than duplicating their content.

This means that when a Content Block is updated, the updated content is automatically used wherever the Row appears.

* * *

Row Structure
-------------

    Row
    ├── Position 1 → Content Block
    ├── Position 2 → Content Block
    ├── Position 3 → Content Block
    ├── Position 4 → Content Block
    └── Position 5 → Content Block

A Row can contain between **1–5 Content Blocks**.

The Row controls the layout.

The Content Blocks control the content.

* * *

Row Layouts
-----------

### Inline

Content Blocks are displayed alongside each other according to the available space.

    ┌────────────┐ ┌────────────┐ ┌────────────┐
    │   Block 1  │ │   Block 2  │ │   Block 3  │
    └────────────┘ └────────────┘ └────────────┘

### Stacked

Content Blocks are displayed vertically.

    ┌──────────────────────┐
    │       Block 1        │
    └──────────────────────┘
    
    ┌──────────────────────┐
    │       Block 2        │
    └──────────────────────┘
    
    ┌──────────────────────┐
    │       Block 3        │
    └──────────────────────┘

* * *

Shortcodes
----------

### Content Block

    [rd3_block id="ID"]

### Row

    [rd3_row id="ID"]

* * *

Recommended Usage
-----------------

    WordPress Page/Post
            │
            ├── RD3 Row
            │      │
            │      ├── Content Block
            │      ├── Content Block
            │      └── Content Block
            │
            └── Other Page Content

This keeps **content** and **layout** separate.

### Content Blocks

Use Content Blocks for reusable content.

*   Service descriptions
*   Calls to action
*   Contact information
*   Introduction sections
*   Reusable notices
*   Business information
*   Frequently used content sections

### Rows

Use Rows to control how multiple Content Blocks are grouped together.

*   Service cards
*   Feature sections
*   Call-to-action groups
*   Information panels
*   Reusable page sections

* * *

Installation
------------

1.  Copy the `rd3-content-blocks` plugin directory into:
    
        wp-content/plugins/
    
2.  Activate **RD3 Content Blocks** from the WordPress Plugins screen.
3.  Open:
    
        RD3 Content Blocks
    
4.  Create your first Content Block.
5.  Create a Row if multiple Content Blocks need to be combined.
6.  Insert the Content Block or Row shortcode into a Page or Post.

* * *

Development Environment
-----------------------

    WordPress
    XAMPP
    PHP
    MySQL
    WordPress Classic Editor

Local development site:

    RD3Reboot

* * *

Plugin Structure
----------------

    rd3-content-blocks/
    │
    ├── rd3-content-blocks.php
    │
    ├── admin/
    │   ├── admin.php
    │   ├── blocks.php
    │   ├── rows.php
    │   ├── editor.php
    │   └── usage.php
    │
    └── assets/
        └── editor.js

* * *

Development Principles
----------------------

*   Keep the plugin simple.
*   Keep files focused on specific responsibilities.
*   Avoid unnecessary dependencies.
*   Use native WordPress functionality where practical.
*   Keep Content Blocks and Rows separate.
*   Avoid unnecessary database systems.
*   Avoid unnecessary complexity.
*   Make functionality easy to test.
*   Prevent invalid nesting.
*   Keep the administration interface understandable.
*   Keep reusable content separate from layout.
*   Prefer predictable WordPress administration behaviour.
*   Keep the plugin lightweight.

* * *

Current Development Status
--------------------------

### Content Blocks

*   ☑ Content Block custom post type
*   ☑ Content Block administration
*   ☑ Content Block shortcode
*   ☑ Content Block shortcode column
*   ☑ Content Block copy button
*   ☑ Content Block Used On column
*   ☑ Content Block usage detection
*   ☑ Content Block usage count
*   ☑ Used On sorting
*   ☑ A–Z Used On sorting
*   ☑ Z–A Used On sorting
*   ☑ Unused Content Blocks placed at the end when sorting
*   ☑ Content Block nesting protection
*   ☑ Publish box error messaging

### Rows

*   ☑ RD3 Row custom post type
*   ☑ Row administration
*   ☑ Row Settings
*   ☑ Row layout selection
*   ☑ Up to 5 Content Blocks per Row
*   ☑ Inline Row layout
*   ☑ Stacked Row layout
*   ☑ Row shortcode
*   ☑ Row shortcode column
*   ☑ Row copy button
*   ☑ Row usage detection
*   ☑ Row nesting protection

### Editor

*   ☑ Classic Editor integration
*   ☑ Insert RD3 Block button
*   ☑ Insert RD3 Row button
*   ☑ Content Block data passed to Classic Editor
*   ☑ Row data passed to Classic Editor
*   ☑ Prevent insertion tools inside Content Blocks
*   ☑ Prevent insertion tools inside Rows

### Administration

*   ☑ RD3 Content Blocks administration menu
*   ☑ Content Blocks administration
*   ☑ Rows administration
*   ☑ Shortcode display
*   ☑ Shortcode copy buttons
*   ☑ Used On information
*   ☑ Sortable Used On column
*   ☑ How to Use documentation
*   ☑ Publish validation
*   ☑ Error messaging

* * *

Version History
---------------

### v0.2.0

**Rows and Content Blocks Integration**

Added:

*   RD3 Rows
*   Row layouts
*   Content Block selection
*   Row shortcodes
*   Row shortcode copying
*   Classic Editor Row insertion
*   Content Block/Row nesting protection
*   Publish box validation
*   Used On tracking
*   Content Block usage detection
*   Sortable Used On column
*   A–Z and Z–A Used On sorting
*   Updated administration
*   Updated documentation

### v0.1.0

**Initial Content Blocks Release**

Initial RD3 Content Blocks functionality including:

*   Content Block custom post type
*   Reusable Content Blocks
*   Content Block shortcodes
*   Shortcode display
*   Shortcode copy functionality
*   Classic Editor integration

* * *

Roadmap
-------

Future development may include:

*   Improved Row visual editing
*   Additional Row layout options
*   Better frontend styling controls
*   Improved shortcode management
*   Additional editor integration
*   Further administration improvements
*   Improved usage reporting

Features will only be added where they provide a clear benefit without unnecessarily increasing the complexity of the plugin.

* * *

License
-------

This project is currently under development by RD3 Tech.

Copyright © RD3 Tech.