<?php
// Verifica si el token es válido
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Aquí debes verificar si el token existe en la base de datos y no ha caducado
    // (Asegúrate de incluir tu archivo de conexión aquí)
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_expiry > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Muestra el formulario para ingresar la nueva contraseña
        ?>
        <form action="actualizar_contrasena.php" method="post">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <label for="new_password">Nueva Contraseña:</label>
            <input type="password" id="new_password" name="new_password" required>
            <button type="submit">Restablecer Contraseña</button>
        </form>
        <?php
    } else {
        echo "Token inválido o ha caducado.";
    }
}
?>
