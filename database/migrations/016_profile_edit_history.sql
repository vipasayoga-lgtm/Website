-- Versioned profile-edit audit trail, append-only — never updated or
-- deleted, per the project's established non-destructive convention.
-- Distinct from `activity_logs` (a flat coarse event log): this is
-- field-level old/new-value diffing, one row per changed field per save.
-- `version` is a simple per-user incrementing counter across all fields,
-- not a per-field version — sufficient for "Admin can compare versions"
-- without needing per-field version branching.

CREATE TABLE profile_edit_history (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    field_name VARCHAR(100) NOT NULL,
    old_value TEXT NULL,
    new_value TEXT NULL,
    changed_by_user_id BIGINT UNSIGNED NOT NULL,
    version INT UNSIGNED NOT NULL,
    browser VARCHAR(60) NULL,
    device VARCHAR(30) NULL,
    timezone VARCHAR(60) NULL,
    note VARCHAR(500) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_profileedithistory_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_profileedithistory_changedby FOREIGN KEY (changed_by_user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_profileedithistory_user (user_id, version)
) ENGINE=InnoDB;
