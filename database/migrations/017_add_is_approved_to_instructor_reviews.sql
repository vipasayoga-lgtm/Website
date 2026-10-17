-- Migration: Add is_approved column to instructor_reviews
-- Adds approval flag required for frontpage review moderation

ALTER TABLE instructor_reviews
ADD COLUMN is_approved TINYINT(1) NOT NULL DEFAULT 0 AFTER review_text;
