<?php
$plain = "123456";
$hash = '$2y$10$F2D0U35zvGuMZ4hN4rVheOpMdbwVQy6iY6lMpT/ghO8PSzVdKMxze'; // ambil dari DB

if (password_verify($plain, $hash)) {
    echo "✅ Cocok\n";
} else {
    echo "❌ Tidak cocok\n";
}
