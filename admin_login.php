<?php
ob_start(); // start output buffering
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = "Both fields are required.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $admin = $result->fetch_assoc();

            if (password_verify($password, $admin['password'])) {
                // ✅ login successful
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $username;

                // You can use header OR JS redirect safely now
                echo "<script>alert('Login successful! Redirecting...'); window.location.href='admin.php';</script>";
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "Admin username not found.";
        }

        $stmt->close();
    }
}

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Smart Complaint Management System</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <div class="logo">
                <img src="https://www.moha.gov.bt/wp-content/uploads/2019/05/cropped-rgob-logo-270x270.jpg" alt="Royal Government of Bhutan">
            </div>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="login.php">Citizen Login</a></li>
                    <li><a href="register.php">Register Citizen</a></li>
                    <li><a href="admin_login.php" class="active">Admin Login</a></li>
                    <li><a href="help.php">Help</a></li>
                    <li><a href="about.php">About Us</a></li>
                </ul>
            </nav>
        </div>
    </header>
 <div class="marquee-bar">
        <marquee behavior="scroll" direction="left" scrollamount="6">
            🌏 Register for Global Peace Prayer 🌿 | Join hands for harmony and hope ✨ | Be part of the movement for a better world 🌼
        </marquee>
    </div>
    <!-- Banner -->
    <div class="banner">
        <div class="container">
            <h1>Admin Login</h1>
            <p>Royal Government of Bhutan</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container">
        <section class="form-container">
            <h2>Login as Admin</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="post" action="">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Login</button>
                    <a href="index.php" class="btn btn-secondary">Back to Home</a>
                </div>
            </form>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Royal Government of Bhutan. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

