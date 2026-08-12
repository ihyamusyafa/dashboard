<?php
include 'config/database.php';
$result = pg_query($conn, "SELECT NOW()");
if ($result) {
  echo "Koneksi ke Supabase berhasil!";
} else {
  echo "Error: " . pg_last_error($conn);
}
?>
