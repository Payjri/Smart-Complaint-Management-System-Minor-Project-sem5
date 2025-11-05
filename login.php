<?php 
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $citizen_id = trim($_POST['citizen_id'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($citizen_id === '' || $password === '') {
        $error = "Both fields are required.";
    } else {
        // Check if user exists
        $stmt = $conn->prepare("SELECT u.password, c.name FROM users u 
                                JOIN citizens c ON u.citizen_id = c.citizen_id 
                                WHERE u.citizen_id = ?");
        $stmt->bind_param("s", $citizen_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['citizen_id'] = $citizen_id;
                $_SESSION['citizen_name'] = $user['name'];
                header("Location: complaint.php");
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "Citizen ID not found. Please register first.";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citizen Login - Smart Complaint Management System</title>
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
                    <li><a href="login.php" class="active">Login</a></li>
                    <li><a href="register.php">Register Citizen</a></li>
                    <li><a href="admin.php">Admin Dashboard</a></li>
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
            <h1>Citizen Login</h1>
            <p>Royal Government of Bhutan</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container">
        <section class="form-container">
            <h2>Login to Your Account</h2>

            <?php if (isset($_GET['registered'])): ?>
                <div class="alert alert-success">Registration successful! Please log in below.</div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="post" action="">
                <div class="form-group">
                    <label for="citizen_id">Citizen ID:</label>
                    <input type="text" id="citizen_id" name="citizen_id" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Login</button>
                    <a href="register.php" class="btn btn-secondary">New user? Register</a>
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
