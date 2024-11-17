-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Automatically indexed as the PRIMARY KEY
    username VARCHAR(50) NOT NULL UNIQUE, -- UNIQUE constraint creates an index
    email VARCHAR(100) NOT NULL UNIQUE, -- UNIQUE constraint creates an index
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (role) -- Add an index for faster filtering by role
);

-- Expenses Table
CREATE TABLE IF NOT EXISTS expenses (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Automatically indexed as PRIMARY KEY
    user_id INT NOT NULL, -- Foreign key to users table
    amount DECIMAL(10, 2) NOT NULL,
    category VARCHAR(50) NOT NULL,
    description TEXT,
    expense_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id), -- Index for faster user-based lookups
    INDEX idx_category (category), -- Index for searching/filtering by category
    INDEX idx_expense_date (expense_date), -- Index for filtering by date
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE -- Referential integrity
);

-- Logs Table
CREATE TABLE IF NOT EXISTS action_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL, -- Foreign key to the users table
    expense_id INT NULL,  -- No longer a foreign key
    action_type ENUM('CREATE', 'READ', 'UPDATE', 'DELETE') NOT NULL,
    description TEXT,
    user_agent VARCHAR(255),
    os VARCHAR(50),
    ip_address VARCHAR(45),
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) -- Retain foreign key for user_id
);


