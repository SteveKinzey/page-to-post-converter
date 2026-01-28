=== Page to Post Converter ===
Contributors: SteveKinzey
Tags: convert, pages, posts, elementor, bricks, divi, beaver builder
Requires at least: 5.0
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 2.1.1
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Convert WordPress pages into posts with full page builder support. True conversion preserves IDs, metadata, and builder layouts.

== Description ==
A powerful utility to convert WordPress Pages to Posts while preserving page builder layouts and metadata.

**Features:**
* True conversion (keeps same post ID, no duplication)
* Bulk or one-by-one conversion
* Filters for author, post status, keyword search
* Sort by publish date
* Automatic featured image preservation
* Page builder data preservation (auto-detected)

**Supported Page Builders:**
* Classic Editor
* Full Site Editor (Gutenberg)
* Elementor
* Bricks
* Breakdance
* Divi
* Beaver Builder
* WPBakery

== Installation ==
1. Upload the plugin zip file via WordPress Admin > Plugins > Add New > Upload.
2. Activate the plugin.
3. Go to Tools > Page to Post Converter.
4. Configure settings at Settings > Page to Post Converter.

== Frequently Asked Questions ==

= Does this support page builders? =
Yes! The plugin auto-detects installed page builders and preserves their data during conversion.

= Will my page URLs change? =
The post ID stays the same, but the URL structure will change from /page-name/ to your post permalink structure.

= Can I convert multiple pages at once? =
Yes, use the checkboxes to select multiple pages and click "Convert Selected".

== Changelog ==

= 2.1.1 =
* Fixed PHP fatal errors (stray endif statements)
* True conversion using wp_update_post() instead of creating duplicates
* Added input sanitization for improved security
* Added "Delete Original Page" setting option
* Added support for Bricks, Breakdance, Divi, Beaver Builder, WPBakery
* Auto-detection of installed page builders

= 1.3.2 =
* Hide Elementor settings if Elementor is not installed
* Improved compatibility and conditional logic

= 1.3.1 =
* Sort by publish date (Newest First / Oldest First)
* Full compatibility with author, status, and keyword filters

= 1.3 =
* Added bulk and single page conversion options
* Added filters for author, post status, and title search

= 1.2 =
* Elementor metadata preservation
* Admin UI toggle to preserve Elementor data

== Upgrade Notice ==

= 2.1.1 =
Critical update - fixes PHP errors and improves conversion logic. Now supports multiple page builders.
