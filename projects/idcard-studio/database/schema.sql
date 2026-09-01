-- ID Card Studio Database Schema

CREATE TABLE IF NOT EXISTS templates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    orientation VARCHAR(50) NOT NULL,
    category VARCHAR(50) NOT NULL,
    description TEXT,
    primary_color VARCHAR(50) DEFAULT '#6366F1',
    photo_shape VARCHAR(50) DEFAULT 'circle',
    qr_code_enabled TINYINT(1) DEFAULT 1,
    hologram_enabled TINYINT(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS cards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    dob DATE NULL,
    employee_id VARCHAR(50) NOT NULL,
    company VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    photo_path VARCHAR(555) DEFAULT '',
    primary_color VARCHAR(50) DEFAULT '#6366F1',
    photo_shape VARCHAR(50) DEFAULT 'circle',
    logo_placement VARCHAR(50) DEFAULT 'top-left',
    font_family VARCHAR(50) DEFAULT 'inter',
    qr_code_enabled TINYINT(1) DEFAULT 1,
    hologram_enabled TINYINT(1) DEFAULT 0,
    address TEXT,
    terms TEXT,
    emergency_contact VARCHAR(255) DEFAULT '',
    include_emergency TINYINT(1) DEFAULT 1,
    include_back_barcode TINYINT(1) DEFAULT 0,
    text_alignment VARCHAR(50) DEFAULT 'left',
    status VARCHAR(50) DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Clear tables first to prevent duplicates on reinstall
DELETE FROM templates;

-- Insert initial templates
INSERT INTO templates (name, orientation, category, description, primary_color, photo_shape, qr_code_enabled, hologram_enabled) VALUES
('Tech Corp Standard', 'portrait', 'Corporate', 'Clean, high-contrast corporate design with default colors.', '#6366F1', 'circle', 1, 0),
('Event Pass V2', 'landscape', 'Security', 'Horizontal layout with large barcode and profile photo.', '#14B8A6', 'square', 1, 1),
('University Staff', 'portrait', 'Employee', 'Modern geometric overlay design with deep brand frame.', '#0F172A', 'circle', 1, 1);

-- Clear cards first
DELETE FROM cards;

-- Insert initial mock cards
INSERT INTO cards (name, dob, employee_id, company, email, photo_path, primary_color, photo_shape, logo_placement, font_family, qr_code_enabled, hologram_enabled, address, terms, emergency_contact, include_emergency, include_back_barcode, text_alignment, status) VALUES
('Rahul Sharma', '1998-04-15', 'EMP-1024', 'TechNova Solutions', 'rahul.s@technova.com', 'https://lh3.googleusercontent.com/aida-public/AB6AXuBoF9akReTpLK_Dn9nQNstUXJbd3TvhXH0WKXqQuV86vcRiRqrZOxQjNYy8rD4AVxYGO83XfX-R6MIPRNk4Kjk9hYFvQUylDB7ToNnILPK4G9UmrM3Trj61v1WCxu9WY06rhNP_CySF9SPfUx1IPkFLo0lfGoIc3aoYm7P1H9wKfKa85fWyCYtotP8hDBLFlqqUJZPq9Xo8DbDYBgcJWUsbZWjLHyWFmMb0LvZ73NnRAzoxOaMIKbb7EA', '#6366F1', 'circle', 'top-left', 'inter', 1, 0, '100 Tech Park Drive, Suite 400\nSan Jose, CA 95110', 'This card is the property of TechNova Solutions. If found, please return to the company address.', 'Emergency Contact: HR Dept - Ext 401', 1, 1, 'left', 'Active'),
('Sarah Chen', '1996-09-22', 'EMP-1025', 'DesignHub', 'sarah.c@designhub.co', 'https://lh3.googleusercontent.com/aida-public/AB6AXuCjuoIarpzpHnS_9nEmI5m3C9wHXaXWk7m2EuBhv2i-A-hPRM2mYMXLU4D-U1zAvv4Zm5EI1iihGlrLc1GywhCr1oNkPnY_q2ViCpXAekw75KA5HP3dUplSu5SA_Swv2Rlth1vKKIFiAXI9_4V3edoqrg9YyD_mNbBoK1YCQnzru37ZaXlHiLdgKxXVVzS50WTIdXvRbBIhLb3VokkwjstCU13cYuJtG9KnbsnLZGshXwMCW_o_DyHYnQ', '#14B8A6', 'circle', 'top-center', 'inter', 1, 1, '250 Creative Ave\nSan Francisco, CA 94107', 'This card is the property of DesignHub. If found, please drop in any US mailbox.', 'Emergency Contact: Design Dept', 1, 0, 'center', 'Pending'),
('Marcus Wright', '1994-11-05', 'EMP-1026', 'Global Logistics', 'marcus.w@globallogistics.com', 'https://lh3.googleusercontent.com/aida-public/AB6AXuAQ3vxzueX44sFnoLJk7hQNo6GGEAPpVY8puGjNORCb8OSsi5eKmPtaITQzHxbZWWlJRXBOYN_9e1Ixbo8Qfiw_I9o66jC79Xc6HDqlHHmY5QR3blJKIsN1cP-QKo-WRoDNPqBBjadhW4KILU1V2d3vAAHYJGLIHuIDtKT3Dw1_SOxrMg7A4ELKj-1Rt8vT8tXBGxTuCpqqouDl-vXvivum87olUoOXLizEUYMHzo5PpJmE58lqo3JQFA', '#0F172A', 'circle', 'top-right', 'inter', 1, 1, '500 Shipping Way\nOakland, CA 94607', 'This card is the property of Global Logistics. If found, please return to security.', 'Emergency Contact: Logistics HQ', 1, 1, 'right', 'Printed');
