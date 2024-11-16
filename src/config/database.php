<?php
// Database connection class
class Database {
    private $host = 'db'; // Your DB host, change if necessary
    private $dbname = 'expense_db'; // Update with your DB name
    private $username = 'my_user'; // Your DB username
    private $password = 'h4Si3fiVeADnXy2'; // Your DB password
    public $conn;

    // Get database connection
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname;charset=utf8", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Database connection error: " . $e->getMessage();
        }

        return $this->conn;
    }

    // Method to run the schema SQL
    public function runSchema($sqlFile) {
        try {
            // Read the schema SQL file
            $sql = file_get_contents($sqlFile);
            if ($sql === false) {
                throw new Exception("Failed to read the schema file.");
            }

            // Execute the SQL commands to create tables
            $this->conn->exec($sql);
            echo "Database and tables created successfully.";
        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "General error: " . $e->getMessage();
        }
    }
}

// Create an instance of Database class and run the schema
try {
    $database = new Database();
    $connection = $database->getConnection(); // Establish DB connection
    $database->runSchema(__DIR__ . '/../db/schema.sql'); // Run schema.sql file to create tables
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
