<?php
// Credenciales definidas en .env y pasadas al contenedor por docker-compose.yml
$conn = mysqli_connect(
    getenv('DB_HOST') ?: 'mysql',
    getenv('DB_USER'),
    getenv('DB_PASSWORD'),
    getenv('DB_NAME')
);

if (!$conn) {
    error_log('Error de conexión a MySQL: ' . mysqli_connect_error());
    http_response_code(500);
    die('Servicio no disponible');
}
