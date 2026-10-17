-- Emergency contact fields for the client profile page redesign — sensible
-- for a platform where clients share health/injury information and attend
-- live physical sessions. Both nullable: entirely optional, never required
-- to use the platform.

ALTER TABLE client_profiles
    ADD COLUMN emergency_contact_name VARCHAR(150) NULL AFTER medical_notes,
    ADD COLUMN emergency_contact_phone VARCHAR(20) NULL AFTER emergency_contact_name;
