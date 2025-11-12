<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/classes/User.php';

// If already logged in, redirect to dashboard
if (isLoggedIn()) {
    redirect('index.php');
}

// Check for remember token
if (!isLoggedIn() && isset($_COOKIE['remember_token'])) {
    $user = new User();
    if ($user->autoLogin($_COOKIE['remember_token'])) {
        redirect('index.php');
    }
}

$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = "Invalid request. Please try again.";
    } else {
        try {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $rememberMe = isset($_POST['remember_me']);

            $user = new User();
            $user->login($email, $password, $rememberMe);

            setFlashMessage('success', 'Login successful!');
            redirect('index.php');
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

// Check for flash messages
$flash = getFlashMessage();
if ($flash) {
    if ($flash['type'] === 'success') {
        $success = $flash['message'];
    } else {
        $error = $flash['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CHARUSAT Event Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0f0f1e 0%, #1a1a2e 50%, #16213e 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            color: #e0e0e0;
        }

        .container {
            width: 100%;
            max-width: 400px;
        }

        .login-card {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            border-radius: 10px;
            border: 1px solid rgba(102, 126, 234, 0.2);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
            padding: 40px;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-section h1 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .logo-section p {
            color: #a0a0a0;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #e0e0e0;
            font-weight: 600;
            font-size: 14px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid rgba(102, 126, 234, 0.3);
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
            background: rgba(255, 255, 255, 0.05);
            color: #e0e0e0;
        }

        input[type="email"]::placeholder,
        input[type="password"]::placeholder {
            color: #808080;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
            background: rgba(255, 255, 255, 0.08);
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        input[type="checkbox"] {
            margin-right: 8px;
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #667eea;
        }

        .checkbox-group label {
            margin: 0;
            font-weight: 500;
            cursor: pointer;
            color: #e0e0e0;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.6);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
            animation: slideIn 0.3s ease-out;
        }

        .alert-error {
            background-color: rgba(255, 0, 0, 0.1);
            border: 1px solid rgba(255, 0, 0, 0.3);
            color: #ff6b6b;
        }

        .alert-success {
            background-color: rgba(0, 255, 0, 0.1);
            border: 1px solid rgba(0, 255, 0, 0.3);
            color: #4ade80;
        }

        .footer-links {
            text-align: center;
            margin-top: 20px;
        }

        .footer-links a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            margin: 0 5px;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        .divider {
            color: #505070;
            margin: 0 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-card">
            <div class="logo-section">
                <h1>🎉 CHARUSAT Events</h1>
                <p>Event Management System</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="Enter your email"
                        required
                        value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Enter your password"
                        required
                    >
                </div>

                <div class="checkbox-group">
                    <input 
                        type="checkbox" 
                        id="remember_me" 
                        name="remember_me"
                    >
                    <label for="remember_me">Remember me for 30 days</label>
                </div>

                <button type="submit" class="btn-login">Login</button>

                <div class="footer-links">
                    <p>Don't have an account? <a href="register.php">Register here</a></p>
                    <p style="margin-top: 10px; font-size: 12px; color: #999;">
                        Demo: admin@charusat.edu / Admin@123456
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
