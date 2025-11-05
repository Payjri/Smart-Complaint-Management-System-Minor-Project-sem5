<?php
session_start();
include 'db.php';

if (!isset($_SESSION['citizen_id'])) {
    header("Location: login.php");
    exit();
}

$citizen_id = $_SESSION['citizen_id'];

// handle submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ministry = $_POST['ministry'] ?? '';
    $complaint_text = trim($_POST['complaint_text'] ?? '');

    if ($ministry === '' || $complaint_text === '') {
        $error = "Please choose ministry and provide complaint details.";
    } else {
        $stmt = $conn->prepare("INSERT INTO complaints (citizen_id, ministry, complaint_text) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $citizen_id, $ministry, $complaint_text);
        if ($stmt->execute()) {
            $success = "Complaint submitted successfully.";
        } else {
            $error = "Error submitting complaint: " . $stmt->error;
        }
        $stmt->close();
    }
}

// fetch history
$stmt = $conn->prepare("SELECT * FROM complaints WHERE citizen_id = ? ORDER BY timestamp DESC");
$stmt->bind_param("s", $citizen_id);
$stmt->execute();
$complaints_result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Complaint - Smart Complaint Management System</title>
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
                    <li><a href="complaint.php" class="active">Submit Complaint</a></li>
                    <li><a href="admin.php">Admin Dashboard</a></li>
                    <li><a href="help.php">Help</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Banner -->
    <div class="banner">
        <div class="container">
            <h1>Submit Complaint</h1>
            <p>Welcome, <?php echo htmlspecialchars($_SESSION['name'] ?? ''); ?> (ID: <?php echo htmlspecialchars($_SESSION['citizen_id']); ?>)</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container">
        <section class="form-container">
            <h2>Submit a New Complaint</h2>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <form method="post" action="">
                <div class="form-group">
                    <label for="ministry">Ministry/Department:</label>
                    <select id="ministry" name="ministry" required>
                        <option value="">Select Ministry/Department</option>
                        <option value="Ministry of Education">Ministry of Education</option>
                        <option value="Ministry of Health">Ministry of Health</option>
                        <option value="Ministry of Agriculture">Ministry of Agriculture</option>
                        <option value="Ministry of Finance">Ministry of Finance</option>
                        <option value="Ministry of Home Affairs">Ministry of Home Affairs</option>
                        <option value="Ministry of Foreign Affairs">Ministry of Foreign Affairs</option>
                        <option value="Ministry of Works and Human Settlement">Ministry of Works and Human Settlement</option>
                        <option value="Ministry of Information and Communications">Ministry of Information and Communications</option>
                        <option value="Ministry of Economic Affairs">Ministry of Economic Affairs</option>
                        <option value="Ministry of Labour and Human Resources">Ministry of Labour and Human Resources</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="complaint_text">Complaint Details:</label>
                    <textarea id="complaint_text" name="complaint_text" rows="5" required></textarea>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Submit Complaint</button>
                </div>
            </form>
        </section>

        <section class="complaint-history">
            <h2>Your Complaint History</h2>
            
            <?php if ($complaints_result->num_rows > 0): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Complaint ID</th>
                            <th>Ministry/Department</th>
                            <th>Complaint Details</th>
                            <th>Status</th>
                            <th>Date Submitted</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $complaints_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['complaint_id']; ?></td>
                                <td><?php echo htmlspecialchars($row['ministry']); ?></td>
                                <td><?php echo htmlspecialchars(mb_substr($row['complaint_text'], 0, 50) . '...'); ?></td>
                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                                <td><?php echo date('d-m-Y', strtotime($row['timestamp'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>You have not submitted any complaints yet.</p>
            <?php endif; ?>
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
