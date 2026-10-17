-- Migration 018: Add UPI / QR payment support and screenshot attachment
USE vipasa_yoga;

ALTER TABLE payments
    MODIFY COLUMN gateway ENUM('razorpay','stripe','paypal','wallet','upi','qr','other') NOT NULL DEFAULT 'upi',
    ADD COLUMN screenshot_path VARCHAR(255) NULL AFTER raw_response;
