<?php
// admin/users.php
require '../config/db.php';
require 'header_admin.php';

if ($_SESSION['role'] !== 'admin') {
    die("Acceso denegado");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    
    // Check if username exists
    $check = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $check->execute([$user]);
    if($check->fetch()) {
        echo "<script>alert('El usuario ya existe');</script>";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, role) VALUES (?, ?, ?)");
        try {
            $stmt->execute([$user, $pass, $role]);
            echo "<script>alert('Usuario creado exitosamente');</script>";
        } catch (Exception $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        }
    }
}

$users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();
?>

<h1>Gestión de Usuarios</h1>

<div class="grid-layout" style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
    <div class="card" style="height: fit-content;">
        <h3>Crear Nuevo Usuario</h3>
        <form method="POST">
            <div class="form-group">
                <label>Nombre de Usuario</label>
                <input type="text" name="username" placeholder="ej: bartender1" required autocomplete="off">
            </div>
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password" placeholder="******" required autocomplete="off">
            </div>
            <div class="form-group">
                <label>Rol</label>
                <select name="role">
                    <option value="bartender">Bartender (Ventas)</option>
                    <option value="waiter">Mesero (Pedidos)</option>
                    <option value="admin">Administrador (Total)</option>
                </select>
            </div>
            <button class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Crear Usuario</button>
        </form>
    </div>

    <div class="card">
        <h3>Usuarios del Sistema</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users as $u): ?>
                <tr>
                    <td><?php echo $u['id']; ?></td>
                    <td>
                        <span style="font-weight: bold; color: var(--gold-primary);"><?php echo htmlspecialchars($u['username']); ?></span>
                    </td>
                    <td>
                        <span class="badge" style="padding: 2px 8px; border-radius: 4px; background: #333; font-size: 0.8rem;">
                            <?php echo ucfirst($u['role']); ?>
                        </span>
                    </td>
                    <td>
                        <?php if($u['username'] !== 'admin'): ?>
                        <a href="#" style="color: var(--danger); font-size: 0.8rem;" onclick="alert('Función de eliminar pendiente de seguridad')">Eliminar</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require '../includes/footer.php'; ?>
