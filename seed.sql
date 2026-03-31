CREATE TABLE IF NOT EXISTS applicants (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    address VARCHAR(255) NOT NULL,
    position_applied VARCHAR(120) NOT NULL,
    years_experience INT UNSIGNED NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO applicants (full_name, email, phone, address, position_applied, years_experience, image_path)
VALUES
('Ava Thompson', 'ava.thompson@example.com', '+1-555-0101', '121 Park Avenue, New York, NY', 'Frontend Developer', 3, 'https://picsum.photos/seed/applicant1/300/300'),
('Noah Carter', 'noah.carter@example.com', '+1-555-0102', '80 Sunset Blvd, Los Angeles, CA', 'Backend Developer', 5, 'https://picsum.photos/seed/applicant2/300/300'),
('Mia Rodriguez', 'mia.rodriguez@example.com', '+1-555-0103', '55 Lake Shore Dr, Chicago, IL', 'UI/UX Designer', 4, 'https://picsum.photos/seed/applicant3/300/300'),
('Liam Patel', 'liam.patel@example.com', '+1-555-0104', '410 Pine Street, Seattle, WA', 'QA Engineer', 2, 'https://picsum.photos/seed/applicant4/300/300'),
('Sophia Kim', 'sophia.kim@example.com', '+1-555-0105', '900 Market Street, San Francisco, CA', 'Project Manager', 6, 'https://picsum.photos/seed/applicant5/300/300');
