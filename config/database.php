<?php
$conn = pg_connect("host=aws-0-ap-southeast-2.pooler.supabase.com port=5432 dbname=postgres user=postgres password=YOUR_PASSWORD sslmode=require");
if (!$conn) {
    die("Koneksi gagal: " . pg_last_error());
}
?>
