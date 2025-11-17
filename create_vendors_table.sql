-- Create vendors table if it doesn't exist

CREATE TABLE IF NOT EXISTS vendors (
  id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  contact VARCHAR(255) DEFAULT NULL,
  address TEXT DEFAULT NULL,
  date DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Insert sample data if table is empty
INSERT IGNORE INTO vendors (id, name, contact, address) VALUES
(1, 'Sample Vendor 1', '123-456-7890', '123 Main St, City, State'),
(2, 'Sample Vendor 2', '987-654-3210', '456 Oak Ave, City, State');
