<?php

require_once __DIR__ . '/../includes/admin_auth.php';

// Agar admin already login hai to dashboard par bhej dein
if (admin_is_authenticated()) {
    redirect('dashboard.php');
}

$error = null;

// Login nonce create karein
if (empty($_SESSION['login_nonce'])) {
    $_SESSION['login_nonce'] = bin2hex(random_bytes(24));
}

// Login form submit hone par
if (is_post_request()) {

    $email = post_string('email', 150);
    $password = (string) ($_POST['password'] ?? '');

    // CSRF aur login nonce verify karein
    if (
        !verify_csrf_token($_POST['csrf_token'] ?? null) ||
        !hash_equals(
            $_SESSION['login_nonce'],
            (string) ($_POST['login_nonce'] ?? '')
        )
    ) {
        $error = 'Unable to sign in. Please try again.';
    } else {

        // Admin database se find karein
        $statement = $pdo->prepare(
            'SELECT id, name, email, password
             FROM admins
             WHERE email = :email
             LIMIT 1'
        );

        $statement->execute([
            'email' => $email
        ]);

        $admin = $statement->fetch();

        // Password verify karein
        if (
            $admin &&
            password_verify($password, $admin['password'])
        ) {

            // Session ID regenerate karein
            session_regenerate_id(true);

            // Admin information session mein save karein
            $_SESSION['admin_id'] = (int) $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];
            $_SESSION['admin_email'] = $admin['email'];

            // Login nonce remove karein
            unset($_SESSION['login_nonce']);

            // Dashboard par redirect
            redirect('dashboard.php');
        }

        $error = 'Unable to sign in. Please check your credentials.';
    }
}

?>

<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Admin Login | Digital Skills Academy</title>

    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Bootstrap Icons -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    <style>

        body {
            min-height: 100vh;
            background: linear-gradient(
                135deg,
                #102a43,
                #1769e0
            );
            font-family: Arial, sans-serif;
        }

        .login-card {
            max-width: 430px;
            border: 0;
            border-radius: 18px;
            overflow: hidden;
        }

        .brand-mark {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 52px;
            height: 52px;
            color: #fff;
            background: #1769e0;
            border-radius: 14px;
            font-size: 24px;
        }

        .form-control {
            border-radius: 9px;
            padding: .75rem;
        }

        .form-control:focus {
            border-color: #1769e0;
            box-shadow: 0 0 0 .2rem rgba(23, 105, 224, .15);
        }

        .btn-primary {
            background: #1769e0;
            border-color: #1769e0;
            border-radius: 9px;
        }

        .btn-primary:hover {
            background: #1258bd;
            border-color: #1258bd;
        }

        .demo-info {
            background: #f1f6ff;
            border-radius: 10px;
            padding: 12px;
            font-size: 14px;
        }

    </style>

</head>

<body>

<div class="container min-vh-100 d-flex align-items-center justify-content-center py-4">

    <div class="card login-card shadow-lg w-100">

        <div class="card-body p-4 p-md-5">

            <!-- Logo / Heading -->
            <div class="text-center mb-4">

                <span class="brand-mark">
                    <i class="bi bi-lightning-charge-fill"></i>
                </span>

                <h1 class="h4 mt-3 mb-1">
                    Academy Admin
                </h1>

                <p class="text-secondary small mb-0">
                    Sign in to manage your academy
                </p>

            </div>


            <!-- Error Message -->
            <?php if ($error): ?>

                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    <?= e($error) ?>
                </div>

            <?php endif; ?>


            <!-- Demo Login Information -->
            <div class="demo-info mb-4">

                <div class="fw-bold mb-2">
                    <i class="bi bi-info-circle me-1"></i>
                    Demo Login
                </div>

                <div>
                    <strong>Email:</strong>
                    admin@gmail.com
                </div>

                <div>
                    <strong>Password:</strong>
                    admin123
                </div>

            </div>


            <!-- Login Form -->
            <form
                method="post"
                action="login.php"
            >

                <!-- CSRF Token -->
                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= e(csrf_token()) ?>"
                >

                <!-- Login Nonce -->
                <input
                    type="hidden"
                    name="login_nonce"
                    value="<?= e($_SESSION['login_nonce']) ?>"
                >


                <!-- Email -->
                <div class="mb-3">

                    <label
                        class="form-label"
                        for="email"
                    >
                        Email address
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-envelope"></i>
                        </span>

                        <input
                            class="form-control"
                            type="email"
                            id="email"
                            name="email"
                            value="<?= old('email', 'admin@gmail.com') ?>"
                            required
                            autocomplete="username"
                        >

                    </div>

                </div>


                <!-- Password -->
                <div class="mb-4">

                    <label
                        class="form-label"
                        for="password"
                    >
                        Password
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>

                        <input
                            class="form-control"
                            type="password"
                            id="password"
                            name="password"
                            value="admin123"
                            required
                            autocomplete="current-password"
                        >

                        <button
                            class="btn btn-outline-secondary"
                            type="button"
                            id="togglePassword"
                            title="Show/Hide Password"
                        >
                            <i class="bi bi-eye"></i>
                        </button>

                    </div>

                </div>


                <!-- Sign In Button -->
                <button
                    class="btn btn-primary w-100 py-2"
                    type="submit"
                >

                    Sign In

                    <i class="bi bi-arrow-right ms-1"></i>

                </button>

            </form>


            <!-- Return Website -->
            <a
                class="d-block text-center small mt-4 text-decoration-none"
                href="../index.php"
            >

                <i class="bi bi-arrow-left me-1"></i>
                Return to website

            </a>

        </div>

    </div>

</div>


<!-- Password Show/Hide -->
<script>

document
    .getElementById('togglePassword')
    .addEventListener('click', function () {

        const password = document.getElementById('password');
        const icon = this.querySelector('i');

        if (password.type === 'password') {

            password.type = 'text';

            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');

        } else {

            password.type = 'password';

            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');

        }

    });

</script>

</body>

</html>