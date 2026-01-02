<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Kopero Coffee</title>
    <link rel="stylesheet" href="assets/css/auth.css">
    <style>
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            font-size: 14px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>

<body>

    <section class="auth-page">
        <div class="auth-container">

            <div class="auth-image">
                <img src="assets/img/sign-in.jpg" alt="Coffee Register">
            </div>

            <div class="auth-box">
                <h2>Create Account ☕</h2>
                <p class="auth-subtitle">Join us and start your coffee journey</p>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?= $_SESSION['error'];
                        unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <form action="actions/auth_controller.php?act=register" method="post">
                    <input type="hidden" name="type" value="register">

                    <input type="text" name="full_name" placeholder="Full Name" required>
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="email" name="email" placeholder="Email Address" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required>

                    <div class="remember-row">
                        <label>
                            <input type="checkbox" required>
                            I agree to the <a href="#">Terms & Conditions</a>
                        </label>
                    </div>

                    <button type="submit">Create Account</button>

                    <div class="divider">
                        <span>OR</span>
                    </div>

                    <a href="#" class="btn-google">
                        <img src="assets/img/google.svg" alt="Google" width="20">
                        Continue with Google
                    </a>

                </form>

                <p class="auth-footer">
                    Already have an account?
                    <a href="login.php">Sign in</a>
                </p>
            </div>

        </div>
    </section>

</body>

</html>