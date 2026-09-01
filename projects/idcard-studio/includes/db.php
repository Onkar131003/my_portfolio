<?php
// Database Configuration Constants
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'id_card_studio');

function getDBConnection() {
    $host = DB_HOST;
    $user = DB_USER;
    $pass = DB_PASS;
    $dbname = DB_NAME;

    try {
        // Try connecting to the database
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        return $pdo;
    } catch (PDOException $e) {
        // If database doesn't exist, try connecting to server to create it
        if ($e->getCode() == 1049) { // Unknown database error
            try {
                $tempPdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
                $tempPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $tempPdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
                
                // Now retry connecting with dbname
                $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);

                // Run schema auto-install if schema exists
                $schemaFile = dirname(__DIR__) . '/database/schema.sql';
                if (file_exists($schemaFile)) {
                    $sql = file_get_contents($schemaFile);
                    // PDO exec doesn't run multiple queries easily unless we loop or split, but exec works for multiline in some configurations.
                    // To be safe, we split by semicolon and execute one by one
                    $queries = array_filter(array_map('trim', explode(';', $sql)));
                    foreach ($queries as $query) {
                        if (!empty($query)) {
                            $pdo->exec($query);
                        }
                    }
                }
                return $pdo;
            } catch (PDOException $ex) {
                die("Database creation failed: " . $ex->getMessage());
            }
        } else {
            die("Database connection failed: " . $e->getMessage());
        }
    }
}

// Global PDO instance
$pdo = getDBConnection();
?>
