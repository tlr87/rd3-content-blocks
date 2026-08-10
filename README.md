# RD3 Content Blocks

A lightweight WordPress plugin for creating reusable content blocks that can be inserted into Pages and Posts using a shortcode or the Classic Editor.

## Features

* Create unlimited Content Blocks
* Use Content Blocks anywhere in WordPress Pages and Posts
* Insert blocks using the Classic Editor
* Use the `[rd3_block]` shortcode
* Copy a block's shortcode from the Content Blocks list
* Manage blocks using the normal WordPress admin interface
* Includes a simple How to Use page
* Prevents Content Blocks from containing other Content Blocks
* Lightweight with no external services or databases

## Requirements

* WordPress
* Classic Editor
* PHP version supported by your WordPress installation

## Usage

Create a Content Block under:

**RD3 Content Blocks → Add New**

Give the block a name and add your content.

Once saved, the Content Blocks list provides a shortcode such as:

```text
[rd3_block id="2256"]
```

Copy the shortcode and place it inside a WordPress Page or Post.

You can also use the **Insert RD3 Block** button in the Classic Editor.

## Important Rule

A Content Block cannot contain another RD3 Content Block.

For example, this should **not** be placed inside a Content Block:

```text
[rd3_block id="2256"]
```

If an RD3 Content Block shortcode is detected inside another Content Block, the plugin prevents the block from being saved and displays an error.

## Why?

RD3 Content Blocks are intended to be simple reusable pieces of content.

Preventing blocks from containing other blocks keeps the system predictable and avoids nested or recursive Content Blocks.

## License

This project is licensed under the **GNU General Public License v2.0 or later (GPL-2.0-or-later)**.

You are free to:

* Use the software
* Copy the software
* Modify the software
* Redistribute the software
* Create derivative works

See the `LICENSE` file for the full license text.

## Author

RD3 Tech

https://rd3tech.com

## Status

Early development.

The plugin is being developed as a lightweight reusable Content Block system for WordPress.

Features and implementation may change as development continues.
