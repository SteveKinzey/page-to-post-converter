# Page to Post Converter – Release Notes

## [v2.1.1] - 2026-01-28
### Fixed
- PHP fatal errors caused by stray `endif` statements
- Conversion now uses `wp_update_post()` for true conversion (same ID) instead of `wp_insert_post()` which created duplicates

### Added
- Input sanitization for improved security (`sanitize_text_field`, `absint`, `esc_attr`, `esc_url`)
- "Delete Original Page" setting option
- Support for multiple page builders (auto-detected):
  - Bricks
  - Breakdance
  - Divi
  - Beaver Builder
  - WPBakery
- Success message showing count of converted pages

### Changed
- Cleaner HTML table structure in admin UI
- Featured image handling preserved automatically with true conversion

---

## [v1.3.2] - 2025-04-22
### Added
- Plugin now runs independently of Elementor
- Conditional display of Elementor settings only if Elementor is active

---

## [v1.3.1] - 2025-04-22
### Added
- Sort by publish date (Newest First / Oldest First) in the converter interface
- Sort integrates with existing filters: author, post status, and keyword search

### Changed
- Refined admin UI for smoother filtering and batch actions

### Fixed
- Minor display issues with bulk convert checkboxes and Elementor toggle persistency

---

## [v1.3] - 2025-04-22
### Added
- Bulk conversion of selected Pages to Posts
- "Convert Now" button for one-by-one conversion
- Filters: Author, Status, Title Search

---

## [v1.2] - 2025-04-22
### Added
- Elementor metadata preservation (`_elementor_data`, `_elementor_edit_mode`, etc.)
- Admin settings page with toggle for "Preserve Elementor Data"
- Automatic featured image carryover

### Changed
- Default behavior now retains Elementor layout when converting

### Fixed
- Issues where Elementor pages lost layout data after conversion
