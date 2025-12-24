<?php
// public/index.php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Puerta de Oro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            background: radial-gradient(circle at center, var(--bg-card) 0%, var(--bg-dark) 100%);
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 2.5rem;
            background: rgba(19, 27, 36, 0.95);
            border: 1px solid var(--brand-teal);
            box-shadow: 0 0 30px rgba(0, 158, 115, 0.2);
        }
        .logo {
            text-align: center;
            color: var(--brand-lime);
            font-size: 2rem;
            margin-bottom: 2rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 3px;
            text-shadow: 0 0 20px rgba(164, 198, 57, 0.3);
        }
    </style>
</head>
<body>

<div class="card login-card">
    <div class="logo">Puerta de Oro</div>
    
    <?php if(isset($_GET['error'])): ?>
        <div style="color: var(--danger); margin-bottom: 1rem; text-align: center;">
            Credenciales incorrectas
        </div>
    <?php endif; ?>

    <form action="login_process.php" method="POST">
        <div class="form-group">
            <label style="color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Usuario</label>
            <input type="text" name="username" required placeholder="admin">
        </div>
        <div class="form-group">
            <label style="color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Contraseña</label>
            <input type="password" name="password" required placeholder="******">
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">INGRESAR</button>
    </form>
</div>

</body>
</html>
