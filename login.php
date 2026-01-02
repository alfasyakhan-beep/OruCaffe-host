<?php
session_start();
// Jika sudah login, lempar ke index
if (isset($_SESSION['status']) && $_SESSION['status'] == "login") {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Kopero Coffee</title>
    <link rel="stylesheet" href="assets/css/auth.css">
    <style>
        /* CSS Tambahan Kecil untuk Alert Error */
        .alert {
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 14px;
            font-size: 13px;
            text-align: center;
        }

        .alert-danger {
            background-color: #ffebee;
            color: #c62828;
            border: 1px solid #ef9a9a;
        }

        .alert-success {
            background-color: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #a5d6a7;
        }
    </style>
</head>

<body>

    <section class="auth-page">
        <div class="auth-container">

            <div class="auth-image">
                <img src="assets/img/sign-in.jpg" alt="Coffee Login">
            </div>

            <div class="auth-box">
                <h2>Welcome Back! 👋</h2>
                <p class="auth-subtitle">Please enter your details to sign in.</p>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?= $_SESSION['error'];
                        unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <?= $_SESSION['success'];
                        unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <form action="actions/auth_controller.php?act=login" method="post">
                    <input type="hidden" name="type" value="login">

                    <input type="text" name="username_email" placeholder="Email or Username" required>

                    <input type="password" name="password" placeholder="Password" required>

                    <div class="remember-row">
                        <label>
                            <input type="checkbox" name="remember">
                            Remember me
                        </label>
                        <a href="#">Forgot Password?</a>
                    </div>

                    <button type="submit">Sign In</button>

                    <div class="divider">
                        <span>OR</span>
                    </div>

                    <a href="#" class="btn-google">
                        <img src="assets/img/google.svg" alt="Google">
                        Continue with Google
                    </a>

                </form>

                <p class="auth-footer">
                    Don't have an account?
                    <a href="register.php">Sign up</a>
                </p>
            </div>

        </div>
    </section>

</body>

</html>