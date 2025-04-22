=== Page to Post Converter ===
Contributors: SteveKinzey
Tags: convert, pages, posts, elementor
Requires at least: 5.0
Tested up to: 6.5
Requires PHP: 7.4
Stable tag: 1.3.2
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Convert WordPress pages into posts. Supports bulk conversion, filtering, and Elementor data preservation when available.

== Description ==
A simple utility to convert WordPress Pages to Posts. Features include:
- Bulk conversion
- One-by-one conversion
- Filters for author, post status, keyword search, and publish date
- Automatic featured image carryover
- Elementor data preservation if Elementor is installed

== Installation ==
1. Upload the plugin zip file via WordPress Admin > Plugins > Add New > Upload.
2. Activate the plugin.
3. Go to Tools > Page to Post Converter.

== Frequently Asked Questions ==
= Does this support Elementor? =
Yes, if Elementor is installed. Otherwise, the plugin works independently.

== Changelog ==
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
* Elementor metadata preservation (_elementor_data, etc.)
* Admin UI toggle to preserve Elementor data

== Upgrade Notice ==
= 1.3.2 =
Improved compatibility — Elementor is now optional.
