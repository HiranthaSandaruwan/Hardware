<?php
require __DIR__ . '/config.php';
$user = current_user();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';
    
    // You can add email sending functionality here
    // For now, we'll just show a success message
    $success = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - <?= htmlspecialchars($APP_NAME) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'partials/header.php'; ?>
    <?php include 'partials/nav.php'; ?>

    <div class="container">
        <div class="content-wrapper">
            <h1>Contact Us</h1>
            
            <?php if (isset($success)): ?>
                <div class="alert alert-success">
                    Thank you for your message! We'll get back to you soon.
                </div>
            <?php endif; ?>

            <div class="contact-form">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" required 
                               value="<?= $user ? htmlspecialchars($user['username']) : '' ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required 
                               value="<?= $user ? htmlspecialchars($user['email']) : '' ?>">
                    </div>

                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" id="subject" name="subject" required>
                    </div>

                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" rows="6" required></textarea>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn">Send Message</button>
                    </div>
                </form>
            </div>

            <div class="contact-info">
                <h2>Other Ways to Reach Us</h2>
                <div class="info-item">
                    <strong>Email:</strong> support@hardwaretracker.com
                </div>
                <div class="info-item">
                    <strong>Phone:</strong> +94 XX XXX XXXX
                </div>
                <div class="info-item">
                    <strong>Hours:</strong> Monday - Friday, 9:00 AM - 5:00 PM
                </div>
            </div>
        </div>
    </div>

    <?php include 'partials/footer.php'; ?>

    <style>
        .contact-form {
            max-width: 600px;
            margin: 2rem 0;
            padding: 2rem;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        .form-group textarea {
            resize: vertical;
        }

        .contact-info {
            margin-top: 3rem;
            padding: 2rem;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .info-item {
            margin: 1rem 0;
        }

        .alert {
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 4px;
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }
    </style>
</body>
</html>
