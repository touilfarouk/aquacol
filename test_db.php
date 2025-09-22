<?php
require_once('config.php');

echo "Database connection test:\n";

try {
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    echo "✅ SUCCESS - Connected to database\n";

    // Check if ribs table exists
    $result = $pdo->query("SHOW TABLES LIKE 'ribs'");
    if ($result->fetch()) {
        echo "✅ RIBs table exists\n";

        // Count existing records
        $count = $pdo->query("SELECT COUNT(*) FROM ribs")->fetchColumn();
        echo "📊 Current RIB records: $count\n";

        // Show sample data
        $sample = $pdo->query("SELECT * FROM ribs LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
        echo "📋 Sample data:\n";
        foreach ($sample as $rib) {
            echo "  - ID: {$rib['id']}, Wilaya: {$rib['code_wilaya']}, Structure: {$rib['structure_onta']}, RIB: {$rib['rib']}\n";
        }
    } else {
        echo "❌ RIBs table does NOT exist - need to import ribs.sql\n";
    }

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
?>
