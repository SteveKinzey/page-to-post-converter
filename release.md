# Pages to Posts Converter – Release Notes

## [v1.3.1] - 2025-04-22
### Added
- Sorting by publish date in the admin UI (Newest First / Oldest First)
- Maintains compatibility with existing filters: author, status, and keyword search

### Changed
- Improved UX for filtering and batch operations
- Refactored filter logic for cleaner admin output

### Fixed
- Minor visual issues with checkbox selections in list table


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
