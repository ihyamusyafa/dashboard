<?php
try {
    $conn = new PDO(
        "pgsql:host=aws-0-ap-southeast-2.pooler.supabase.com;port=5432;dbname=postgres;sslmode=require",
        "postgres",
        "YOUR_PASSWORD"
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>
