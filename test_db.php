<?php
include 'db.php';

if ($conn) {
    echo "✅ Connected to database successfully!";
} else {
    echo "❌ Connection failed!";
}
?>
