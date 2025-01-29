<?php
 $usuario = $_SESSION['huertaenred']['user'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar a la base de datos
    // (Asegúrate de incluir tu archivo de conexión aquí)

    $email = $_POST['email'];

    // Verifica si el correo existe en la base de datos
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Genera un token
        $token = bin2hex(random_bytes(50)); // Token de 100 caracteres
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour")); // Caduca en 1 hora

        // Guarda el token en la base de datos
        $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expiry = ? WHERE email = ?");
        $stmt->bind_param("sss", $token, $expiry, $email);
        $stmt->execute();

        // Envía el correo electrónico con el enlace de recuperación
        $resetLink = "https://urbanagest.tecnica3.com.ar/restablecer_contrasena.php?token=" . $token;
        mail($email, "Recuperación de contraseña", "Haz clic en este enlace para restablecer tu contraseña: " . $resetLink);

        echo "Se ha enviado un enlace de recuperación a tu correo electrónico.";
    } else {
        echo "No se encontró un usuario con ese correo electrónico.";
    }
}
?>
