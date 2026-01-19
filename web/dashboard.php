<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: acceso.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body { font-family: Arial; margin: 0; padding: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 40px; border-radius: 8px; }
        h1 { color: #333; }
        .welcome { color: #32b8c6; font-size: 18px; margin-bottom: 30px; }
        .logout-btn { background: #e74c3c; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
        .logout-btn:hover { background: #c0392b; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h1>
        <p class="welcome">Te has autenticado correctamente ✓</p>
        <a href="logout.php" class="logout-btn">Cerrar Sesión</a>
    </div>
</body>
</html>
