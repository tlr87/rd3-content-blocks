# RD3 Content Blocks

Reusable Content Blocks and Rows for WordPress.

RD3 Content Blocks provides a simple way to create reusable pieces of content that can be inserted into WordPress Pages and Posts using shortcodes.

Rows allow multiple Content Blocks to be combined into reusable layouts.

---

## Current Version

**v0.2.0**

### v0.2.0 — Rows and Content Blocks Integration

This release introduces RD3 Rows and expands the Content Blocks system.

### Added

* RD3 Row custom post type
* Row Settings
* Up to 5 Content Blocks per Row
* Inline Row layout
* Stacked Row layout
* Row shortcode generation
* Row shortcode display
* Row shortcode copy button
* Content Block shortcode display
* Content Block shortcode copy button
* Insert RD3 Block button in the Classic Editor
* Insert RD3 Row button in the Classic Editor
* Content Block data passed to the Classic Editor
* Row data passed to the Classic Editor
* Reusable Row layouts

### Content Block Protection

Content Blocks cannot contain:

```text
[rd3_block]
```

or

```text
[rd3_row]
```

shortcodes.

Attempting to update or publish a Content Block containing either shortcode is prevented.

An error message is displayed inside the WordPress Publish box.

### Row Protection

Rows are designed to use existing Content Blocks rather than containing Content Block content directly.

Rows support up to five Content Blocks.

---

# Features

## Content Blocks

Content Blocks are reusable pieces of WordPress content.

They can contain:

* Text
* Images
* HTML
* WordPress shortcodes
* Other normal WordPress content

Each Content Block receives its own shortcode.

Example:

```text
[rd3_block id="2256"]
```

The shortcode can be copied directly from the Content Blocks administration screen.

---

## Rows

Rows combine existing Content Blocks into a reusable layout.

A Row can contain between **1 and 5 Content Blocks**.

Example:

```text
Row
├── Content Block 1
├── Content Block 2
├── Content Block 3
└── Content Block 4
```

Rows can use either:

* Inline layout
* Stacked layout

Example Row shortcode:

```text
[rd3_row id="2257"]
```

---

# WordPress Administration

RD3 Content Blocks adds its own administration section:

```text
RD3 Content Blocks
├── Content Blocks
├── Add New
├── Rows
├── Add New Row
└── How to Use
```

The Content Blocks and Rows are implemented as WordPress custom post types.

### Content Block post type

```text
rd3_content_block
```

### Row post type

```text
rd3_row
```

---

# Classic Editor Integration

RD3 Content Blocks integrates with the WordPress Classic Editor.

When editing a normal Page or Post, the editor provides:

```text
Insert RD3 Block
Insert RD3 Row
```

### Insert RD3 Block

Allows a published Content Block to be selected and inserted.

### Insert RD3 Row

Allows a published Row to be selected and inserted.

RD3 does not provide these insertion buttons when editing a Content Block or Row itself.

This prevents unnecessary nesting and keeps the structure simple.

---

# Content Block Structure

A Content Block is intended to contain reusable content.

```text
Content Block
└── Content
```

A Content Block must not contain another RD3 Block or RD3 Row.

The following are prohibited inside a Content Block:

```text
[rd3_block]
[rd3_row]
```

---

# Row Structure

A Row references existing Content Blocks.

```text
Row
├── Position 1 → Content Block
├── Position 2 → Content Block
├── Position 3 → Content Block
├── Position 4 → Content Block
└── Position 5 → Content Block
```

The Row does not duplicate the Content Block content.

This means that if a Content Block is updated, the updated Content Block content is automatically used wherever the Row is displayed.

---

# Shortcodes

## Content Block

```text
[rd3_block id="ID"]
```

Example:

```text
[rd3_block id="2256"]
```

## Row

```text
[rd3_row id="ID"]
```

Example:

```text
[rd3_row id="2257"]
```

---

# Recommended Usage

The intended relationship is:

```text
WordPress Page/Post
        │
        ├── RD3 Row
        │      │
        │      ├── Content Block
        │      ├── Content Block
        │      └── Content Block
        │
        └── Other Page Content
```

This keeps **content** and **layout** separate.

### Content Blocks

Use Content Blocks for reusable content.

Examples:

* Service descriptions
* Calls to action
* Contact information
* Introduction sections
* Reusable notices
* Business information

### Rows

Use Rows to control how multiple Content Blocks are grouped together.

---

# Example

A services section could be structured as:

```text
Page
└── Services Row
    ├── Computer Problems
    ├── Computer Solutions
    ├── Workplace Technology
    └── Managed Support
```

Each item can be maintained independently as a Content Block.

The Row controls their layout.

---

# Installation

1. Copy the `rd3-content-blocks` plugin directory into:

```text
wp-content/plugins/
```

2. Activate **RD3 Content Blocks** from the WordPress Plugins screen.

3. Open:

```text
RD3 Content Blocks
```

4. Create your first Content Block.

5. Create a Row if multiple Content Blocks need to be combined.

---

# Development Environment

Current development environment:

```text
WordPress
XAMPP
PHP
MySQL
WordPress Classic Editor
```

Local development site:

```text
RD3Reboot
```

---

# Plugin Structure

Current structure:

```text
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
```

The plugin is intentionally kept modular and relatively small.

---

# Development Principles

RD3 Content Blocks is being developed with the following principles:

* Keep the plugin simple.
* Keep files focused on specific responsibilities.
* Avoid unnecessary dependencies.
* Use native WordPress functionality where practical.
* Keep Content Blocks and Rows separate.
* Avoid unnecessary database systems.
* Avoid unnecessary complexity.
* Make functionality easy to test.
* Prevent invalid nesting.
* Keep the administration interface understandable.

---

# Current Development Status

## Completed

* [x] Content Block custom post type
* [x] Content Block administration
* [x] Content Block shortcode
* [x] Content Block shortcode column
* [x] Content Block copy button
* [x] Classic Editor integration
* [x] Insert RD3 Block button
* [x] RD3 Row custom post type
* [x] Row administration
* [x] Row Settings
* [x] Row layout selection
* [x] Up to 5 Content Blocks per Row
* [x] Row shortcode
* [x] Row shortcode column
* [x] Row copy button
* [x] Insert RD3 Row button
* [x] Content Block nesting protection
* [x] Row nesting protection
* [x] Publish box error messaging
* [x] Usage / Help documentation

---

# Version History

## v0.2.0

**Rows and Content Blocks Integration**

Added:

* RD3 Rows
* Row layouts
* Content Block selection
* Row shortcodes
* Row shortcode copying
* Classic Editor Row insertion
* Content Block/Row nesting protection
* Publish box validation
* Updated administration
* Updated documentation

---

## v0.1.0

**Initial Content Blocks Release**

Initial RD3 Content Blocks functionality including:

* Content Block custom post type
* Reusable Content Blocks
* Content Block shortcodes
* Shortcode display
* Shortcode copy functionality
* Classic Editor integration

---

# Roadmap

Future development may include:

* Improved Row visual editing
* Additional Row layout options
* Better frontend styling controls
* Improved shortcode management
* Additional editor integration
* Further administration improvements

Features will be added only where they provide a clear benefit without unnecessarily increasing the complexity of the plugin.

---

# License

This project is currently under development by RD3 Tech.

Copyright © RD3 Tech.
