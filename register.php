<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $citizen_id = trim($_POST['citizen_id'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($citizen_id === '' || $password === '' || $confirm_password === '') {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check citizen exists and not registered
        $stmt = $conn->prepare("SELECT registered, name FROM citizens WHERE citizen_id = ?");
        $stmt->bind_param("s", $citizen_id);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 0) {
            $error = "Citizen ID not found. Please contact admin.";
        } else {
            $cit = $res->fetch_assoc();
            if ($cit['registered']) {
                $error = "This citizen has already completed registration. Please login.";
            } else {
                // Add to users table
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $ins = $conn->prepare("INSERT INTO users (citizen_id, password) VALUES (?, ?)");
                $ins->bind_param("ss", $citizen_id, $hash);
                if ($ins->execute()) {
                    // mark citizen as registered
                    $upd = $conn->prepare("UPDATE citizens SET registered = 1 WHERE citizen_id = ?");
                    $upd->bind_param("s", $citizen_id);
                    $upd->execute();

                    // Redirect using PHP header (more reliable than JS)
                    header("Location: login.php?registered=1");
                    exit();
                } else {
                    $error = "Error during registration: " . $ins->error;
                }

                $ins->close();
            }
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
    <title>Citizen Registration - Smart Complaint Management System</title>
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
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php" class="active">Register Citizen</a></li>
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
            <h1>Citizen Registration</h1>
            <p>Royal Government of Bhutan</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container">
        <section class="form-container">
            <h2>Register as a Citizen</h2>
            <p>Please enter your Citizen ID and password to register for the Smart Complaint Management System.</p>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
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
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Register</button>
                    <a href="login.php" class="btn btn-secondary">Already registered? Login</a>
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
