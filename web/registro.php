<?php
session_start();
include "conexion.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");
    $cpassword = trim($_POST["cpassword"] ?? "");
    
    if (empty($username) || empty($email) || empty($password)) {
        $error = "Todos los campos son requeridos";
    } elseif ($password !== $cpassword) {
        $error = "Las contraseñas no coinciden";
    } elseif (strlen($password) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = "Este email ya está registrado";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);
            
            if ($stmt->execute()) {
                $success = "Registro exitoso! Redirigiendo...";
                echo "<script>setTimeout(() => window.location.href = 'acceso.php', 2000);</script>";
            } else {
                $error = "Error en el registro";
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
    <style>
        body { font-family: Arial; display: flex; justify-content: center; align-items: center; height: 100vh; background: #f5f5f5; }
        .register-container { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h2 { text-align: center; color: #333; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; }
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #e74c3c; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        button:hover { background: #c0392b; }
        .error { color: red; text-align: center; margin-bottom: 20px; }
        .success { color: green; text-align: center; margin-bottom: 20px; }
        .login-link { text-align: center; margin-top: 20px; }
        a { color: #32b8c6; text-decoration: none; }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Registro</h2>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Nombre de usuario:</label>
                <input type="text" name="name" required>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Contraseña:</label>
                <input type="password" name="password" required minlength="6">
            </div>
            <div class="form-group">
                <label>Confirmar contraseña:</label>
                <input type="password" name="cpassword" required minlength="6">
            </div>
            <button type="submit">Registrarse</button>
        </form>
        <div class="login-link">
            ¿Ya tienes cuenta? <a href="acceso.php">Inicia sesión aquí</a>
        </div>
    </div>
</body>
</html>
