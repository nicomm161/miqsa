
<!-- Validación y registro de los datos para almacenar en la base de datos -->
<?php 

// Conexión a la base de datos
include("../conection-db/conectiondb.php");

// Variables de patrones y control del bucle
$patronEmail = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/";
$patronContrasena = "/^(?=\w*\d)(?=\w*[A-Z])(?=\w*[a-z])\S{8,16}$/";
$patronTelefono = "/^[0-9]{3}-[0-9]{3}-[0-9]{3}$/";
$control = true;
$errores = array(); // Control de errores añadiéndolos a un array

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = mysqli_real_escape_string($conex, $_POST["nombre"]);
    $email = mysqli_real_escape_string($conex, $_POST["email"]);
    $filtrar_email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $contrasena = mysqli_real_escape_string($conex, $_POST["contraseña"]);
    $telefono = mysqli_real_escape_string($conex, $_POST["telefono"]);
    $fecha_registro = date('Y-m-d');
    $success_message = "";
    $error_message = "";

    // Validaciones
    if (!$filtrar_email || !preg_match($patronEmail, $email)) { 
        $errores[] = "¡El email no es correcto!"; 
        $control = false; 
    } 

    if (!preg_match($patronContrasena, $contrasena)) { 
        $errores[] = "¡La contraseña no es correcta, debe tener entre 8 y 16 caracteres, al menos un número, una letra mayúscula y una letra minúscula!"; 
        $control = false; 
    } 

    if(!preg_match($patronTelefono, $telefono)) { 
        $errores[] = "¡El teléfono no es correcto, sigue el formato 123-123-123!"; 
        $control = false; 
    }

    // Control de errores con la base de datos al insertar los datos
    if ($control) {
        $contrasenaHashed = password_hash($contrasena, PASSWORD_DEFAULT);
        $insertar_registro = "INSERT INTO clientes (nombre, telefono, email, contraseña, fecha_registro) VALUES ('$nombre','$telefono','$filtrar_email','$contrasenaHashed','$fecha_registro')";
        try {
            if ($conex->query($insertar_registro) === TRUE) {
                $success_message = "¡Registro exitoso! Serás redirigido al inicio de sesión.";
                $redirect_url = "../web-usuarios/login.html";
            }
        
        } catch (Exception $e) {
            if (mysqli_errno($conex) == 1062) {
                $error_message = "¡Este correo ya existe! Por favor, intenta con otro.";
                $redirect_url = "../web-usuarios/registro.html";
            } else {
                $error_message = "Error: " . htmlspecialchars($e->getMessage());
            }
        }
    } else {
        $error_message = implode("<br>", array_map('htmlspecialchars', $errores));
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
    <link rel="shortcut icon" href="../img/miqsa-logo.jpeg" type="image/x-icon">
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php if (!empty($success_message)) : ?>
    <script>
        Swal.fire({
            title: '¡Registro exitoso!',
            text: 'Serás redirigido al inicio de sesión, espera 5 segundos o pulse ok',
            icon: 'success',
            timer: 5000,
            showConfirmButton: false
        }).then(function() {
            window.location.href = '<?php echo $redirect_url; ?>';
        });
    </script>
<?php elseif (!empty($error_message)) : ?>
    <script>
        Swal.fire({
            title: 'Error',
            html: 'Algo salió mal, espera 5 segundos o pulse ok: <?php echo $error_message; ?>',
            icon: 'error',
            timer: 5000,
            showConfirmButton: true
        }).then(function() {
            window.location.href = '<?php echo $redirect_url; ?>';
        });
    </script>
<?php endif; ?>
</body>
</html>