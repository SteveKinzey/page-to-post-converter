# Page to Post Converter

Converts WordPress Pages into Posts with full page builder compatibility.

## Features
- Convert individual or multiple pages into posts
- True conversion (same ID, no duplication)
- Preserve featured images
- Filters: Author, Status, Keyword Search
- Sort by Publish Date
- Page builder data preservation

## Supported Page Builders
- Classic Editor
- Full Site Editor (Gutenberg)
- Elementor
- Bricks
- Breakdance
- Divi
- Beaver Builder
- WPBakery

## Version 2.1.1
- Fixed PHP fatal errors
- True conversion using `wp_update_post()` instead of duplication
- Added input sanitization for security
- Added "Delete Original Page" option
- Support for multiple page builders (auto-detected)

## Installation
1. Upload the plugin zip file via WordPress Admin > Plugins > Add New > Upload
2. Activate the plugin
3. Go to Tools > Page to Post Converter

## Settings
Go to Settings > Page to Post Converter to configure:
- Preserve Page Builder Data
- Delete Original Page after conversion
- Category/Tag conversion options
