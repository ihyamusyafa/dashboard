<?php
try {
    $conn = new PDO(
        "pgsql:host=aws-0-ap-southeast-2.pooler.supabase.com;port=6543;dbname=postgres;sslmode=require",
        "postgres.bfwyxlovlfiswcexdibt", // username unik dari Supabase
        "Magangbni2026",                 // password dari Supabase
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            "sni_hostname" => "aws-0-ap-southeast-2.pooler.supabase.com"
        ]
    );
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>
