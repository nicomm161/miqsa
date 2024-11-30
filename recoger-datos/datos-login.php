
<?php

// Conexión a la base de datos
include("../conection-db/conectiondb.php");

// Variables de control y errores
$control_login = true;
$errores_login = array();
$success_message = "";
$error_message = "";
$url_login = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $email_login = htmlspecialchars(trim($_POST["email-login"]));
    $contrasena_login = htmlspecialchars(trim($_POST["contrasena-login"]));
    $checkbox_login = isset($_POST['checkbox-login']) ? $_POST['checkbox-login'] : '';

    // Consulta para encontrar el usuario
    $consulta_login = "SELECT * FROM clientes WHERE email = '$email_login'";
    $resultado_login = $conex->query($consulta_login);

    if ($resultado_login->num_rows > 0) {
        $usuario = $resultado_login->fetch_assoc();

        // Verificar la contraseña
        if (password_verify($contrasena_login, $usuario['contraseña'])) {
            // Iniciar sesión
            session_start();
            $_SESSION['email-login'] = $email_login;

            // Manejo de cookies y de recordar sesión
            if (!empty($checkbox_login)) {
                setcookie('Usuario', $email_login, time() + (86400 * 30), "/"); // Cookie válida por 30 días
            } else {
                setcookie('Usuario', '', time() - 3600, "/"); // Eliminar cookie
            }
            $success_message = "¡Gracias por iniciar sesión, " . htmlspecialchars($email_login) . "!";
            $url_login = "../index.html";
        } else {
            $error_message = "Contraseña incorrecta, vuelve a intentarlo";
            $url_login = "../web-usuarios/login.html";
        }
    } else {
        $error_message = "No se encontró el correo, regístrate";
        $url_login = "../web-usuarios/registro.html";
    }
    
    $conex->close();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="shortcut icon" href="../img/miqsa-logo.jpeg" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php if (!empty($success_message)) : ?>
    <script>
        Swal.fire({
            title: '¡Gracias por iniciar sesión!',
            text: '<?php echo $success_message; ?>',
            icon: 'success',
            timer: 5000,
            showConfirmButton: false
        }).then(function() {
            window.location.href = '<?php echo $url_login; ?>';
        });
    </script>
<?php elseif (!empty($error_message)) : ?>
    <script>
        Swal.fire({
            title: 'Error',
            html: '<?php echo $error_message; ?>',
            icon: 'error',
            timer: 5000,
            showConfirmButton: true
        }).then(function() {
            window.location.href = '<?php echo $url_login; ?>';
        });
    </script>
<?php endif; ?>
</body>
</html>
