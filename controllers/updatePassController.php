<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $newPassword = $_POST['new_password'];

    // Asegúrate de incluir tu archivo de conexión aquí
    // Hashea la nueva contraseña
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Actualiza la contraseña y elimina el token
    $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expiry = NULL WHERE reset_token = ?");
    $stmt->bind_param("ss", $hashedPassword, $token);
    if ($stmt->execute()) {
        echo "Contraseña restablecida con éxito.";
    } else {
        echo "Error al restablecer la contraseña.";
    }
}
?>
