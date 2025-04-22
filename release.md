# Pages to Posts Converter – Release Notes

## [v1.3.1] - 2025-04-22
### Added
<<<<<<< HEAD
- Sorting by publish date in the admin UI (Newest First / Oldest First)
- Maintains compatibility with existing filters: author, status, and keyword search

### Changed
- Improved UX for filtering and batch operations
- Refactored filter logic for cleaner admin output

### Fixed
- Minor visual issues with checkbox selections in list table

=======
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
- “Convert Now” button for one-by-one conversion
- Filters: Author, Status, Title Search

---
>>>>>>> e95b082 (Update release.md with v1.3.1 changelog)

## [v1.2] - 2025-04-22
### Added
- Elementor metadata preservation (`_elementor_data`, `_elementor_edit_mode`, etc.)
- Admin settings page with toggle for “Preserve Elementor Data”
- Automatic featured image carryover

### Changed
- Default behavior now retains Elementor layout when converting

### Fixed
- Issues where Elementor pages lost layout data after conversion

---

## [v1.1] - YYYY-MM-DD
### Added
- Core conversion logic for Pages → Posts
- Maintains post content, excerpt, status, and author

### Known Issues
- Elementor layouts were not preserved in this version
