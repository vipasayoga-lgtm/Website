-- Legal consent records, append-only (never updated/deleted) per the
-- project's established non-destructive convention. `booking_id` is
-- nullable: Stage 5 records one general platform/liability consent per
-- client (booking_id NULL) when their profile reaches 100%; a future
-- per-booking "before Join Session" consent reuses this same table with
-- booking_id set, rather than needing a second table.

CREATE TABLE consents (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    booking_id BIGINT UNSIGNED NULL,
    consent_version VARCHAR(20) NOT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent VARCHAR(255) NULL,
    timezone VARCHAR(60) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_consent_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_consent_booking FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE SET NULL,
    INDEX idx_consent_user (user_id)
) ENGINE=InnoDB;
