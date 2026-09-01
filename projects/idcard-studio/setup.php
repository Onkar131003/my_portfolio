<?php
// Setup script to initialize MySQL Database and load schemas

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'id_card_studio';
$success = false;
$message = '';

try {
    // 1. Connect to MySQL server first without DB
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    // 2. Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
    
    // 3. Connect to the created database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    // 4. Load schema.sql and execute
    $schemaFile = __DIR__ . '/database/schema.sql';
    if (!file_exists($schemaFile)) {
        throw new Exception("Schema file schema.sql not found at $schemaFile.");
    }
    
    $sql = file_get_contents($schemaFile);
    
    // Split SQL into individual statements
    $queries = array_filter(array_map('trim', explode(';', $sql)));
    
    foreach ($queries as $query) {
        if (!empty($query)) {
            $pdo->exec($query);
        }
    }
    
    $success = true;
    $message = "Database `$dbname` and tables initialized successfully with mock data!";
} catch (Exception $e) {
    $success = false;
    $message = "Setup failed: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Setup - ID Card Studio</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .setup-card {
            max-width: 500px;
            width: 100%;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            padding: 2.5rem;
        }
    </style>
</head>
<body>
    <div class="setup-card text-center">
        <h1 class="h3 font-bold mb-4 text-indigo">ID Card Studio</h1>
        <h2 class="h5 mb-4 text-secondary">Database Installer</h2>
        
        <?php if ($success): ?>
            <div class="alert alert-success d-flex align-items-center justify-content-center gap-2 mb-4" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg>
                <div><?php echo htmlspecialchars($message); ?></div>
            </div>
            <a href="index.php" class="btn btn-primary w-100 py-2 btn-lg shadow-sm" style="background-color: #6366F1; border-color: #6366F1;">
                Go to Dashboard
            </a>
        <?php else: ?>
            <div class="alert alert-danger d-flex align-items-center justify-content-center gap-2 mb-4" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                </svg>
                <div><?php echo htmlspecialchars($message); ?></div>
            </div>
            <button onclick="window.location.reload();" class="btn btn-danger w-100 py-2 btn-lg shadow-sm">
                Try Again
            </button>
        <?php endif; ?>
    </div>
</body>
</html>
