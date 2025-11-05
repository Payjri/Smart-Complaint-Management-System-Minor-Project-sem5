<?php
// admin.php - Admin dashboard with add-citizen and manage complaints
session_start();
include 'db.php';

// ✅ Correct session variable check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Add citizen (admin form)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_citizen') {
    $citizen_id = trim($_POST['citizen_id'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $dob = $_POST['dob'] ?? null;
    $address = trim($_POST['address'] ?? '');

    if ($citizen_id === '' || $name === '') {
        $error = "Citizen ID and Name are required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO citizens (citizen_id, name, dob, address) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $citizen_id, $name, $dob, $address);
        if ($stmt->execute()) {
            $success = "Citizen added successfully.";
        } else {
            $error = "Error adding citizen: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Update complaint status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $complaint_id = (int)$_POST['complaint_id'];
    $status = $_POST['status'] ?? 'Pending';
    $stmt = $conn->prepare("UPDATE complaints SET status = ? WHERE complaint_id = ?");
    $stmt->bind_param("si", $status, $complaint_id);
    if ($stmt->execute()) {
        $success = "Status updated.";
    } else {
        $error = "Failed to update status: " . $stmt->error;
    }
    $stmt->close();
}

// Optional ministry filter
$ministry_filter = $_GET['ministry'] ?? '';

// Fetch complaints (with join to citizens)
if ($ministry_filter !== '') {
    $stmt = $conn->prepare("SELECT c.*, ct.name 
                            FROM complaints c 
                            JOIN citizens ct ON c.citizen_id = ct.citizen_id 
                            WHERE c.ministry = ? 
                            ORDER BY c.timestamp DESC");
    $stmt->bind_param("s", $ministry_filter);
} else {
    $stmt = $conn->prepare("SELECT c.*, ct.name 
                            FROM complaints c 
                            JOIN citizens ct ON c.citizen_id = ct.citizen_id 
                            ORDER BY c.timestamp DESC");
}
$stmt->execute();
$complaints_result = $stmt->get_result();

// Fetch citizens with complaint counts
$cit_stmt = $conn->prepare("SELECT c.citizen_id, c.name, c.dob, c.address, c.registered, 
                                   COUNT(co.complaint_id) as complaint_count
                            FROM citizens c 
                            LEFT JOIN complaints co ON c.citizen_id = co.citizen_id
                            GROUP BY c.citizen_id 
                            ORDER BY c.name ASC");
$cit_stmt->execute();
$citizens_result = $cit_stmt->get_result();

// Ministry counts for filters
$min_stmt = $conn->prepare("SELECT ministry, COUNT(*) as cnt FROM complaints GROUP BY ministry");
$min_stmt->execute();
$ministry_counts_result = $min_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Admin Dashboard - Smart Complaint Management System</title>
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
                    <li><a href="admin.php" class="active">Dashboard</a></li>
                    <li><a href="admin_logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Banner -->
    <div class="banner">
        <div class="container">
            <h1>Admin Dashboard</h1>
            <p>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?> | Royal Government of Bhutan</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <!-- Add Citizen Section -->
        <section class="form-container">
            <h2>Add Citizen (Admin)</h2>
            <form method="post" action="">
                <input type="hidden" name="action" value="add_citizen">
                <div class="form-group">
                    <label>Citizen ID</label>
                    <input type="text" name="citizen_id" required>
                </div>
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="date" name="dob">
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="address">
                </div>
                <button type="submit" class="btn btn-primary">Add Citizen</button>
            </form>
            <p>Note: Admin adds citizen info only. Citizens will set their own password on registration page.</p>
        </section>

        <!-- Complaint Management -->
        <section>
            <h2>Complaints</h2>
            <div class="filters">
                <a href="admin.php"<?php echo $ministry_filter==='' ? ' class="active"' : ''; ?>>All</a>
                <?php while ($r = $ministry_counts_result->fetch_assoc()): ?>
                    <a href="admin.php?ministry=<?php echo urlencode($r['ministry']); ?>"<?php echo $ministry_filter==$r['ministry'] ? ' class="active"' : ''; ?>>
                        <?php echo htmlspecialchars($r['ministry']); ?> (<?php echo $r['cnt']; ?>)
                    </a>
                <?php endwhile; ?>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Citizen</th>
                        <th>Ministry</th>
                        <th>Complaint</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $complaints_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['complaint_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['citizen_id'].' - '.$row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['ministry']); ?></td>
                            <td><?php echo htmlspecialchars(mb_substr($row['complaint_text'],0,80)).'...'; ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                            <td><?php echo date('d-m-Y', strtotime($row['timestamp'])); ?></td>
                            <td>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="complaint_id" value="<?php echo $row['complaint_id']; ?>">
                                    <select name="status">
                                        <option value="Pending"<?php if($row['status']=='Pending') echo ' selected'; ?>>Pending</option>
                                        <option value="In Progress"<?php if($row['status']=='In Progress') echo ' selected'; ?>>In Progress</option>
                                        <option value="Resolved"<?php if($row['status']=='Resolved') echo ' selected'; ?>>Resolved</option>
                                    </select>
                                    <button type="submit" name="update_status" class="btn btn-primary">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        <!-- Registered Citizens -->
        <section>
            <h2>Registered Citizens</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>CID</th>
                        <th>Name</th>
                        <th>DOB</th>
                        <th>Address</th>
                        <th>Registered</th>
                        <th>#Complaints</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($c = $citizens_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($c['citizen_id']); ?></td>
                            <td><?php echo htmlspecialchars($c['name']); ?></td>
                            <td><?php echo $c['dob'] ? date('d-m-Y', strtotime($c['dob'])) : '-'; ?></td>
                            <td><?php echo htmlspecialchars($c['address']); ?></td>
                            <td><?php echo $c['registered'] ? 'Yes' : 'No'; ?></td>
                            <td><?php echo $c['complaint_count']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
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
