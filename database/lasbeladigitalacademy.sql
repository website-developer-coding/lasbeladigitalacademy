


SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `contact_messages`;
DROP TABLE IF EXISTS `enrollments`;
DROP TABLE IF EXISTS `gallery`;
DROP TABLE IF EXISTS `syllabus`;
DROP TABLE IF EXISTS `fees`;
DROP TABLE IF EXISTS `services`;
DROP TABLE IF EXISTS `courses`;
DROP TABLE IF EXISTS `admins`;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE `admins` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_admins_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `courses` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(150) NOT NULL,
    `slug` VARCHAR(180) NOT NULL,
    `category` VARCHAR(100) NOT NULL,
    `short_description` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `duration` VARCHAR(100) NOT NULL,
    `level` VARCHAR(50) NOT NULL,
    `image` VARCHAR(255) DEFAULT NULL,
    `status` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_courses_slug` (`slug`),
    KEY `idx_courses_category` (`category`),
    KEY `idx_courses_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `services` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(150) NOT NULL,
    `slug` VARCHAR(180) NOT NULL,
    `description` TEXT NOT NULL,
    `icon` VARCHAR(100) DEFAULT NULL,
    `image` VARCHAR(255) DEFAULT NULL,
    `status` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_services_slug` (`slug`),
    KEY `idx_services_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `fees` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `course_id` INT UNSIGNED NOT NULL,
    `fee_type` VARCHAR(100) NOT NULL,
    `amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `discount_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `duration` VARCHAR(100) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `status` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_fees_course_id` (`course_id`),
    KEY `idx_fees_status` (`status`),
    CONSTRAINT `fk_fees_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `syllabus` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `course_id` INT UNSIGNED NOT NULL,
    `module_title` VARCHAR(180) NOT NULL,
    `module_description` TEXT NOT NULL,
    `module_order` INT UNSIGNED NOT NULL DEFAULT 1,
    `status` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_syllabus_course_id` (`course_id`),
    KEY `idx_syllabus_course_order` (`course_id`, `module_order`),
    KEY `idx_syllabus_status` (`status`),
    CONSTRAINT `fk_syllabus_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `gallery` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(150) NOT NULL,
    `image` VARCHAR(255) NOT NULL,
    `category` VARCHAR(100) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `status` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_gallery_category` (`category`),
    KEY `idx_gallery_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `enrollments` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `course_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `phone` VARCHAR(30) NOT NULL,
    `gender` VARCHAR(30) DEFAULT NULL,
    `education` VARCHAR(150) DEFAULT NULL,
    `address` TEXT DEFAULT NULL,
    `message` TEXT DEFAULT NULL,
    `status` VARCHAR(30) NOT NULL DEFAULT 'pending',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_enrollments_course_id` (`course_id`),
    KEY `idx_enrollments_email` (`email`),
    KEY `idx_enrollments_status` (`status`),
    CONSTRAINT `fk_enrollments_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `contact_messages` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(150) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `phone` VARCHAR(30) DEFAULT NULL,
    `subject` VARCHAR(200) NOT NULL,
    `message` TEXT NOT NULL,
    `status` VARCHAR(30) NOT NULL DEFAULT 'unread',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_contact_messages_email` (`email`),
    KEY `idx_contact_messages_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `courses`
    (`title`, `slug`, `category`, `short_description`, `description`, `duration`, `level`, `image`, `status`)
VALUES
    ('Website Development', 'website-development', 'Development', 'Build modern websites from the ground up.', 'Learn HTML, CSS, Bootstrap, JavaScript, jQuery, PHP and MySQL through practical projects.', '6 months', 'Beginner to Advanced', 'website-development.jpg', 1),
    ('Digital Marketing', 'digital-marketing', 'Marketing', 'Learn practical strategies to grow businesses online.', 'Explore search engine optimization, social media, content marketing, email marketing and analytics.', '3 months', 'Beginner', 'digital-marketing.jpg', 1),
    ('Graphic Designing', 'graphic-designing', 'Design', 'Create professional visual designs for digital and print.', 'Develop design skills using composition, typography, branding principles and modern design tools.', '4 months', 'Beginner to Intermediate', 'graphic-designing.jpg', 1),
    ('Artificial Intelligence', 'artificial-intelligence', 'Artificial Intelligence', 'Understand the foundations and applications of AI.', 'Study AI concepts, problem solving, data preparation and practical intelligent systems.', '6 months', 'Intermediate', 'artificial-intelligence.jpg', 1),
    ('Machine Learning', 'machine-learning', 'Data Science', 'Build and evaluate useful machine learning models.', 'Learn data preparation, supervised learning, model evaluation and practical machine learning workflows.', '6 months', 'Intermediate', 'machine-learning.jpg', 1),
    ('Cyber Security', 'cyber-security', 'Security', 'Learn the foundations of digital security and safe computing.', 'Explore security principles, common threats, defensive practices and responsible security testing.', '5 months', 'Beginner to Intermediate', 'cyber-security.jpg', 1),
    ('Python Programming', 'python-programming', 'Programming', 'Learn Python programming through hands-on exercises.', 'Build a strong programming foundation with Python syntax, functions, files, data and projects.', '4 months', 'Beginner', 'python-programming.jpg', 1),
    ('UI/UX Design', 'ui-ux-design', 'Design', 'Design clear, useful and engaging digital experiences.', 'Learn user research, wireframing, prototyping, visual hierarchy and usability fundamentals.', '3 months', 'Beginner', 'ui-ux-design.jpg', 1),
    ('Freelancing', 'freelancing', 'Business', 'Prepare for a successful career as a digital freelancer.', 'Learn portfolio building, proposals, client communication, pricing and professional delivery.', '2 months', 'Beginner', 'freelancing.jpg', 1),
    ('E-Commerce', 'e-commerce', 'Business', 'Learn how to plan and operate an online store.', 'Cover e-commerce models, product presentation, customer service, marketing and order workflows.', '3 months', 'Beginner', 'e-commerce.jpg', 1),
    ('Video Editing', 'video-editing', 'Media', 'Create polished videos for web and social media.', 'Learn editing workflow, storytelling, audio, transitions, titles and export fundamentals.', '3 months', 'Beginner', 'video-editing.jpg', 1),
    ('Database Management', 'database-management', 'Data', 'Learn how to organize and manage relational data.', 'Study database concepts, SQL, table design, relationships, indexes and safe data access.', '3 months', 'Beginner to Intermediate', 'database-management.jpg', 1);

INSERT INTO `services`
    (`title`, `slug`, `description`, `icon`, `image`, `status`)
VALUES
    ('Practical Training', 'practical-training', 'Learn through guided exercises, real examples and portfolio-ready projects.', 'bi-laptop', 'practical-training.jpg', 1),
    ('Career Guidance', 'career-guidance', 'Receive support for career planning, portfolios and digital work opportunities.', 'bi-compass', 'career-guidance.jpg', 1),
    ('Freelancing Support', 'freelancing-support', 'Build the confidence and skills needed to work with clients online.', 'bi-briefcase', 'freelancing-support.jpg', 1),
    ('Project-Based Learning', 'project-based-learning', 'Turn lessons into practical projects that demonstrate your skills.', 'bi-kanban', 'project-based-learning.jpg', 1),
    ('Student Community', 'student-community', 'Connect with fellow learners and grow through collaboration.', 'bi-people', 'student-community.jpg', 1);

INSERT INTO `fees`
    (`course_id`, `fee_type`, `amount`, `discount_amount`, `duration`, `description`, `status`)
VALUES
    (1, 'Full Course', 30000.00, 5000.00, '6 months', 'Includes practical website development training.', 1),
    (2, 'Full Course', 18000.00, 3000.00, '3 months', 'Includes digital marketing exercises and assessments.', 1),
    (3, 'Full Course', 22000.00, 4000.00, '4 months', 'Includes design assignments and portfolio guidance.', 1),
    (4, 'Full Course', 35000.00, 5000.00, '6 months', 'Includes foundational artificial intelligence projects.', 1),
    (5, 'Full Course', 35000.00, 5000.00, '6 months', 'Includes practical machine learning exercises.', 1),
    (6, 'Full Course', 28000.00, 4000.00, '5 months', 'Includes cybersecurity principles and lab activities.', 1),
    (7, 'Full Course', 22000.00, 4000.00, '4 months', 'Includes Python exercises and practical projects.', 1),
    (8, 'Full Course', 18000.00, 3000.00, '3 months', 'Includes user experience and interface design projects.', 1),
    (9, 'Full Course', 12000.00, 2000.00, '2 months', 'Includes portfolio, proposal and client communication practice.', 1),
    (10, 'Full Course', 18000.00, 3000.00, '3 months', 'Includes online store planning and marketing fundamentals.', 1),
    (11, 'Full Course', 18000.00, 3000.00, '3 months', 'Includes video editing exercises and practical assignments.', 1),
    (12, 'Full Course', 18000.00, 3000.00, '3 months', 'Includes SQL and relational database practice.', 1);

INSERT INTO `syllabus`
    (`course_id`, `module_title`, `module_description`, `module_order`, `status`)
VALUES
    (1, 'HTML and CSS Foundations', 'Create structured pages and style them with responsive CSS.', 1, 1),
    (1, 'Bootstrap and Responsive Design', 'Build responsive layouts using Bootstrap components and utilities.', 2, 1),
    (1, 'PHP and MySQL Basics', 'Create dynamic pages and connect them to MySQL with PDO.', 3, 1),
    (2, 'Marketing Fundamentals', 'Understand audiences, goals, channels and campaign planning.', 1, 1),
    (2, 'SEO and Content', 'Learn search visibility and useful content creation.', 2, 1),
    (2, 'Social Media and Analytics', 'Plan social campaigns and measure their results.', 3, 1),
    (3, 'Design Principles', 'Practice layout, color, typography and visual hierarchy.', 1, 1),
    (3, 'Branding Projects', 'Create consistent visual identities for sample brands.', 2, 1),
    (4, 'AI Concepts', 'Explore intelligent systems, data and problem-solving approaches.', 1, 1),
    (4, 'Practical AI Applications', 'Apply foundational AI ideas to guided projects.', 2, 1),
    (5, 'Data Preparation', 'Prepare and understand data for model development.', 1, 1),
    (5, 'Model Training and Evaluation', 'Train models and evaluate their practical performance.', 2, 1),
    (6, 'Security Fundamentals', 'Understand risks, protection principles and safe computing.', 1, 1),
    (6, 'Defensive Practices', 'Apply practical measures for safer systems and accounts.', 2, 1),
    (7, 'Python Syntax and Logic', 'Learn variables, conditions, loops, functions and data structures.', 1, 1),
    (7, 'Python Projects', 'Use Python to build small practical applications.', 2, 1),
    (8, 'User Research and Wireframes', 'Understand users and plan clear screen structures.', 1, 1),
    (8, 'Prototyping and Usability', 'Create prototypes and improve experiences through testing.', 2, 1),
    (9, 'Freelance Profile and Portfolio', 'Prepare a professional profile and useful portfolio.', 1, 1),
    (9, 'Proposals and Client Work', 'Practice proposals, communication, pricing and delivery.', 2, 1),
    (10, 'E-Commerce Foundations', 'Understand online store models, products and customers.', 1, 1),
    (10, 'Store Marketing and Operations', 'Plan promotion, orders and customer support workflows.', 2, 1),
    (11, 'Editing Workflow', 'Learn media organization, cuts, transitions and storytelling.', 1, 1),
    (11, 'Audio, Titles and Export', 'Improve presentation with audio, titles and suitable exports.', 2, 1),
    (12, 'Relational Database Concepts', 'Understand tables, relationships, keys and normalization.', 1, 1),
    (12, 'SQL and Safe Data Access', 'Write SQL and use prepared statements with PDO.', 2, 1);

INSERT INTO `gallery`
    (`title`, `image`, `category`, `description`, `status`)
VALUES
    ('Website Development Class', 'classroom-web-development.jpg', 'Classes', 'Students practicing website development.', 1),
    ('Design Workshop', 'design-workshop.jpg', 'Workshops', 'A practical graphic design workshop.', 1),
    ('Student Project Presentation', 'student-project-presentation.jpg', 'Projects', 'Students presenting their digital projects.', 1),
    ('Digital Skills Session', 'digital-skills-session.jpg', 'Classes', 'A collaborative digital skills learning session.', 1),
    ('Career Guidance Event', 'career-guidance-event.jpg', 'Events', 'An academy career guidance event.', 1),
    ('Student Community', 'student-community.jpg', 'Students', 'Learners collaborating and growing together.', 1),
    ('Hands-on Training', 'hands-on-training.jpg', 'Training', 'Practical training with real digital projects.', 1),
    ('Completion Certificates', 'completion-certificates.jpg', 'Certificates', 'Celebrating learner progress and achievement.', 1);

INSERT INTO `enrollments`
    (`course_id`, `name`, `email`, `phone`, `gender`, `education`, `address`, `message`, `status`)
VALUES
    (1, 'Ayesha Khan', 'ayesha.khan.demo@example.com', '+92 300 1000001', 'Female', 'Intermediate', 'Lasbela, Balochistan', 'Interested in building professional websites.', 'approved'),
    (1, 'Ahmed Raza', 'ahmed.raza.demo@example.com', '+92 300 1000002', 'Male', 'Bachelor student', 'Lasbela, Balochistan', 'I want to improve my web development skills.', 'pending'),
    (1, 'Sadia Noor', 'sadia.noor.demo@example.com', '+92 300 1000003', 'Female', 'Intermediate', 'Uthal, Balochistan', 'Looking forward to practical projects.', 'contacted'),
    (2, 'Bilal Ahmed', 'bilal.ahmed.demo@example.com', '+92 300 1000004', 'Male', 'Bachelor student', 'Karachi, Sindh', 'I want to learn SEO and social media marketing.', 'approved'),
    (2, 'Hina Javed', 'hina.javed.demo@example.com', '+92 300 1000005', 'Female', 'Intermediate', 'Hub, Balochistan', 'Interested in growing a small business online.', 'pending'),
    (2, 'Usman Ali', 'usman.ali.demo@example.com', '+92 300 1000006', 'Male', 'Bachelor', 'Quetta, Balochistan', 'Please share the class schedule.', 'contacted'),
    (3, 'Hira Baloch', 'hira.baloch.demo@example.com', '+92 300 1000007', 'Female', 'Intermediate', 'Lasbela, Balochistan', 'I want to create a strong design portfolio.', 'approved'),
    (3, 'Maham Shah', 'maham.shah.demo@example.com', '+92 300 1000008', 'Female', 'Bachelor student', 'Uthal, Balochistan', 'Interested in branding and social media design.', 'pending'),
    (3, 'Faisal Khan', 'faisal.khan.demo@example.com', '+92 300 1000009', 'Male', 'Intermediate', 'Ormara, Balochistan', 'I am changing my career toward design.', 'rejected'),
    (4, 'Danish Iqbal', 'danish.iqbal.demo@example.com', '+92 300 1000010', 'Male', 'Bachelor', 'Karachi, Sindh', 'Interested in practical AI applications.', 'approved'),
    (4, 'Rabia Javed', 'rabia.javed.demo@example.com', '+92 300 1000011', 'Female', 'Bachelor student', 'Lasbela, Balochistan', 'I want to understand the foundations of AI.', 'pending'),
    (4, 'Owais Noor', 'owais.noor.demo@example.com', '+92 300 1000012', 'Male', 'Intermediate', 'Hub, Balochistan', 'Please contact me about prerequisites.', 'contacted'),
    (5, 'Hamza Ali', 'hamza.ali.demo@example.com', '+92 300 1000013', 'Male', 'Bachelor', 'Quetta, Balochistan', 'I want to learn model training step by step.', 'approved'),
    (5, 'Sana Noor', 'sana.noor.demo@example.com', '+92 300 1000014', 'Female', 'Bachelor student', 'Karachi, Sindh', 'Interested in data-driven projects.', 'pending'),
    (6, 'Zainab Noor', 'zainab.noor.demo@example.com', '+92 300 1000015', 'Female', 'Intermediate', 'Lasbela, Balochistan', 'I want to learn how to protect digital accounts.', 'approved'),
    (6, 'Naveed Khan', 'naveed.khan.demo@example.com', '+92 300 1000016', 'Male', 'Bachelor student', 'Uthal, Balochistan', 'Interested in defensive security practices.', 'pending'),
    (7, 'Maryam Ali', 'maryam.ali.demo@example.com', '+92 300 1000017', 'Female', 'Intermediate', 'Hub, Balochistan', 'I am a beginner and want to learn Python.', 'approved'),
    (7, 'Kashif Raza', 'kashif.raza.demo@example.com', '+92 300 1000018', 'Male', 'Bachelor student', 'Karachi, Sindh', 'Interested in automation and programming projects.', 'contacted'),
    (7, 'Saira Bibi', 'saira.bibi.demo@example.com', '+92 300 1000019', 'Female', 'Intermediate', 'Lasbela, Balochistan', 'Please guide me about the enrollment process.', 'pending'),
    (8, 'Sana Malik', 'sana.malik.demo@example.com', '+92 300 1000020', 'Female', 'Bachelor student', 'Quetta, Balochistan', 'I want to learn user research and prototyping.', 'approved'),
    (8, 'Junaid Ahmed', 'junaid.ahmed.demo@example.com', '+92 300 1000021', 'Male', 'Intermediate', 'Uthal, Balochistan', 'Interested in designing mobile interfaces.', 'pending'),
    (9, 'Mariam Khan', 'mariam.khan.demo@example.com', '+92 300 1000022', 'Female', 'Bachelor', 'Karachi, Sindh', 'I want to start working with online clients.', 'approved'),
    (9, 'Arslan Shah', 'arslan.shah.demo@example.com', '+92 300 1000023', 'Male', 'Intermediate', 'Lasbela, Balochistan', 'Interested in proposals and portfolio building.', 'contacted'),
    (10, 'Noor Fatima', 'noor.fatima.demo@example.com', '+92 300 1000024', 'Female', 'Bachelor student', 'Hub, Balochistan', 'I am planning a small online store.', 'pending'),
    (10, 'Waqas Ahmed', 'waqas.ahmed.demo@example.com', '+92 300 1000025', 'Male', 'Bachelor', 'Karachi, Sindh', 'I want to understand e-commerce operations.', 'approved'),
    (11, 'Rabia Khan', 'rabia.khan.demo@example.com', '+92 300 1000026', 'Female', 'Intermediate', 'Uthal, Balochistan', 'Interested in editing videos for social media.', 'pending'),
    (11, 'Talha Raza', 'talha.raza.demo@example.com', '+92 300 1000027', 'Male', 'Bachelor student', 'Lasbela, Balochistan', 'I want to build a video portfolio.', 'approved'),
    (12, 'Iqra Baloch', 'iqra.baloch.demo@example.com', '+92 300 1000028', 'Female', 'Bachelor student', 'Lasbela, Balochistan', 'Interested in SQL and database design.', 'contacted'),
    (12, 'Salman Ali', 'salman.ali.demo@example.com', '+92 300 1000029', 'Male', 'Intermediate', 'Quetta, Balochistan', 'I want to learn safe database access.', 'pending'),
    (5, 'Laiba Noor', 'laiba.noor.demo@example.com', '+92 300 1000030', 'Female', 'Bachelor student', 'Karachi, Sindh', 'Interested in machine learning projects.', 'approved');

INSERT INTO `contact_messages`
    (`name`, `email`, `phone`, `subject`, `message`, `status`)
VALUES
    ('Ayesha Khan', 'ayesha.khan.demo@example.com', '+92 300 1000001', 'Website Development Course', 'Please share the next class start date and schedule.', 'unread'),
    ('Bilal Ahmed', 'bilal.ahmed.demo@example.com', '+92 300 1000004', 'Digital Marketing Fees', 'I would like more information about the course fee and discount.', 'read'),
    ('Hira Baloch', 'hira.baloch.demo@example.com', '+92 300 1000007', 'Graphic Designing Admission', 'Can beginners join the graphic designing course?', 'unread'),
    ('Danish Iqbal', 'danish.iqbal.demo@example.com', '+92 300 1000010', 'AI Course Information', 'Please tell me about the required education level for AI.', 'read'),
    ('Sana Noor', 'sana.noor.demo@example.com', '+92 300 1000014', 'Machine Learning Classes', 'Are practical projects included in the machine learning course?', 'unread'),
    ('Hamza Ali', 'hamza.ali.demo@example.com', '+92 300 1000013', 'Cyber Security Training', 'I am interested in the next cybersecurity batch.', 'read'),
    ('Maryam Ali', 'maryam.ali.demo@example.com', '+92 300 1000017', 'Python Programming', 'Please guide me about the Python course syllabus.', 'unread'),
    ('Sana Malik', 'sana.malik.demo@example.com', '+92 300 1000020', 'UI/UX Design Guidance', 'I would like to know whether a laptop is required for classes.', 'read'),
    ('Noor Fatima', 'noor.fatima.demo@example.com', '+92 300 1000024', 'E-Commerce Course', 'Please contact me about e-commerce enrollment details.', 'unread'),
    ('Iqra Baloch', 'iqra.baloch.demo@example.com', '+92 300 1000028', 'Database Management', 'I want to learn SQL and would like to know the class timings.', 'read');

