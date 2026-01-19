<?php
include 'conexion.php';
if (isset($_POST['test'])) {
    $username = $_POST['username'];
    $email = $_POST['email']; 
    $password = $_POST['password'];
    $sql = "INSERT INTO users (username,email,password) VALUES ('$username','$email','$password')";
    if (mysqli_query($conn, $sql)) {
        echo "¡Usuario CREADO OK! <br>";
    } else {
        echo "ERROR: " . mysqli_error($conn);
    }
}
?>
<h3>Test Registro</h3>
<form method="POST">
    User: <input name="username"><br>
    Email: <input name="email"><br>  
    Pass: <input name="password" type="password"><br>
    <button name="test">TEST INSERT</button>
</form>
<?php
$res = mysqli_query($conn, "SELECT * FROM users");
echo "<h4>Usuarios BD:</h4>";
while($row = mysqli_fetch_assoc($res)) {
    echo $row['username']." - ".$row['email']."<br>";
}
?>
