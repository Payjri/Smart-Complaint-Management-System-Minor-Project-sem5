<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help - Smart Complaint Management System</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container header-content">
            <div class="logo">
                <i class="fas fa-landmark"></i>
                <h1>Royal Government of Bhutan</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register Citizen</a></li>
                    <li><a href="admin.php">Admin Dashboard</a></li>
                    <li><a href="help.php" class="active">Help</a></li>
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
            <h2>Help & Support</h2>
            <p>Find answers to frequently asked questions</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3>Frequently Asked Questions (FAQs)</h3>
            </div>
            <div class="card-body">
                <div class="faq-item" style="margin-bottom: 30px;">
                    <h4><i class="fas fa-question-circle"></i> How can I file complain log?</h4>
                    <p>To register for the system, you need to have a valid Citizen ID (CID) issued by the Royal Government of Bhutan. Visit the registration page and enter your Citizen ID along with a password of your choice. Once registered, you can login using your Citizen ID and password.</p>
                </div>
                <div class="faq-item" style="margin-bottom: 30px;">
                    <h4><i class="fas fa-question-circle"></i> I forgot my password. What should I do?</h4>
                    <p>If you have forgotten your password, please contact the system administrator at info@complaint.gov.bt or call +975-2-322525 for assistance.</p>
                </div>
                <div class="faq-item" style="margin-bottom: 30px;">
                    <h4><i class="fas fa-question-circle"></i> How do I submit a complaint?</h4>
                    <p>To submit a complaint, you need to login to the system using your Citizen ID and password. Once logged in, you can select the ministry or department you want to submit the complaint to, provide details of your complaint, and submit it. You will receive a complaint ID for tracking purposes.</p>
                </div>
                <div class="faq-item" style="margin-bottom: 30px;">
                    <h4><i class="fas fa-question-circle"></i> How can I track the status of my complaint?</h4>
                    <p>After logging in, you can view your complaint history which shows all the complaints you have submitted along with their current status (Pending, In Progress, or Resolved).</p>
                </div>
                <div class="faq-item" style="margin-bottom: 30px;">
                    <h4><i class="fas fa-question-circle"></i> What happens after I submit a complaint?</h4>
                    <p>Once you submit a complaint, it is forwarded to the relevant ministry or department. The complaint will be reviewed and assigned to the appropriate personnel for action. You can track the status of your complaint through your account.</p>
                </div>
                <div class="faq-item" style="margin-bottom: 30px;">
                    <h4><i class="fas fa-question-circle"></i> Is my personal information secure?</h4>
                    <p>Yes, the Royal Government of Bhutan takes data security seriously. Your personal information is stored securely and is only accessible to authorized personnel for the purpose of addressing your complaints.</p>
                </div>
            </div>
        </div>

        <div class="card" style="margin-top: 30px;">
            <div class="card-header">
                <h3>Contact Support</h3>
            </div>
            <div class="card-body">
                <p>If you need further assistance, please contact our support team:</p>
                <ul style="list-style: none; padding: 0;">
                    <li style="margin-bottom: 10px;"><i class="fas fa-envelope"></i> Email: info@complaint.gov.bt</li>
                    <li style="margin-bottom: 10px;"><i class="fas fa-phone"></i> Phone: +975-2-322525</li>
                    <li><i class="fas fa-map-marker-alt"></i> Address: Smart Complaint Management System, Thimphu, Bhutan</li>
                </ul>
                <p style="margin-top: 20px;">Our support team is available from Monday to Friday, 9:00 AM to 5:00 PM.</p>
            </div>
        </div>
    </div>

    <footer>
        <div class="container footer-content">
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="register.php">Register</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="admin.php">Admin Dashboard</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Resources</h3>
                <ul>
                    <li><a href="help.php">Help</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="#">FAQs</a></li>
                    <li><a href="#">Contact Us</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact Information</h3>
                <ul>
                    <li><i class="fas fa-map-marker-alt"></i> Thimphu, Bhutan</li>
                    <li><i class="fas fa-phone"></i> +975-2-322525</li>
                    <li><i class="fas fa-envelope"></i> info@complaint.gov.bt</li>
                </ul>
            </div>
        </div>
        <div class="container copyright">
            <p>&copy; <?php echo date('Y'); ?> Royal Government of Bhutan. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>
